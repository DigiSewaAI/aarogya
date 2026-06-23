<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AAROGYA - स्वास्थ्य सेवा')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        .glass {
            backdrop-filter: blur(16px);
            background: rgba(255,255,255,.75);
            border: 1px solid rgba(255,255,255,.2);
        }
        .hero-bg {
            background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%),
                        radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%),
                        #f8fafc;
        }
        .transition-smooth {
            transition: all 0.3s ease;
        }
        /* ✅ Social icons – apply fill color from text color */
        .social-icon {
            fill: currentColor !important;
            width: 24px;
            height: 24px;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    {{-- Include Navbar Partial --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Include Footer Partial --}}
    @include('partials.footer')

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>