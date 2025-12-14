@extends('layouts.admin')
@section('title', 'Reservations Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#172030]">Reservations</h1>
            <p class="text-[#333C4D] mt-2 opacity-75">Manage facility bookings and requests</p>
        </div>
       
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#10B981] bg-opacity-10 border border-[#10B981] border-opacity-20 rounded-lg text-[#10B981] flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-[#EF4444] bg-opacity-10 border border-[#EF4444] border-opacity-20 rounded-lg text-[#EF4444] flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#002366] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Total Bookings</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">All time records</p>
                </div>
                <div class="bg-[#002366] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#F59E0B] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Pending</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">Awaiting approval</p>
                </div>
                <div class="bg-[#F59E0B] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#10B981] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Approved</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">{{ $stats['approved'] ?? 0 }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">Confirmed events</p>
                </div>
                <div class="bg-[#10B981] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#FFFFFF] rounded-lg shadow-lg p-6 border-l-4 border-[#1D2636] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Total Revenue</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">₱{{ number_format($stats['total_revenue'] ?? 0) }}</p>
                    <p class="text-xs text-[#333C4D] mt-1 opacity-75">Generated income</p>
                </div>
                <div class="bg-[#1D2636] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#1D2636]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#FFFFFF] rounded-lg shadow-lg mb-6 p-6">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by event, user name, or email..."
                           class="w-full pl-10 pr-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent">
                </div>
            </div>

            <select name="facility_id" class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] bg-[#FFFFFF] min-w-[180px]">
                <option value="">All Facilities</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] bg-[#FFFFFF] min-w-[150px]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit" class="px-6 py-2 bg-[#002366] text-[#FFFFFF] rounded-lg hover:bg-[#001A4A] flex items-center font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filter
            </button>

            @if(request()->anyFilled(['search', 'status', 'facility_id']))
                <a href="{{ route('admin.reservations.index') }}" 
                   class="px-6 py-2 bg-[#333C4D] bg-opacity-20 text-[#333C4D] rounded-lg hover:bg-[#333C4D] hover:bg-opacity-30 flex items-center font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
        @if($reservations->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-[#172030]">No reservations found</h3>
                <p class="mt-2 text-sm text-[#333C4D] opacity-75">Try adjusting your search or filter criteria.</p>
            </div>
        @else
            @include('admin.reservations.partials.table', ['reservations' => $reservations])
        @endif
    </div>

    @if($reservations->hasPages())
        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection