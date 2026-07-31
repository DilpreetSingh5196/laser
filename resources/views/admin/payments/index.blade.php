@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Payment Verification</h2>

<form method="GET" action="{{ route('admin.payments.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by client or firm name" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
</form>

<div class="table-responsive d-none d-md-block">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Client</th>
                <th>Price</th>
                <th>Payment Status</th>
                <th>Actions / Remark</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->client->client_name }} <br><small class="text-muted">{{ $order->client->firm_name }}</small></td>
                <td>{{ $order->price ? 'Rs. ' . $order->price : 'N/A' }}</td>
                <td>
                    @if($order->payment_status == 'Pending Verification')
                        <span class="badge bg-warning text-dark">Pending Verification</span>
                    @elseif($order->payment_status == 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($order->payment_status == 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                    @endif
                </td>
                <td>
                    @if($order->payment_status == 'Pending Verification')
                    <form action="{{ route('admin.payments.verify', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="input-group mb-2">
                            <input type="text" name="admin_remark" class="form-control form-control-sm" placeholder="Remark (Optional)">
                        </div>
                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success w-100 mb-1">Approve</button>
                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger w-100">Reject</button>
                    </form>
                    @else
                        <span class="text-muted d-block mb-1">{{ $order->admin_remark ?? 'No remarks' }}</span>
                        @if($order->payment_status == 'Approved')
                            <a href="{{ route('admin.orders.bill', $order) }}" target="_blank" class="btn btn-sm btn-info w-100">View Bill</a>
                        @endif
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No payment verification requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-block d-md-none">
    @forelse($payments as $order)
        <div class="card shadow-sm mb-3 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Order #{{ $order->id }}</h5>
                    @if($order->payment_status == 'Pending Verification')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($order->payment_status == 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($order->payment_status == 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                    @endif
                </div>
                <div class="mb-2">
                    <strong>Client:</strong> {{ $order->client->client_name }} <span class="text-muted small">({{ $order->client->firm_name }})</span>
                </div>
                <div class="mb-3">
                    <strong>Price:</strong> {{ $order->price ? 'Rs. ' . $order->price : 'N/A' }}
                </div>
                
                <div class="mt-3">
                    @if($order->payment_status == 'Pending Verification')
                    <form action="{{ route('admin.payments.verify', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <input type="text" name="admin_remark" class="form-control" placeholder="Remark (Optional)">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="approve" class="btn btn-success flex-fill">Approve</button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger flex-fill">Reject</button>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-light p-2 small mb-2 border">Remark: {{ $order->admin_remark ?? 'None' }}</div>
                        @if($order->payment_status == 'Approved')
                            <a href="{{ route('admin.orders.bill', $order) }}" target="_blank" class="btn btn-info w-100 text-white">View Bill</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary text-center">No payment verification requests found.</div>
    @endforelse
</div>

{{ $payments->links('pagination::bootstrap-5') }}
@endsection
