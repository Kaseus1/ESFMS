@extends('layouts.admin')

@section('title', 'Facility Details - ' . $facility->name)

@section('content')
<div class="mb-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.facilities.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-[#333C4D] border-opacity-20 text-[#333C4D] text-sm font-semibold rounded-lg shadow hover:bg-[#F8F9FA] transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($facility->trashed())
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Deleted</span>
            @elseif($facility->status)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
            @endif
            
            @if(!$facility->trashed())
                <a href="{{ route('admin.facilities.edit', $facility->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#002366] text-white text-sm font-semibold rounded-lg shadow hover:bg-[#00285C] transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Facility
                </a>
            @endif
        </div>
    </div>


   

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-5 border-t-4 border-[#002366]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[#333C4D] text-sm font-semibold">Total Reservations</h3>
                    <p class="text-3xl font-bold mt-2 text-[#002366]">{{ $facilityStats['total_reservations'] }}</p>
                </div>
                <div class="bg-[#002366] bg-opacity-10 rounded-full p-3">
                    <svg class="w-8 h-8 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-5 border-t-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[#333C4D] text-sm font-semibold">Approved</h3>
                    <p class="text-3xl font-bold mt-2 text-green-600">{{ $facilityStats['approved_reservations'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-5 border-t-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[#333C4D] text-sm font-semibold">Pending</h3>
                    <p class="text-3xl font-bold mt-2 text-yellow-600">{{ $facilityStats['pending_reservations'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-5 border-t-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[#333C4D] text-sm font-semibold">Rejected</h3>
                    <p class="text-3xl font-bold mt-2 text-red-600">{{ $facilityStats['rejected_reservations'] }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Facility Information --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                    <h3 class="text-lg font-semibold text-[#172030]">Facility Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Facility Name</dt>
                            <dd class="mt-1 text-base font-semibold text-[#172030]">{{ $facility->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Location</dt>
                            <dd class="mt-1 text-base text-[#172030]">{{ $facility->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Capacity</dt>
                            <dd class="mt-1 text-base text-[#172030]">
                                {{ $facility->capacity ?? 'N/A' }}
                                @if($facility->max_capacity)
                                    - {{ $facility->max_capacity }} people
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Operating Hours</dt>
                            <dd class="mt-1 text-base text-[#172030]">{{ $facility->opening_time }} - {{ $facility->closing_time }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Hourly Rate</dt>
                            <dd class="mt-1 text-xl font-bold text-[#002366]">₱{{ number_format($facility->hourly_rate ?? 0, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Buffer Time</dt>
                            <dd class="mt-1 text-base text-[#172030]">{{ $facility->buffer_time ?? 0 }} minutes</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Visibility</dt>
                            <dd class="mt-1">
                                @if($facility->is_public)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#002366] bg-opacity-10 text-[#002366]">Public</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Private</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-[#333C4D]">Status</dt>
                            <dd class="mt-1">
                                @if($facility->trashed())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Deleted</span>
                                @elseif($facility->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </dd>
                        </div>
                        @if($facility->description)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-[#333C4D]">Description</dt>
                                <dd class="mt-1 text-sm text-[#333C4D]">{{ $facility->description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Facility Image --}}
            @if($facility->image)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                        <h3 class="text-lg font-semibold text-[#172030]">Facility Image</h3>
                    </div>
                    <div class="relative h-64 bg-[#F8F9FA]">
                        <img src="{{ Storage::disk('public')->url($facility->image) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            @endif

            {{-- Recent Reservations --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                    <h3 class="text-lg font-semibold text-[#172030]">Recent Reservations</h3>
                </div>
                <div class="p-6">
                    @if($facility->reservations && $facility->reservations->count() > 0)
                        <div class="space-y-3">
                            @foreach($facility->reservations as $reservation)
                                <div class="flex items-center justify-between p-4 bg-[#F8F9FA] border border-[#333C4D] border-opacity-20 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-[#172030]">{{ $reservation->user->name ?? 'Unknown User' }}</p>
                                        <p class="mt-1 text-xs text-[#333C4D]">
                                            {{ date('M d, Y • g:i A', strtotime($reservation->start_time)) }} - {{ date('g:i A', strtotime($reservation->end_time)) }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $reservation->status === 'approved' ? 'bg-green-100 text-green-700' : 
                                           ($reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-4 text-[#333C4D] font-medium">No reservations found</p>
                            <p class="text-sm text-[#333C4D] opacity-60">This facility has no reservations yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Info --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                    <h3 class="text-lg font-semibold text-[#172030]">Quick Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-[#333C4D]">Facility ID</dt>
                        <dd class="mt-1 text-sm text-[#172030]">#{{ $facility->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-[#333C4D]">Created</dt>
                        <dd class="mt-1 text-sm text-[#172030]">{{ $facility->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-[#333C4D]">Last Updated</dt>
                        <dd class="mt-1 text-sm text-[#172030]">{{ $facility->updated_at->format('M d, Y g:i A') }}</dd>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                    <h3 class="text-lg font-semibold text-[#172030]">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($facility->trashed())
                        <form action="{{ route('admin.facilities.restore', $facility->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-green-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Restore Facility
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility->id) }}" onsubmit="return confirm('PERMANENTLY DELETE this facility? This cannot be undone!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-red-300 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-50 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Permanently Delete
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-[#002366] text-white text-sm font-semibold rounded-lg shadow hover:bg-[#00285C] transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Facility
                        </a>
                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility->id) }}" onsubmit="return confirm('Delete this facility? It will be moved to trash.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Facility
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection