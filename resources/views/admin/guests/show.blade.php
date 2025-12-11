@extends('layouts.admin')

@section('title', 'Guest Details - ' . $guest->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.guests.index') }}" 
               class="px-4 py-2 bg-[#333C4D] bg-opacity-10 text-[#172030] rounded-lg hover:bg-opacity-20 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Guests
            </a>
            <div>
                <h1 class="text-3xl font-bold text-[#172030]">Guest Details</h1>
                <p class="text-[#333C4D] mt-1">{{ $guest->name }}</p>
            </div>
        </div>
        
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
        <!-- Guest Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-[#172030]">Personal Information</h2>
                    <div class="flex space-x-2">
                        @if($guest->status === 'pending')
                            <form method="POST" action="{{ route('admin.guests.approve', $guest) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-[#10B981] text-white rounded-lg hover:bg-[#059669] flex items-center"
                                        onclick="return confirm('Approve this guest?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.guests.reject', $guest) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-[#DC2626] flex items-center"
                                        onclick="return confirm('Reject this guest?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.guests.destroy', $guest) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-[#EF4444] text-white rounded-lg hover:bg-[#DC2626] flex items-center"
                                    onclick="return confirm('Delete this guest? This action cannot be undone.')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Full Name</label>
                        <p class="text-lg text-[#172030] font-semibold">{{ $guest->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Email Address</label>
                        <p class="text-lg text-[#172030]">{{ $guest->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Contact Number</label>
                        <p class="text-lg text-[#172030]">{{ $guest->contact_number ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Organization</label>
                        <p class="text-lg text-[#172030]">{{ $guest->organization ?? 'Not specified' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Purpose of Visit</label>
                        <p class="text-lg text-[#172030]">{{ $guest->purpose ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Reservation History -->
            @if($guest->reservations && $guest->reservations->count() > 0)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#172030] mb-4">Recent Reservations</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#333C4D] divide-opacity-10">
                            <thead class="bg-[#F8F9FA]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Facility</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-[#333C4D] divide-opacity-10">
                                @foreach($guest->reservations as $reservation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#172030]">
                                            {{ $reservation->facility->name ?? 'Unknown Facility' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#172030]">
                                            {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#172030]">
                                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $reservation->status === 'approved' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981]' : 
                                                   ($reservation->status === 'pending' ? 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B]' : 
                                                   ($reservation->status === 'cancelled' ? 'bg-[#EF4444] bg-opacity-10 text-[#EF4444]' : 'bg-[#333C4D] bg-opacity-10 text-[#333C4D]')) }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-[#333C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10h8V11h-2m-6 0H6a2 2 0 00-2 2v1a2 2 0 002 2h12a2 2 0 002-2v-1a2 2 0 00-2-2H12V7"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-[#172030]">No Reservations</h3>
                    <p class="mt-2 text-sm text-[#333C4D]">This guest hasn't made any facility reservations yet.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#172030] mb-4">Account Status</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Status</label>
                        <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full shadow-sm
                            {{ $guest->status === 'approved' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981] border border-[#10B981] border-opacity-20' : 
                               ($guest->status === 'pending' ? 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B] border border-[#F59E0B] border-opacity-20' : 
                               'bg-[#EF4444] bg-opacity-10 text-[#EF4444] border border-[#EF4444] border-opacity-20') }}">
                            {{ ucfirst($guest->status) }}
                        </span>
                    </div>
                    
                    @if($guest->status === 'approved' && $guest->approved_at)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Approved At</label>
                            <p class="text-sm text-[#172030]">{{ $guest->approved_at->format('M d, Y - H:i') }}</p>
                        </div>
                    @endif

                    @if($guest->status === 'rejected' && $guest->rejection_reason)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Rejection Reason</label>
                            <p class="text-sm text-[#172030]">{{ $guest->rejection_reason }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Last Updated</label>
                        <p class="text-sm text-[#172030]">{{ $guest->updated_at->format('M d, Y - H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#172030] mb-4">Reservation Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#333C4D]">Total Reservations</span>
                        <span class="text-lg font-bold text-[#172030]">{{ $guest->reservations ? $guest->reservations->count() : 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#333C4D]">Pending</span>
                        <span class="text-lg font-bold text-[#F59E0B]">{{ $guest->reservations ? $guest->reservations->where('status', 'pending')->count() : 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#333C4D]">Approved</span>
                        <span class="text-lg font-bold text-[#10B981]">{{ $guest->reservations ? $guest->reservations->where('status', 'approved')->count() : 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#333C4D]">Completed</span>
                        <span class="text-lg font-bold text-[#002366]">{{ $guest->reservations ? $guest->reservations->where('status', 'completed')->count() : 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#333C4D]">Cancelled</span>
                        <span class="text-lg font-bold text-[#EF4444]">{{ $guest->reservations ? $guest->reservations->where('status', 'cancelled')->count() : 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Info Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#172030] mb-4">Account Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Account ID</label>
                        <p class="text-sm text-[#172030]">{{ $guest->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Role</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#002366] bg-opacity-10 text-[#002366]">
                            {{ ucfirst($guest->role) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Registered On</label>
                        <p class="text-sm text-[#172030]">{{ $guest->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-[#333C4D]">{{ $guest->created_at->diffForHumans() }}</p>
                    </div>
                    @if($guest->email_verified_at)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Email Verified</label>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-[#10B981] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-[#10B981]">Yes</span>
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Email Verified</label>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-[#EF4444] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-[#EF4444]">No</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
