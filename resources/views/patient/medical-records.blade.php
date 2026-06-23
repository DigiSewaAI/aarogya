@extends('layouts.app')

@section('title', __('messages.medical_records'))

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.medical_records') }}</h2>
        <a href="{{ route('patient.medical-records.create') }}" 
           class="bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
            + {{ __('messages.upload_record') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($records->count() > 0)
        <div class="grid gap-4">
            @foreach($records as $record)
                <div class="bg-white rounded-2xl shadow p-6 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">
                                @switch($record->category)
                                    @case('lab_report') 🧪 @break
                                    @case('prescription') 💊 @break
                                    @case('x_ray') 🩻 @break
                                    @case('scan_report') 📋 @break
                                    @case('vaccination') 💉 @break
                                    @default 📁
                                @endswitch
                            </span>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $record->title }}</p>
                                <p class="text-sm text-slate-500">{{ ucfirst(str_replace('_', ' ', $record->category)) }}</p>
                            </div>
                        </div>
                        @if($record->description)
                            <p class="text-sm text-slate-500 mt-1">{{ $record->description }}</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $record->created_at->format('M d, Y') }} • {{ $record->formatted_size }}
                            @if($record->shared_with_doctor)
                                <span class="text-green-600 ml-2">✓ Shared with doctor</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <a href="{{ route('patient.medical-records.download', $record->id) }}" 
                           class="text-cyan-600 hover:text-cyan-800 text-sm">⬇️ {{ __('messages.download') }}</a>
                        
                        <form method="POST" action="{{ route('patient.medical-records.share', $record->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm {{ $record->shared_with_doctor ? 'text-green-600' : 'text-slate-500' }} hover:underline">
                                {{ $record->shared_with_doctor ? '🔓 Shared' : '🔒 Private' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('patient.medical-records.delete', $record->id) }}" 
                              onsubmit="return confirm('{{ __('messages.confirm_delete_record') }}')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm hover:text-red-700">🗑️</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $records->links() }}</div>
    @else
        <div class="bg-white rounded-2xl shadow p-12 text-center">
            <div class="text-6xl mb-4">📁</div>
            <p class="text-slate-500">{{ __('messages.no_records') }}</p>
            <a href="{{ route('patient.medical-records.create') }}" 
               class="inline-block mt-4 bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                {{ __('messages.upload_first_record') }}
            </a>
        </div>
    @endif
</div>
@endsection