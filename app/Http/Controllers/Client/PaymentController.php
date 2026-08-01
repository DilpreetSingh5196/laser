<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function pay(Request $request, Order $order)
    {
        if ($order->client_id !== auth()->guard('client')->id()) {
            abort(403);
        }

        if ($order->status !== 'Price Assigned') {
            return back()->with('error', 'Order is not ready for payment.');
        }

        $order->status = 'Payment Verification Pending';
        $order->payment_status = 'Pending Verification';
        $order->save();

        NotificationService::sendOrderNotification(
            $order,
            "Payment Submitted for Order (#{$order->id})",
            "The client has indicated payment completion. The order is now awaiting administrative payment confirmation."
        );

        return redirect()->route('client.orders.index')->with('success', 'Payment submitted and pending verification.');
    }
}
