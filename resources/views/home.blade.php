<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.home') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins + Noto Sans Devanagari -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            scroll-behavior: smooth; 
        }
        .glass { backdrop-filter: blur(16px); background: rgba(255,255,255,.75); border: 1px solid rgba(255,255,255,.2); }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
        .trust-badge { transition: all 0.3s ease; }
        .trust-badge:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15); }
        .specialty-card { transition: all 0.3s ease; cursor: pointer; }
        .specialty-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px -8px rgba(0,0,0,0.15); background: #f0fdf4; }
        .social-icon { fill: currentColor; width: 24px; height: 24px; }

        /* Nepali Font – केवल आवश्यक स्पेसिङ */
        html[lang="ne"] body,
        body.lang-ne {
            font-family: 'Noto Sans Devanagari', 'Poppins', sans-serif;
        }

        html[lang="ne"] p,
        html[lang="ne"] h1,
        html[lang="ne"] h2,
        html[lang="ne"] h3,
        html[lang="ne"] .leading-relaxed,
        html[lang="ne"] .text-slate-600,
        html[lang="ne"] .text-slate-500,
        html[lang="ne"] .text-sm,
        html[lang="ne"] .text-lg,
        html[lang="ne"] .text-xl,
        html[lang="ne"] .text-2xl,
        html[lang="ne"] .text-3xl,
        html[lang="ne"] .text-4xl,
        html[lang="ne"] .text-5xl,
        html[lang="ne"] .text-6xl,
        html[lang="ne"] button,
        html[lang="ne"] a,
        html[lang="ne"] input,
        html[lang="ne"] .text-xs {
            line-height: 1.6 !important;
            letter-spacing: 0.015em !important;
        }

        html[lang="ne"] h1,
        html[lang="ne"] h2,
        html[lang="ne"] h3 {
            letter-spacing: 0.02em !important;
        }

        /* English Fix – Desktop: nowrap, Mobile: wrap */
        html[lang="en"] p,
        html[lang="en"] h1,
        html[lang="en"] h2,
        html[lang="en"] h3,
        html[lang="en"] .leading-relaxed,
        html[lang="en"] .text-slate-600,
        html[lang="en"] .text-slate-500 {
            line-height: 1.5 !important;
            letter-spacing: 0.01em !important;
        }

        html[lang="en"] h1 {
            letter-spacing: 0.01em !important;
        }

        @media (min-width: 1024px) {
            html[lang="en"] .flex-wrap.gap-5 {
                flex-wrap: nowrap !important;
                gap: 0.75rem 1.5rem !important;
            }
        }

        @media (max-width: 1023px) {
            html[lang="en"] .flex-wrap.gap-5 {
                flex-wrap: wrap !important;
                gap: 0.5rem 1rem !important;
            }
            html[lang="en"] .flex-wrap.gap-5 > div {
                white-space: normal !important;
            }
        }
        /* ✅ Hero Headline & Subtitle Height Fix */
html[lang="en"] .hero-headline,
html[lang="ne"] .hero-headline {
    line-height: 1.2 !important;
}

