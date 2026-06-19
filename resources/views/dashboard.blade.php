<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.dashboard') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-slate-50">

    @include('partials.navbar')

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6 text-white">
                <h2 class="text-3xl font-bold">
                    {{ __('messages.dashboard_welcome', ['name' => Auth::user()->name]) }}
                </h2>
                <p class="opacity-90">{{ __('messages.dashboard_subtitle') }}</p>
            </div>
            <div class="p-8 grid md:grid-cols-3 gap-6">
                {{-- Check Symptoms --}}
                <div class="bg-cyan-50 p-6 rounded-2xl">
                    <div class="text-4xl mb-3">🩺</div>
                    <h3 class="font-bold text-xl">{{ __('messages.dashboard_check_symptoms') }}</h3>
                    <p class="text-slate-600 mt-2">{{ __('messages.dashboard_check_symptoms_desc') }}</p>
                    <a href="{{ route('symptom.checker') }}" class="inline-block mt-4 text-cyan-600 font-semibold">
                        {{ __('messages.dashboard_check_action') }} →
                    </a>
                </div>

                {{-- Find Doctor --}}
                <div class="bg-cyan-50 p-6 rounded-2xl">
                    <div class="text-4xl mb-3">👨‍⚕️</div>
                    <h3 class="font-bold text-xl">{{ __('messages.dashboard_find_doctor') }}</h3>
                    <p class="text-slate-600 mt-2">{{ __('messages.dashboard_find_doctor_desc') }}</p>
                    <a href="{{ route('doctors') }}" class="inline-block mt-4 text-cyan-600 font-semibold">
                        {{ __('messages.dashboard_find_action') }} →
                    </a>
                </div>

                {{-- My Reports --}}
                <div class="bg-cyan-50 p-6 rounded-2xl">
                    <div class="text-4xl mb-3">📋</div>
                    <h3 class="font-bold text-xl">{{ __('messages.dashboard_my_reports') }}</h3>
                    <p class="text-slate-600 mt-2">{{ __('messages.dashboard_my_reports_desc') }}</p>
                    <a href="#" class="inline-block mt-4 text-cyan-600 font-semibold">
                        {{ __('messages.dashboard_reports_action') }} →
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>