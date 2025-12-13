<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SoulSkin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .stat-card.products { border-left-color: #4fc1b8; }
        .stat-card.orders { border-left-color: #667eea; }
        .stat-card.customers { border-left-color: #f093fb; }
        .stat-card.revenue { border-left-color: #4facfe; }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .product-form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .recent-orders-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>
                    <p class="mb-0">Welcome, {{ session('customer_name') }}!</p>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-light">
                        <i class="bi bi-house"></i> Back to Storefront
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card products">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-box-seam" style="font-size: 2.5rem; color: #4fc1b8;"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $totalProducts }}</div>
                            <div class="stat-label">Total Products</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card orders">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-cart-check" style="font-size: 2.5rem; color: #667eea;"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $totalOrders }}</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card customers">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-people" style="font-size: 2.5rem; color: #f093fb;"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ $totalCustomers }}</div>
                            <div class="stat-label">Total Customers</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card revenue">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-currency-dollar" style="font-size: 2.5rem; color: #4facfe;"></i>
                        </div>
                        <div>
                            <div class="stat-number">${{ number_format($totalRevenue, 2) }}</div>
                            <div class="stat-label">Total Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('admin.products.create') }}" class="action-btn">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </a>
                    <a href="{{ route('admin.orders') }}" class="action-btn">
                        <i class="bi bi-list-ul"></i> View Orders
                    </a>
                    <a href="{{ route('admin.customers') }}" class="action-btn">
                        <i class="bi bi-people"></i> View Customers
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="action-btn">
                        <i class="bi bi-box-seam"></i> Manage Products
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Product Upload Form -->
            <div class="col-lg-6">
                <div class="product-form-card">
                    <h3 class="mb-4"><i class="bi bi-plus-square"></i> Add New Product</h3>
                    
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stock" name="stock" min="0" value="{{ old('stock', 0) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image URL</label>
                            <input type="text" class="form-control" id="image" name="image" value="{{ old('image') }}" placeholder="https://example.com/image.jpg">
                            <small class="text-muted">Enter a URL for the product image</small>
                        </div>

                        <button type="submit" class="btn action-btn w-100">
                            <i class="bi bi-check-circle"></i> Add Product
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="col-lg-6">
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
                                            <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
