@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Payment Settings</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">Update Payment Details</div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">UPI ID</label>
                        <input type="text" name="payment_upi_id" class="form-control" value="{{ \App\Models\Setting::get('payment_upi_id') }}" placeholder="example@upi">
                        @error('payment_upi_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number (Pay)</label>
                        <input type="text" name="payment_phone" class="form-control" value="{{ \App\Models\Setting::get('payment_phone') }}" placeholder="Enter phone number">
                        @error('payment_phone')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">QR Code Image</label>
                        <input type="file" name="payment_qr_code" class="form-control" accept="image/*">
                        @error('payment_qr_code')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        
                        @if(\App\Models\Setting::get('payment_qr_code'))
                            <div class="mt-3">
                                <strong>Current QR Code:</strong><br>
                                <img src="{{ asset('storage/' . \App\Models\Setting::get('payment_qr_code')) }}" alt="QR Code" class="img-thumbnail mt-2" style="max-height: 200px;">
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
