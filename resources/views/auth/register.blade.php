<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | SoulSkin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F1F3E0;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            background-color: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        h2 {
            color: #A1BC98;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #A1BC98;
            background-color: #F1F3E0;
        }

        .form-control:focus {
            border-color: #A1BC98;
            box-shadow: 0 0 5px rgba(161, 188, 152, 0.5);
            background-color: white;
        }

        .btn-dark {
            background-color: #A1BC98;
            border: none;
            border-radius: 10px;
            color: white;
        }

        .btn-dark:hover {
            background-color: #8fa885;
            color: white;
        }

        a {
            color: #A1BC98;
            text-decoration: none;
        }

        a:hover {
            color: #7a9671;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Create your <strong>SoulSkin</strong> account.</h2>

        <form action="{{ route('register.submit') }}" method="POST" class="card p-4 mx-auto" style="max-width: 400px;">
            @csrf

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
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

            {{-- Full Name --}}
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>


            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-dark w-100">Create Account</button>

            <p class="text-center mt-3">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </p>
        </form>
    </div>
</body>
</html>
