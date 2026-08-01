<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\NotificationService;

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
        $order->load(['client', 'items']);
        return view('admin.orders.show', compact('order'));
    }

    public function assignPrice(Request $request, Order $order)
    {
        $request->validate([
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0'
        ]);

        $totalPrice = 0;
        foreach ($request->prices as $itemId => $price) {
            $item = $order->items()->find($itemId);
            if ($item) {
                $item->update(['price' => $price]);
                $totalPrice += $price;
            }
        }

        $order->update([
            'price' => $totalPrice,
            'status' => 'Price Assigned'
        ]);

        NotificationService::sendOrderNotification(
            $order,
            "Price Assigned to Order (#{$order->id})",
            "An administrator has assigned a price of Rs. " . number_format((float)$totalPrice, 2) . " to your order. You may now proceed with payment verification."
        );

        return redirect()->route('admin.orders.index')->with('success', 'Prices assigned successfully.');
    }

    public function bill(Order $order)
    {
        $order->load(['client', 'items']);
        return view('shared.bill', compact('order'));
    }
    public function destroy(Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->item_image && file_exists(public_path($item->item_image))) {
                unlink(public_path($item->item_image));
            }
        }
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
