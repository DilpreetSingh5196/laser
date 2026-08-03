<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $priceAssignedOrders = Order::where('status', 'Price Assigned')->count();
        $paymentPendingOrders = Order::where('status', 'Payment Verification Pending')->count();
        $paidOrders = Order::whereIn('status', ['Approved', 'Approved with Cash'])->count();
        
        $recentOrders = Order::with('client')->orderBy('id', 'desc')->take(10)->get();
        
        return view('admin.dashboard', compact(
            'totalClients',
            'totalOrders',
            'pendingOrders',
            'priceAssignedOrders',
            'paymentPendingOrders',
            'paidOrders',
            'recentOrders'
        ));
    }
}
