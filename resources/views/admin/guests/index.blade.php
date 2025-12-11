@extends('layouts.admin')

@section('title', 'Guest Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
   
        <div class="flex gap-3">    
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Manage Users
            </a>
        </div>
    </div>

    

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    @php
        $totalGuests = \App\Models\User::where('role', 'guest')->count();
        $pendingGuests = \App\Models\User::where('role', 'guest')->where('status', 'pending')->count();
        $approvedGuests = \App\Models\User::where('role', 'guest')->where('status', 'approved')->count();
        $rejectedGuests = \App\Models\User::where('role', 'guest')->where('status', 'rejected')->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#002366] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Total Guests</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">{{ $totalGuests }}</p>
                    <p class="text-xs text-[#333C4D] mt-1">All guest accounts</p>
                </div>
                <div class="bg-[#002366] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#F59E0B] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Pending</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">{{ $pendingGuests }}</p>
                    <p class="text-xs text-[#333C4D] mt-1">Awaiting approval</p>
                </div>
                <div class="bg-[#F59E0B] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#10B981] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Approved</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">{{ $approvedGuests }}</p>
                    <p class="text-xs text-[#333C4D] mt-1">Active guests</p>
                </div>
                <div class="bg-[#10B981] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-[#EF4444] hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#333C4D] uppercase">Rejected</p>
                    <p class="text-3xl font-bold text-[#172030] mt-2">{{ $rejectedGuests }}</p>
                    <p class="text-xs text-[#333C4D] mt-1">Declined requests</p>
                </div>
                <div class="bg-[#EF4444] bg-opacity-10 rounded-lg p-3">
                    <svg class="w-8 h-8 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-lg mb-6 p-6">
        <form method="GET" action="{{ route('admin.guests.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#333C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name or email..."
                           class="w-full pl-10 pr-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent">
                </div>
            </div>
            <select name="status" class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] bg-white text-[#172030]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] flex items-center font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.guests.index') }}" 
                   class="px-6 py-2 bg-[#333C4D] bg-opacity-10 text-[#172030] rounded-lg hover:bg-opacity-20 flex items-center font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                   Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Guests Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($guests->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-[#333C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-[#172030]">No guests found</h3>
                <p class="mt-2 text-sm text-[#333C4D]">Try adjusting your search or filter criteria.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#333C4D] divide-opacity-10">
                    <thead class="bg-[#F8F9FA]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Guest</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Purpose</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Registered</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-[#333C4D] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#333C4D] divide-opacity-10">
                        @foreach($guests as $guest)
                            <tr class="hover:bg-[#F8F9FA] transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-11 w-11">
                                            <div class="h-11 w-11 rounded-full bg-gradient-to-br from-[#002366] to-[#00285C] flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                {{ strtoupper(substr($guest->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-[#172030]">{{ $guest->name }}</div>
                                            <div class="text-xs text-[#333C4D]">ID: {{ $guest->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-[#172030]">{{ $guest->email }}</div>
                                    @if($guest->contact_number)
                                        <div class="text-xs text-[#333C4D]">{{ $guest->contact_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-[#172030]">{{ Str::limit($guest->purpose ?? 'Not specified', 50) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-[#172030]">{{ $guest->organization ?? 'Not specified' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full shadow-sm
                                        {{ $guest->status === 'approved' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981] border border-[#10B981] border-opacity-20' : 
                                           ($guest->status === 'pending' ? 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B] border border-[#F59E0B] border-opacity-20' : 
                                           'bg-[#EF4444] bg-opacity-10 text-[#EF4444] border border-[#EF4444] border-opacity-20') }}">
                                        {{ ucfirst($guest->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-[#172030]">{{ $guest->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-[#333C4D]">{{ $guest->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.guests.show', $guest) }}" 
   class="text-[#002366] hover:text-[#001A4A] font-medium">View</a>
{{-- Temporarily disabled until route is added
<a href="{{ route('admin.guests.edit', $guest) }}" 
   class="text-[#002366] hover:text-[#001A4A] font-medium">Edit</a>
--}}
                                        @if($guest->status === 'pending')
                                            <form method="POST" action="{{ route('admin.guests.approve', $guest) }}" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to approve this guest?');">
                                                @csrf
                                                <button type="submit" class="text-[#10B981] hover:text-[#059669] font-medium">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.guests.reject', $guest) }}" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to reject this guest?');">
                                                @csrf
                                                <button type="submit" class="text-[#EF4444] hover:text-[#DC2626] font-medium">Reject</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.guests.destroy', $guest) }}" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this guest?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[#EF4444] hover:text-[#DC2626] font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $guests->links() }}
    </div>
</div>
@endsection
