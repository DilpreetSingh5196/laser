@extends('layouts.client')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Edit Order #{{ $order->id }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('client.orders.update', $order) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Item Image (Leave empty to keep current)</label>
                <input type="file" name="item_image" class="form-control @error('item_image') is-invalid @enderror" accept="image/*">
                @error('item_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($order->item_image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $order->item_image) }}" alt="Current Image" width="100" class="img-thumbnail">
                    </div>
                @endif
            </div>
            
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $order->quantity) }}" min="1" required>
                @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Update Order</button>
            <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
