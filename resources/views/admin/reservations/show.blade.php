@extends('layouts.admin')

@section('title', 'Reservation Details')

@section('content')
<div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-green-600">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Reservation Details</h2>
        <div class="flex gap-2 flex-wrap">
            @if($reservation->status === 'pending')
                <form method="POST" action="{{ route('admin.reservations.approve', $reservation->id) }}" class="inline-block">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">
                        <i class="fa-solid fa-check mr-2"></i>Approve
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.reservations.reject', $reservation->id) }}" class="inline-block">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md">
                        <i class="fa-solid fa-xmark mr-2"></i>Reject
                    </button>
                </form>
            @endif
            @if($reservation->status === 'pending' || $reservation->status === 'approved')
                <a href="{{ route('admin.reservations.edit', $reservation->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                    <i class="fa-solid fa-pen-to-square mr-2"></i>Edit
                </a>
            @endif
        </div>
    </div>

    <div class="mb-6">
        <span class="px-4 py-2 rounded-full text-sm font-bold uppercase
            @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($reservation->status === 'approved') bg-green-100 text-green-800
            @elseif($reservation->status === 'rejected') bg-red-100 text-red-800
            @elseif($reservation->status === 'cancelled') bg-gray-100 text-gray-800
            @endif">
            <i class="fa-solid 
                @if($reservation->status === 'pending') fa-clock
                @elseif($reservation->status === 'approved') fa-check-circle
                @elseif($reservation->status === 'rejected') fa-circle-xmark
                @elseif($reservation->status === 'cancelled') fa-ban
                @endif mr-2"></i>
            {{ ucfirst($reservation->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Event Name</label>
                <p class="text-gray-900 font-medium">{{ $reservation->event_name ?? 'N/A' }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Facility</label>
                @if($reservation->facility)
                    <p class="text-gray-900 font-medium">
                        <i class="fa-solid fa-building text-green-600 mr-2"></i>
                        {{ $reservation->facility->name }}
                    </p>
                    @if($reservation->facility->trashed())
                        <p class="text-xs text-red-500 mt-1">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                            This facility has been deleted
                        </p>
                    @endif
                    @if($reservation->facility->location)
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fa-solid fa-location-dot mr-1"></i>
                            {{ $reservation->facility->location }}
                        </p>
                    @endif
                @else
                    <p class="text-red-600 font-medium">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        Facility no longer exists
                    </p>
                @endif
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Reserved By</label>
                @if($reservation->user)
                    <p class="text-gray-900 font-medium">
                        <i class="fa-solid fa-user text-blue-600 mr-2"></i>
                        {{ $reservation->user->name }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fa-solid fa-envelope mr-1"></i>
                        {{ $reservation->user->email }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fa-solid fa-tag mr-1"></i>
                        {{ ucfirst($reservation->user->role ?? 'guest') }}
                    </p>
                @else
                    <p class="text-red-600 font-medium">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        User no longer exists
                    </p>
                @endif
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Participants</label>
                <p class="text-gray-900 font-medium">
                    <i class="fa-solid fa-users text-purple-600 mr-2"></i>
                    {{ $reservation->participants ?? 'Not specified' }} {{ $reservation->participants ? 'people' : '' }}
                </p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Start Time</label>
                <p class="text-gray-900 font-medium">
                    {{-- Fixed Icon: fa-calendar-start is Pro only, changed to fa-calendar-check --}}
                    <i class="fa-solid fa-calendar-check text-green-600 mr-2"></i>
                    {{ $reservation->start_time ? \Carbon\Carbon::parse($reservation->start_time)->format('F d, Y') : 'N/A' }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fa-solid fa-clock text-gray-400 mr-2"></i>
                    {{ $reservation->start_time ? \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') : '--:--' }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">End Time</label>
                <p class="text-gray-900 font-medium">
                     {{-- Fixed Icon: fa-calendar-end is Pro only, changed to fa-calendar-xmark --}}
                    <i class="fa-solid fa-calendar-xmark text-red-600 mr-2"></i>
                    {{ $reservation->end_time ? \Carbon\Carbon::parse($reservation->end_time)->format('F d, Y') : 'N/A' }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fa-solid fa-clock text-gray-400 mr-2"></i>
                    {{ $reservation->end_time ? \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') : '--:--' }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Duration</label>
                <p class="text-gray-900 font-medium">
                    <i class="fa-solid fa-hourglass-half text-orange-600 mr-2"></i>
                    @if($reservation->start_time && $reservation->end_time)
                        {{ \Carbon\Carbon::parse($reservation->start_time)->diffForHumans(\Carbon\Carbon::parse($reservation->end_time), true) }}
                    @else
                        N/A
                    @endif
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Created</label>
                <p class="text-gray-900 font-medium">
                    <i class="fa-solid fa-calendar-plus text-blue-600 mr-2"></i>
                    {{ $reservation->created_at->format('M d, Y h:i A') }}
                </p>
                <p class="text-sm text-gray-500 mt-1">{{ $reservation->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    @if($reservation->notes)
    <div class="mt-6 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
        <label class="text-sm font-semibold text-gray-600 block mb-2">
            <i class="fa-solid fa-note-sticky text-blue-600 mr-2"></i>Notes
        </label>
        <p class="text-gray-700">{{ $reservation->notes }}</p>
    </div>
    @endif

    @if($reservation->purpose)
    <div class="mt-4 bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
        <label class="text-sm font-semibold text-gray-600 block mb-2">
            <i class="fa-solid fa-bullseye text-purple-600 mr-2"></i>Purpose
        </label>
        <p class="text-gray-700">{{ $reservation->purpose }}</p>
    </div>
    @endif

    @if($reservation->requires_setup || $reservation->requires_equipment)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($reservation->requires_setup)
        <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
            <label class="text-sm font-semibold text-gray-600 block mb-2">
                <i class="fa-solid fa-wrench text-yellow-600 mr-2"></i>Setup Requirements
            </label>
            <p class="text-gray-700">{{ $reservation->setup_requirements ?? 'Setup required' }}</p>
        </div>
        @endif

        @if($reservation->requires_equipment)
        <div class="bg-indigo-50 rounded-lg p-4 border-l-4 border-indigo-500">
            <label class="text-sm font-semibold text-gray-600 block mb-2">
                <i class="fa-solid fa-laptop text-indigo-600 mr-2"></i>Equipment Needed
            </label>
            <p class="text-gray-700">{{ $reservation->equipment_needed ?? 'Equipment required' }}</p>
        </div>
        @endif
    </div>
    @endif

    @if($reservation->is_recurring)
    <div class="mt-6 bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
        <label class="text-sm font-semibold text-gray-600 block mb-2">
            <i class="fa-solid fa-repeat text-purple-600 mr-2"></i>Recurring Reservation
        </label>
        <p class="text-gray-700">
            <strong>Type:</strong> {{ ucfirst($reservation->recurrence_type ?? 'N/A') }}
        </p>
        @if($reservation->recurrence_end_date)
            <p class="text-gray-700 mt-1">
                <strong>Ends:</strong> {{ \Carbon\Carbon::parse($reservation->recurrence_end_date)->format('M d, Y') }}
            </p>
        @endif
    </div>
    @endif

    <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('admin.reservations.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Reservations
        </a>

        @if(in_array($reservation->status, ['pending', 'approved']))
            <a href="{{ route('admin.reservations.edit', $reservation->id) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                <i class="fa-solid fa-pen-to-square mr-2"></i>Edit Reservation
            </a>
        @endif

        @if($reservation->facility)
            <a href="{{ route('admin.facilities.show', $reservation->facility->id) }}" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition shadow-md">
                <i class="fa-solid fa-building mr-2"></i>View Facility
            </a>
        @endif

        <form method="POST" action="{{ route('admin.reservations.destroy', $reservation->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this reservation? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md">
                <i class="fa-solid fa-trash-can mr-2"></i>Delete
            </button>
        </form>
    </div>
</div>
@endsection