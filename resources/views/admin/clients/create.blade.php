@extends('layouts.admin')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0"><a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-arrow-left"></i> Back</a> Create Client</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clients.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Firm Name</label>
                    <input type="text" name="firm_name" class="form-control @error('firm_name') is-invalid @enderror" value="{{ old('firm_name') }}" required>
                    @error('firm_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name') }}" required>
                    @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" value="{{ old('mobile_number') }}" required>
                    @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                <label class="form-check-label" for="status">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
