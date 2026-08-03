@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Monthly Reports</h2>

<div class="card mb-4">
    <div class="card-body bg-dark text-white rounded">
        @if($monthsData->isEmpty())
            <p class="m-0">No orders found.</p>
        @else
            <div class="accordion accordion-flush" id="reportsAccordion">
                @foreach($monthsData as $data)
                    @php
                        $monthName = \Carbon\Carbon::createFromDate($data->year, $data->month, 1)->format('F');
                        $accordionId = "month-{$data->year}-{$data->month}";
                        $headingId = "heading-{$data->year}-{$data->month}";
                    @endphp
                    
                    <div class="accordion-item mb-2" style="background: #1a1d24; border: 1px solid #2c313c; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header" id="{{ $headingId }}">
                            <button class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}" aria-expanded="false" aria-controls="{{ $accordionId }}" style="background: transparent; box-shadow: none;" data-year="{{ $data->year }}" data-month="{{ $data->month }}">
                                <div class="d-flex justify-content-between w-100 pe-3 align-items-center">
                                    <div>
                                        <strong class="fs-5">{{ $monthName }} {{ $data->year }}</strong> 
                                        <span class="ms-3 badge bg-secondary text-light rounded-pill">{{ $data->cases }} cases</span>
                                    </div>
                                    <div class="text-success fw-bold fs-5">
                                        ${{ number_format($data->total_price, 2) }}
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="{{ $accordionId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingId }}" data-bs-parent="#reportsAccordion">
                            <div class="accordion-body bg-light text-dark p-3 orders-container">
                                <div class="text-center py-4 loading-indicator">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading orders...</p>
                                </div>
                                <div class="orders-content"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    /* Styling to match the dark sleek look for the accordion headers */
    .accordion-button::after {
        filter: invert(1);
    }
    .accordion-button:not(.collapsed) {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: white !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accordionButtons = document.querySelectorAll('.accordion-button');
        
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                if (!isExpanded) return; // Only load on open
                
                const year = this.getAttribute('data-year');
                const month = this.getAttribute('data-month');
                const targetId = this.getAttribute('data-bs-target');
                const container = document.querySelector(targetId);
                const contentDiv = container.querySelector('.orders-content');
                const loadingDiv = container.querySelector('.loading-indicator');
                
                // If we already loaded data, skip (unless they trigger pagination)
                if (contentDiv.innerHTML.trim() !== '') return;
                
                loadOrders(year, month, 10, 1, '', contentDiv, loadingDiv);
            });
        });
        
        let searchTimeout;
        document.body.addEventListener('input', function(e) {
            if (e.target.classList.contains('search-input')) {
                clearTimeout(searchTimeout);
                const input = e.target;
                const year = input.getAttribute('data-year');
                const month = input.getAttribute('data-month');
                const search = input.value;
                const container = input.closest('.orders-container');
                const limit = container.querySelector('.limit-select') ? container.querySelector('.limit-select').value : 10;
                const contentDiv = container.querySelector('.orders-content');
                const loadingDiv = container.querySelector('.loading-indicator');
                
                searchTimeout = setTimeout(() => {
                    loadOrders(year, month, limit, 1, search, contentDiv, loadingDiv, true);
                }, 400);
            }
        });

        document.body.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.classList.contains('search-input')) {
                e.preventDefault();
                clearTimeout(searchTimeout);
                const input = e.target;
                const year = input.getAttribute('data-year');
                const month = input.getAttribute('data-month');
                const search = input.value;
                const container = input.closest('.orders-container');
                const limit = container.querySelector('.limit-select') ? container.querySelector('.limit-select').value : 10;
                const contentDiv = container.querySelector('.orders-content');
                const loadingDiv = container.querySelector('.loading-indicator');
                
                loadOrders(year, month, limit, 1, search, contentDiv, loadingDiv, true);
            }
        });
        
        // Handle pagination clicks, search button, and limit changes dynamically using Event Delegation
        document.body.addEventListener('click', function(e) {
            if (e.target.closest('.search-btn')) {
                e.preventDefault();
                clearTimeout(searchTimeout);
                const btn = e.target.closest('.search-btn');
                const container = btn.closest('.orders-container');
                const input = container.querySelector('.search-input');
                const year = input.getAttribute('data-year');
                const month = input.getAttribute('data-month');
                const search = input ? input.value : '';
                const limit = container.querySelector('.limit-select') ? container.querySelector('.limit-select').value : 10;
                const contentDiv = container.querySelector('.orders-content');
                const loadingDiv = container.querySelector('.loading-indicator');
                
                loadOrders(year, month, limit, 1, search, contentDiv, loadingDiv, true);
            }

            // Check if clicking a pagination link
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const link = e.target.closest('.pagination a');
                const url = new URL(link.href);
                const year = url.searchParams.get('year');
                const month = url.searchParams.get('month');
                const limit = url.searchParams.get('limit') || 10;
                const page = url.searchParams.get('page') || 1;
                const search = url.searchParams.get('search') || '';
                
                const container = link.closest('.orders-container');
                const contentDiv = container.querySelector('.orders-content');
                const loadingDiv = container.querySelector('.loading-indicator');
                
                loadOrders(year, month, limit, page, search, contentDiv, loadingDiv);
            }
        });
        
        document.body.addEventListener('change', function(e) {
            if (e.target.classList.contains('limit-select')) {
                const select = e.target;
                const limit = select.value;
                const year = select.getAttribute('data-year');
                const month = select.getAttribute('data-month');
                
                const container = select.closest('.orders-container');
                const searchInput = container.querySelector('.search-input');
                const search = searchInput ? searchInput.value : '';
                const contentDiv = container.querySelector('.orders-content');
                const loadingDiv = container.querySelector('.loading-indicator');
                
                loadOrders(year, month, limit, 1, search, contentDiv, loadingDiv);
            }
        });
        
        function loadOrders(year, month, limit, page, search, contentDiv, loadingDiv, keepFocus = false) {
            if (!keepFocus) {
                loadingDiv.style.display = 'block';
                contentDiv.style.display = 'none';
            }
            
            fetch(`/admin/reports/orders?year=${year}&month=${month}&limit=${limit}&page=${page}&search=${encodeURIComponent(search || '')}`)
                .then(response => response.text())
                .then(html => {
                    contentDiv.innerHTML = html;
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                    if (keepFocus) {
                        const newSearchInput = contentDiv.querySelector('.search-input');
                        if (newSearchInput) {
                            newSearchInput.focus();
                            const val = newSearchInput.value;
                            newSearchInput.value = '';
                            newSearchInput.value = val;
                        }
                    }
                })
                .catch(err => {
                    console.error('Error fetching orders:', err);
                    contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load orders. Please try again.</div>';
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                });
        }
    });
</script>
@endsection
