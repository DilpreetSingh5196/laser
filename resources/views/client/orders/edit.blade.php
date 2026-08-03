@extends('layouts.client')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><a href="{{ route('client.orders.index') }}" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-arrow-left"></i> Back</a> Edit Order #{{ $order->id }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('client.orders.update', $order) }}" method="POST" enctype="multipart/form-data" id="orderForm">
            @csrf
            @method('PUT')
            
            <div id="items-container">
                @foreach($order->items as $index => $item)
                    @php
                        $currentUnit = $item->length_cm || $item->breadth_cm ? 'cm' : 'inch';
                        $currentLength = $currentUnit == 'cm' ? $item->length_cm : $item->length_inch;
                        $currentBreadth = $currentUnit == 'cm' ? $item->breadth_cm : $item->breadth_inch;
                    @endphp
                    <div class="card mb-3 item-block border-info">
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Item #<span class="item-number">{{ $index + 1 }}</span></h5>
                            <button type="button" class="btn btn-sm btn-danger remove-item-btn">Remove</button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Item Image (Leave empty to keep current)</label>
                                <input type="file" name="items[{{ $index }}][item_image]" class="form-control" accept="image/*">
                                @if($item->item_image)
                                    <div class="mt-2">
                                        <img src="{{ asset($item->item_image) }}" alt="Current Image" width="100" class="img-thumbnail">
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Design File / Attachment (Leave empty to keep current)</label>
                                <input type="file" name="items[{{ $index }}][design_file]" class="form-control" accept=".cdr,.pdf,.ai,.eps,.svg,.dxf,.zip,.rar,.tar,.png,.jpg,.jpeg">
                                @if($item->design_file)
                                    <div class="mt-2">
                                        <a href="{{ asset($item->design_file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-download"></i> Current Design File ({{ strtoupper(pathinfo($item->design_file, PATHINFO_EXTENSION)) }})
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Measurement Unit <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="items[{{ $index }}][unit]" value="inch" {{ $currentUnit == 'inch' ? 'checked' : '' }}>
                                    <label class="form-check-label">Inches</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="items[{{ $index }}][unit]" value="cm" {{ $currentUnit == 'cm' ? 'checked' : '' }}>
                                    <label class="form-check-label">Centimeters</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Item Length</label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][length]" class="form-control" value="{{ $currentLength }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Item Breadth</label>
                                    <input type="number" step="0.01" name="items[{{ $index }}][breadth]" class="form-control" value="{{ $currentBreadth }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="items[{{ $index }}][description]" class="form-control" rows="2">{{ $item->description }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @error('items')<div class="text-danger mb-3">{{ $message }}</div>@enderror

            <div class="mb-4">
                <button type="button" class="btn btn-outline-primary" id="add-item-btn">
                    <i class="bi bi-plus-circle"></i> Add Another Item
                </button>
            </div>

            <button type="submit" class="btn btn-primary">Update Order</button>
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
                <label class="form-label">Design File / Attachment (Optional)</label>
                <input type="file" name="items[__INDEX__][design_file]" class="form-control" accept=".cdr,.pdf,.ai,.eps,.svg,.dxf,.zip,.rar,.tar,.png,.jpg,.jpeg">
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
        let itemIndex = {{ count($order->items) }};

        // Attach event listeners to existing remove buttons
        container.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                btn.closest('.item-block').remove();
                updateItemNumbers();
            });
        });

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
                
                // Hide remove button if there's only 1 item
                const removeBtn = block.querySelector('.remove-item-btn');
                if (blocks.length === 1) {
                    removeBtn.style.display = 'none';
                } else {
                    removeBtn.style.display = 'block';
                }
            });
        }

        updateItemNumbers();

        addBtn.addEventListener('click', addItem);
    });
</script>
@endsection
