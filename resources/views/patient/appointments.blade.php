@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.my_appointments') }}</h2>
        <a href="{{ route('doctors') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-xl hover:bg-cyan-700 transition text-sm">
            + {{ __('messages.book_appointment') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6">{{ session('success') }}</div>
    @endif

    @if($appointments->count() > 0)
        <div class="space-y-4">
            @foreach($appointments as $appointment)
                <div class="bg-white rounded-2xl shadow p-6 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <p class="font-semibold text-slate-800">{{ $appointment->doctor->name }}</p>
                        <p class="text-sm text-slate-500">{{ $appointment->doctor->specialization }}</p>
                        <p class="text-sm text-slate-600 mt-1">
                            📅 {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }} 
                            🕐 {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </p>
                        @if($appointment->symptoms)
                            <p class="text-xs text-slate-400 mt-1">🩺 {{ $appointment->symptoms }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 flex-wrap">
                        {{-- Status Badge --}}
                        <span class="px-3 py-1 text-xs rounded-full {{ $appointment->status_badge }}">
                            {{ ucfirst($appointment->status) }}
                        </span>

                        {{-- QR Code Button --}}
                        <button onclick="showQRModal('{{ $appointment->id }}')" 
        class="bg-purple-100 text-purple-700 px-3 py-1 rounded-lg text-sm hover:bg-purple-200 transition flex items-center gap-1">
    <span>📱</span> QR Code
</button>

                        {{-- Cancel Button --}}
                        @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                            <form method="POST" action="{{ route('patient.appointments.cancel', $appointment->id) }}" 
                                  onsubmit="return confirm('{{ __('messages.confirm_cancel') }}')">
                                @csrf
                                <button type="submit" class="text-red-600 text-sm hover:underline">{{ __('messages.cancel') }}</button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- QR Code Modal (hidden by default, shown per appointment) --}}
                <div id="qrModal-{{ $appointment->id }}" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden" onclick="if(event.target===this) closeQRModal('{{ $appointment->id }}')">
                    <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl relative">
                        <button onclick="closeQRModal('{{ $appointment->id }}')" class="absolute top-3 right-4 text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
                        <div class="text-center">
                            <h3 class="font-bold text-lg text-slate-800 mb-1">{{ __('messages.appointment_qr') }}</h3>
                            <p class="text-sm text-slate-500">{{ $appointment->doctor->name }}</p>
                            <p class="text-xs text-slate-400">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }} 
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </p>
                            <div class="my-4 flex justify-center">
                                @if($appointment->qr_code_base64)
                                    <img src="data:image/png;base64,{{ $appointment->qr_code_base64 }}" 
                                         alt="QR Code for appointment #{{ $appointment->id }}" 
                                         class="w-48 h-48">
                                @else
                                    <div class="w-48 h-48 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                            <p class="text-xs mt-1">QR not available</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400">{{ __('messages.scan_to_view_appointment') }}</p>
                            <div class="mt-4 flex justify-center gap-2">
                                @if($appointment->qr_code_base64)
                                    <a href="data:image/png;base64,{{ $appointment->qr_code_base64 }}" 
                                       download="appointment-{{ $appointment->id }}.png" 
                                       class="bg-cyan-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-cyan-700 transition">
                                        ⬇️ {{ __('messages.download') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow p-12 text-center">
            <div class="text-6xl mb-4">📋</div>
            <p class="text-slate-500">{{ __('messages.no_appointments') }}</p>
            <a href="{{ route('doctors') }}" class="inline-block mt-4 bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                {{ __('messages.find_doctor') }}
            </a>
        </div>
    @endif
</div>

<script>
    function showQRModal(id) {
        document.getElementById('qrModal-' + id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeQRModal(id) {
        document.getElementById('qrModal-' + id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="qrModal-"]').forEach(modal => {
                modal.classList.add('hidden');
            });
            document.body.style.overflow = 'auto';
        }
    });
</script>
@endsection