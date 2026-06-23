<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.services') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
        /* ✅ Social icons – apply fill color from text color */
        .social-icon {
            fill: currentColor;
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    @include('partials.navbar')

    <!-- Hero Section -->
    <section class="hero-bg overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 pt-8 pb-16">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-700 font-medium mb-4">
                    {{ __('messages.services_hero_badge') }}
                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900">
                    {!! __('messages.services_hero_heading') !!}
                </h1>
                <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto">
                    {{ __('messages.services_hero_subtitle') }}
                </p>
            </div>

            <!-- Services Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- AI Symptom Analysis --}}
                <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-8 border border-slate-100">
                    <div class="text-5xl mb-4">🤖</div>
                    <h3 class="text-2xl font-bold text-slate-800">{{ __('messages.service_ai_title') }}</h3>
                    <p class="text-slate-500 mt-2">{{ __('messages.service_ai_desc_full') }}</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>✓ {{ __('messages.service_ai_feature1') }}</li>
                        <li>✓ {{ __('messages.service_ai_feature2') }}</li>
                        <li>✓ {{ __('messages.service_ai_feature3') }}</li>
                    </ul>
                    <a href="{{ route('symptom.checker') }}" class="inline-block mt-6 bg-cyan-600 text-white px-5 py-2 rounded-xl hover:bg-cyan-700 transition">{{ __('messages.service_ai_cta') }} →</a>
                </div>

                {{-- Verified Doctors --}}
                <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-8 border border-slate-100">
                    <div class="text-5xl mb-4">👨‍⚕️</div>
                    <h3 class="text-2xl font-bold text-slate-800">{{ __('messages.service_doctor_title') }}</h3>
                    <p class="text-slate-500 mt-2">{{ __('messages.service_doctor_desc_full') }}</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>✓ {{ __('messages.service_doctor_feature1') }}</li>
                        <li>✓ {{ __('messages.service_doctor_feature2') }}</li>
                        <li>✓ {{ __('messages.service_doctor_feature3') }}</li>
                    </ul>
                    <a href="{{ route('doctors') }}" class="inline-block mt-6 bg-cyan-600 text-white px-5 py-2 rounded-xl hover:bg-cyan-700 transition">{{ __('messages.service_doctor_cta') }} →</a>
                </div>

                {{-- Health Dashboard --}}
                <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-8 border border-slate-100">
                    <div class="text-5xl mb-4">📋</div>
                    <h3 class="text-2xl font-bold text-slate-800">{{ __('messages.service_dashboard_title') }}</h3>
                    <p class="text-slate-500 mt-2">{{ __('messages.service_dashboard_desc_full') }}</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>✓ {{ __('messages.service_dashboard_feature1') }}</li>
                        <li>✓ {{ __('messages.service_dashboard_feature2') }}</li>
                        <li>✓ {{ __('messages.service_dashboard_feature3') }}</li>
                    </ul>
                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 bg-cyan-600 text-white px-5 py-2 rounded-xl hover:bg-cyan-700 transition">{{ __('messages.service_dashboard_cta') }} →</a>
                </div>

                {{-- Appointment Booking --}}
                <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-8 border border-slate-100">
                    <div class="text-5xl mb-4">📅</div>
                    <h3 class="text-2xl font-bold text-slate-800">{{ __('messages.service_booking_title') }}</h3>
                    <p class="text-slate-500 mt-2">{{ __('messages.service_booking_desc') }}</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>✓ {{ __('messages.service_booking_feature1') }}</li>
                        <li>✓ {{ __('messages.service_booking_feature2') }}</li>
                        <li>✓ {{ __('messages.service_booking_feature3') }}</li>
                    </ul>
                    <a href="#" class="inline-block mt-6 bg-cyan-600 text-white px-5 py-2 rounded-xl hover:bg-cyan-700 transition">{{ __('messages.service_booking_cta') }}</a>
                </div>

                {{-- Medication Tracking --}}
                <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-8 border border-slate-100">
                    <div class="text-5xl mb-4">💊</div>
                    <h3 class="text-2xl font-bold text-slate-800">{{ __('messages.service_tracking_title') }}</h3>
                    <p class="text-slate-500 mt-2">{{ __('messages.service_tracking_desc_full') }}</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>✓ {{ __('messages.service_tracking_feature1') }}</li>
                        <li>✓ {{ __('messages.service_tracking_feature2') }}</li>
                        <li>✓ {{ __('messages.service_tracking_feature3') }}</li>
                    </ul>
                    <a href="#" class="inline-block mt-6 bg-cyan-600 text-white px-5 py-2 rounded-xl hover:bg-cyan-700 transition">{{ __('messages.service_tracking_cta') }} →</a>
                </div>

                {{-- Emergency Assistance --}}
                <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-8 border border-slate-100">
                    <div class="text-5xl mb-4">🚑</div>
                    <h3 class="text-2xl font-bold text-slate-800">{{ __('messages.service_emergency_title') }}</h3>
                    <p class="text-slate-500 mt-2">{{ __('messages.service_emergency_desc') }}</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>✓ {{ __('messages.service_emergency_feature1') }}</li>
                        <li>✓ {{ __('messages.service_emergency_feature2') }}</li>
                        <li>✓ {{ __('messages.service_emergency_feature3') }}</li>
                    </ul>
                    <a href="#" class="inline-block mt-6 bg-red-500 text-white px-5 py-2 rounded-xl hover:bg-red-600 transition">{{ __('messages.service_emergency_cta') }}</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Section: Why Choose Us -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold">{!! __('messages.why_choose_us_heading') !!}</h2>
                <p class="text-slate-500 mt-2">{{ __('messages.why_choose_us_subtitle') }}</p>
            </div>
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl mb-3">🔒</div>
                    <h3 class="font-bold">{{ __('messages.why_secure') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('messages.why_secure_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">💰</div>
                    <h3 class="font-bold">{{ __('messages.why_transparent') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('messages.why_transparent_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">⭐</div>
                    <h3 class="font-bold">{{ __('messages.why_rating') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('messages.why_rating_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">📞</div>
                    <h3 class="font-bold">{{ __('messages.why_support') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('messages.why_support_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>