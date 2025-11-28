<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | SoulSkin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="display-5">Admin Dashboard</h1>
            <p class="lead">You have administrator access, {{ session('customer_name') }}.</p>
        </div>
        <div class="card bg-secondary text-white mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h5 class="card-title">Quick Links</h5>
                <p class="card-text">Protect this area with the admin middleware to keep customers out.</p>
                <a href="{{ route('home') }}" class="btn btn-outline-light">Back to storefront</a>
            </div>
        </div>
    </div>
</body>
</html>
