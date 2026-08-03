<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $clientId = auth()->guard('client')->id();
        
        $totalOrders = Order::where('client_id', $clientId)->count();
        $pendingOrders = Order::where('client_id', $clientId)->where('status', 'Pending')->count();
        $waitingForPriceOrders = Order::where('client_id', $clientId)->where('status', 'Pending')->count();
        $paymentPendingOrders = Order::where('client_id', $clientId)->where('status', 'Payment Verification Pending')->count();
        $paidOrders = Order::where('client_id', $clientId)->whereIn('status', ['Approved', 'Approved with Cash'])->count();
        
        $recentOrders = Order::where('client_id', $clientId)->orderBy('id', 'desc')->take(10)->get();
        
        return view('client.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'waitingForPriceOrders',
            'paymentPendingOrders',
            'paidOrders',
            'recentOrders'
        ));
    }
}