html[lang="en"] .hero-subheadline,
html[lang="ne"] .hero-subheadline {
    line-height: 1.5 !important;
}
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    @include('partials.navbar')

    {{-- HERO SECTION --}}
    <section class="hero-bg overflow-hidden pt-4 pb-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-10 items-start">
                {{-- LEFT: Text Content --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-700 font-medium text-sm">
                        🏥 {{ __('messages.hero_badge') }}
                    </div>
                    <h1 class="mt-4 text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-slate-900 hero-headline">
    {{ __('messages.hero_headline') }}
</h1>
<p class="mt-2 text-lg text-slate-600 leading-relaxed hero-subheadline">
    {{ __('messages.hero_subheadline') }}
</p>
                    <div class="mt-5 flex flex-wrap gap-4">
                        <a href="{{ route('doctors') }}" class="bg-cyan-600 text-white px-8 py-4 rounded-2xl shadow-lg hover:bg-cyan-700 transition text-center">
                            📅 {{ __('messages.book_appointment') }}
                        </a>
                        <a href="{{ route('symptom.checker') }}" class="border border-slate-300 px-8 py-4 rounded-2xl hover:bg-white transition text-center">
                            🔍 {{ __('messages.check_symptoms') }}
                        </a>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-5">
                        <div class="flex items-center gap-2 text-sm text-slate-600"><span class="text-green-500 text-lg">✓</span> {{ __('messages.verified_doctors') }}</div>
                        <div class="flex items-center gap-2 text-sm text-slate-600"><span class="text-green-500 text-lg">✓</span> {{ __('messages.verified_clinics') }}</div>
                        <div class="flex items-center gap-2 text-sm text-slate-600"><span class="text-green-500 text-lg">✓</span> {{ __('messages.secure_records') }}</div>
                        <div class="flex items-center gap-2 text-sm text-slate-600"><span class="text-green-500 text-lg">✓</span> {{ __('messages.ai_support') }}</div>
                    </div>
                </div>

                {{-- RIGHT: Hero Image --}}
                <div>
                    <div class="glass rounded-[30px] p-6 shadow-2xl bg-slate-50">
                        <img src="{{ asset('images/doctor_hero.png') }}" 
                             class="rounded-3xl w-full h-[420px] object-contain" 
                             alt="{{ __('messages.doctor_hero_alt') }}">
                    </div>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <div class="mt-8 max-w-4xl mx-auto">
                <form action="{{ route('doctors') }}" method="GET" class="flex flex-col sm:flex-row gap-3 bg-white p-3 rounded-2xl shadow-lg">
                    <input type="text" name="search" placeholder="{{ __('messages.search_doctor_clinic_specialty') }}"
                           class="flex-1 px-6 py-3 rounded-xl border-0 focus:ring-2 focus:ring-cyan-400 outline-none text-slate-700">
                    <button type="submit" class="bg-cyan-600 text-white px-8 py-3 rounded-xl hover:bg-cyan-700 transition font-semibold">
                        🔍 {{ __('messages.search') }}
                    </button>
                </form>

                {{-- Specialties --}}
                <div class="flex flex-wrap justify-center gap-3 mt-3">
                    <span class="text-sm text-slate-500 font-medium">{{ __('messages.popular_specialties') }}:</span>
                    <a href="{{ route('doctors', ['specialization' => 'Cardiologist']) }}" class="specialty-card bg-white px-4 py-2 rounded-full text-sm text-slate-700 shadow-sm border border-slate-200 hover:border-cyan-400 transition">
                        ❤️ Cardiology
                    </a>
                    <a href="{{ route('doctors', ['specialization' => 'Dermatologist']) }}" class="specialty-card bg-white px-4 py-2 rounded-full text-sm text-slate-700 shadow-sm border border-slate-200 hover:border-cyan-400 transition">
                        🧴 Dermatology
                    </a>
                    <a href="{{ route('doctors', ['specialization' => 'Neurologist']) }}" class="specialty-card bg-white px-4 py-2 rounded-full text-sm text-slate-700 shadow-sm border border-slate-200 hover:border-cyan-400 transition">
                        🧠 Neurology
                    </a>
                    <a href="{{ route('doctors', ['specialization' => 'Orthopedic']) }}" class="specialty-card bg-white px-4 py-2 rounded-full text-sm text-slate-700 shadow-sm border border-slate-200 hover:border-cyan-400 transition">
                        🦴 Orthopedics
                    </a>
                    <a href="{{ route('doctors', ['specialization' => 'Pediatrician']) }}" class="specialty-card bg-white px-4 py-2 rounded-full text-sm text-slate-700 shadow-sm border border-slate-200 hover:border-cyan-400 transition">
                        👶 Pediatrics
                    </a>
                    <a href="{{ route('doctors', ['specialization' => 'Oncologist']) }}" class="specialty-card bg-white px-4 py-2 rounded-full text-sm text-slate-700 shadow-sm border border-slate-200 hover:border-cyan-400 transition">
                        🩺 Oncology
                    </a>
                    <a href="{{ route('clinics') }}" class="specialty-card bg-cyan-50 px-4 py-2 rounded-full text-sm text-cyan-700 shadow-sm border border-cyan-200 hover:bg-cyan-100 transition">
                        🏥 {{ __('messages.view_all_clinics') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- STATISTICS SECTION --}}
    @php
        $statsDoctors = App\Models\Doctor::where('verification_status', 'approved')->where('is_active', true)->count();
        $statsClinics = App\Models\Clinic::where('verification_status', 'approved')->where('is_active', true)->count();
        $statsSymptoms = 10000;

        // Dynamic display: if count > 2 show number, else show trust label
        $doctorDisplay = $statsDoctors > 2 ? number_format($statsDoctors) . '+' : __('messages.trusted_doctors_network');
        $clinicDisplay = $statsClinics > 2 ? number_format($statsClinics) . '+' : __('messages.trusted_clinics_network');
        $doctorLabel = $statsDoctors > 2 ? __('messages.stats_doctors') : '';
        $clinicLabel = $statsClinics > 2 ? __('messages.stats_clinics') : '';
    @endphp

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="bg-slate-50 p-6 rounded-3xl">
                    <h3 class="text-3xl font-bold text-cyan-600">{{ number_format($statsSymptoms) }}+</h3>
                    <p class="text-slate-500 mt-1">{{ __('messages.stats_analysis') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl">
                    @if($statsDoctors > 2)
                        <h3 class="text-3xl font-bold text-cyan-600">{{ $doctorDisplay }}</h3>
                        <p class="text-slate-500 mt-1">{{ $doctorLabel }}</p>
                    @else
                        <div class="text-3xl font-bold text-cyan-600">✅</div>
                        <p class="text-slate-500 mt-1">{{ $doctorDisplay }}</p>
                    @endif
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl">
                    @if($statsClinics > 2)
                        <h3 class="text-3xl font-bold text-cyan-600">{{ $clinicDisplay }}</h3>
                        <p class="text-slate-500 mt-1">{{ $clinicLabel }}</p>
                    @else
                        <div class="text-3xl font-bold text-cyan-600">🏥</div>
                        <p class="text-slate-500 mt-1">{{ $clinicDisplay }}</p>
                    @endif
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl">
                    <h3 class="text-3xl font-bold text-cyan-600">24/7</h3>
                    <p class="text-slate-500 mt-1">{{ __('messages.stats_support') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- WHY CHOOSE AAROGYA --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold">{{ __('messages.why_choose_arogya') }}</h2>
                <p class="text-slate-500 mt-2">{{ __('messages.why_choose_arogya_sub') }}</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl text-center shadow-sm card-hover">
                    <div class="text-4xl mb-3">✅</div>
                    <h3 class="font-bold text-slate-800">{{ __('messages.verified_doctors') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_verified_doctors_desc') }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl text-center shadow-sm card-hover">
                    <div class="text-4xl mb-3">🤖</div>
                    <h3 class="font-bold text-slate-800">{{ __('messages.ai_support') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_ai_assistance_desc') }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl text-center shadow-sm card-hover">
                    <div class="text-4xl mb-3">📅</div>
                    <h3 class="font-bold text-slate-800">{{ __('messages.easy_booking') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.service_booking_desc_short') }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl text-center shadow-sm card-hover">
                    <div class="text-4xl mb-3">🇳🇵</div>
                    <h3 class="font-bold text-slate-800">{{ __('messages.bilingual_platform') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.bilingual_platform_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURED DOCTORS --}}
    @php
        $featuredDoctors = App\Models\Doctor::with('user')
                            ->where('verification_status', 'approved')
                            ->where('is_active', true)
                            ->orderBy('id', 'desc')
                            ->limit(6)
                            ->get();
    @endphp

    @if($featuredDoctors->count())
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h2 class="text-4xl font-bold">{{ __('messages.recent_verified_doctors') }}</h2>
                        <p class="text-slate-500 mt-1">{{ __('messages.latest_doctors_joined') }}</p>
                    </div>
                    <a href="{{ route('doctors') }}" class="text-cyan-600 hover:underline font-medium">{{ __('messages.view_all_doctors') }} →</a>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredDoctors as $doc)
                        <div class="bg-slate-50 rounded-3xl shadow-md card-hover overflow-hidden">
                            <div class="h-32 bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
                                @if($doc->profile_photo)
                                    <img src="{{ asset('storage/' . $doc->profile_photo) }}" class="w-24 h-24 rounded-full border-4 border-white object-cover" alt="{{ $doc->user->name ?? $doc->name }}">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center text-3xl font-bold text-cyan-600">{{ substr($doc->user->name ?? $doc->name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-slate-800">{{ $doc->user->name ?? $doc->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $doc->specialization }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">✓ {{ __('messages.verified') }}</span>
                                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">{{ $doc->experience ?? 0 }} {{ __('messages.doctor_experience_years') }}</span>
                                </div>
                                <div class="mt-4 flex gap-3">
                                    <a href="{{ route('doctor.show', $doc->id) }}" class="flex-1 text-center bg-slate-200 text-slate-700 py-2 rounded-xl hover:bg-slate-300 transition text-sm">{{ __('messages.view_profile') }}</a>
                                    <a href="{{ route('appointment.create', $doc->id) }}" class="flex-1 text-center bg-cyan-600 text-white py-2 rounded-xl hover:bg-cyan-700 transition text-sm">{{ __('messages.book_appointment') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FEATURED CLINICS --}}
    @php
        $featuredClinics = App\Models\Clinic::with('user')
                            ->where('verification_status', 'approved')
                            ->where('is_active', true)
                            ->orderBy('id', 'desc')
                            ->limit(4)
                            ->get();
    @endphp

    @if($featuredClinics->count())
        <section class="py-20 bg-slate-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h2 class="text-4xl font-bold">{{ __('messages.partner_clinics') }}</h2>
                        <p class="text-slate-500 mt-1">{{ __('messages.partner_clinics_sub') }}</p>
                    </div>
                    <a href="{{ route('clinics') }}" class="text-cyan-600 hover:underline font-medium">{{ __('messages.view_all_clinics') }} →</a>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($featuredClinics as $clinic)
                        @php
                            $doctorCount = $clinic->doctors()->where('verification_status','approved')->count();
                        @endphp
                        <div class="bg-white p-6 rounded-3xl text-center card-hover shadow">
                            @if($clinic->logo)
                                <img src="{{ asset('storage/' . $clinic->logo) }}" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-cyan-200" alt="{{ $clinic->name }}">
                            @else
                                <div class="w-24 h-24 rounded-full bg-cyan-100 flex items-center justify-center mx-auto text-3xl font-bold text-cyan-600">{{ substr($clinic->name, 0, 1) }}</div>
                            @endif
                            <h3 class="font-bold text-lg mt-4">{{ $clinic->name }}</h3>
                            <p class="text-sm text-slate-500">{{ $clinic->address ?? '' }}</p>
                            <p class="text-xs text-slate-400 mt-1">
                                @if($doctorCount > 0)
                                    {{ __('messages.doctors_count', ['count' => $doctorCount]) }}
                                @else
                                    {{ __('messages.no_doctors_yet') }}
                                @endif
                            </p>
                            <a href="{{ route('clinic.show', $clinic->id) }}" class="inline-block mt-4 text-cyan-600 hover:underline text-sm">{{ __('messages.view_clinic') }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- SERVICES --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold">{{ __('messages.our_services') }}</h2>
                <p class="text-slate-500 mt-3">{{ __('messages.services_subtitle') }}</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl shadow card-hover">
                    <div class="text-5xl mb-4">🩺</div>
                    <h3 class="font-bold text-xl mb-2">{{ __('messages.service_ai_title') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('messages.service_ai_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl shadow card-hover">
                    <div class="text-5xl mb-4">👨‍⚕️</div>
                    <h3 class="font-bold text-xl mb-2">{{ __('messages.service_doctor_title') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('messages.service_doctor_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl shadow card-hover">
                    <div class="text-5xl mb-4">🏥</div>
                    <h3 class="font-bold text-xl mb-2">{{ __('messages.service_clinic_title') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('messages.service_clinic_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl shadow card-hover">
                    <div class="text-5xl mb-4">📅</div>
                    <h3 class="font-bold text-xl mb-2">{{ __('messages.service_booking_title') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('messages.service_booking_desc_short') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl shadow card-hover">
                    <div class="text-5xl mb-4">📋</div>
                    <h3 class="font-bold text-xl mb-2">{{ __('messages.service_dashboard_title') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('messages.service_dashboard_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl shadow card-hover">
                    <div class="text-5xl mb-4">📁</div>
                    <h3 class="font-bold text-xl mb-2">{{ __('messages.service_records_title') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('messages.service_records_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="py-20 bg-slate-100">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-center text-4xl font-bold mb-14">{{ __('messages.how_it_works') }}</h2>
            <div class="relative">
                <div class="grid md:grid-cols-5 gap-6">
                    <div class="bg-white p-8 rounded-3xl text-center relative z-10 shadow">
                        <div class="text-5xl mb-4">1️⃣</div>
                        <h3 class="font-bold text-lg">{{ __('messages.step1_title') }}</h3>
                        <p class="text-sm text-slate-500 mt-2">{{ __('messages.step1_desc') }}</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl text-center relative z-10 shadow">
                        <div class="text-5xl mb-4">2️⃣</div>
                        <h3 class="font-bold text-lg">{{ __('messages.step2_title') }}</h3>
                        <p class="text-sm text-slate-500 mt-2">{{ __('messages.step2_desc') }}</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl text-center relative z-10 shadow">
                        <div class="text-5xl mb-4">3️⃣</div>
                        <h3 class="font-bold text-lg">{{ __('messages.step3_title') }}</h3>
                        <p class="text-sm text-slate-500 mt-2">{{ __('messages.step3_desc_choose') }}</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl text-center relative z-10 shadow">
                        <div class="text-5xl mb-4">4️⃣</div>
                        <h3 class="font-bold text-lg">{{ __('messages.step4_title') }}</h3>
                        <p class="text-sm text-slate-500 mt-2">{{ __('messages.step4_desc') }}</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl text-center relative z-10 shadow">
                        <div class="text-5xl mb-4">5️⃣</div>
                        <h3 class="font-bold text-lg">{{ __('messages.step5_title') }}</h3>
                        <p class="text-sm text-slate-500 mt-2">{{ __('messages.step5_desc') }}</p>
                    </div>
                </div>
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-cyan-200 transform -translate-y-1/2"></div>
            </div>
        </div>
    </section>

    {{-- TRUST SECTION --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold">{{ __('messages.trust_title') }}</h2>
                <p class="text-slate-500 mt-3">{{ __('messages.trust_subtitle') }}</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-6 rounded-3xl shadow text-center trust-badge">
                    <div class="text-4xl mb-3">✅</div>
                    <h3 class="font-bold">{{ __('messages.trust_verified_doctors') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_verified_doctors_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl shadow text-center trust-badge">
                    <div class="text-4xl mb-3">🏥</div>
                    <h3 class="font-bold">{{ __('messages.trust_verified_clinics') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_verified_clinics_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl shadow text-center trust-badge">
                    <div class="text-4xl mb-3">🔒</div>
                    <h3 class="font-bold">{{ __('messages.trust_secure_records') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_secure_records_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl shadow text-center trust-badge">
                    <div class="text-4xl mb-3">🛡️</div>
                    <h3 class="font-bold">{{ __('messages.trust_privacy') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_privacy_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl shadow text-center trust-badge">
                    <div class="text-4xl mb-3">🤖</div>
                    <h3 class="font-bold">{{ __('messages.trust_ai_assistance') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_ai_assistance_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-3xl shadow text-center trust-badge">
                    <div class="text-4xl mb-3">💬</div>
                    <h3 class="font-bold">{{ __('messages.trust_24_7_support') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('messages.trust_24_7_support_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- DOCTOR & CLINIC ONBOARDING CTA --}}
    <section class="py-20 bg-gradient-to-r from-cyan-700 to-blue-800">
        <div class="max-w-5xl mx-auto px-6 text-center text-white">
            <h2 class="text-4xl font-bold">{{ __('messages.onboarding_title') }}</h2>
            <p class="text-xl opacity-90 mt-3">{{ __('messages.onboarding_subtitle') }}</p>
            <div class="flex flex-wrap justify-center gap-6 mt-8">
                <a href="{{ route('register', ['role' => 'doctor']) }}" class="bg-white text-cyan-700 px-10 py-4 rounded-2xl font-bold shadow-lg hover:bg-slate-100 transition">
                    👨‍⚕️ {{ __('messages.register_as_doctor') }}
                </a>
                <a href="{{ route('register', ['role' => 'clinic']) }}" class="bg-cyan-600 text-white border-2 border-white px-10 py-4 rounded-2xl font-bold hover:bg-cyan-500 transition">
                    🏥 {{ __('messages.register_as_clinic') }}
                </a>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-gradient-to-r from-cyan-600 to-blue-700 rounded-[40px] p-12 text-center text-white">
                <h2 class="text-4xl font-bold">{{ __('messages.final_cta_heading') }}</h2>
                <p class="mt-4 text-lg opacity-90">{{ __('messages.final_cta_desc') }}</p>
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <a href="{{ route('register') }}" class="bg-white text-cyan-700 px-8 py-3 rounded-2xl font-bold hover:bg-slate-100 transition">
                        {{ __('messages.cta_button') }}
                    </a>
                    <a href="{{ route('doctors') }}" class="bg-transparent border-2 border-white px-8 py-3 rounded-2xl font-bold hover:bg-white/10 transition">
                        {{ __('messages.find_doctors') }}
                    </a>
                    <a href="{{ route('doctors') }}" class="bg-transparent border-2 border-white px-8 py-3 rounded-2xl font-bold hover:bg-white/10 transition">
                        📅 {{ __('messages.book_appointment') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    @include('partials.footer')

</body>
</html>