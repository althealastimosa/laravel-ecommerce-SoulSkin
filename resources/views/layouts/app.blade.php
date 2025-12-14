<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SoulSkin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root{
            --ss-primary: #A1BC98;
            --ss-background: #F1F3E0;
        }
        body { background-color: var(--ss-background); color: #222; }
        .navbar-brand { font-weight: bold; color: #fff; }
        .cart-badge { position: relative; }
        .navbar { background: var(--ss-primary) !important; }
        .navbar .nav-link, .navbar .navbar-text { color: #fff !important; }
        .btn-outline-light { color: #fff; border-color: rgba(255,255,255,0.6); }

        /* Cards */
        .card {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            border: 0;
            overflow: hidden;
        }
        .card .card-body { padding: 1.25rem; }
        .card h5, .card .card-title { color: #222; }

        /* Image area */
        .card-img-top { background: rgba(0,0,0,0.04); }
        .card-img-top,
        .card .card-img-top {
            width: 100%;
            height: 220px;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        .bg-secondary { background-color: rgba(0,0,0,0.06) !important; }

        /* Buttons */
        .btn-primary {
            background-color: var(--ss-primary);
            border-color: var(--ss-primary);
            color: #fff;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #8BAA7E;
            border-color: #8BAA7E;
            color: #fff;
        }
        .btn-outline-primary {
            color: var(--ss-primary);
            border-color: rgba(161,188,152,0.9);
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: rgba(161,188,152,0.06);
            color: #6a7a63;
            border-color: var(--ss-primary);
        }

        /* Button shape and sizing */
        .btn {
            border-radius: 8px;
        }
        .btn-sm { padding: .375rem .75rem; }

        /* Links & pagination */
        .page-link, .page-item .page-link { color: var(--ss-primary); }
        a.nav-link:hover { opacity: 0.95; }

        /* Product card small tweaks */
        .card .card-text.text-muted { color: #6b6b6b; }
        .container .card { background: #fff; }
        /* Limit product card width inside grid columns and center them */
        .row > [class*='col-'] > .card { max-width: 360px; margin: 0 auto; }

        /* Global container width for cleaner layouts */
        .container { max-width: 1200px; }

        /* Navbar tweaks */
        .navbar { padding-top: .5rem; padding-bottom: .5rem; }

        /* Card header styling for clearer sections */
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            font-weight: 700;
            font-size: 1.25rem;
            padding: 1rem 1.25rem;
        }
        .card .card-body { padding: 1rem 1.25rem; }

        /* Order page layout helpers */
        .order-title { font-size: 1.6rem; font-weight: 700; margin-bottom: 0.5rem; }
        .order-summary .table { margin-bottom: 0; }

        /* Status badges */
        .badge-status {
            padding: .45em .65em;
            border-radius: 8px;
            font-weight: 600;
        }
        .badge-status.pending { background: #ffc107; color: #222; }
        .badge-status.processing { background: #17a2b8; color: #fff; }
        .badge-status.shipped { background: #0d6efd; color: #fff; }
        .badge-status.delivered { background: #198754; color: #fff; }
        .badge-status.cancelled { background: #dc3545; color: #fff; }

        /* Floating back button */
        .btn-back {
            position: fixed;
            left: 20px;
            bottom: 20px;
            z-index: 999;
            background: #6c757d;
            color: #fff;
            border-radius: 10px;
            padding: .5rem .9rem;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            border: 0;
        }

        /* Make right-column cards same width and stacked nicely */
        .admin-side .card { margin-bottom: 1.25rem; }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">SoulSkin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>

                    @if(session('customer_id'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart"></i> Cart
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">My Orders</a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav">
                    @if(session('customer_id'))
                        <li class="nav-item">
                            <span class="navbar-text me-3">Hello, {{ session('customer_name') }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
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

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

