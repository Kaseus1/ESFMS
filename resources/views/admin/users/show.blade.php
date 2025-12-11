@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" 
               class="p-2 bg-[#F8F9FA] rounded-lg hover:bg-[#333C4D] hover:bg-opacity-10 transition">
                <svg class="w-5 h-5 text-[#333C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-[#172030]">User Details</h1>
                <p class="text-[#333C4D] mt-2">View detailed information about {{ $user->name }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="px-4 py-2 bg-[#002366] text-[#FFFFFF] rounded-lg hover:bg-[#001A4A] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit User
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-[#002366] to-[#00285C] px-6 py-8 text-white">
                    <div class="flex items-center">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                            <p class="text-white text-opacity-80">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[#333C4D]">Status</span>
                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ $user->status === 'approved' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981]' : 
                                   ($user->status === 'pending' ? 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B]' : 
                                    'bg-[#EF4444] bg-opacity-10 text-[#EF4444]') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[#333C4D]">Role</span>
                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ $user->role === 'admin' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981]' : 
                                   ($user->role === 'faculty' ? 'bg-[#172030] bg-opacity-10 text-[#172030]' : 
                                    'bg-[#1D2636] bg-opacity-10 text-[#1D2636]') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[#333C4D]">Email</span>
                            <span class="text-sm text-[#172030]">{{ $user->email }}</span>
                        </div>

                        @if($user->contact_number)
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[#333C4D]">Contact</span>
                            <span class="text-sm text-[#172030]">{{ $user->contact_number }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[#333C4D]">Joined</span>
                            <span class="text-sm text-[#172030]">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>

                        @if($user->email_verified_at)
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[#333C4D]">Email Verified</span>
                            <span class="text-sm text-[#10B981] flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Verified
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-2">
                        @if($user->status === 'pending')
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-[#10B981] text-[#FFFFFF] rounded-lg hover:bg-[#059669] text-sm font-medium transition">
                                Approve User
                            </button>
                        </form>
                        @endif

                        @if($user->status !== 'approved')
                        <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline" 
                              onsubmit="return confirm('Are you sure you want to reject this user?');">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-[#EF4444] text-[#FFFFFF] rounded-lg hover:bg-[#DC2626] text-sm font-medium transition">
                                Reject User
                            </button>
                        </form>
                        @endif

                        @if($user->id !== auth()->id() && $user->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.resetPassword', $user) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-[#F59E0B] text-[#FFFFFF] rounded-lg hover:bg-[#D97706] text-sm font-medium transition">
                                Reset Password
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Information -->
            <div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-[#F8F9FA] border-b border-[#333C4D] border-opacity-10">
                    <h3 class="text-lg font-semibold text-[#172030]">General Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">User ID</label>
                            <p class="text-sm text-[#172030]">{{ $user->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Full Name</label>
                            <p class="text-sm text-[#172030]">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Email Address</label>
                            <p class="text-sm text-[#172030]">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Contact Number</label>
                            <p class="text-sm text-[#172030]">{{ $user->contact_number ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Role</label>
                            <p class="text-sm text-[#172030] capitalize">{{ $user->role }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Status</label>
                            <p class="text-sm text-[#172030] capitalize">{{ $user->status }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Joined Date</label>
                            <p class="text-sm text-[#172030]">{{ $user->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Last Updated</label>
                            <p class="text-sm text-[#172030]">{{ $user->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Information -->
            @if($user->approved_at || $user->rejection_reason)
            <div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-[#F8F9FA] border-b border-[#333C4D] border-opacity-10">
                    <h3 class="text-lg font-semibold text-[#172030]">Approval Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($user->approved_at)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Approved At</label>
                            <p class="text-sm text-[#172030]">{{ $user->approved_at->format('M d, Y g:i A') }}</p>
                        </div>
                        @endif

                        @if($user->approvedBy)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Approved By</label>
                            <p class="text-sm text-[#172030]">{{ $user->approvedBy->name }}</p>
                        </div>
                        @endif

                        @if($user->rejection_reason)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Rejection Reason</label>
                            <p class="text-sm text-[#172030]">{{ $user->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Reservations -->
            @if($user->reservations && $user->reservations->count() > 0)
            <div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-[#F8F9FA] border-b border-[#333C4D] border-opacity-10">
                    <h3 class="text-lg font-semibold text-[#172030]">Recent Reservations</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($user->reservations as $reservation)
                        <div class="flex items-center justify-between p-4 border border-[#333C4D] border-opacity-10 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-[#172030]">{{ $reservation->facility->name ?? 'Unknown Facility' }}</p>
                                <p class="text-xs text-[#333C4D] opacity-75">{{ $reservation->start_time->format('M d, Y g:i A') }} - {{ $reservation->end_time->format('g:i A') }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ $reservation->status === 'approved' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981]' : 
                                   ($reservation->status === 'pending' ? 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B]' : 
                                    'bg-[#EF4444] bg-opacity-10 text-[#EF4444]') }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection