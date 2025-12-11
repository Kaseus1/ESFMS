@extends('layouts.faculty')

@section('title', 'Reservation Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('faculty.reservations.index') }}" class="hover:text-[#002147] transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                My Reservations
            </a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-gray-900 font-medium">Reservation #{{ $reservation->id }}</span>
        </nav>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-r-lg p-4 shadow-sm animate-slide-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-r-lg p-4 shadow-sm animate-slide-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Header Card with Gradient -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-200">
            <!-- Hero Section -->
            <div class="relative bg-gradient-to-r from-[#002147] via-[#003366] to-[#004080] px-8 py-10">
                <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
                <div class="relative">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm">
                                    Reservation ID: #{{ $reservation->id }}
                                </span>
                            </div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                                {{ $reservation->event_name ?? 'Facility Reservation' }}
                            </h1>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white/95">
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-lg px-4 py-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-white/70 font-medium">Date</p>
                                        <p class="text-sm font-semibold">{{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-lg px-4 py-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-white/70 font-medium">Time Slot</p>
                                        <p class="text-sm font-semibold">{{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-lg px-4 py-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-white/70 font-medium">Facility</p>
                                        <p class="text-sm font-semibold">{{ $reservation->facility->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex-shrink-0">
                            @if($reservation->status === 'approved')
                                <div class="inline-flex items-center px-5 py-3 rounded-xl text-base font-bold bg-green-500 text-white shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Approved
                                </div>
                            @elseif($reservation->status === 'pending')
                                <div class="inline-flex items-center px-5 py-3 rounded-xl text-base font-bold bg-yellow-400 text-yellow-900 shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Pending Review
                                </div>
                            @elseif($reservation->status === 'rejected')
                                <div class="inline-flex items-center px-5 py-3 rounded-xl text-base font-bold bg-red-500 text-white shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Rejected
                                </div>
                            @elseif($reservation->status === 'cancelled')
                                <div class="inline-flex items-center px-5 py-3 rounded-xl text-base font-bold bg-gray-500 text-white shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Cancelled
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('faculty.reservations.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to List
                    </a>

                    @if($reservation->status === 'pending' || $reservation->status === 'approved')
                        <a href="{{ route('faculty.reservations.edit', $reservation->id) }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-[#002147] text-white rounded-lg text-sm font-medium hover:bg-[#001a39] transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Details
                        </a>
                    
                        <button type="button" 
                                onclick="document.getElementById('cancelModal').classList.remove('hidden')"
                                class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-red-300 rounded-lg text-sm font-medium text-red-700 hover:bg-red-50 hover:border-red-400 transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Reservation
                        </button>
                    @endif

                    @if($reservation->status === 'cancelled' && $reservation->canBeDeleted())
                        <form method="POST" action="{{ route('faculty.reservations.destroy', $reservation->id) }}" 
                              onsubmit="return confirm('Are you sure you want to permanently delete this reservation?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-all shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Permanently
                            </button>
                        </form>
                    @elseif($reservation->status === 'cancelled' && !$reservation->canBeDeleted())
                        <div class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-500 rounded-lg text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Can be deleted {{ $reservation->cancelled_at->addDays(7)->diffForHumans() }}
                        </div>
                    @endif

                    <a href="{{ route('faculty.facilities.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm ml-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        New Reservation
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-[#002147] rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            Event Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Event Name</dt>
                                <dd class="text-base text-gray-900 font-semibold">{{ $reservation->event_name ?? 'N/A' }}</dd>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Duration</dt>
                                <dd class="text-base text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($reservation->start_time)->diffForHumans(\Carbon\Carbon::parse($reservation->end_time), true) }}</dd>
                            </div>
                            
                            @if($reservation->participants)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Expected Participants</dt>
                                <dd class="text-base text-gray-900 font-semibold">
                                    <span class="inline-flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $reservation->participants }} people
                                    </span>
                                </dd>
                            </div>
                            @endif

                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Reserved By</dt>
                                <dd class="text-base text-gray-900 font-semibold">{{ $reservation->user->name }}</dd>
                                <dd class="text-sm text-gray-500 mt-1">{{ $reservation->user->email }}</dd>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Start Date & Time</dt>
                                <dd class="text-base text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($reservation->start_time)->format('F d, Y \a\t g:i A') }}</dd>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">End Date & Time</dt>
                                <dd class="text-base text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($reservation->end_time)->format('F d, Y \a\t g:i A') }}</dd>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Submitted On</dt>
                                <dd class="text-base text-gray-900 font-semibold">{{ $reservation->created_at->format('F d, Y') }}</dd>
                                <dd class="text-sm text-gray-500 mt-1">{{ $reservation->created_at->format('g:i A') }}</dd>
                            </div>
                            
                            @if($reservation->updated_at->ne($reservation->created_at))
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Last Updated</dt>
                                <dd class="text-base text-gray-900 font-semibold">{{ $reservation->updated_at->format('F d, Y') }}</dd>
                                <dd class="text-sm text-gray-500 mt-1">{{ $reservation->updated_at->format('g:i A') }}</dd>
                            </div>
                            @endif
                        </dl>
                        
                        @if($reservation->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Additional Notes</dt>
                            <dd class="text-sm text-gray-700 leading-relaxed bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">{{ $reservation->notes }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Cancellation Info -->
                @if($reservation->status === 'cancelled' && $reservation->cancellation_reason)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            Cancellation Details
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
                            <p class="text-sm font-bold text-red-800 mb-2">Reason:</p>
                            <p class="text-gray-700 mb-3">{{ $reservation->cancellation_reason }}</p>
                            @if($reservation->cancelled_at)
                                <p class="text-xs text-gray-500">
                                    Cancelled {{ $reservation->cancelled_at->diffForHumans() }} 
                                    ({{ $reservation->cancelled_at->format('M d, Y g:i A') }})
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Facility Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow sticky top-6">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-[#002147] rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            Facility Details
                        </h3>
                    </div>
                    @if($reservation->facility)
                    <div class="p-6">
                        <div class="mb-5">
                            <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $reservation->facility->name }}</h4>
                            @if($reservation->facility->type)
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">{{ $reservation->facility->type }}</p>
                            @endif
                        </div>
                        
                        @if($reservation->facility->description)
                        <p class="text-sm text-gray-700 mb-5 leading-relaxed">{{ Str::limit($reservation->facility->description, 120) }}</p>
                        @endif
                        
                        <div class="space-y-3 mb-5">
                            @if($reservation->facility->capacity)
                            <div class="flex items-center text-sm text-gray-700 bg-gray-50 rounded-lg p-3">
                                <svg class="w-5 h-5 mr-3 text-[#002147]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-medium">Capacity: {{ $reservation->facility->capacity }} people</span>
                            </div>
                            @endif
                            
                            @if($reservation->facility->location)
                            <div class="flex items-center text-sm text-gray-700 bg-gray-50 rounded-lg p-3">
                                <svg class="w-5 h-5 mr-3 text-[#002147]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="font-medium">{{ $reservation->facility->location }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('faculty.facilities.show', $reservation->facility) }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-[#002147] text-white rounded-lg text-sm font-semibold hover:bg-[#001a39] transition-all shadow-sm">
                            View Full Details
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @else
                    <div class="p-6">
                        <p class="text-sm text-gray-500 text-center py-4">Facility information not available.</p>
                    </div>
                    @endif
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-gradient-to-br from-[#002147] via-[#003366] to-[#004080] rounded-xl shadow-lg overflow-hidden text-white">
                    <div class="px-6 py-4 border-b border-white/10">
                        <h3 class="text-lg font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Quick Overview
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-white/80 font-medium">Time Until Event</span>
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold">
                                @if(\Carbon\Carbon::parse($reservation->start_time)->isFuture())
                                    {{ now()->diffInDays(\Carbon\Carbon::parse($reservation->start_time)) }} days
                                @else
                                    <span class="text-lg">Completed</span>
                                @endif
                            </p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-white/80 font-medium">Booking Status</span>
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold
                                @if($reservation->status === 'approved') bg-green-500 text-white
                                @elseif($reservation->status === 'pending') bg-yellow-400 text-yellow-900
                                @elseif($reservation->status === 'rejected') bg-red-500 text-white
                                @else bg-gray-500 text-white @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>

                        @if($reservation->participants)
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-white/80 font-medium">Total Participants</span>
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold">{{ $reservation->participants }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-[#002147] rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            Activity Timeline
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="space-y-4">
                                <!-- Created -->
                                <li class="relative">
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <span class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg ring-4 ring-blue-100">
                                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                            @if($reservation->updated_at->ne($reservation->created_at))
                                            <span class="absolute top-full left-1/2 -ml-px h-10 w-0.5 bg-gray-300" aria-hidden="true"></span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                                                <p class="text-sm font-bold text-gray-900">Reservation Created</p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $reservation->created_at->format('M d, Y \a\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                <!-- Updated -->
                                @if($reservation->updated_at->ne($reservation->created_at))
                                <li class="relative">
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <span class="h-12 w-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg ring-4 ring-yellow-100">
                                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                            </span>
                                            <span class="absolute top-full left-1/2 -ml-px h-10 w-0.5 bg-gray-300" aria-hidden="true"></span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                                                <p class="text-sm font-bold text-gray-900">Details Updated</p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $reservation->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                
                                <!-- Current Status -->
                                <li class="relative">
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <span class="h-12 w-12 rounded-xl flex items-center justify-center shadow-lg
                                                @if($reservation->status === 'approved') bg-gradient-to-br from-green-500 to-green-600 ring-4 ring-green-100
                                                @elseif($reservation->status === 'pending') bg-gradient-to-br from-yellow-500 to-yellow-600 ring-4 ring-yellow-100
                                                @elseif($reservation->status === 'rejected') bg-gradient-to-br from-red-500 to-red-600 ring-4 ring-red-100
                                                @elseif($reservation->status === 'cancelled') bg-gradient-to-br from-gray-500 to-gray-600 ring-4 ring-gray-100
                                                @else bg-gradient-to-br from-blue-500 to-blue-600 ring-4 ring-blue-100 @endif">
                                                @if($reservation->status === 'approved')
                                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                @elseif($reservation->status === 'pending')
                                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                @elseif($reservation->status === 'rejected')
                                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                @else
                                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                                </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="rounded-lg p-3 border-2
                                                @if($reservation->status === 'approved') bg-green-50 border-green-300
                                                @elseif($reservation->status === 'pending') bg-yellow-50 border-yellow-300
                                                @elseif($reservation->status === 'rejected') bg-red-50 border-red-300
                                                @elseif($reservation->status === 'cancelled') bg-gray-50 border-gray-300
                                                @else bg-blue-50 border-blue-300 @endif">
                                                <p class="text-sm font-bold text-gray-900">Status: {{ ucfirst($reservation->status) }}</p>
                                                <p class="text-xs text-gray-600 mt-1">Current booking status</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
@if($reservation->status === 'pending' || $reservation->status === 'approved')
<div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl" onclick="event.stopPropagation()">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Cancel Reservation
        </h3>
        <p class="text-sm text-gray-600 mb-4">
            Are you sure you want to cancel this reservation? This action cannot be undone.
        </p>
        <form method="POST" action="{{ route('faculty.reservations.cancel', $reservation->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for cancellation (optional)
                </label>
                <textarea name="cancellation_reason" 
                          rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Let us know why you're cancelling..."></textarea>
                <p class="mt-1 text-xs text-gray-500">This helps us improve our service</p>
            </div>
            <div class="flex gap-3">
                <button type="button" 
                        onclick="document.getElementById('cancelModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium">
                    Keep Reservation
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                    Confirm Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-down {
    animation: slide-down 0.3s ease-out;
}

.bg-grid-pattern {
    background-image: 
        linear-gradient(to right, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

@media (max-width: 640px) {
    .sticky {
        position: relative !important;
        top: auto !important;
    }
}
</style>
@endsection