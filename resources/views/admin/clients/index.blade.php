@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Clients</h2>
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">Create Client</a>
</div>

<form method="GET" action="{{ route('admin.clients.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search clients..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Firm Name</th>
                <th>Client Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->firm_name }}</td>
                <td>{{ $client->client_name }}</td>
                <td>{{ $client->mobile_number }}</td>
                <td>{{ $client->email }}</td>
                <td>
                    @if($client->status)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No clients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $clients->links('pagination::bootstrap-5') }}
@endsection
