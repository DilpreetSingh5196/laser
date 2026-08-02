<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderNotificationMail;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_phones' => 'nullable|string',
            'payment_upi_id' => 'nullable|string',
            'payment_phone' => 'nullable|string',
            'payment_qr_code' => 'nullable|image|max:2048'
        ]);

        if ($request->has('company_name')) {
            Setting::set('company_name', $request->company_name);
        }

        if ($request->has('company_address')) {
            Setting::set('company_address', $request->company_address);
        }

        if ($request->has('company_phones')) {
            Setting::set('company_phones', $request->company_phones);
        }

        if ($request->has('payment_upi_id')) {
            Setting::set('payment_upi_id', $request->payment_upi_id);
        }

        if ($request->has('payment_phone')) {
            Setting::set('payment_phone', $request->payment_phone);
        }

        if ($request->hasFile('payment_qr_code')) {
            // Delete old QR code if exists
            $oldQr = Setting::get('payment_qr_code');
            if ($oldQr && file_exists(public_path($oldQr))) {
                @unlink(public_path($oldQr));
            }

            $uploadDir = public_path('settings');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('payment_qr_code')->getClientOriginalExtension();
            $request->file('payment_qr_code')->move($uploadDir, $imageName);
            Setting::set('payment_qr_code', 'settings/' . $imageName);
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'notification_emails' => 'nullable|array',
            'notification_emails.*' => 'nullable|email',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|numeric',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|max:50',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
        ]);

        $emails = $request->input('notification_emails', []);
        $cleanEmails = array_values(array_filter(array_map('trim', $emails), function($email) {
            return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
        }));
        Setting::set('notification_emails', json_encode($cleanEmails));

        $smtpKeys = [
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'smtp_encryption',
            'smtp_from_address',
            'smtp_from_name'
        ];

        foreach ($smtpKeys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return back()->with('success', 'Email & SMTP settings updated successfully.');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            Mail::to($request->test_email)->send(new OrderNotificationMail(
                null,
                'SMTP Diagnostic Test',
                'Congratulations! Your dynamic SMTP configuration is functioning correctly.'
            ));

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
