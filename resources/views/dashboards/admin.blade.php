@extends('layouts.admin')

@section('content')

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate-slide-in" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-slide-in" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div class="space-y-6">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-[#002366] via-[#00285C] to-[#001A4A] rounded-2xl shadow-xl p-8 text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div class="max-w-2xl">
                <h2 class="text-3xl md:text-4xl font-bold mb-3">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-blue-100 text-lg">Here's your facility management overview for today.</p>
            </div>
            <div class="hidden lg:block">
                <svg class="w-40 h-40 text-blue-200 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <a href="{{ route('admin.facilities.index') }}" 
           class="group bg-white shadow-md rounded-xl p-6 border-l-4 border-[#002366] hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-[#333C4D] bg-opacity-10 rounded-lg p-3 group-hover:bg-[#333C4D] bg-opacity-20 transition-colors">
                    <svg class="w-8 h-8 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#002366] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <h4 class="text-sm font-semibold text-[#333C4D] mb-2 uppercase tracking-wide">Total Facilities</h4>
            <p class="text-4xl font-bold text-[#002366] mb-2">{{ $stats['total_facilities'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 group-hover:text-[#002366] transition-colors flex items-center gap-1">
                View all 
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </p>
        </a>

        <a href="{{ route('admin.reservations.index') }}" 
           class="group bg-white shadow-md rounded-xl p-6 border-l-4 border-gray-400 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gray-100 rounded-lg p-3 group-hover:bg-gray-200 transition-colors">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="bg-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $stats['total_reservations'] ?? 0 }}
                </div>
            </div>
            <h4 class="text-sm font-semibold text-[#333C4D] mb-2 uppercase tracking-wide">Total Bookings</h4>
            <p class="text-4xl font-bold text-[#333C4D] mb-2">{{ $stats['total_reservations'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 group-hover:text-[#333C4D] transition-colors">Click to manage</p>
        </a>

        <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" 
           class="group bg-white shadow-md rounded-xl p-6 border-l-4 border-yellow-500 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 relative">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-100 rounded-lg p-3 group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if(($stats['pending_reservations'] ?? 0) > 0)
                <div class="absolute -top-2 -right-2">
                    <span class="relative flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-yellow-500"></span>
                    </span>
                </div>
                @endif
            </div>
            <h4 class="text-sm font-semibold text-[#333C4D] mb-2 uppercase tracking-wide">Pending</h4>
            <p class="text-4xl font-bold text-yellow-600 mb-2">{{ $stats['pending_reservations'] ?? 0 }}</p>
            <p class="text-xs text-yellow-600 font-medium group-hover:text-yellow-700 transition-colors">⚠️ Needs approval</p>
        </a>

        <a href="{{ route('admin.reservations.index', ['status' => 'approved']) }}" 
           class="group bg-white shadow-md rounded-xl p-6 border-l-4 border-green-500 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 rounded-lg p-3 group-hover:bg-green-200 transition-colors">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-green-600 text-xl">✓</div>
            </div>
            <h4 class="text-sm font-semibold text-[#333C4D] mb-2 uppercase tracking-wide">Approved</h4>
            <p class="text-4xl font-bold text-green-600 mb-2">{{ $stats['approved_reservations'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 group-hover:text-green-600 transition-colors">Confirmed bookings</p>
        </a>

        <a href="{{ route('admin.reservations.index', ['status' => 'rejected']) }}" 
           class="group bg-white shadow-md rounded-xl p-6 border-l-4 border-red-500 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-200 transition-colors">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
            <h4 class="text-sm font-semibold text-[#333C4D] mb-2 uppercase tracking-wide">Rejected</h4>
            <p class="text-4xl font-bold text-red-600 mb-2">{{ $stats['rejected_reservations'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 group-hover:text-red-600 transition-colors">Declined requests</p>
        </a>
    </div>

    {{-- Quick Actions Bar --}}
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#002366]">
        <h3 class="text-lg font-bold text-[#172030] mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Quick Actions
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.facilities.create') }}" class="group flex items-center justify-center gap-3 bg-gradient-to-r from-[#002366] to-[#00285C] hover:from-[#00285C] hover:to-[#001A4A] text-white px-5 py-4 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">Add Facility</span>
            </a>
            <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" class="group flex items-center justify-center gap-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-5 py-4 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="font-semibold">Review Pending</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="group flex items-center justify-center gap-3 bg-gradient-to-r from-[#002366] to-[#00285C] hover:from-[#00285C] hover:to-[#001A4A] text-white px-5 py-4 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="font-semibold">Manage Users</span>
            </a>
            <a href="{{ route('admin.guests.index') }}" class="group flex items-center justify-center gap-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-5 py-4 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-semibold">Manage Guests</span>
            </a>
        </div>
    </div>

    {{-- Calendar & Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Calendar --}}
        <div class="lg:col-span-2 bg-white shadow-xl rounded-xl p-6 border-t-4 border-[#002366]">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
                <h2 class="text-2xl font-bold text-[#172030] flex items-center gap-2">
                    <svg class="w-7 h-7 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Reservation Calendar
                </h2>
                <div class="flex flex-wrap gap-3 text-sm">
                    <span class="flex items-center gap-2 px-3 py-2 bg-green-100 text-green-800 rounded-lg font-medium shadow-sm">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        Approved
                    </span>
                    <span class="flex items-center gap-2 px-3 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-medium shadow-sm">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        Pending
                    </span>
                    <span class="flex items-center gap-2 px-3 py-2 bg-red-100 text-red-800 rounded-lg font-medium shadow-sm">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        Rejected
                    </span>
                    <span class="flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-800 rounded-lg font-medium shadow-sm">
                        <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                        Cancelled
                    </span>
                </div>
            </div>
            <div id="adminCalendar" class="rounded-lg" style="min-height: 600px;"></div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#002366]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#172030] flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Recent Activity
                </h3>
                <a href="{{ route('admin.reservations.index') }}" class="text-sm text-[#002366] hover:underline flex items-center gap-1 font-medium">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
            
            <div class="space-y-3 max-h-[600px] overflow-y-auto custom-scrollbar">
                @forelse($recentReservations as $res)
                <a href="{{ route('admin.reservations.show', $res->id) }}" 
                   class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all group border border-transparent hover:border-[#333C4D]">
                    <div class="flex-shrink-0 mt-0.5">
                        @if($res->status === 'pending')
                            <div class="w-2.5 h-2.5 bg-yellow-500 rounded-full animate-pulse"></div>
                        @elseif($res->status === 'approved')
                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full"></div>
                        @elseif($res->status === 'rejected')
                            <div class="w-2.5 h-2.5 bg-red-500 rounded-full"></div>
                        @else
                            <div class="w-2.5 h-2.5 bg-gray-500 rounded-full"></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-[#333C4D] group-hover:text-[#002366] transition truncate">
                            {{ $res->event_name }}
                        </p>
                        <p class="text-xs text-[#333C4D] mt-0.5 opacity-60">
                            <span class="font-medium">{{ $res->facility?->name ?? 'Facility Deleted' }}</span> • {{ $res->user?->name ?? 'User Deleted' }}
                        </p>
                        <p class="text-xs text-[#333C4D] mt-1 opacity-40">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $res->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                            @if($res->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($res->status === 'approved') bg-green-100 text-green-800
                            @elseif($res->status === 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($res->status) }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="w-16 h-16 text-[#333C4D] opacity-20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-center text-[#333C4D] font-medium opacity-60">No recent activity</p>
                    <p class="text-center text-[#333C4D] text-sm mt-1 opacity-40">New reservations will appear here</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slide-in {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

@endsection 

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calendar
    const calendarEl = document.getElementById('adminCalendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                day: 'Day',
                list: 'List'
            },
            events: "{{ route('admin.dashboard.events') }}",
            eventDidMount: function(info) {
                const status = info.event.extendedProps.status;
                
                // Color coding based on status
                if (status === 'approved') {
                    info.el.style.backgroundColor = '#10B981';
                    info.el.style.borderColor = '#059669';
                } else if (status === 'pending') {
                    info.el.style.backgroundColor = '#F59E0B';
                    info.el.style.borderColor = '#D97706';
                } else if (status === 'rejected') {
                    info.el.style.backgroundColor = '#EF4444';
                    info.el.style.borderColor = '#DC2626';
                } else if (status === 'cancelled') {
                    info.el.style.backgroundColor = '#6B7280';
                    info.el.style.borderColor = '#4B5563';
                } else {
                    info.el.style.backgroundColor = '#3B82F6';
                    info.el.style.borderColor = '#2563EB';
                }
                
                info.el.style.color = '#fff';
                info.el.style.borderRadius = '6px';
                info.el.style.padding = '4px 8px';
                info.el.style.cursor = 'pointer';
                
                // Add tooltip
                const tooltip = `
                    <strong>${info.event.title}</strong><br>
                    <small>Facility: ${info.event.extendedProps.facility}</small><br>
                    <small>Reserved by: ${info.event.extendedProps.user}</small><br>
                    <small>Status: ${status}</small>
                `;
                info.el.setAttribute('title', tooltip.replace(/<[^>]*>/g, ' '));
            },
            eventContent: function(arg) {
                let wrapper = document.createElement('div');
                wrapper.style.overflow = 'hidden';
                wrapper.style.textOverflow = 'ellipsis';
                wrapper.style.whiteSpace = 'nowrap';
                
                let title = document.createElement('div');
                title.style.fontWeight = '600';
                title.style.fontSize = '0.875rem';
                title.textContent = arg.event.title;
                
                let facility = document.createElement('small');
                facility.style.fontSize = '0.75rem';
                facility.style.opacity = '0.9';
                facility.textContent = `📍 ${arg.event.extendedProps.facility}`;
                
                wrapper.appendChild(title);
                wrapper.appendChild(facility);
                
                return { domNodes: [wrapper] };
            },
            eventClick: function(info) {
                // Redirect to reservation details when clicking on event
                window.location.href = `/admin/reservations/${info.event.id}`;
            },
            eventMouseEnter: function(info) {
                info.el.style.transform = 'scale(1.05)';
                info.el.style.transition = 'transform 0.2s ease';
                info.el.style.zIndex = '1000';
            },
            eventMouseLeave: function(info) {
                info.el.style.transform = 'scale(1)';
            },
            // Loading state
            loading: function(isLoading) {
                if (isLoading) {
                    calendarEl.style.opacity = '0.5';
                } else {
                    calendarEl.style.opacity = '1';
                }
            },

            // Custom day cell styling
            dayCellDidMount: function(info) {
                // Highlight today
                if (info.isToday) {
                    info.el.style.backgroundColor = '#ecfdf5';
                }
            },
            // Weekend styling
            dayHeaderDidMount: function(info) {
                if (info.dow === 0 || info.dow === 6) { // Sunday or Saturday
                    info.el.style.color = '#ef4444';
                    info.el.style.fontWeight = '700';
                }
            }
        });
        
        calendar.render();
        
        // Make calendar responsive
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                calendar.changeView('listWeek');
            } else {
                calendar.changeView('dayGridMonth');
            }
        });
    }
});
</script>
@endpush