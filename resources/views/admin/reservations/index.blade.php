@extends('layouts.admin')

@section('content')
@php
    $routePrefix = auth()->user()->role;
    $currentStatus = request()->query('status');
@endphp

<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    

    {{-- Status Filter Tabs --}}
    <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex overflow-x-auto" aria-label="Tabs">
                {{-- All Tab --}}
                <a href="{{ route($routePrefix . '.reservations.index') }}" 
                   class="
                       {{ !$currentStatus ? 'border-primary-navy text-primary-navy bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                       whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200 flex items-center gap-2
                   ">
                    <span>📋 All Reservations</span>
                    <span class="
                        {{ !$currentStatus ? 'bg-primary-navy text-white' : 'bg-gray-200 text-gray-600' }}
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

    {{-- Success/Error Messages --}}
   

    {{-- Reservations Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @include('reservations.partials.table', ['reservations' => $reservations])
    </div>

    {{-- Pagination --}}
    @if($reservations->hasPages())
        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection