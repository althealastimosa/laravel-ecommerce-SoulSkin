<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | SoulSkin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6f5f1; /* Soft pastel background */
            color: #2c7a7b; /* Teal-ish text */
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            background-color: #ffffff; /* White card */
            color: #2c7a7b; /* Card text */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background-color: #4fc1b8; /* Teal button */
            color: #fff;
            border: none;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background-color: #38a69d;
        }
        .display-5 {
            color: #27676c;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="display-5">Admin Dashboard</h1>
            <p class="lead">You have administrator access, {{ session('customer_name') }}.</p>
        </div>
        <div class="card mx-auto p-4" style="max-width: 600px;">
            <h5 class="card-title">Quick Links</h5>
            <p class="card-text">Protect this area with the admin middleware to keep customers out.</p>
            <a href="{{ route('home') }}" class="btn btn-custom w-100">Back to storefront</a>
        </div>
    </div>
</body>
</html>
