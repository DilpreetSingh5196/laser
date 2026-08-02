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
                                <img src="{{ asset(\App\Models\Setting::get('payment_qr_code')) }}" alt="QR Code" class="img-thumbnail mt-2" style="max-height: 200px;">
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-4 mt-md-0">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span>Email Notifications & SMTP Settings</span>
                <i class="bi bi-envelope"></i>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update-email') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3 border-bottom pb-2">Admin Notification Recipients</h5>
                    <p class="text-muted small mb-3">Add multiple email addresses that should receive immediate email alerts whenever orders are created, priced, or completed.</p>
                    
                    <div id="emails-container">
                        @php
                            $adminEmails = \App\Services\NotificationService::getAdminEmails();
                            if (empty($adminEmails)) {
                                $adminEmails = [auth()->guard('admin')->user()->email ?? ''];
                            }
                        @endphp

                        @foreach($adminEmails as $index => $email)
                        <div class="input-group mb-2 email-item">
                            <input type="email" name="notification_emails[]" class="form-control" value="{{ $email }}" placeholder="admin@example.com">
                            <button type="button" class="btn btn-outline-danger remove-email-btn" title="Remove"><i class="bi bi-trash"></i> Remove</button>
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-4 mt-2">
                        <button type="button" id="add-email-btn" class="btn btn-outline-success btn-sm font-weight-bold">
                            <i class="bi bi-plus-circle-fill me-1"></i> + Add More Email
                        </button>
                    </div>

                    <h5 class="mb-3 mt-4 border-bottom pb-2">SMTP Mail Server Settings</h5>
                    
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control" value="{{ \App\Models\Setting::get('smtp_host') }}" placeholder="smtp.hostinger.com / smtp.gmail.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Port</label>
                            <input type="number" name="smtp_port" class="form-control" value="{{ \App\Models\Setting::get('smtp_port', 587) }}" placeholder="587 / 465">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Encryption Type</label>
                            <select name="smtp_encryption" class="form-select">
                                @php $enc = \App\Models\Setting::get('smtp_encryption', 'tls'); @endphp
                                <option value="tls" {{ $enc == 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                                <option value="ssl" {{ $enc == 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                                <option value="none" {{ $enc == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">SMTP Username / Email</label>
                            <input type="text" name="smtp_username" class="form-control" value="{{ \App\Models\Setting::get('smtp_username') }}" placeholder="user@domain.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">SMTP Password</label>
                            <input type="password" name="smtp_password" class="form-control" value="{{ \App\Models\Setting::get('smtp_password') }}" placeholder="Enter SMTP/App Password">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Sender Email Address</label>
                            <input type="email" name="smtp_from_address" class="form-control" value="{{ \App\Models\Setting::get('smtp_from_address') }}" placeholder="noreply@domain.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Sender Name</label>
                            <input type="text" name="smtp_from_name" class="form-control" value="{{ \App\Models\Setting::get('smtp_from_name', \App\Models\Setting::get('company_name', 'Jai Maa Durga')) }}" placeholder="Jai Maa Durga">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-2">Save Email Settings</button>
                </form>
            </div>
        </div>

        <div class="card border-info">
            <div class="card-header bg-info text-dark fw-bold">Test Your SMTP Configuration</div>
            <div class="card-body">
                <form action="{{ route('admin.settings.test-email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Recipient Email Address for Test</label>
                        <input type="email" name="test_email" class="form-control" value="{{ auth()->guard('admin')->user()->email ?? '' }}" required placeholder="Enter email to check delivery">
                    </div>
                    <button type="submit" class="btn btn-outline-info text-dark fw-bold"><i class="bi bi-send me-1"></i> Send Test Email</button>
                    <small class="d-block text-muted mt-2">Clicking this will instantly attempt to deliver a test email using your configured SMTP settings above.</small>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailsContainer = document.getElementById('emails-container');
    const addEmailBtn = document.getElementById('add-email-btn');

    // Add new email input group on button click
    addEmailBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'input-group mb-2 email-item';
        div.innerHTML = `
            <input type="email" name="notification_emails[]" class="form-control" placeholder="admin@example.com" required>
            <button type="button" class="btn btn-outline-danger remove-email-btn" title="Remove"><i class="bi bi-trash"></i> Remove</button>
        `;
        emailsContainer.appendChild(div);
    });

    // Remove email input group when trash button clicked
    emailsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-email-btn') || e.target.closest('.remove-email-btn')) {
            const item = e.target.closest('.email-item');
            if (item) {
                // Keep at least one box if desired, or let them delete all
                if (emailsContainer.querySelectorAll('.email-item').length > 1) {
                    item.remove();
                } else {
                    const input = item.querySelector('input');
                    if(input) input.value = '';
                }
            }
        }
    });
});
</script>
@endsection
