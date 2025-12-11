{{-- resources/views/student/reservations/partials/table.blade.php --}}

<div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Facility</th>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Start Time</th>
                    <th class="px-4 py-2 text-left">End Time</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                    @if($reservation->facility && $reservation->user)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $reservation->facility->name }}</td>
                            <td class="px-4 py-2">{{ $reservation->user->name }}</td>
                            <td class="px-4 py-2">{{ $reservation->start_time->format('M d, Y h:i A') }}</td>
                            <td class="px-4 py-2">{{ $reservation->end_time->format('M d, Y h:i A') }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-white text-xs font-medium
                                    @if($reservation->status === 'approved') bg-green-500
                                    @elseif($reservation->status === 'pending') bg-yellow-500
                                    @elseif($reservation->status === 'rejected') bg-red-500
                                    @elseif($reservation->status === 'cancelled') bg-gray-500
                                    @else bg-blue-500
                                    @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('student.reservations.show', $reservation->id) }}" 
                                   class="text-blue-600 hover:underline">View</a>
                                
                                @if($reservation->status === 'pending' && $reservation->user_id === auth()->id())
                                    <a href="{{ route('student.reservations.edit', $reservation->id) }}" 
                                       class="text-yellow-600 hover:underline ml-2">Edit</a>
                                    
                                    {{-- FIXED: Cancel Button - Uses PATCH method and cancel route --}}
                                    <form action="{{ route('student.reservations.cancel', $reservation->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-orange-600 hover:underline ml-2" 
                                                onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                            Cancel
                                        </button>
                                    </form>
                                @endif

                                {{-- Delete Button - Only for cancelled reservations after 7 days --}}
                                @if($reservation->status === 'cancelled' && $reservation->canBeDeleted())
                                    <form action="{{ route('student.reservations.destroy', $reservation->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2" 
                                                onclick="return confirm('Are you sure you want to permanently delete this reservation?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                            No reservations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Only show pagination if $reservations is a Paginator --}}
    @if($reservations instanceof \Illuminate\Pagination\Paginator || $reservations instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4">
            {{ $reservations->links() }}
        </div>
    @endif
</div>