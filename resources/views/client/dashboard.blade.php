@extends('layouts.client')

@section('page_title', 'Client Dashboard Overview')

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
<div class="table-responsive">
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
                    @if($order->item_image)
                        <img src="{{ asset('storage/' . $order->item_image) }}" alt="Item" width="50">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $order->quantity }}</td>
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
@endsection
