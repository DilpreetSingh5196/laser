<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

        return redirect()->route('client.orders.index')->with('success', 'Payment submitted and pending verification.');
    }
}
