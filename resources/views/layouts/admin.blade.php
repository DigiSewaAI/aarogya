<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.admin_dashboard') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-slate-50">
    @include('partials.navbar')
    
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex gap-6">
            @include('partials.admin-sidebar')
            <div class="flex-1 min-w-0">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>