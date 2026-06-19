@extends('layouts.clinic')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.edit') }} {{ __('messages.clinic_profile') }}</h2>
        <a href="{{ route('clinic.profile') }}" class="text-slate-500 hover:text-slate-700 transition">
            ← {{ __('messages.back') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-4 mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-xl mt-4 mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('clinic.profile.update') }}" enctype="multipart/form-data" class="mt-6">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.clinic_name') }}</label>
                <input type="text" name="clinic_name" value="{{ old('clinic_name', $clinic->clinic_name) }}" class="w-full border rounded-xl px-4 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $clinic->phone) }}" class="w-full border rounded-xl px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.auth_email') }}</label>
                <input type="email" name="email" value="{{ old('email', $clinic->email) }}" class="w-full border rounded-xl px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.address') }}</label>
                <input type="text" name="address" value="{{ old('address', $clinic->address) }}" class="w-full border rounded-xl px-4 py-2">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.description') }}</label>
                <textarea name="description" rows="4" class="w-full border rounded-xl px-4 py-2">{{ old('description', $clinic->description) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.logo') }}</label>
                @if($clinic->logo)
                    <img src="{{ asset('storage/' . $clinic->logo) }}" class="w-24 h-24 rounded-full object-cover mb-2" alt="Logo">
                @endif
                <input type="file" name="logo" class="w-full border rounded-xl px-4 py-2">
                <p class="text-xs text-slate-400 mt-1">Max 2MB (JPG, PNG, GIF)</p>
            </div>
        </div>
        <button type="submit" class="mt-6 bg-cyan-600 text-white px-8 py-2 rounded-xl hover:bg-cyan-700 transition">
            {{ __('messages.save') }}
        </button>
    </form>
</div>
@endsection