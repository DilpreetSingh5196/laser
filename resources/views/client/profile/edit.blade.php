@extends('layouts.client')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Back</a>
    <h2 class="m-0">Client Profile</h2>
</div>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">Update Profile</div>
            <div class="card-body">
                <form action="{{ route('client.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Firm Name</label>
                        <input type="text" name="firm_name" class="form-control @error('firm_name') is-invalid @enderror" value="{{ old('firm_name', $client->firm_name) }}" required>
                        @error('firm_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client Name</label>
                        <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name', $client->client_name) }}" required>
                        @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" value="{{ old('mobile_number', $client->mobile_number) }}" required>
                        @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $client->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">Change Password</div>
            <div class="card-body">
                <form action="{{ route('client.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="client_current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#client_current_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="client_password" class="form-control @error('password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#client_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="client_password_confirmation" class="form-control" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#client_password_confirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('data-target'));
            const icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});
</script>
@endsection
