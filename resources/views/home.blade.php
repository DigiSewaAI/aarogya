<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.home') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- Include the shared navbar (one file controls all pages) -->
    @include('partials.navbar')

    <!-- HERO SECTION -->
    <section class="hero-bg overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 pt-8 pb-16">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-700 font-medium">
                        {{ __('messages.hero_badge') }}
                    </div>
                    <h1 class="mt-6 text-5xl lg:text-6xl font-extrabold leading-tight text-slate-900">
                        {!! __('messages.hero_heading') !!}
                    </h1>
                    <p class="mt-4 text-xl text-slate-600 leading-relaxed">
                        {{ __('messages.hero_description') }}
                    </p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="{{ route('symptom.checker') }}" class="bg-cyan-600 text-white px-8 py-4 rounded-2xl shadow-lg hover:bg-cyan-700 transition">
                            {{ __('messages.check_symptoms') }}
                        </a>
                        <a href="{{ route('doctors') }}" class="border border-slate-300 px-8 py-4 rounded-2xl hover:bg-white transition">
                            {{ __('messages.find_doctors') }}
                        </a>
                    </div>
                    <div class="mt-8 flex gap-10">
                        <div><h3 class="text-3xl font-bold text-cyan-600">10K+</h3><p class="text-slate-500">{{ __('messages.stats_analysis') }}</p></div>
                        <div><h3 class="text-3xl font-bold text-cyan-600">500+</h3><p class="text-slate-500">{{ __('messages.stats_doctors') }}</p></div>
                        <div><h3 class="text-3xl font-bold text-cyan-600">24/7</h3><p class="text-slate-500">{{ __('messages.stats_support') }}</p></div>
                    </div>
                </div>
                <div>
                    <div class="glass rounded-[30px] p-8 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=900" class="rounded-3xl w-full h-[500px] object-cover" alt="{{ __('messages.doctor_profile') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold">{{ __('messages.our_services') }}</h2>
                <p class="text-slate-500 mt-4">{{ __('messages.services_subtitle') }}</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl hover:shadow-xl transition">
                    <div class="text-5xl mb-4">🩺</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('messages.service_ai_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.service_ai_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl hover:shadow-xl transition">
                    <div class="text-5xl mb-4">👨‍⚕️</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('messages.service_doctor_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.service_doctor_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl hover:shadow-xl transition">
                    <div class="text-5xl mb-4">📋</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('messages.service_dashboard_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.service_dashboard_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl hover:shadow-xl transition">
                    <div class="text-5xl mb-4">💊</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('messages.service_tracking_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.service_tracking_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="py-20 bg-slate-100">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-center text-4xl font-bold mb-14">{{ __('messages.how_it_works') }}</h2>
            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-white p-10 rounded-3xl text-center shadow">
                    <div class="text-6xl mb-5">1️⃣</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('messages.step1_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.step1_desc') }}</p>
                </div>
                <div class="bg-white p-10 rounded-3xl text-center shadow">
                    <div class="text-6xl mb-5">2️⃣</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('messages.step2_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.step2_desc') }}</p>
                </div>
                <div class="bg-white p-10 rounded-3xl text-center shadow">
                    <div class="text-6xl mb-5">3️⃣</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('messages.step3_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.step3_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-center text-4xl font-bold mb-14">{{ __('messages.testimonials') }}</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl">
                    ★★★★★
                    <p class="mt-4 text-slate-600">{{ __('messages.testimonial1') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl">
                    ★★★★★
                    <p class="mt-4 text-slate-600">{{ __('messages.testimonial2') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl">
                    ★★★★★
                    <p class="mt-4 text-slate-600">{{ __('messages.testimonial3') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-gradient-to-r from-cyan-600 to-blue-700 rounded-[40px] p-12 text-center text-white">
                <h2 class="text-4xl font-bold">{{ __('messages.cta_heading') }}</h2>
                <p class="mt-4 text-lg opacity-90">{{ __('messages.cta_desc') }}</p>
                <a href="{{ route('register') }}" class="inline-block mt-6 bg-white text-cyan-700 px-8 py-3 rounded-2xl font-bold">
                    {{ __('messages.cta_button') }}
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="flex flex-col md:flex-row justify-between gap-10">
                <div>
                    <h3 class="font-bold text-2xl">AAROGYA</h3>
                    <p class="mt-3 text-slate-400">{{ __('messages.footer_tagline') }}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">{{ __('messages.footer_important') }}</h4>
                    <p class="text-slate-400">{{ __('messages.footer_disclaimer') }}</p>
                </div>
            </div>
            <hr class="border-slate-800 my-8">
            <div class="text-center text-slate-500">© 2026 AAROGYA. All Rights Reserved.</div>
        </div>
    </footer>

</body>
</html>