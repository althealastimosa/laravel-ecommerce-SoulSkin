<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | SoulSkin </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e0f7f1;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            background-color: #f0fcfa;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        h2 {
            color: #2ca58d;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #a0e5d8;
            background-color: #e0faf4;
        }

        .form-control:focus {
            border-color: #4fd1c5;
            box-shadow: 0 0 5px rgba(94, 215, 203, 0.5);
        }

        .btn-dark {
            background-color: #4fd1c5;
            border: none;
            border-radius: 10px;
        }

        .btn-dark:hover {
            background-color: #38b59f;
        }

        a {
            color: #2ca58d;
            text-decoration: none;
        }

        a:hover {
            color: #1e7862;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Welcome Back to <strong>SoulSkin
</strong></h2>

        <form action="{{ route('login.submit') }}" method="POST" class="card p-4 mx-auto" style="max-width: 400px;">
            @csrf

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control"  required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"  required>
            </div>

            <button type="submit" class="btn btn-dark w-100">Login</button>

            <p class="text-center mt-3">
                Don’t have an account? <a href="{{ route('register') }}">Register here</a>
            </p>
        </form>
    </div>
</body>
</html>
