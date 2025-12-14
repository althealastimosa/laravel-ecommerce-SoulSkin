@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    :root{
        --ss-primary: #A1BC98;
        --ss-background: #F1F3E0;
        --ss-accent: rgba(161,188,152,0.15);
    }
    .dashboard-header {
        background: var(--ss-primary);
        color: white;
        padding: 1.5rem 0.75rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 6px 18px var(--ss-accent);
        border-radius: 8px;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.25rem 1rem;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        border-left: 6px solid transparent;
        display: block;
        min-height: 110px;
    }
    .stat-card:hover { transform: translateY(-6px); box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
    .stat-card.products { border-left-color: var(--ss-primary); }
    .stat-card.orders { border-left-color: var(--ss-primary); }
    .stat-card.customers { border-left-color: var(--ss-primary); }
    .stat-number { font-size: 2.25rem; font-weight: 700; color: #2c3e50; line-height: 1; }
    .stat-label { color: #7f8c8d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .recent-orders-card { background: #ffffff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 8px 26px rgba(0,0,0,0.05); min-height: 260px; }
    .table th { background-color: #f8f9fa; font-weight: 600; color: #495057; border-top: 0; }
    .badge-status { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; }
    .dashboard-header .btn { background: #ffffff; color: #2c3e50; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); border: none; }
    .recent-orders-card .btn-outline-primary { color: var(--ss-primary); border-color: var(--ss-primary); }
    @media (max-width: 767px){ .stat-number { font-size: 1.8rem; } .dashboard-header { padding: 1rem; } }
</style>
@endsection

@section('content')
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>
                    <p class="mb-0">Welcome, {{ session('customer_name') }}!</p>
                </div>
                <div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-light">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                    <div class="stat-card products">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-box-seam" style="font-size: 2.5rem; color: var(--ss-primary);"></i>
                            </div>
                            <div>
                                <div class="stat-number">{{ $totalProducts }}</div>
                                <div class="stat-label">Total Products</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                    <div class="stat-card orders">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-cart-check" style="font-size: 2.5rem; color: var(--ss-primary);"></i>
                            </div>
                            <div>
                                <div class="stat-number">{{ $totalOrders }}</div>
                                <div class="stat-label">Total Orders</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.customers') }}" class="text-decoration-none">
                    <div class="stat-card customers">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-people" style="font-size: 2.5rem; color: var(--ss-primary);"></i>
                            </div>
                            <div>
                                <div class="stat-number">{{ $totalCustomers }}</div>
                                <div class="stat-label">Total Customers</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="recent-orders-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0"><i class="bi bi-clock-history"></i> Recent Orders</h3>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}" class="text-decoration-none">
                                                    <strong>{{ $order->order_number }}</strong>
                                                </a>
                                            </td>
                                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-status 
                                                    @if($order->status == 'pending') bg-warning
                                                    @elseif($order->status == 'processing') bg-info
                                                    @elseif($order->status == 'shipped') bg-primary
                                                    @elseif($order->status == 'delivered') bg-success
                                                    @else bg-danger
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
