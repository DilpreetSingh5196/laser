<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('client')->whereIn('status', ['Payment Verification Pending', 'Approved', 'Payment Rejected']);
        
        if ($request->has('search')) {
            $query->whereHas('client', function($q) use ($request) {
                $q->where('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('firm_name', 'like', '%' . $request->search . '%');
            });
        }
        $limit = (int) $request->input('limit', 10);
        if ($limit <= 0) $limit = 10;
        $payments = $query->orderBy('updated_at', 'desc')->paginate($limit)->appends($request->query());
        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, Order $order)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_remark' => 'nullable|string'
        ]);

        if ($request->action == 'approve') {
            $order->status = 'Approved';
            $order->payment_status = 'Approved';
        } else {
            $order->status = 'Payment Rejected';
            $order->payment_status = 'Rejected';
        }
        
        $order->admin_remark = $request->admin_remark;
        $order->save();

        NotificationService::sendPaymentVerificationNotification($order);

        return redirect()->route('admin.payments.index')->with('success', 'Payment verified successfully.');
    }
}
