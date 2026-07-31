@extends('layouts.client')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Create Order</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('client.orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Item Image</label>
                <input type="file" name="item_image" class="form-control @error('item_image') is-invalid @enderror" accept="image/*" required>
                @error('item_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit Order</button>
            <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
