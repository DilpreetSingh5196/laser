@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Clients</h2>
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">Create Client</a>
</div>

<form method="GET" action="{{ route('admin.clients.index') }}" class="mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-sm-8 col-md-9">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search clients..." value="{{ request('search') }}">
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
    <table class="table table-bordered table-striped table-hover">
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
            <tr class="client-row" style="cursor: pointer;" title="Click to view order statistics" data-firm="{{ $client->firm_name }}" data-name="{{ $client->client_name }}" data-orders="{{ $client->orders_count }}" data-payment="{{ number_format($client->orders_sum_price ?? 0, 2) }}">
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

<div class="d-block d-md-none">
    @forelse($clients as $client)
        <div class="card shadow-sm mb-3 border-0 client-row" style="cursor: pointer;" title="Click to view order statistics" data-firm="{{ $client->firm_name }}" data-name="{{ $client->client_name }}" data-orders="{{ $client->orders_count }}" data-payment="{{ number_format($client->orders_sum_price ?? 0, 2) }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">{{ $client->client_name }}</h5>
                    <span class="badge {{ $client->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $client->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Firm Name:</strong> {{ $client->firm_name }}
                </div>
                <div class="mb-2">
                    <strong>Mobile:</strong> <a href="tel:{{ $client->mobile_number }}" class="text-decoration-none">{{ $client->mobile_number }}</a>
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-warning flex-fill text-dark">Edit</a>
                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="flex-fill d-flex" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger w-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary text-center">No clients found.</div>
    @endforelse
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
    <div class="text-muted small mb-2 mb-md-0">
        Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} entries
    </div>
    <div>
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Client Summary Modal -->
<div class="modal fade" id="clientSummaryModal" tabindex="-1" aria-labelledby="clientSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="clientSummaryModalLabel">
                    <i class="bi bi-info-circle-fill me-2 text-info"></i><span id="modalFirmName"></span> - Client Overview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 text-center">
                    <h5 class="mb-1 fw-bold text-dark" id="modalClientName"></h5>
                    <p class="text-muted small mb-0">Order & Payment Statistics</p>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center border">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Total Orders</div>
                            <div class="fs-4 fw-bold text-primary" id="modalTotalOrders">0</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center border">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Total Payment</div>
                            <div class="fs-4 fw-bold text-success" id="modalTotalPayment">Rs. 0.00</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clientRows = document.querySelectorAll('.client-row');
        const modalElement = document.getElementById('clientSummaryModal');
        const clientModal = new bootstrap.Modal(modalElement);

        clientRows.forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) {
                    return;
                }

                const firm = this.getAttribute('data-firm');
                const name = this.getAttribute('data-name');
                const orders = this.getAttribute('data-orders');
                const payment = this.getAttribute('data-payment');

                document.getElementById('modalFirmName').textContent = firm;
                document.getElementById('modalClientName').textContent = name;
                document.getElementById('modalTotalOrders').textContent = orders;
                document.getElementById('modalTotalPayment').textContent = 'Rs. ' + payment;

                clientModal.show();
            });
        });
    });
</script>
@endsection
