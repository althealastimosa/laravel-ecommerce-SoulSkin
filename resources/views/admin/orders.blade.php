<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin - SoulSkin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --ss-primary: #A1BC98;
            --ss-background: #F1F3E0;
        }
        body {
            background-color: var(--ss-background);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .page-header {
            background: var(--ss-primary);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(161,188,152,0.3);
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
        .btn-light {
            background-color: #ffffff;
            color: var(--ss-primary);
            border: 1px solid #ffffff;
        }
        .btn-light:hover {
            background-color: #f8f9fa;
            color: var(--ss-primary);
        }
        .btn-outline-light {
            background-color: transparent;
            color: #ffffff;
            border: 1px solid #ffffff;
        }
        .btn-outline-light:hover {
            background-color: #ffffff;
            color: var(--ss-primary);
        }
        .btn-success {
            background-color: var(--ss-primary);
            border-color: var(--ss-primary);
            color: #ffffff;
        }
        .btn-success:hover {
            background-color: #8BAA7E;
            border-color: #8BAA7E;
            color: #ffffff;
        }
        .btn-outline-primary {
            color: var(--ss-primary);
            border-color: var(--ss-primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--ss-primary);
            border-color: var(--ss-primary);
            color: #ffffff;
        }
        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #212529;
        }
        .badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #000;
        }
        .badge.bg-primary {
            background-color: var(--ss-primary) !important;
            color: #ffffff;
        }
        .badge.bg-success {
            background-color: #198754 !important;
            color: #ffffff;
        }
        .badge.bg-danger {
            background-color: #dc3545 !important;
            color: #ffffff;
        }
        .table {
            background-color: #ffffff;
        }
        .table thead th {
            background-color: #f8f9fa;
            color: #212529;
            font-weight: 600;
            border-bottom: 2px solid var(--ss-primary);
        }
        .table tbody tr:hover {
            background-color: rgba(161,188,152,0.05);
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"><i class="bi bi-cart-check"></i> Orders</h1>
                    <p class="mb-0">View and manage all orders</p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light me-2">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-light me-2">
                        <i class="bi bi-house"></i> Storefront
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge 
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
                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>

                                                @if($order->status == 'pending')
                                                    <form method="POST" action="{{ route('admin.orders.accept', $order) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to accept this order?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check2-circle"></i> Accept
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('admin.orders.decline', $order) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to decline this order? Stock will be restored.');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-x-circle"></i> Decline
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                        <p class="text-muted mt-3">No orders found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

