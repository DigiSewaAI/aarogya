@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.notifications') }}</h2>
        @if($unreadCount > 0)
            @php
                $role = Auth::user()->role;
                $readAllRoute = $role . '.notifications.read-all';
            @endphp
            <form method="POST" action="{{ route($readAllRoute) }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-cyan-600 hover:underline">
                    {{ __('messages.mark_all_as_read') }}
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6">{{ session('success') }}</div>
    @endif

    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
                @php
                    $role = Auth::user()->role;
                    $readRoute = $role . '.notifications.read';
                @endphp
                <div class="bg-white rounded-xl shadow p-4 flex items-start gap-4 {{ $notification->is_read ? 'opacity-60' : 'border-l-4 border-cyan-500' }}">
                    <div class="flex-1">
                        <p class="text-slate-800">{{ $notification->message }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        @if($notification->link)
                            <a href="{{ $notification->link }}" class="text-sm text-cyan-600 hover:underline mt-1 inline-block">
                                {{ __('messages.view_details') }}
                            </a>
                        @endif
                    </div>
                    @if(!$notification->is_read)
                        <form method="POST" action="{{ route($readRoute, $notification->id) }}">
                            @csrf
                            <button type="submit" class="text-xs text-slate-400 hover:text-slate-600" title="{{ __('messages.mark_as_read') }}">
                                ✅
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow p-12 text-center">
            <div class="text-6xl mb-4">🔔</div>
            <p class="text-slate-500">{{ __('messages.no_notifications') }}</p>
        </div>
    @endif
</div>
@endsection