<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <style>
        :root {
            --ss-primary: #A1BC98;
            --ss-background: #F1F3E0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: var(--ss-background);
            padding: 2rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 2rem;
        }
        .nav {
            margin-bottom: 2rem;
        }
        .nav a {
            display: inline-block;
            padding: 10px 20px;
            background: var(--ss-primary);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            transition: background 0.2s ease;
        }
        .nav a:hover {
            background: #8BAA7E;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('home') }}">Home</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-button" style="display: inline-block; padding: 10px 20px; background: var(--ss-primary); color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; transition: background 0.2s ease; border: none; cursor: pointer; font-size: inherit; font-family: inherit;">
                    Logout
                </button>
            </form>
        </div>
        <h1>Admin Settings</h1>
        <p>This is the admin settings page. Configure your admin preferences here.</p>
    </div>
</body>
</html>

