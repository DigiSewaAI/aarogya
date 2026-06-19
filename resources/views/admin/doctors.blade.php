<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.doctors_title') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .glass { backdrop-filter: blur(16px); background: rgba(255,255,255,.75); border: 1px solid rgba(255,255,255,.2); }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- Shared Navbar -->
    @include('partials.navbar')

    <!-- Hero Section for Doctors -->
    <section class="hero-bg overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 pt-8 pb-16">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-700 font-medium mb-4">
                    {{ __('messages.doctors_badge') }}
                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900">
                    {!! __('messages.doctors_heading') !!}
                </h1>
                <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto">
                    {{ __('messages.doctors_subtitle') }}
                </p>
            </div>

            <!-- Doctors Grid -->
            @if(isset($doctors) && count($doctors) > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($doctors as $doctor)
                        <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100">
                            <!-- Profile Photo -->
                            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 h-32 flex items-center justify-center">
                                @if($doctor->profile_photo)
                                    <img src="{{ asset('storage/' . $doctor->profile_photo) }}" 
                                         alt="{{ $doctor->name }}" 
                                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                                @else
                                    <div class="bg-white rounded-full p-3 w-24 h-24 flex items-center justify-center shadow-lg">
                                        <span class="text-5xl">👨‍⚕️</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-slate-800">{{ $doctor->name ?? __('messages.doctor_profile') }}</h3>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="bg-cyan-100 text-cyan-700 text-xs px-2 py-1 rounded-full">
                                        {{ $doctor->specialization ?? __('messages.doctor_specialization') }}
                                    </span>
                                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">
                                        {{ $doctor->experience ?? '0' }} {{ __('messages.doctor_experience_years') }}
                                    </span>
                                </div>
                                <div class="mt-4 space-y-2 text-slate-600">
                                    <p class="flex items-center gap-2">
                                        💰 <span class="font-semibold">{{ __('messages.currency') }} {{ number_format($doctor->consultation_fee ?? 0, 2) }}</span> / {{ __('messages.doctor_fee') }}
                                    </p>
                                    <p class="flex items-center gap-2">📅 <span>{{ __('messages.doctor_available_today') }}</span></p>
                                    <p class="flex items-center gap-2">
                                        ⭐ 4.9 ({{ rand(10, 200) }} {{ __('messages.doctor_reviews_count', ['count' => '']) }})
                                    </p>
                                </div>
                                <div class="mt-6 flex gap-3">
                                    @auth
                                        <a href="#" onclick="bookAppointment({{ $doctor->id }}, '{{ $doctor->name }}')" 
                                           class="flex-1 bg-cyan-600 text-white text-center py-2 rounded-xl hover:bg-cyan-700 transition">
                                            {{ __('messages.doctor_book') }}
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="flex-1 bg-cyan-600 text-white text-center py-2 rounded-xl hover:bg-cyan-700 transition">
                                            {{ __('messages.doctor_book') }}
                                        </a>
                                    @endauth
                                    <a href="{{ route('doctor.show', $doctor->id) }}" 
                                       class="px-4 py-2 border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                                        {{ __('messages.doctor_profile') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $doctors->links() }}
                </div>
            @else
                <!-- No Doctors Available -->
                <div class="bg-white/60 backdrop-blur-sm rounded-3xl shadow-xl p-12 text-center border border-slate-100">
                    <div class="text-7xl mb-4">👨‍⚕️🚫</div>
                    <h2 class="text-2xl font-bold text-slate-700">{{ __('messages.doctors_no_doctors') }}</h2>
                    <p class="text-slate-500 mt-2">{{ __('messages.doctors_no_doctors_sub') }}</p>
                    <a href="{{ url('/') }}" class="inline-block mt-6 bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                        {{ __('messages.doctors_go_home') }}
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold">{{ __('messages.doctors_features_heading') }}</h2>
            <div class="grid md:grid-cols-3 gap-8 mt-12">
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <div class="text-4xl mb-3">✅</div>
                    <h3 class="font-bold">{{ __('messages.doctors_feature1_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.doctors_feature1_desc') }}</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <div class="text-4xl mb-3">💬</div>
                    <h3 class="font-bold">{{ __('messages.doctors_feature2_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.doctors_feature2_desc') }}</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <div class="text-4xl mb-3">📋</div>
                    <h3 class="font-bold">{{ __('messages.doctors_feature3_title') }}</h3>
                    <p class="text-slate-500">{{ __('messages.doctors_feature3_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    @include('partials.footer')

    <script>
        function bookAppointment(doctorId, doctorName) {
            alert('Appointment booking with Dr. ' + doctorName + ' will be available soon!');
            // Later we will redirect to booking page:
            // window.location.href = '/appointments/book/' + doctorId;
        }
    </script>
</body>
</html>