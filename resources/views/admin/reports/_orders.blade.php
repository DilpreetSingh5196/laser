<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <strong>Total Orders:</strong> {{ $orders->total() }}
    </div>
    <div class="d-flex align-items-center">
        <label class="me-2 fw-bold text-muted small">Show:</label>
        <select class="form-select form-select-sm limit-select" data-year="{{ $year }}" data-month="{{ $month }}" style="width: 80px;">
            <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ $limit == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
        </select>
    </div>
</div>

@if($orders->isEmpty())
    <p class="text-muted">No orders found for this month.</p>
@else
    <!-- Desktop View (Table) -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>
                        {{ $order->client->firm_name }}<br>
                        <small class="text-muted">{{ $order->client->mobile_number }}</small>
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status == 'Completed' ? 'success' : ($order->status == 'Pending' ? 'warning' : 'primary') }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>Rs. {{ number_format($order->price, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info text-white">View</a>
                        <a href="{{ route('admin.orders.bill', $order) }}" target="_blank" class="btn btn-sm btn-secondary">Bill</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile View (Cards) -->
    <div class="d-md-none">
        @foreach($orders as $order)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span>Order #{{ $order->id }}</span>
                <span class="badge bg-{{ $order->status == 'Completed' ? 'success' : ($order->status == 'Pending' ? 'warning' : 'primary') }}">
                    {{ $order->status }}
                </span>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Client:</strong> {{ $order->client->firm_name }} ({{ $order->client->mobile_number }})</p>
                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
                <p class="mb-3"><strong>Total Price:</strong> Rs. {{ number_format($order->price, 2) }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info text-white flex-grow-1"><i class="bi bi-eye"></i> View</a>
                    <a href="{{ route('admin.orders.bill', $order) }}" target="_blank" class="btn btn-secondary flex-grow-1"><i class="bi bi-receipt"></i> Bill</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
@endif
