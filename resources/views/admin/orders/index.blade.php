@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Orders</h2>

<form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by client or firm name" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Item Image</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->client->client_name }} <br><small class="text-muted">{{ $order->client->firm_name }}</small></td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($order->items as $index => $item)
                            @if($item->item_image)
                                <div class="text-center" style="width: 40px;">
                                    <img src="{{ asset($item->item_image) }}" alt="Item" style="width: 35px; height: 35px; object-fit: cover; border-radius: 3px;">
                                    <div class="text-muted" style="font-size: 0.65rem;">#{{ $index + 1 }}</div>
                                </div>
                            @else
                                <div class="text-center" style="width: 40px;">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 3px;">
                                        <small class="text-muted" style="font-size: 0.65rem;">N/A</small>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.65rem;">#{{ $index + 1 }}</div>
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
                <td>
                    <span class="badge {{ $order->status == 'Pending' ? 'bg-warning text-dark' : 'bg-info text-dark' }}">{{ $order->status }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary me-1">View</a>
                        @if($order->payment_status == 'Approved')
                            <a href="{{ route('admin.orders.bill', $order) }}" target="_blank" class="btn btn-sm btn-info me-1">Bill</a>
                        @endif
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links('pagination::bootstrap-5') }}
@endsection
