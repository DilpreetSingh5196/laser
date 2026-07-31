@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Order Details #{{ $order->id }}</h2>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Client Information</div>
            <div class="card-body">
                <p><strong>Firm Name:</strong> {{ $order->client->firm_name }}</p>
                <p><strong>Client Name:</strong> {{ $order->client->client_name }}</p>
                <p><strong>Mobile:</strong> {{ $order->client->mobile_number }}</p>
                <p><strong>Email:</strong> {{ $order->client->email }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Order Information</div>
            <div class="card-body">
                <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Payment Status:</strong> {{ $order->payment_status }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                @if($order->item_image)
                <div class="mb-3">
                    <strong>Item Image:</strong><br>
                    <img src="{{ asset('storage/' . $order->item_image) }}" alt="Item" class="img-fluid img-thumbnail mt-2" style="max-height: 200px;">
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">Assign Price</div>
    <div class="card-body">
        <form action="{{ route('admin.orders.assign-price', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter Price" value="{{ old('price', $order->price) }}" required>
                <button type="submit" class="btn btn-primary">Approve / Assign Price</button>
            </div>
            @error('price')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </form>
    </div>
</div>
<a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
@endsection
