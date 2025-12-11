@extends('layouts.guest')

@section('title', 'Facility Details - ' . $facility->name)

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F8F9FA 0%, #FFFFFF 100%);">
    <div class="bg-white shadow-md border-b" style="border-color: rgba(51, 60, 77, 0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center py-4 sm:py-6">

                <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('guest.facilities.index') }}"
                       class="inline-flex items-center justify-center px-4 py-3 sm:px-4 sm:py-2 border shadow-sm text-sm leading-4 font-medium rounded-lg text-white transition-all duration-200 hover:shadow-lg transform hover:scale-105 w-full sm:w-auto"
                       style="background: linear-gradient(135deg, #333C4D, #172030); border-color: #333C4D;">
                        <svg class="-ml-0.5 mr-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="truncate">Back to Facilities</span>
                    </a>
                    <div class="text-left">
                        <h1 class="text-xl sm:text-2xl font-bold" style="color: #172030;">Facility Details</h1>
                        <p class="text-sm font-medium" style="color: #002366; line-height: 1.4;">{{ $facility->name }}</p>
                    </div>
                </div>

                <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:space-x-3">
                    <div class="flex-shrink-0 order-2 sm:order-1">
                        @if($facility->status)
                            <span class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                <span class="truncate">Available</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                <span class="truncate">Unavailable</span>
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('guest.reservations.create', ['facility_id' => $facility->id]) }}"
                       class="inline-flex items-center justify-center px-5 py-3 sm:px-5 sm:py-2.5 border-0 text-sm font-semibold rounded-lg shadow-md text-white transition-all duration-200 transform hover:scale-105 hover:shadow-xl w-full sm:w-auto order-1 sm:order-2"
                       style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <svg class="-ml-0.5 mr-2 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="truncate">Make Reservation</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Facility Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white shadow-lg rounded-xl border overflow-hidden" style="border-color: rgba(51, 60, 77, 0.15);">
                    <div class="px-6 py-4" style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-bold text-white">Facility Information</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white backdrop-blur-sm border border-white/30"
                                  style="background: rgba(255, 255, 255, 0.2);">
                                {{ ucwords(str_replace('_', ' ', $facility->type)) }}
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-lg p-4 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-sm font-semibold mb-1" style="color: #002366;">Facility Name</dt>
                                <dd class="text-lg font-bold" style="color: #172030;">{{ $facility->name }}</dd>
                            </div>
                            <div class="rounded-lg p-4 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-sm font-semibold mb-1" style="color: #002366;">Location</dt>
                                <dd class="text-lg font-semibold" style="color: #172030;">{{ $facility->location }}</dd>
                            </div>
                            <div class="rounded-lg p-4 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-sm font-semibold mb-1" style="color: #002366;">Capacity</dt>
                                <dd class="text-lg font-semibold" style="color: #172030;">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" style="color: #002366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $facility->capacity ?? 'Not specified' }}
                                    </span>
                                    @if($facility->max_capacity)
                                        <span class="text-sm" style="color: #333C4D;">(Max: {{ $facility->max_capacity }})</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="rounded-lg p-4 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-sm font-semibold mb-1" style="color: #002366;">Operating Hours</dt>
                                <dd class="text-lg font-semibold" style="color: #172030;">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" style="color: #002366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm">
                                            {{ $facility->opening_time ? \Carbon\Carbon::parse($facility->opening_time)->format('g:i A') : 'N/A' }} - 
                                            {{ $facility->closing_time ? \Carbon\Carbon::parse($facility->closing_time)->format('g:i A') : 'N/A' }}
                                        </span>
                                    </div>
                                </dd>
                            </div>
                            <div class="rounded-lg p-4 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-sm font-semibold mb-1" style="color: #002366;">Availability</dt>
                                <dd class="mt-1">
                                    @if($facility->is_public)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Public Booking
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border" style="background: #F8F9FA; color: #333C4D; border-color: #333C4D;">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2" style="color: #333C4D;" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Private
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="rounded-lg p-4 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-sm font-semibold mb-1" style="color: #002366;">Status</dt>
                                <dd class="mt-1">
                                    @if($facility->status)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Unavailable
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if($facility->description)
                                <div class="md:col-span-2 rounded-lg p-4 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                    <dt class="text-sm font-semibold mb-2" style="color: #002366;">Description</dt>
                                    <dd class="text-sm leading-relaxed" style="color: #333C4D;">{{ $facility->description }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Upcoming Reservations -->
                @if($facility->reservations && $facility->reservations->count() > 0)
                <div class="bg-white shadow-lg rounded-xl border overflow-hidden" style="border-color: rgba(51, 60, 77, 0.15);">
                    <div class="px-6 py-4" style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <h3 class="text-lg leading-6 font-bold text-white">Upcoming Reservations</h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            @foreach($facility->reservations as $reservation)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow duration-200" style="background: linear-gradient(to right, #FFFFFF, #F8F9FA); border-color: rgba(51, 60, 77, 0.15);">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-bold mb-1" style="color: #172030;">
                                                {{ $reservation->event_name }}
                                            </p>
                                            <p class="text-sm font-medium mb-1" style="color: #002366;">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, Y - g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}
                                            </p>
                                            <p class="text-sm" style="color: #333C4D;">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Reserved by: <span class="font-semibold">{{ $reservation->user->name ?? 'Unknown User' }}</span>
                                                @if($reservation->participants)
                                                    • {{ $reservation->participants }} participants
                                                @endif
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                                      {{ $reservation->status === 'approved' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                                         ($reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-red-100 text-red-800 border border-red-200') }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white shadow-lg rounded-xl border overflow-hidden" style="border-color: rgba(51, 60, 77, 0.15);">
                    <div class="px-6 py-4" style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <h3 class="text-lg leading-6 font-bold text-white">Upcoming Reservations</h3>
                    </div>
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12" style="color: #333C4D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-4 text-sm font-medium" style="color: #333C4D;">No upcoming reservations found for this facility.</p>
                        <p class="mt-1 text-xs" style="color: #333C4D; opacity: 0.7;">Be the first to book this space!</p>
                    </div>
                </div>
                @endif

                <!-- Facility Image -->
                @if($facility->image)
                    <div class="bg-white shadow-lg rounded-xl border overflow-hidden" style="border-color: rgba(51, 60, 77, 0.15);">
                        <div class="px-6 py-4" style="background: linear-gradient(135deg, #002366, #001A4A);">
                            <h3 class="text-lg leading-6 font-bold text-white">Facility Image</h3>
                        </div>
                        <div class="p-6">
                            <div class="rounded-xl overflow-hidden shadow-md">
                                <img src="{{ $facility->image_url ?? asset('images/facility-placeholder.jpg') }}" 
                                     alt="{{ $facility->name }}"
                                     class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info -->
                <div class="bg-white shadow-lg rounded-xl border overflow-hidden" style="border-color: rgba(51, 60, 77, 0.15);">
                    <div class="px-6 py-4" style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <h3 class="text-lg leading-6 font-bold text-white">Quick Info</h3>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="space-y-4">
                            <div class="rounded-lg p-3 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-xs font-semibold uppercase tracking-wide" style="color: #002366;">Facility ID</dt>
                                <dd class="mt-1 text-sm font-bold" style="color: #172030;">#{{ $facility->id }}</dd>
                            </div>
                            <div class="rounded-lg p-3 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-xs font-semibold uppercase tracking-wide" style="color: #002366;">Created</dt>
                                <dd class="mt-1 text-sm font-bold" style="color: #172030;">{{ $facility->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div class="rounded-lg p-3 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <dt class="text-xs font-semibold uppercase tracking-wide" style="color: #002366;">Last Updated</dt>
                                <dd class="mt-1 text-sm font-bold" style="color: #172030;">{{ $facility->updated_at->format('M d, Y') }}</dd>
                            </div>
                            @if($facility->guest_accessible)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-green-700">Access</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Guest Accessible
                                    </span>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow-lg rounded-xl border overflow-hidden" style="border-color: rgba(51, 60, 77, 0.15);">
                    <div class="px-6 py-4" style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <h3 class="text-lg leading-6 font-bold text-white">Actions</h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <a href="{{ route('guest.reservations.create', ['facility_id' => $facility->id]) }}" 
                               class="w-full inline-flex justify-center items-center px-5 py-3 border-0 text-sm font-bold rounded-lg shadow-md text-white transition-all duration-200 transform hover:scale-105"
                               style="background: linear-gradient(135deg, #002366, #001A4A);">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Make Reservation
                            </a>

                            @if($facility->guest_accessible)
                            <div class="w-full px-4 py-3 bg-green-50 border-2 border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span class="text-sm text-green-800 font-bold">Guest Accessible</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Operating Hours Summary -->
                <div class="bg-white shadow-lg rounded-xl border overflow-hidden" style="border-color: rgba(51, 60, 77, 0.15);">
                    <div class="px-6 py-4" style="background: linear-gradient(135deg, #002366, #001A4A);">
                        <h3 class="text-lg leading-6 font-bold text-white">Operating Hours</h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center rounded-lg p-3 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <span class="font-semibold" style="color: #002366;">Opening:</span>
                                <span class="font-bold" style="color: #172030;">
                                    {{ $facility->opening_time ? \Carbon\Carbon::parse($facility->opening_time)->format('g:i A') : 'N/A' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center rounded-lg p-3 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <span class="font-semibold" style="color: #002366;">Closing:</span>
                                <span class="font-bold" style="color: #172030;">
                                    {{ $facility->closing_time ? \Carbon\Carbon::parse($facility->closing_time)->format('g:i A') : 'N/A' }}
                                </span>
                            </div>
                            @if($facility->buffer_time && $facility->buffer_time > 0)
                            <div class="flex justify-between items-center rounded-lg p-3 border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                                <span class="font-semibold" style>Buffer Time:</span>
                                <span class="font-bold text-gray-900">{{ $facility->buffer_time }} minutes</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection