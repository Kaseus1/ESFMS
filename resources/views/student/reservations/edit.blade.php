@extends('layouts.student')

@section('title', 'Edit Reservation')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Reservation</h1>
                    <p class="mt-1 text-sm text-gray-500">Update your reservation details</p>
                </div>
                <a href="{{ route('student.reservations.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Reservations
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg">
            <!-- Facility Info Card -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    @if($facility->image_url)
                        <img src="{{ $facility->image_url }}" alt="{{ $facility->name }}" class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $facility->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $facility->location }} • {{ $facility->getTypeLabel() }}</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('student.reservations.update', $reservation) }}" method="POST" class="p-6" x-data="reservationForm()" x-init="init()">
                @csrf
                @method('PUT')
                
                <!-- Event Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Event Details</h3>
                    
                    <!-- Event Name -->
                    <div class="mb-6">
                        <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="event_name" 
                               id="event_name"
                               value="{{ old('event_name', $reservation->event_name) }}"
                               x-model="formData.event_name"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_name') border-red-300 @enderror">
                        @error('event_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Facility Selection -->
                    <div class="mb-6">
                        <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Facility <span class="text-red-500">*</span>
                        </label>
                        <select name="facility_id" 
                                id="facility_id"
                                x-model="formData.facility_id"
                                @change="updateFacilityDetails()"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('facility_id') border-red-300 @enderror">
                            <option value="">Select a facility</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" 
                                    {{ $facility->id == $reservation->facility_id ? 'selected' : '' }}
                                    data-name="{{ $facility->name }}"
                                    data-location="{{ $facility->location }}"
                                    data-type="{{ $facility->type_label }}"
                                    data-capacity="{{ $facility->capacity }}"
                                    data-max-capacity="{{ $facility->max_capacity ?? $facility->capacity }}"
                                    data-image="{{ $facility->image_url ?? '' }}">
                                    {{ $facility->name }} ({{ $facility->location }}) - Max: {{ $facility->max_capacity ?? $facility->capacity }} people
                                </option>
                            @endforeach
                        </select>
                        @error('facility_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Participants -->
                    <div class="mb-6">
                        <label for="participants" class="block text-sm font-medium text-gray-700 mb-2">
                            Number of Participants
                        </label>
                        <input type="number" 
                               name="participants" 
                               id="participants"
                               value="{{ old('participants', $reservation->participants) }}"
                               x-model="formData.participants"
                               min="1"
                               max="{{ $facility->max_capacity ?? $facility->capacity }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Requirements / Notes
                        </label>
                        <textarea name="notes" 
                                  id="notes"
                                  x-model="formData.notes"
                                  rows="4"
                                  placeholder="Any special requirements or additional information..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-300 @enderror">{{ old('notes', $reservation->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Date & Time</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date"
                                   value="{{ old('start_date', $reservation->start_time->format('Y-m-d')) }}"
                                   x-model="formData.start_date"
                                   @change="checkAvailability()"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-300 @enderror">
                            @error('start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date"
                                   value="{{ old('end_date', $reservation->end_time->format('Y-m-d')) }}"
                                   x-model="formData.end_date"
                                   @change="checkAvailability()"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-300 @enderror">
                            @error('end_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="start_time" 
                                   id="start_time"
                                   value="{{ old('start_time', $reservation->start_time->format('H:i')) }}"
                                   x-model="formData.start_time"
                                   @change="checkAvailability()"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_time') border-red-300 @enderror">
                            @error('start_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="end_time" 
                                   id="end_time"
                                   value="{{ old('end_time', $reservation->end_time->format('H:i')) }}"
                                   x-model="formData.end_time"
                                   @change="checkAvailability()"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_time') border-red-300 @enderror">
                            @error('end_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div class="mt-6" x-show="checkingAvailability">
                        <div class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Checking availability...</span>
                        </div>
                    </div>

                    <div class="mt-6" x-show="!checkingAvailability && availabilityStatus !== ''" 
                         :class="{'bg-green-50 border-green-200 text-green-800': availabilityStatus === 'available', 'bg-red-50 border-red-200 text-red-800': availabilityStatus === 'unavailable'}">
                        <div class="px-4 py-3 rounded-lg border">
                            <div class="flex items-center">
                                <svg x-show="availabilityStatus === 'available'" class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg x-show="availabilityStatus === 'unavailable'" class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="font-medium" x-text="availabilityMessage"></span>
                            </div>
                            <div x-show="conflictingReservations.length > 0" class="mt-2">
                                <p class="text-sm font-medium">Conflicting reservations:</p>
                                <ul class="mt-1 text-sm">
                                    <template x-for="reservation in conflictingReservations" :key="reservation.id">
                                        <li x-text="`${reservation.event_name} (${formatTime(reservation.start_time)} - ${formatTime(reservation.end_time)})`"></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Reservation Info -->
                <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Current Reservation</h4>
                    <div class="text-sm text-blue-800">
                        <p><strong>Event:</strong> {{ $reservation->event_name }}</p>
                        <p><strong>Date:</strong> {{ $reservation->start_time->format('M d, Y') }}</p>
                        <p><strong>Time:</strong> {{ $reservation->start_time->format('g:i A') }} - {{ $reservation->end_time->format('g:i A') }}</p>
                        <p><strong>Participants:</strong> {{ $reservation->participants ?? 'Not specified' }}</p>
                        <p><strong>Status:</strong> 
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($reservation->status === 'approved') bg-green-100 text-green-800
                                @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reservation->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('student.reservations.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            :disabled="availabilityStatus === 'unavailable'"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        Update Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function reservationForm() {
    return {
        formData: {
            event_name: '{{ old('event_name', $reservation->event_name) }}',
            facility_id: '{{ $reservation->facility_id }}',
            participants: '{{ old('participants', $reservation->participants) }}',
            notes: '{{ old('notes', $reservation->notes) }}',
            start_date: '{{ old('start_date', $reservation->start_time->format('Y-m-d')) }}',
            end_date: '{{ old('end_date', $reservation->end_time->format('Y-m-d')) }}',
            start_time: '{{ old('start_time', $reservation->start_time->format('H:i')) }}',
            end_time: '{{ old('end_time', $reservation->end_time->format('H:i')) }}',
        },
        checkingAvailability: false,
        availabilityStatus: '',
        availabilityMessage: '',
        conflictingReservations: [],

        init() {
            this.checkAvailability();
        },

        updateFacilityDetails() {
            const facilitySelect = document.getElementById('facility_id');
            if (facilitySelect.value) {
                this.formData.facility_id = facilitySelect.value;
                this.checkAvailability();
            }
        },

        async checkAvailability() {
            if (!this.formData.facility_id || !this.formData.start_date || !this.formData.end_date || 
                !this.formData.start_time || !this.formData.end_time) {
                this.availabilityStatus = '';
                this.availabilityMessage = '';
                this.conflictingReservations = [];
                return;
            }

            this.checkingAvailability = true;
            this.availabilityStatus = '';
            this.availabilityMessage = '';

            try {
                const response = await fetch('{{ route('student.reservations.checkAvailability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        facility_id: this.formData.facility_id,
                        start_date: this.formData.start_date,
                        end_date: this.formData.end_date,
                        start_time: this.formData.start_time,
                        end_time: this.formData.end_time,
                        reservation_id: '{{ $reservation->id }}'
                    })
                });

                const data = await response.json();
                
                if (data.available) {
                    this.availabilityStatus = 'available';
                    this.availabilityMessage = 'This time slot is available!';
                    this.conflictingReservations = [];
                } else {
                    this.availabilityStatus = 'unavailable';
                    this.availabilityMessage = data.message || 'This time slot is not available.';
                    this.conflictingReservations = data.conflicting_reservations || [];
                }
            } catch (error) {
                console.error('Error checking availability:', error);
                this.availabilityStatus = '';
                this.availabilityMessage = 'Error checking availability. Please try again.';
            } finally {
                this.checkingAvailability = false;
            }
        },

        formatTime(timeString) {
            return new Date('1970-01-01T' + timeString).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }
    }
}
</script>
@endsection