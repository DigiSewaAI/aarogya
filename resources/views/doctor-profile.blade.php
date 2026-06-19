<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ $doctor->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
    </style>
</head>
<body class="bg-slate-50">
    @include('partials.navbar')
    
    <section class="hero-bg overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 py-12">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-cyan-600 to-blue-600 p-8 text-white">
                    <div class="flex items-center gap-6">
                        @if($doctor->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->profile_photo) }}" 
                                 alt="{{ $doctor->name }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-32 h-32 rounded-full bg-white flex items-center justify-center text-6xl shadow-lg">
                                👨‍⚕️
                            </div>
                        @endif
                        <div>
                            <h1 class="text-3xl font-bold">{{ $doctor->name }}</h1>
                            <p class="text-cyan-100 text-lg">{{ $doctor->specialization }}</p>
                            <p class="text-sm text-cyan-200 mt-1">
                                ⭐ 4.9 (120 {{ __('messages.reviews') }}) • 
                                {{ $doctor->experience ?? 0 }} {{ __('messages.experience_years') }}
                            </p>
                            @if($doctor->verification_status == 'approved')
                                <span class="inline-block mt-2 bg-green-500 text-white text-xs px-3 py-1 rounded-full">
                                    ✅ {{ __('messages.verified') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-slate-500">{{ __('messages.qualification') }}</p>
                            <p class="font-medium text-slate-800">{{ $doctor->qualification ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">{{ __('messages.nmc_registration') }}</p>
                            <p class="font-medium text-slate-800">{{ $doctor->nmc_registration ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">{{ __('messages.consultation_fee') }}</p>
                            <p class="font-medium text-cyan-600 text-xl">{{ __('messages.currency') }} {{ number_format($doctor->consultation_fee ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">{{ __('messages.clinic_name') }}</p>
                            <p class="font-medium text-slate-800">{{ $doctor->clinic_name ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-slate-500">{{ __('messages.bio') }}</p>
                            <p class="text-slate-700 mt-1 leading-relaxed">{{ $doctor->bio ?? 'No bio available.' }}</p>
                        </div>
                    </div>

                    <!-- Schedule / Availability -->
                    @if(isset($doctor->schedules) && $doctor->schedules->count() > 0)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <h3 class="font-semibold text-slate-800">{{ __('messages.available_slots') }}</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                                @foreach($doctor->schedules as $schedule)
                                    @if($schedule->is_active)
                                        <div class="bg-cyan-50 p-2 rounded-xl text-center text-sm">
                                            <span class="font-medium">{{ $schedule->day_name ?? $schedule->day_of_week }}</span>
                                            <p class="text-xs text-slate-500">{{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="mt-8 pt-6 border-t border-slate-200 flex flex-wrap gap-4">
                        @auth
                            @if(Auth::user()->isPatient())
                                {{-- ✅ FIXED: singular route name 'appointment.create' --}}
                                <a href="{{ route('appointment.create', $doctor->id) }}" 
                                   class="bg-cyan-600 text-white px-8 py-3 rounded-xl hover:bg-cyan-700 transition shadow-lg flex items-center gap-2">
                                    📅 {{ __('messages.book_appointment') }}
                                </a>
                            @else
                                <span class="bg-slate-200 text-slate-500 px-8 py-3 rounded-xl cursor-not-allowed">
                                    {{ __('messages.book_appointment') }}
                                </span>
                                <p class="text-sm text-slate-400 mt-2">{{ __('messages.patient_only_booking') }}</p>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="bg-cyan-600 text-white px-8 py-3 rounded-xl hover:bg-cyan-700 transition shadow-lg flex items-center gap-2">
                                🔐 {{ __('messages.login_to_book') }}
                            </a>
                        @endauth
                        
                        <a href="{{ route('doctors') }}" 
                           class="border border-slate-300 px-8 py-3 rounded-xl hover:bg-slate-50 transition text-slate-700">
                            ← {{ __('messages.back_to_doctors') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>