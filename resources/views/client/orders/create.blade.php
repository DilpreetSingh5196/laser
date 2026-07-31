@extends('layouts.client')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><a href="{{ route('client.orders.index') }}" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-arrow-left"></i> Back</a> Create Order</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('client.orders.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
            @csrf
            
            <div id="items-container">
                <!-- Items will be injected here -->
            </div>
            
            @error('items')<div class="text-danger mb-3">{{ $message }}</div>@enderror

            <div class="mb-4">
                <button type="button" class="btn btn-outline-primary" id="add-item-btn">
                    <i class="bi bi-plus-circle"></i> Add Another Item
                </button>
            </div>

            <button type="submit" class="btn btn-primary">Submit Order</button>
            <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<!-- Template for an Item -->
<template id="item-template">
    <div class="card mb-3 item-block border-info">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Item #<span class="item-number"></span></h5>
            <button type="button" class="btn btn-sm btn-danger remove-item-btn">Remove</button>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Item Image <span class="text-danger">*</span></label>
                <input type="file" name="items[__INDEX__][item_image]" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="items[__INDEX__][quantity]" class="form-control" value="1" min="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Measurement Unit <span class="text-danger">*</span></label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="items[__INDEX__][unit]" value="inch" checked>
                    <label class="form-check-label">Inches</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="items[__INDEX__][unit]" value="cm">
                    <label class="form-check-label">Centimeters</label>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Item Length</label>
                    <input type="number" step="0.01" name="items[__INDEX__][length]" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Item Breadth</label>
                    <input type="number" step="0.01" name="items[__INDEX__][breadth]" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="items[__INDEX__][description]" class="form-control" rows="2"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-template');
        const addBtn = document.getElementById('add-item-btn');
        let itemIndex = 0;

        function addItem() {
            const clone = template.content.cloneNode(true);
            const block = clone.querySelector('.item-block');
            
            // Replace __INDEX__ with the current itemIndex
            block.innerHTML = block.innerHTML.replace(/__INDEX__/g, itemIndex);
            
            // Update Item Number text
            block.querySelector('.item-number').textContent = itemIndex + 1;
            
            // Handle remove button
            block.querySelector('.remove-item-btn').addEventListener('click', function() {
                block.remove();
                updateItemNumbers();
            });

            container.appendChild(clone);
            itemIndex++;
            updateItemNumbers();
        }

        function updateItemNumbers() {
            const blocks = container.querySelectorAll('.item-block');
            blocks.forEach((block, index) => {
                block.querySelector('.item-number').textContent = index + 1;
                // We don't necessarily need to rewrite names, as long as indexes are unique.
                // But it's cleaner to keep them sequential or just leave them.
                // Since it's a dynamic form and PHP doesn't care about sequential keys for arrays, we leave the original index intact.
                
                // Hide remove button if there's only 1 item
                const removeBtn = block.querySelector('.remove-item-btn');
                if (blocks.length === 1) {
                    removeBtn.style.display = 'none';
                } else {
                    removeBtn.style.display = 'block';
                }
            });
        }

        // Add initial item
        addItem();

        addBtn.addEventListener('click', addItem);
    });
</script>
@endsection
