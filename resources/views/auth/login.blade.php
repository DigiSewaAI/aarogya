<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.auth_login_title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; } .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }</style>
</head>
<body class="bg-slate-50">
    @include('partials.navbar')
    <section class="hero-bg overflow-hidden">
        <div class="max-w-md mx-auto px-6 py-16">
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-slate-100">
                <div class="text-center mb-8">
                    <div class="text-5xl mb-3">🔐</div>
                    <h2 class="text-3xl font-bold text-slate-900">{{ __('messages.auth_login_title') }}</h2>
                </div>

                {{-- Display validation errors --}}
                @if($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Display session error (if any) --}}
                @if(session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.auth_email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required autofocus>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.auth_password') }}</label>
                            <input type="password" name="password" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                        </div>
                        <button type="submit" class="w-full bg-cyan-600 text-white py-3 rounded-xl hover:bg-cyan-700 transition font-semibold">
                            {{ __('messages.auth_login_button') }}
                        </button>
                    </div>
                </form>
                <div class="mt-6 text-center">
                    <a href="{{ route('register') }}" class="text-cyan-600 hover:underline">
                        {{ __('messages.auth_no_account') }} {{ __('messages.auth_register') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
    @include('partials.footer')
</body>
</html>