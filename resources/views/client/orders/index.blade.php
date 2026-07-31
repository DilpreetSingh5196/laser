@extends('layouts.client')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Orders</h2>
    <a href="{{ route('client.orders.create') }}" class="btn btn-primary">Create Order</a>
</div>

<div class="table-responsive d-none d-md-block">
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Order ID</th>
                <th>Item Image</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Price</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($order->items as $index => $item)
                            @if($item->item_image)
                                <div class="text-center">
                                    <img src="{{ asset($item->item_image) }}" alt="Item {{ $index + 1 }}" width="45" height="45" style="cursor: pointer; object-fit: cover; border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#imageModal{{ $item->id }}">
                                    <div class="text-muted" style="font-size: 0.7rem;">#{{ $index + 1 }}</div>
                                </div>
                                
                                <!-- Image Modal -->
                                <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title">Item #{{ $index + 1 }} Image (Order #{{ $order->id }})</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-center">
                                        <img src="{{ asset($item->item_image) }}" class="img-fluid" alt="Item">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 4px;">
                                        <small class="text-muted">N/A</small>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.7rem;">#{{ $index + 1 }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </td>
                <td>
                    <ul class="list-unstyled mb-0" style="font-size: 0.85rem;">
                        @foreach($order->items as $index => $item)
                            <li><strong>#{{ $index + 1 }}:</strong> {{ $item->quantity }}</li>
                        @endforeach
                    </ul>
                </td>
                <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                <td>{{ $order->price ? 'Rs. ' . $order->price : 'Waiting for Admin' }}</td>
                <td>
                    <span class="badge {{ $order->payment_status == 'Approved' ? 'bg-success' : ($order->payment_status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ $order->payment_status }}
                    </span>
                    @if($order->admin_remark)
                        <br><small class="text-muted">Remark: {{ $order->admin_remark }}</small>
                    @endif
                </td>
                <td>
                    @if($order->status == 'Price Assigned')
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#payModal{{ $order->id }}">
                            Pay Now
                        </button>
                        
                        <!-- Pay Modal -->
                        <div class="modal fade" id="payModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form action="{{ route('client.payment.pay', $order) }}" method="POST">
                                  @csrf
                                  @method('PUT')
                                  <div class="modal-header">
                                    <h5 class="modal-title">Confirm Payment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body text-center">
                                    <p>Please make a payment of <strong>Rs. {{ $order->price }}</strong> for Order #{{ $order->id }}.</p>
                                    
                                    @php
                                        $qr = \App\Models\Setting::get('payment_qr_code');
                                        $upi = \App\Models\Setting::get('payment_upi_id');
                                        $phone = \App\Models\Setting::get('payment_phone');
                                    @endphp

                                    @if($qr || $upi || $phone)
                                        <div class="card my-3 shadow-sm">
                                            <div class="card-body">
                                                <h6 class="mb-3 text-primary">Payment Details</h6>
                                                
                                                @if($qr)
                                                    <img src="{{ asset('storage/' . $qr) }}" alt="QR Code" class="img-fluid img-thumbnail mb-3" style="max-width: 200px;">
                                                @endif
                                                
                                                @if($upi)
                                                    <p class="mb-1"><strong>UPI ID:</strong> {{ $upi }}</p>
                                                @endif
                                                
                                                @if($phone)
                                                    <p class="mb-0"><strong>Phone:</strong> {{ $phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <p class="text-muted"><small>After making the payment, click "Proceed with Payment" to notify the admin.</small></p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Proceed with Payment</button>
                                  </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    @elseif($order->payment_status == 'Approved')
                        <a href="{{ route('client.orders.bill', $order) }}" target="_blank" class="btn btn-sm btn-info">View Bill</a>
                    @else
                        <button class="btn btn-sm btn-secondary" disabled>Pay Now</button>
                    @endif
                    
                    @if($order->status == 'Pending')
                        <div class="mt-2">
                            <a href="{{ route('client.orders.edit', $order) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('client.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-block d-md-none">
    @forelse($orders as $order)
        <div class="card shadow-sm mb-3 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <strong>Order #{{ $order->id }}</strong>
                <span class="badge bg-light text-dark">{{ $order->status }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3 d-flex flex-wrap gap-2">
                    @foreach($order->items as $index => $item)
                        @if($item->item_image)
                            <img src="{{ asset($item->item_image) }}" alt="Item" width="50" height="50" style="object-fit: cover; border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#imageModal{{ $item->id }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 4px;">
                                <small class="text-muted">N/A</small>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mb-2 text-muted small">
                    <strong>Qty:</strong>
                    @foreach($order->items as $index => $item)
                        <span class="me-2">#{{$index+1}}: {{$item->quantity}}</span>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Price:</span>
                    <span>{{ $order->price ? 'Rs. ' . $order->price : 'Pending' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Payment:</span>
                    <span class="badge {{ $order->payment_status == 'Approved' ? 'bg-success' : ($order->payment_status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ $order->payment_status }}
                    </span>
                </div>
                @if($order->admin_remark)
                    <div class="alert alert-info py-1 px-2 small mb-3">Remark: {{ $order->admin_remark }}</div>
                @endif
                <div class="d-grid gap-2">
                    @if($order->status == 'Price Assigned')
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payModal{{ $order->id }}">Pay Now</button>
                    @elseif($order->payment_status == 'Approved')
                        <a href="{{ route('client.orders.bill', $order) }}" target="_blank" class="btn btn-info text-white">View Bill</a>
                    @else
                        <button class="btn btn-secondary" disabled>Pay Now</button>
                    @endif
                    
                    @if($order->status == 'Pending')
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('client.orders.edit', $order) }}" class="btn btn-warning flex-fill text-white"><i class="bi bi-pencil"></i> Edit</a>
                            <form action="{{ route('client.orders.destroy', $order) }}" method="POST" class="flex-fill d-flex" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary text-center">No orders found.</div>
    @endforelse
</div>

{{ $orders->links('pagination::bootstrap-5') }}
@endsection
