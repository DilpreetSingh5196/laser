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
                    @if($order->item_image)
                        <img src="{{ asset('storage/' . $order->item_image) }}" alt="Item" width="50">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->price ? '$' . $order->price : 'N/A' }}</td>
                <td>
                    <span class="badge {{ $order->status == 'Pending' ? 'bg-warning text-dark' : 'bg-info text-dark' }}">{{ $order->status }}</span>
                </td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
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
