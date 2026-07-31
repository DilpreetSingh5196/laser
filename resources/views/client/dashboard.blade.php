@extends('layouts.client')

@section('page_title', 'Client Dashboard Overview')
@section('mobile_title', 'Client')

@section('content')
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white text-center">
            <div class="card-body">
                <h3>{{ $totalOrders }}</h3>
                <p class="mb-0">Total Orders</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-warning text-dark text-center">
            <div class="card-body">
                <h3>{{ $pendingOrders }}</h3>
                <p class="mb-0">Pending Orders</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-secondary text-white text-center">
            <div class="card-body">
                <h3>{{ $waitingForPriceOrders }}</h3>
                <p class="mb-0">Waiting for Price</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-danger text-white text-center">
            <div class="card-body">
                <h3>{{ $paymentPendingOrders }}</h3>
                <p class="mb-0">Payment Pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white text-center">
            <div class="card-body">
                <h3>{{ $paidOrders }}</h3>
                <p class="mb-0">Paid Orders</p>
            </div>
        </div>
    </div>
</div>

<h4>Recent Orders</h4>
<div class="table-responsive d-none d-md-block">
    <table class="table table-striped table-bordered">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Status</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($order->items as $index => $item)
                            @if($item->item_image)
                                <img src="{{ asset($item->item_image) }}" alt="Item" style="width: 35px; height: 35px; object-fit: cover; border-radius: 3px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 3px;">
                                    <small class="text-muted" style="font-size: 0.65rem;">N/A</small>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </td>
                <td>
                    <ul class="list-unstyled mb-0" style="font-size: 0.8rem;">
                        @foreach($order->items as $index => $item)
                            <li><strong>#{{ $index + 1 }}:</strong> {{ $item->quantity }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $order->price ? 'Rs. ' . $order->price : 'N/A' }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->payment_status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No recent orders.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-block d-md-none">
    @forelse($recentOrders as $order)
        <div class="card shadow-sm mb-3 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <strong>Order #{{ $order->id }}</strong>
                <span class="badge bg-light text-dark">{{ $order->status }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3 d-flex flex-wrap gap-2">
                    @foreach($order->items as $index => $item)
                        @if($item->item_image)
                            <div class="text-center">
                                <img src="{{ asset($item->item_image) }}" alt="Item" width="45" height="45" style="object-fit: cover; border-radius: 4px;">
                            </div>
                        @else
                            <div class="text-center">
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 4px;">
                                    <small class="text-muted" style="font-size: 0.7rem;">N/A</small>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Price:</span>
                    <span>{{ $order->price ? 'Rs. ' . $order->price : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Payment:</span>
                    <span class="badge {{ $order->payment_status == 'Approved' ? 'bg-success' : ($order->payment_status == 'Rejected' ? 'bg-danger' : 'bg-secondary') }}">{{ $order->payment_status }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary text-center">No recent orders.</div>
    @endforelse
</div>
@endsection
