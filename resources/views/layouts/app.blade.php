<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - AAROGYA</title>
    <style>
        .logo {
            max-width: 150px;
            height: auto;
        }
        .navbar {
            background: #2E8B57;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="{{ asset('images/logo.png') }}" alt="AAROGYA Logo" class="logo">
        <div class="nav-links">
            <a href="/">गृहपृष्ठ</a>
            <a href="/symptom-checker">लक्षण जाँच</a>
            <a href="/doctors">डाक्टरहरू</a>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>