<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('client');
        if ($request->has('search')) {
            $query->whereHas('client', function($q) use ($request) {
                $q->where('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('firm_name', 'like', '%' . $request->search . '%');
            });
        }
        $orders = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('client');
        return view('admin.orders.show', compact('order'));
    }

    public function assignPrice(Request $request, Order $order)
    {
        $request->validate([
            'price' => 'required|numeric|min:0'
        ]);

        $order->update([
            'price' => $request->price,
            'status' => 'Price Assigned'
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Price assigned successfully.');
    }
}
