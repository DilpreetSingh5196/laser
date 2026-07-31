@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admins</h2>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">Create Admin</a>
</div>

<form method="GET" action="{{ route('admin.admins.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
</form>

<div class="table-responsive">
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

{{ $admins->links('pagination::bootstrap-5') }}
@endsection
