@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admins</h2>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">Create Admin</a>
</div>

<form method="GET" action="{{ route('admin.admins.index') }}" class="mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-sm-8 col-md-9">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </div>
        <div class="col-12 col-sm-4 col-md-3 d-flex justify-content-sm-end align-items-center">
            <label class="me-2 text-muted small fw-bold text-nowrap">Show:</label>
            <select name="limit" class="form-select form-select-sm" style="width: 85px;" onchange="this.form.submit()">
                <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="ms-2 text-muted small text-nowrap">entries</span>
        </div>
    </div>
</form>

<div class="table-responsive d-none d-md-block">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-warning">Edit</a>
                    @if(auth()->guard('admin')->id() !== $admin->id)
                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No admins found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-block d-md-none">
    @forelse($admins as $admin)
        <div class="card shadow-sm mb-3 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">{{ $admin->name }}</h5>
                    <span class="badge bg-secondary text-white">ID: {{ $admin->id }}</span>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> <a href="mailto:{{ $admin->email }}" class="text-decoration-none">{{ $admin->email }}</a>
                </div>
                <div class="mb-3">
                    <strong>Created:</strong> {{ $admin->created_at->format('Y-m-d H:i') }}
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning flex-fill text-dark">Edit</a>
                    @if(auth()->guard('admin')->id() !== $admin->id)
                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="flex-fill d-flex" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger w-100">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary text-center">No admins found.</div>
    @endforelse
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
    <div class="text-muted small mb-2 mb-md-0">
        Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of {{ $admins->total() }} entries
    </div>
    <div>
        {{ $admins->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
