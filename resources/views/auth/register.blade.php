<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.auth_register_title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    @include('partials.navbar')

    <section class="hero-bg overflow-hidden">
        <div class="max-w-md mx-auto px-6 py-16">
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-slate-100">
                <div class="text-center mb-8">
                    <div class="text-5xl mb-3">📝</div>
                    <h2 class="text-3xl font-bold text-slate-900">{{ __('messages.auth_register_title') }}</h2>
                    <p class="text-slate-500 mt-2">{{ __('messages.auth_register_subtitle') }}</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                        <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.auth_name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.auth_email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.auth_password') }}</label>
                            <input type="password" name="password" required
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.auth_password_confirmation') }}</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Phone (Optional) -->
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.phone') ?? 'Phone Number' }}</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Address (Optional) -->
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.address') ?? 'Address' }}</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.role') ?? 'Register as' }}</label>
                            <select name="role" required class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                <option value="patient">{{ __('messages.patient') ?? 'Patient' }}</option>
                                <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>{{ __('messages.doctor') ?? 'Doctor' }}</option>
                                <option value="clinic" {{ old('role') == 'clinic' ? 'selected' : '' }}>{{ __('messages.clinic') ?? 'Clinic' }}</option>
                            </select>
                        </div>

                        <!-- ========== NEW: Facility Type (only for clinic role) ========== -->
                        <div id="facility_type_wrapper" style="display: none;">
                            <label class="block text-slate-700 font-medium mb-1">{{ __('messages.facility_type') }}</label>
                            <select name="facility_type" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                <option value="clinic">{{ __('messages.clinic') }}</option>
                                <option value="hospital">{{ __('messages.hospital') }} ({{ __('messages.coming_soon') }})</option>
                                <option value="diagnostic">{{ __('messages.diagnostic_center') }} ({{ __('messages.coming_soon') }})</option>
                                <option value="other">{{ __('messages.other') }}</option>
                            </select>
                            <p class="text-xs text-slate-400 mt-1">{{ __('messages.facility_type_help') }}</p>
                        </div>
                        <!-- ============================================================= -->

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition shadow-md">
                            {{ __('messages.auth_register_button') }}
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center text-sm text-slate-500">
                    {{ __('messages.auth_already_account') }}
                    <a href="{{ route('login') }}" class="text-cyan-600 hover:underline">{{ __('messages.auth_login') }}</a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <!-- ========== NEW: JavaScript Toggle for Facility Type ========== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.querySelector('select[name="role"]');
            const facilityWrapper = document.getElementById('facility_type_wrapper');

            function toggleFacility() {
                if (roleSelect.value === 'clinic') {
                    facilityWrapper.style.display = 'block';
                } else {
                    facilityWrapper.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleFacility);
            toggleFacility(); // call on page load to set initial state
        });
    </script>
    <!-- ============================================================= -->

</body>
</html>