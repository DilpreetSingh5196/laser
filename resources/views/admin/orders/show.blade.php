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
            <div class="card-header bg-dark text-white">Order Summary</div>
            <div class="card-body">
                <p><strong>Total Items:</strong> {{ count($order->items) }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Payment Status:</strong> {{ $order->payment_status }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-3">Items</h3>
<div class="row">
    @foreach($order->items as $index => $item)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">Item #{{ $index + 1 }}</div>
                <div class="card-body">
                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                    @if($item->length_inch || $item->length_cm)
                        <p><strong>Length:</strong> {{ $item->length_inch ? $item->length_inch . ' Inch' : $item->length_cm . ' cm' }}</p>
                    @endif
                    @if($item->breadth_inch || $item->breadth_cm)
                        <p><strong>Breadth:</strong> {{ $item->breadth_inch ? $item->breadth_inch . ' Inch' : $item->breadth_cm . ' cm' }}</p>
                    @endif
                    @if($item->description)
                        <p><strong>Description:</strong> {{ $item->description }}</p>
                    @endif
                    @if($item->item_image)
                    <div class="mt-3">
                        <strong>Item Image (Click to enlarge):</strong><br>
                        <a href="{{ asset($item->item_image) }}" target="_blank">
                            <img src="{{ asset($item->item_image) }}" alt="Item" class="img-fluid img-thumbnail mt-2" style="max-height: 200px; object-fit: cover;">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">Assign Price</div>
    <div class="card-body">
        <form action="{{ route('admin.orders.assign-price', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="input-group">
                <span class="input-group-text">Rs.</span>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter Price" value="{{ old('price', $order->price) }}" required>
                <button type="submit" class="btn btn-primary">Approve / Assign Price</button>
            </div>
            @error('price')<div class="text-danger mt-1">{{ $message }}</div>@enderror
        </form>
    </div>
</div>
<a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
@endsection
