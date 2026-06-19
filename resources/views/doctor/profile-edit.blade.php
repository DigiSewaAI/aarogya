@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('messages.edit_profile') }}</h2>
                <p class="text-cyan-100 text-sm">Update your professional information</p>
            </div>
            <a href="{{ route('doctor.profile') }}" 
               class="bg-white text-cyan-600 px-4 py-2 rounded-xl hover:bg-cyan-50 transition text-sm font-medium">
                ← {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="p-8">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">⚠️</span>
                    <div>
                        <p class="font-semibold">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Profile Photo --}}
            <div class="flex items-center gap-6 pb-6 border-b border-slate-200">
                <div class="relative">
                    @if($doctor->profile_photo)
                        <img src="{{ asset('storage/' . $doctor->profile_photo) }}" 
                             alt="Profile Photo" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-cyan-100">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <label for="profile_photo" class="absolute bottom-0 right-0 bg-cyan-600 text-white rounded-full p-2 cursor-pointer hover:bg-cyan-700 transition shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                </div>
                <div>
                    <p class="font-medium text-slate-800">{{ $user->name }}</p>
                    <p class="text-sm text-slate-500">{{ __('messages.doctor') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Click the camera icon to update photo (Max 2MB)</p>
                </div>
            </div>

            {{-- Personal Information --}}
            <div>
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">👤</span> Personal Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.auth_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.specialization') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}" 
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.qualification') }}</label>
                        <input type="text" name="qualification" value="{{ old('qualification', $doctor->qualification) }}" 
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.nmc_registration') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="nmc_registration" value="{{ old('nmc_registration', $doctor->nmc_registration) }}" 
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition" required>
                    </div>
                </div>
            </div>

            {{-- Professional Information --}}
            <div class="pt-4 border-t border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">💼</span> Professional Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.experience') }}</label>
                        <input type="number" name="experience" value="{{ old('experience', $doctor->experience) }}" 
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.consultation_fee') }}</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">Rs.</span>
                            <input type="number" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee) }}" 
                                   class="w-full border border-slate-300 rounded-xl px-12 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition" step="0.01" min="0">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Clinic Information --}}
            <div class="pt-4 border-t border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">🏥</span> Clinic Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.clinic_name') }}</label>
                        <input type="text" name="clinic_name" value="{{ old('clinic_name', $doctor->clinic_name) }}" 
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.clinic_address') }}</label>
                        <input type="text" name="clinic_address" value="{{ old('clinic_address', $doctor->clinic_address) }}" 
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition">
                    </div>
                </div>
            </div>

            {{-- Bio --}}
            <div class="pt-4 border-t border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">📝</span> About
                </h3>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.bio') }}</label>
                    <textarea name="bio" rows="5" 
                              class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                              maxlength="1000">{{ old('bio', $doctor->bio) }}</textarea>
                    <p class="text-xs text-slate-400 mt-1">Maximum 1000 characters</p>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="text-red-500">*</span> Required fields
                </div>
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-8 py-3 rounded-xl transition shadow-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('.relative img, .relative .w-24.h-24');
                if (img) {
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection