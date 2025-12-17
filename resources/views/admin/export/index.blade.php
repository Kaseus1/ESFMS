@extends('layouts.admin')

@section('title', 'Export Data')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-[#172030]">Export Data</h1>
        <p class="text-[#333C4D] mt-2 opacity-75">Download system records in CSV format for reporting and analysis.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#002366] hover:shadow-xl transition">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-[#172030]">Users</h3>
                    <p class="text-sm text-[#333C4D] opacity-70 mt-1">Export all registered users including admins, faculty, and students.</p>
                </div>
                <div class="p-3 bg-[#002366] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.export.users') }}" class="inline-flex items-center px-4 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] transition shadow-md w-full justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download CSV
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#F59E0B] hover:shadow-xl transition">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-[#172030]">Reservations</h3>
                    <p class="text-sm text-[#333C4D] opacity-70 mt-1">Export full reservation history including pending and cancelled bookings.</p>
                </div>
                <div class="p-3 bg-[#F59E0B] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.export.reservations') }}" class="inline-flex items-center px-4 py-2 bg-[#F59E0B] text-white rounded-lg hover:bg-yellow-600 transition shadow-md w-full justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download CSV
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#10B981] hover:shadow-xl transition">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-[#172030]">Facilities</h3>
                    <p class="text-sm text-[#333C4D] opacity-70 mt-1">Export list of all facilities, capacities, pricing, and status.</p>
                </div>
                <div class="p-3 bg-[#10B981] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.export.facilities') }}" class="inline-flex items-center px-4 py-2 bg-[#10B981] text-white rounded-lg hover:bg-green-600 transition shadow-md w-full justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download CSV
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#EF4444] hover:shadow-xl transition">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-[#172030]">Guest Requests</h3>
                    <p class="text-sm text-[#333C4D] opacity-70 mt-1">Export external guest registration requests and their approval status.</p>
                </div>
                <div class="p-3 bg-[#EF4444] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.export.guests') }}" class="inline-flex items-center px-4 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-red-600 transition shadow-md w-full justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download CSV
            </a>
        </div>
    </div>
</div>
@endsection