<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $clientId = auth()->guard('client')->id();
        $orders = Order::where('client_id', $clientId)->orderBy('id', 'desc')->paginate(10);
        return view('client.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('client.orders.create');
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = auth()->guard('client')->id();
        
        if ($request->hasFile('item_image')) {
            $data['item_image'] = $request->file('item_image')->store('orders', 'public');
        }

        Order::create($data);
        return redirect()->route('client.orders.index')->with('success', 'Order created successfully.');
    }
}
