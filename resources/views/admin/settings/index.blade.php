@extends('layouts.admin')

@section('content')
<h2 class="mb-4">General & Payment Settings</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-dark text-white">Update Payment Details</div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3 border-bottom pb-2">Company Information</h5>
                    <div class="mb-3">
                        <label class="form-label">Company/Logo Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ \App\Models\Setting::get('company_name', 'Jai Maa Durga') }}" placeholder="Jai Maa Durga">
                        <small class="text-muted">This will change the logo text across the site and bills.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Address</label>
                        <textarea name="company_address" class="form-control" rows="2" placeholder="e.g. Patiala, Punjab">{{ \App\Models\Setting::get('company_address', 'Patiala, Punjab') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Phones (for Bill)</label>
                        <input type="text" name="company_phones" class="form-control" value="{{ \App\Models\Setting::get('company_phones') }}" placeholder="e.g. 9876543210, 1234567890">
                        <small class="text-muted">Separate multiple numbers with commas.</small>
                    </div>

                    <h5 class="mb-3 mt-4 border-bottom pb-2">Payment Verification</h5>

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
