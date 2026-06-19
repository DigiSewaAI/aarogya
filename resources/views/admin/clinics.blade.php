@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.clinics') }}</h2>
        <span class="text-sm text-slate-500">{{ $clinics->total() }} {{ __('messages.total') }}</span>
    </div>

    <form method="GET" class="mt-4 flex gap-4">
        <input type="text" name="search" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}" class="border rounded-xl px-4 py-2 flex-1">
        <select name="status" class="border rounded-xl px-4 py-2">
            <option value="">{{ __('messages.all_status') }}</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
        </select>
        <button type="submit" class="bg-cyan-600 text-white px-6 py-2 rounded-xl">{{ __('messages.filter') }}</button>
        <a href="{{ route('admin.clinics') }}" class="bg-slate-300 text-slate-700 px-6 py-2 rounded-xl">{{ __('messages.reset') }}</a>
    </form>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-3">{{ __('messages.clinic_name') }}</th>
                    <th class="text-left py-3">{{ __('messages.auth_email') }}</th>
                    <th class="text-left py-3">{{ __('messages.phone') }}</th>
                    <th class="text-left py-3">{{ __('messages.verification_status') }}</th>
                    <th class="text-left py-3">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clinics as $clinic)
                <tr class="border-b hover:bg-slate-50">
                    <td class="py-3">{{ $clinic->clinic_name }}</td>
                    <td class="py-3">{{ $clinic->email ?? '-' }}</td>
                    <td class="py-3">{{ $clinic->phone ?? '-' }}</td>
                    <td class="py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($clinic->verification_status == 'approved') bg-green-100 text-green-700
                            @elseif($clinic->verification_status == 'pending') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($clinic->verification_status) }}
                        </span>
                    </td>
                    <td class="py-3 flex gap-2">
                        @if($clinic->verification_status == 'pending')
                            <form method="POST" action="{{ route('admin.verify.clinic', $clinic->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-green-600">{{ __('messages.approve') }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.reject.clinic', $clinic->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600">{{ __('messages.reject') }}</button>
                            </form>
                        @else
                            <span class="text-sm text-slate-400">{{ __('messages.already_processed') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">{{ __('messages.no_clinics_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $clinics->appends(request()->query())->links() }}
</div>
@endsection