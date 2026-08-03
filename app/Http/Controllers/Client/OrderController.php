<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $clientId = auth()->guard('client')->id();
        $limit = (int) $request->input('limit', 10);
        if ($limit <= 0) $limit = 10;
        $orders = Order::where('client_id', $clientId)->orderBy('id', 'desc')->paginate($limit)->appends($request->query());
        return view('client.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('client.orders.create');
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $order = Order::create(['client_id' => auth()->guard('client')->id()]);
        
        $attachments = [];
        foreach ($data['items'] as $index => $itemData) {
            // Safety guard: prevent creating accidental empty item entries if submitted without image or dimensions
            if (empty($itemData['item_image']) && empty($itemData['description']) && empty($itemData['length']) && empty($itemData['breadth']) && empty($itemData['design_file'])) {
                continue;
            }

            $item = [
                'quantity' => $itemData['quantity'],
                'description' => $itemData['description'] ?? null,
            ];
            
            if (isset($itemData['unit'])) {
                if ($itemData['unit'] == 'inch') {
                    $item['length_inch'] = $itemData['length'] ?? null;
                    $item['breadth_inch'] = $itemData['breadth'] ?? null;
                } else {
                    $item['length_cm'] = $itemData['length'] ?? null;
                    $item['breadth_cm'] = $itemData['breadth'] ?? null;
                }
            }

            if (isset($itemData['item_image']) && $itemData['item_image']) {
                $uploadDir = public_path('orders');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $imageName = time() . '_' . uniqid() . '.' . $itemData['item_image']->getClientOriginalExtension();
                $itemData['item_image']->move($uploadDir, $imageName);
                $item['item_image'] = 'orders/' . $imageName;
            }

            if (isset($itemData['design_file']) && $itemData['design_file'] instanceof \Illuminate\Http\UploadedFile && $itemData['design_file']->isValid()) {
                $uploadDir = public_path('design_files');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . uniqid() . '.' . $itemData['design_file']->getClientOriginalExtension();
                $itemData['design_file']->move($uploadDir, $fileName);
                $item['design_file'] = 'design_files/' . $fileName;
            }

            $order->items()->create($item);
        }

        NotificationService::sendOrderNotification(
            $order,
            "New Order Created (#{$order->id})",
            "A new order has been submitted and is pending administrative price assignment."
        );

        return redirect()->route('client.orders.index')->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        if ($order->client_id != auth()->guard('client')->id()) {
            abort(403);
        }
        $order->load('items');
        return view('client.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->client_id != auth()->guard('client')->id()) {
            abort(403);
        }
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'items.*.design_file' => 'nullable|file|max:15360',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.length' => 'nullable|numeric|min:0',
            'items.*.breadth' => 'nullable|numeric|min:0',
            'items.*.unit' => 'required|in:inch,cm',
            'items.*.description' => 'nullable|string',
        ]);

        $existingItemIds = $order->items->pluck('id')->toArray();
        $submittedItemIds = [];

        foreach ($request->items as $index => $itemData) {
            $item = [
                'quantity' => $itemData['quantity'],
                'description' => $itemData['description'] ?? null,
                'length_inch' => null,
                'length_cm' => null,
                'breadth_inch' => null,
                'breadth_cm' => null,
            ];
            
            if (isset($itemData['unit'])) {
                if ($itemData['unit'] == 'inch') {
                    $item['length_inch'] = $itemData['length'] ?? null;
                    $item['breadth_inch'] = $itemData['breadth'] ?? null;
                } else {
                    $item['length_cm'] = $itemData['length'] ?? null;
                    $item['breadth_cm'] = $itemData['breadth'] ?? null;
                }
            }

            if (isset($itemData['id']) && in_array($itemData['id'], $existingItemIds)) {
                $orderItem = $order->items()->find($itemData['id']);
                $submittedItemIds[] = $orderItem->id;
                
                if (isset($itemData['item_image']) && $itemData['item_image']) {
                    if ($orderItem->item_image && file_exists(public_path($orderItem->item_image))) {
                        @unlink(public_path($orderItem->item_image));
                    }
                    $uploadDir = public_path('orders');
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $imageName = time() . '_' . uniqid() . '.' . $itemData['item_image']->getClientOriginalExtension();
                    $itemData['item_image']->move($uploadDir, $imageName);
                    $item['item_image'] = 'orders/' . $imageName;
                }

                if (isset($itemData['design_file']) && $itemData['design_file']) {
                    if ($orderItem->design_file && file_exists(public_path($orderItem->design_file))) {
                        @unlink(public_path($orderItem->design_file));
                    }
                    $uploadDir = public_path('design_files');
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileName = time() . '_' . uniqid() . '.' . $itemData['design_file']->getClientOriginalExtension();
                    $itemData['design_file']->move($uploadDir, $fileName);
                    $item['design_file'] = 'design_files/' . $fileName;
                }
                
                $orderItem->update($item);
            } else {
                if (isset($itemData['item_image']) && $itemData['item_image']) {
                    $uploadDir = public_path('orders');
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $imageName = time() . '_' . uniqid() . '.' . $itemData['item_image']->getClientOriginalExtension();
                    $itemData['item_image']->move($uploadDir, $imageName);
                    $item['item_image'] = 'orders/' . $imageName;
                }
                if (isset($itemData['design_file']) && $itemData['design_file']) {
                    $uploadDir = public_path('design_files');
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileName = time() . '_' . uniqid() . '.' . $itemData['design_file']->getClientOriginalExtension();
                    $itemData['design_file']->move($uploadDir, $fileName);
                    $item['design_file'] = 'design_files/' . $fileName;
                }
                $newItem = $order->items()->create($item);
                $submittedItemIds[] = $newItem->id;
            }
        }

        // Delete items that were removed in the UI
        $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
        foreach ($itemsToDelete as $itemId) {
            $orderItem = $order->items()->find($itemId);
            if ($orderItem && $orderItem->item_image && file_exists(public_path($orderItem->item_image))) {
                @unlink(public_path($orderItem->item_image));
            }
            if ($orderItem && $orderItem->design_file && file_exists(public_path($orderItem->design_file))) {
                @unlink(public_path($orderItem->design_file));
            }
            if($orderItem) $orderItem->delete();
        }

        return redirect()->route('client.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        if ($order->client_id != auth()->guard('client')->id()) {
            abort(403);
        }
        foreach ($order->items as $item) {
            if ($item->item_image && file_exists(public_path($item->item_image))) {
                @unlink(public_path($item->item_image));
            }
            if ($item->design_file && file_exists(public_path($item->design_file))) {
                @unlink(public_path($item->design_file));
            }
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
