@extends('layouts.admin')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0"><a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-arrow-left"></i> Back</a> Edit Client</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Firm Name</label>
                    <input type="text" name="firm_name" class="form-control @error('firm_name') is-invalid @enderror" value="{{ old('firm_name', $client->firm_name) }}" required>
                    @error('firm_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name', $client->client_name) }}" required>
                    @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" value="{{ old('mobile_number', $client->mobile_number) }}" required>
                    @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $client->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ $client->status ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
