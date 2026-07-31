<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

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
            if ($oldQr && Storage::disk('public')->exists($oldQr)) {
                Storage::disk('public')->delete($oldQr);
            }

            $path = $request->file('payment_qr_code')->store('settings', 'public');
            Setting::set('payment_qr_code', $path);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
