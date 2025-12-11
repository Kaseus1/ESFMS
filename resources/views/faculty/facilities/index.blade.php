@extends('layouts.faculty')

@section('title', 'Facilities')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F8F9FA 0%, #FFFFFF 100%);">
    <!-- Header -->
    <div class="bg-white shadow-md border-b" style="border-color: rgba(51, 60, 77, 0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold" style="color: #172030;">Facilities</h1>
                    <p class="mt-2 text-sm font-medium" style="color: #6B7280;">
                        Browse and book available facilities for your events and activities
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-bold shadow-sm border" style="background: linear-gradient(135deg, #E0F2FE, #DBEAFE); color: #002366; border-color: rgba(0, 35, 102, 0.2);">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ $totalFacilities }} Total
                    </span>
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-bold shadow-sm border" style="background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; border-color: rgba(5, 150, 105, 0.2);">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $availableFacilities }} Available
                    </span>
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-bold shadow-sm border" style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; border-color: rgba(217, 119, 6, 0.2);">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $bookedFacilities }} Booked
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Search and Filters -->
        <div class="mb-8">
            <div class="bg-white shadow-lg rounded-xl border p-6" style="border-color: rgba(51, 60, 77, 0.15);">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 gap-4">
                    <div class="flex-1 max-w-lg">
                        <label class="block text-sm font-bold mb-2" style="color: #002366;">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search Facilities
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5" style="color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="searchInput"
                                   class="block w-full pl-11 pr-4 py-3 border rounded-lg leading-5 bg-white shadow-sm transition-all duration-200 focus:ring-2 focus:ring-offset-0" 
                                   style="border-color: #D1D5DB; color: #333C4D; focus:border-color: #002366; focus:ring-color: rgba(0, 35, 102, 0.1);"
                                   placeholder="Search by name, location, or type..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: #002366;">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Type
                            </label>
                            <select id="typeFilter" class="rounded-lg border text-sm bg-white shadow-sm px-4 py-3 font-medium transition-all duration-200 focus:ring-2 focus:ring-offset-0" style="border-color: #D1D5DB; color: #333C4D; min-width: 160px;">
                                <option value="all">All Types</option>
                                <option value="classroom" {{ request('type') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                                <option value="conference_room" {{ request('type') == 'conference_room' ? 'selected' : '' }}>Conference Room</option>
                                <option value="auditorium" {{ request('type') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
                                <option value="laboratory" {{ request('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                <option value="sports_facility" {{ request('type') == 'sports_facility' ? 'selected' : '' }}>Sports Facility</option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: #002366;">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Status
                            </label>
                            <select id="statusFilter" class="rounded-lg border text-sm bg-white shadow-sm px-4 py-3 font-medium transition-all duration-200 focus:ring-2 focus:ring-offset-0" style="border-color: #D1D5DB; color: #333C4D; min-width: 160px;">
                                <option value="">All Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facilities Grid -->
        <div id="facilitiesContainer">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($facilities as $facility)
                <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 border transform hover:-translate-y-1" style="border-color: rgba(51, 60, 77, 0.15);">
                    <!-- Facility Image -->
                    <div class="h-52 overflow-hidden relative" style="background: linear-gradient(135deg, #002366 0%, #001A4A 100%);">
                        @if($facility->image_url)
                            <img src="{{ $facility->image_url }}" 
                                 alt="{{ $facility->name }}" 
                                 class="w-full h-full object-cover absolute inset-0 hover:scale-110 transition-transform duration-500"
                                 onerror="this.style.display='none'">
                        @endif
                        <!-- Fallback placeholder -->
                        <div class="absolute inset-0 flex items-center justify-center" id="fallback-{{ $facility->id }}">
                            <div class="text-center p-6">
                                <svg class="w-16 h-16 mx-auto mb-3 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-white text-sm font-bold opacity-75">{{ Str::limit($facility->name, 25) }}</p>
                            </div>
                        </div>
                        @if($facility->image_url)
                        <script>
                            document.querySelector('img[alt="{{ addslashes($facility->name) }}"]')?.addEventListener('load', function() {
                                document.getElementById('fallback-{{ $facility->id }}')?.remove();
                            });
                        </script>
                        @endif
                        
                        <!-- Status Badge Overlay -->
                        <div class="absolute top-4 right-4">
                            @if($facility->status === 'available' || $facility->status === true || $facility->status === 1)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg border-2 border-white">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Available
                                </span>
                            @elseif($facility->status === 'booked')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-500 text-white shadow-lg border-2 border-white">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Booked
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-500 text-white shadow-lg border-2 border-white">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Unavailable
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Facility Content -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3 gap-2">
                            <h3 class="text-lg font-bold line-clamp-1 flex-1" style="color: #172030;">{{ $facility->name }}</h3>
                            <span class="px-3 py-1 text-xs font-bold rounded-lg border
                                @if($facility->type == 'classroom') bg-blue-50 text-blue-800 border-blue-200
                                @elseif($facility->type == 'conference_room') bg-purple-50 text-purple-800 border-purple-200
                                @elseif($facility->type == 'auditorium') bg-indigo-50 text-indigo-800 border-indigo-200
                                @elseif($facility->type == 'laboratory') bg-pink-50 text-pink-800 border-pink-200
                                @elseif($facility->type == 'sports_facility') bg-orange-50 text-orange-800 border-orange-200
                                @else bg-gray-50 text-gray-800 border-gray-200 @endif">
                                {{ ucwords(str_replace('_', ' ', $facility->type)) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center mb-4 text-sm" style="color: #6B7280;">
                            <svg class="w-4 h-4 mr-2" style="color: #002366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium">{{ $facility->location }}</span>
                        </div>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between p-3 rounded-lg border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" style="color: #002366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-xs font-semibold" style="color: #333C4D;">Capacity</span>
                                </div>
                                <span class="text-sm font-bold" style="color: #172030;">
                                    {{ $facility->capacity ?? 'N/A' }}@if($facility->max_capacity) - {{ $facility->max_capacity }}@endif
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 rounded-lg border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" style="color: #002366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs font-semibold" style="color: #333C4D;">Hours</span>
                                </div>
                                <span class="text-xs font-bold" style="color: #172030;">
                                    {{ $facility->opening_time ? \Carbon\Carbon::parse($facility->opening_time)->format('g:i A') : 'N/A' }} - 
                                    {{ $facility->closing_time ? \Carbon\Carbon::parse($facility->closing_time)->format('g:i A') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Next Reservation -->
                        <div class="mb-4 p-3 rounded-lg border" style="background: linear-gradient(135deg, #F8F9FA, #FFFFFF); border-color: rgba(51, 60, 77, 0.1);">
                            @php
                                $nextReservation = $facility->reservations()
                                    ->where('status', 'approved')
                                    ->where('start_time', '>=', now())
                                    ->orderBy('start_time')
                                    ->first();
                            @endphp
                            
                            @if($nextReservation)
                                <p class="text-xs font-semibold flex items-center" style="color: #D97706;">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Next: {{ \Carbon\Carbon::parse($nextReservation->start_time)->format('M d, g:i A') }}
                                </p>
                            @else
                                <p class="text-xs font-bold flex items-center" style="color: #059669;">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    No upcoming reservations
                                </p>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <a href="{{ route('faculty.facilities.show', $facility) }}" 
                               class="flex-1 text-center py-3 px-4 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 border-2 hover:shadow-md"
                               style="background-color: #FFFFFF; color: #002366; border-color: #002366;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                            <a href="{{ route('faculty.reservations.create', ['facility_id' => $facility->id]) }}" 
                               class="flex-1 text-center py-3 px-4 rounded-lg text-sm font-bold transition-all duration-200 shadow-md hover:shadow-xl flex items-center justify-center gap-2 text-white transform hover:scale-105"
                               style="background: linear-gradient(135deg, #002366, #001A4A);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-6 shadow-lg" style="background: linear-gradient(135deg, #F3F4F6, #E5E7EB);">
                        <svg class="w-10 h-10" style="color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2" style="color: #172030;">No facilities found</h3>
                    <p class="text-sm mb-6" style="color: #6B7280;">Try adjusting your search criteria or filters to find what you're looking for.</p>
                    <button onclick="window.location.href='{{ route('faculty.facilities.index') }}'" 
                            class="inline-flex items-center px-6 py-3 text-sm font-bold rounded-lg transition-all duration-200 text-white shadow-md hover:shadow-lg transform hover:scale-105"
                            style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filters
                    </button>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($facilities->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white shadow-lg rounded-xl border px-6 py-4" style="border-color: rgba(51, 60, 77, 0.15);">
                    {{ $facilities->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    let searchTimeout;
    
    function updateURL() {
        const params = new URLSearchParams();
        
        if (searchInput.value) {
            params.set('search', searchInput.value);
        }
        
        if (typeFilter.value && typeFilter.value !== 'all') {
            params.set('type', typeFilter.value);
        }
        
        if (statusFilter.value) {
            params.set('status', statusFilter.value);
        }
        
        const newURL = params.toString() ? `${window.location.pathname}?${params.toString()}` : window.location.pathname;
        window.location.href = newURL;
    }
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateURL, 500);
    });
    
    typeFilter.addEventListener('change', updateURL);
    statusFilter.addEventListener('change', updateURL);
});
</script>
@endpush
@endsection