@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Payment Verification</h2>

<form method="GET" action="{{ route('admin.payments.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by client or firm name" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
</form>

<div class="table-responsive">
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
                <td>{{ $order->price ? '$' . $order->price : 'N/A' }}</td>
                <td>
                    @if($order->payment_status == 'Pending Verification')
                        <span class="badge bg-warning text-dark">Pending Verification</span>
                    @elseif($order->payment_status == 'Paid')
                        <span class="badge bg-success">Paid</span>
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
                        <span class="text-muted">{{ $order->admin_remark ?? 'No remarks' }}</span>
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

{{ $payments->links('pagination::bootstrap-5') }}
@endsection
