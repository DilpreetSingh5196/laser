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

    public function edit(Order $order)
    {
        if ($order->client_id != auth()->guard('client')->id()) {
            abort(403);
        }
        return view('client.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->client_id != auth()->guard('client')->id()) {
            abort(403);
        }
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'item_image' => 'nullable|image|max:2048'
        ]);

        $data = ['quantity' => $request->quantity];

        if ($request->hasFile('item_image')) {
            if ($order->item_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($order->item_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($order->item_image);
            }
            $data['item_image'] = $request->file('item_image')->store('orders', 'public');
        }

        $order->update($data);
        return redirect()->route('client.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        if ($order->client_id != auth()->guard('client')->id()) {
            abort(403);
        }
        if ($order->item_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($order->item_image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->item_image);
        }
        $order->delete();
        return redirect()->route('client.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function bill(Order $order)
    {
        if ($order->client_id != auth()->guard('client')->id()) {
            abort(403);
        }
        $order->load('client');
        return view('shared.bill', compact('order'));
    }
}
