<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers | Admin - SoulSkin</title>
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
        .badge.bg-danger {
            background-color: #dc3545 !important;
            color: #ffffff;
        }
        .badge.bg-secondary {
            background-color: var(--ss-primary) !important;
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
    </style>
</head>
<body>
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"><i class="bi bi-people"></i> Customers</h1>
                    <p class="mb-0">Manage all customer accounts</p>
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
        <div class="card">
            <div class="card-body">
                @if($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->id }}</td>
                                        <td><strong>{{ $customer->name }}</strong></td>
                                        <td>{{ $customer->email }}</td>
                                        <td>
                                            @if($customer->is_admin)
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-secondary">Customer</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $customers->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people" style="font-size: 3rem; color: #dee2e6;"></i>
                        <p class="text-muted mt-3">No customers found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

