{{-- resources/views/reservations/partials/table.blade.php --}}
@php
    $routePrefix = auth()->user()->role;
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-[#333C4D] divide-opacity-10">
        <thead class="bg-[#F8F9FA]">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Facility</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">User</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Schedule</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-[#333C4D] uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-[#FFFFFF] divide-y divide-[#333C4D] divide-opacity-10">
            @forelse($reservations as $reservation)
                <tr class="hover:bg-[#F8F9FA] transition">
                    {{-- Facility Column --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-[#E5E7EB] rounded-lg flex items-center justify-center text-[#333C4D]">
                                {{-- Placeholder Icon for Facility --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-[#172030]">{{ $reservation->facility->name }}</div>
                                <div class="text-xs text-[#333C4D] opacity-75">{{ $reservation->event_name }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- User Column --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-[#172030]">{{ $reservation->user->name }}</div>
                        <div class="text-xs text-[#333C4D] opacity-75">{{ $reservation->user->email ?? 'No email' }}</div>
                    </td>

                    {{-- Schedule Column --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-[#172030]">{{ $reservation->start_time->format('M d, Y') }}</div>
                        <div class="text-xs text-[#333C4D] opacity-75">
                            {{ $reservation->start_time->format('h:i A') }} - {{ $reservation->end_time->format('h:i A') }}
                        </div>
                    </td>

                    {{-- Status Column --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full shadow-sm border
                            @if($reservation->status === 'approved') 
                                bg-[#10B981] bg-opacity-10 text-[#10B981] border-[#10B981] border-opacity-20
                            @elseif($reservation->status === 'pending') 
                                bg-[#F59E0B] bg-opacity-10 text-[#F59E0B] border-[#F59E0B] border-opacity-20
                            @elseif($reservation->status === 'rejected') 
                                bg-[#EF4444] bg-opacity-10 text-[#EF4444] border-[#EF4444] border-opacity-20
                            @elseif($reservation->status === 'cancelled') 
                                bg-[#333C4D] bg-opacity-10 text-[#333C4D] border-[#333C4D] border-opacity-20
                            @else 
                                bg-blue-100 text-blue-800 border-blue-200
                            @endif">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </td>

                    {{-- Actions Column --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route($routePrefix . '.reservations.show', $reservation->id) }}" 
                               class="text-[#002366] hover:text-[#001A4A] font-medium transition">View</a>
                            
                            @if(auth()->user()->role === 'admin')
                                @if($reservation->status === 'pending')
                                    <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[#10B981] hover:text-[#059669] font-medium transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.reservations.reject', $reservation->id) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to reject this reservation?');">
                                        @csrf
                                        <button type="submit" class="text-[#EF4444] hover:text-[#DC2626] font-medium transition">Reject</button>
                                    </form>
                                @endif
                            @else
                                @if($reservation->status === 'pending' && $reservation->user_id === auth()->id())
                                    <a href="{{ route($routePrefix . '.reservations.edit', $reservation->id) }}" 
                                       class="text-[#172030] hover:text-[#1D2636] font-medium transition">Edit</a>
                                    
                                    <form action="{{ route($routePrefix . '.reservations.destroy', $reservation->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[#EF4444] hover:text-[#DC2626] font-medium transition">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-[#333C4D] opacity-50">
                            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="text-sm font-medium">No reservations found.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>