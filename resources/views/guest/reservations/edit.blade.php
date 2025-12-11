@extends('layouts.guest')

@section('title', 'Edit Reservation')

@section('header')
    <div class="flex items-center">
        <a href="{{ route('guest.reservations.index') }}" 
           class="mr-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Reservation</h1>
            <p class="mt-2 text-sm text-gray-600">Modify your booking for {{ $facility->name }}</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Facility Info Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden sticky top-6">
                <!-- Facility Image -->
                @if($facility->image)
                    <div class="h-48 w-full overflow-hidden">
                        <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="h-48 w-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
                
                <!-- Facility Details -->
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $facility->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ $facility->location }}</p>
                    
                    <!-- Facility Stats -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-lg border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" style="color: #002366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-xs font-semibold" style="color: #333C4D;">Capacity</span>
                            </div>
                            <span class="text-sm font-bold" style="color: #172030;">
                                {{ $facility->capacity ?? 'N/A' }}@if($facility->max_capacity) - {{ $facility->max_capacity }}@endif
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 rounded-lg border" style="background: #F8F9FA; border-color: rgba(51, 60, 77, 0.1);">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" style="color: #002366;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-semibold" style="color: #333C4D;">Hours</span>
                            </div>
                            <span class="text-sm font-bold" style="color: #172030;">
                                {{ $facility->opening_time ? date('g:i A', strtotime($facility->opening_time)) : 'N/A' }} - {{ $facility->closing_time ? date('g:i A', strtotime($facility->closing_time)) : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="lg:col-span-3">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <form action="{{ route('guest.reservations.update', $reservation) }}" method="POST" x-data="reservationForm()" x-init="init()">
                    @csrf
                    @method('PUT')
                    
                    <!-- Event Details -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Event Details</h2>
                        <p class="mt-1 text-sm text-gray-500">Provide information about your reservation</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Event Name -->
                        <div>
                            <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Event Name <span style="color: #EF4444;">*</span>
                            </label>
                            <input type="text" 
                                   name="event_name" 
                                   id="event_name"
                                   value="{{ old('event_name', $reservation->event_name) }}"
                                   x-model="formData.event_name"
                                   required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm @error('event_name') border-red-300 @enderror">
                            @error('event_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Facility Selection -->
                        <div>
                            <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Facility <span style="color: #EF4444;">*</span>
                            </label>
                            <select name="facility_id" 
                                    id="facility_id"
                                    x-model="formData.facility_id"
                                    @change="updateFacilityDetails()"
                                    required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm @error('facility_id') border-red-300 @enderror">
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
                        <div>
                            <label for="participants" class="block text-sm font-medium text-gray-700 mb-2">
                                Expected Number of Participants
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="participants" 
                                       name="participants" 
                                       value="{{ old('participants', $reservation->participants) }}" 
                                       min="1" 
                                       max="{{ $facility->max_capacity ?? $facility->capacity }}" 
                                       x-model="formData.participants"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm pl-10 @error('participants') border-red-300 @enderror">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Maximum capacity: <strong>{{ $facility->max_capacity ?? $facility->capacity }}</strong> people</p>
                            @error('participants')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Requirements / Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes"
                                      x-model="formData.notes"
                                      rows="4"
                                      placeholder="Any special requirements or additional information..."
                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm @error('notes') border-red-300 @enderror">{{ old('notes', $reservation->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-t border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Date & Time</h2>
                        <p class="mt-1 text-sm text-gray-500">Select when you want to use the facility</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span style="color: #EF4444;">*</span>
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date"
                                       value="{{ old('start_date', $reservation->start_time->format('Y-m-d')) }}"
                                       x-model="formData.start_date"
                                       @change="checkAvailability()"
                                       required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm @error('start_date') border-red-300 @enderror">
                                @error('start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date <span style="color: #EF4444;">*</span>
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date"
                                       value="{{ old('end_date', $reservation->end_time->format('Y-m-d')) }}"
                                       x-model="formData.end_date"
                                       @change="checkAvailability()"
                                       required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm @error('end_date') border-red-300 @enderror">
                                @error('end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Time <span style="color: #EF4444;">*</span>
                                </label>
                                <input type="time" 
                                       name="start_time" 
                                       id="start_time"
                                       value="{{ old('start_time', $reservation->start_time->format('H:i')) }}"
                                       x-model="formData.start_time"
                                       @change="checkAvailability()"
                                       required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm @error('start_time') border-red-300 @enderror">
                                @error('start_time')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Time <span style="color: #EF4444;">*</span>
                                </label>
                                <input type="time" 
                                       name="end_time" 
                                       id="end_time"
                                       value="{{ old('end_time', $reservation->end_time->format('H:i')) }}"
                                       x-model="formData.end_time"
                                       @change="checkAvailability()"
                                       required
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#002147] focus:ring-[#002147] sm:text-sm @error('end_time') border-red-300 @enderror">
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
                    <div class="px-6 py-4 bg-blue-50 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Current Reservation</h4>
                        <div class="text-sm text-blue-800">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p><strong>Event:</strong> {{ $reservation->event_name }}</p>
                                    <p><strong>Date:</strong> {{ $reservation->start_time->format('M d, Y') }}</p>
                                    <p><strong>Time:</strong> {{ $reservation->start_time->format('g:i A') }} - {{ $reservation->end_time->format('g:i A') }}</p>
                                </div>
                                <div>
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
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <a href="{{ route('guest.reservations.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147]">
                            Cancel
                        </a>
                        <button type="submit" 
                                :disabled="availabilityStatus === 'unavailable'"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#002147] hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147] disabled:opacity-50 disabled:cursor-not-allowed">
                            Update Reservation
                        </button>
                    </div>
                </form>
            </div>
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
                const response = await fetch('{{ route('guest.reservations.checkAvailability') }}', {
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