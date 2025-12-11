@extends('layouts.faculty')

@section('title', 'My Reservations')

@section('content')
@php
    $routePrefix = 'faculty';
    $currentStatus = request()->query('status');
@endphp

<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Reservations</h1>
            <p class="text-gray-600 mt-1">Manage and track your facility reservations</p>
        </div>
        <a href="{{ route('faculty.facilities.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-[#002147] hover:bg-[#001a39] text-white rounded-lg transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            New Reservation
        </a>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex overflow-x-auto" aria-label="Tabs">
                {{-- All Tab --}}
                <a href="{{ route($routePrefix . '.reservations.index') }}" 
                   class="
                       {{ !$currentStatus ? 'border-[#002147] text-[#002147] bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                       whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center gap-2
                   ">
                    <span>📋 All Reservations</span>
                    <span class="
                        {{ !$currentStatus ? 'bg-[#002147] text-white' : 'bg-gray-200 text-gray-600' }}
                        px-2.5 py-0.5 rounded-full text-xs font-semibold
                    ">
                        {{ $stats['total'] ?? 0 }}
                    </span>
                </a>

                {{-- Pending Tab --}}
                <a href="{{ route($routePrefix . '.reservations.index', ['status' => 'pending']) }}" 
                   class="
                       {{ $currentStatus === 'pending' ? 'border-yellow-500 text-yellow-700 bg-yellow-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                       whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center gap-2
                   ">
                    <span>⏳ Pending</span>
                    <span class="
                        {{ $currentStatus === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-600' }}
                        px-2.5 py-0.5 rounded-full text-xs font-semibold
                    ">
                        {{ $stats['pending'] ?? 0 }}
                    </span>
                </a>

                {{-- Approved Tab --}}
                <a href="{{ route($routePrefix . '.reservations.index', ['status' => 'approved']) }}" 
                   class="
                       {{ $currentStatus === 'approved' ? 'border-green-500 text-green-700 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                       whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center gap-2
                   ">
                    <span>✅ Approved</span>
                    <span class="
                        {{ $currentStatus === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}
                        px-2.5 py-0.5 rounded-full text-xs font-semibold
                    ">
                        {{ $stats['approved'] ?? 0 }}
                    </span>
                </a>

                {{-- Rejected Tab --}}
                <a href="{{ route($routePrefix . '.reservations.index', ['status' => 'rejected']) }}" 
                   class="
                       {{ $currentStatus === 'rejected' ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                       whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center gap-2
                   ">
                    <span>❌ Rejected</span>
                    <span class="
                        {{ $currentStatus === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-600' }}
                        px-2.5 py-0.5 rounded-full text-xs font-semibold
                    ">
                        {{ $stats['rejected'] ?? 0 }}
                    </span>
                </a>

                {{-- Cancelled Tab --}}
                <a href="{{ route($routePrefix . '.reservations.index', ['status' => 'cancelled']) }}" 
                   class="
                       {{ $currentStatus === 'cancelled' ? 'border-gray-500 text-gray-700 bg-gray-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                       whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center gap-2
                   ">
                    <span>🚫 Cancelled</span>
                    <span class="
                        {{ $currentStatus === 'cancelled' ? 'bg-gray-500 text-white' : 'bg-gray-200 text-gray-600' }}
                        px-2.5 py-0.5 rounded-full text-xs font-semibold
                    ">
                        {{ $stats['cancelled'] ?? 0 }}
                    </span>
                </a>
            </nav>
        </div>

        {{-- Active Filter Info --}}
        @if($currentStatus)
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Showing:</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium
                            @if($currentStatus === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($currentStatus === 'approved') bg-green-100 text-green-800
                            @elseif($currentStatus === 'rejected') bg-red-100 text-red-800
                            @elseif($currentStatus === 'cancelled') bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($currentStatus) }} reservations
                        </span>
                    </div>
                    <a href="{{ route($routePrefix . '.reservations.index') }}" 
                       class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">
                        Clear filter ✕
                    </a>
                </div>
            </div>
        @endif
    </div>

 

    {{-- Reservations Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($reservations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Facility
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservations as $reservation)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-lg bg-[#002147] flex items-center justify-center">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $reservation->facility->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $reservation->facility->location }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-medium">{{ $reservation->event_name }}</div>
                                    @if($reservation->purpose)
                                        <div class="text-sm text-gray-500">{{ Str::limit($reservation->purpose, 40) }}</div>
                                    @endif
                                    @if($reservation->is_recurring)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            {{ ucfirst($reservation->recurrence_type) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $reservation->start_time->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $reservation->start_time->format('g:i A') }} - {{ $reservation->end_time->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($reservation->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Approved
                                        </span>
                                    @elseif($reservation->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @elseif($reservation->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Rejected
                                        </span>
                                    @elseif($reservation->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('faculty.reservations.show', $reservation) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition">
                                            View
                                        </a>
                                        @if($reservation->status === 'pending' && $reservation->start_time->isFuture())
                                            <a href="{{ route('faculty.reservations.edit', $reservation) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-[#002147] hover:bg-[#001a39] transition">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No reservations found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($currentStatus)
                        No {{ $currentStatus }} reservations at the moment.
                    @else
                        Get started by creating a new reservation.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('faculty.facilities.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#002147] hover:bg-[#001a39] transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        New Reservation
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($reservations->hasPages())
        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection