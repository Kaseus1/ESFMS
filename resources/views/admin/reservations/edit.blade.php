@extends('layouts.' . (auth()->user()->role === 'admin' ? 'admin' : 'faculty'))

@section('content')
<div x-data="reservationEditForm()" x-init="init()" class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Reservation</h1>
                    <p class="mt-2 text-gray-600">Update your reservation details</p>
                </div>
                <a href="{{ route(auth()->user()->role . '.reservations.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-red-800 font-medium">There were some errors:</h3>
                    <ul class="mt-2 text-red-700 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route(auth()->user()->role . '.reservations.update', $reservation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Event Information</h2>
                        
                        <div class="mb-6">
                            <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Event Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="event_name" 
                                   id="event_name"
                                   x-model="formData.event_name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <div class="mb-6">
                            <label for="participants" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Participants
                            </label>
                            <input type="number" 
                                   name="participants" 
                                   id="participants"
                                   x-model="formData.participants"
                                   min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            
                            <div x-show="selectedFacility && formData.participants > (selectedFacility.max_capacity || selectedFacility.capacity)" 
                                 class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Exceeds facility capacity (<span x-text="selectedFacility.max_capacity || selectedFacility.capacity"></span>)
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes"
                                      rows="4"
                                      x-model="formData.notes"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Select Facility</h2>
                        
                        <input type="hidden" name="facility_id" x-model="selectedFacilityId">
                        
                        <div class="mb-4">
                            <input type="text" 
                                   x-model="facilitySearch"
                                   placeholder="Search facilities by name, type, or location..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                            <template x-for="facility in filteredFacilities" :key="facility.id">
                                <div @click="selectFacility(facility)"
                                     :class="selectedFacilityId === facility.id ? 'border-blue-500 ring-2 ring-blue-200 bg-blue-50' : 'border-gray-200 hover:border-blue-300'"
                                     class="border-2 rounded-lg p-4 cursor-pointer transition">
                                    <div class="flex items-start space-x-3">
                                        <img :src="facility.image_url" 
                                             :alt="facility.name"
                                             class="w-16 h-16 rounded object-cover bg-gray-100">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 truncate" x-text="facility.name"></h3>
                                            <p class="text-sm text-gray-500 font-medium" x-text="facility.type_label"></p>
                                            
                                            <div class="mt-1 text-sm font-semibold text-green-600" x-show="facility.hourly_rate > 0">
                                                ₱<span x-text="facility.hourly_rate"></span>/hr
                                            </div>
                                            <div class="mt-1 text-sm text-gray-600" x-show="!facility.hourly_rate || facility.hourly_rate == 0">
                                                Free
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Schedule</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start</label>
                                <div class="flex space-x-2">
                                    <input type="date" 
                                           name="start_date" 
                                           x-model="formData.start_date"
                                           @change="checkAvailability()"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500" required>
                                    <input type="time" 
                                           name="start_time" 
                                           x-model="formData.start_time"
                                           @change="checkAvailability()"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End</label>
                                <div class="flex space-x-2">
                                    <input type="date" 
                                           name="end_date" 
                                           x-model="formData.end_date"
                                           @change="checkAvailability()"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500" required>
                                    <input type="time" 
                                           name="end_time" 
                                           x-model="formData.end_time"
                                           @change="checkAvailability()"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500" required>
                                </div>
                            </div>
                        </div>

                        <div x-show="availabilityChecked" 
                             x-transition
                             class="p-4 rounded-lg mb-4"
                             :class="isAvailable ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                            <div class="flex items-center">
                                <span :class="isAvailable ? 'text-green-800' : 'text-red-800'" 
                                      class="font-medium flex items-center">
                                      <span class="mr-2" x-text="isAvailable ? '✓' : '⚠'"></span>
                                      <span x-text="availabilityMessage"></span>
                                </span>
                            </div>
                        </div>
                        
                        <div x-show="estimatedCost > 0" class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-blue-800 font-medium">Estimated New Cost:</span>
                                <span class="text-blue-900 font-bold text-lg">₱<span x-text="estimatedCost.toFixed(2)"></span></span>
                            </div>
                            <p class="text-xs text-blue-600 mt-1">*Based on selected facility rate and duration.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route(auth()->user()->role . '.reservations.index') }}" 
                           class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                :disabled="!canSubmit"
                                :class="canSubmit ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
                                class="px-6 py-2 text-white rounded-lg transition flex items-center shadow-sm">
                            <span x-show="checking" class="mr-2 animate-spin">⟳</span>
                            Update Reservation
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Selected Facility</h3>
                        
                        <div x-show="selectedFacility" x-transition>
                            <img :src="selectedFacility?.image_url" 
                                 class="w-full h-48 object-cover rounded-lg mb-4 bg-gray-100">
                            
                            <h4 class="font-bold text-xl text-gray-900 mb-2" x-text="selectedFacility?.name"></h4>
                            
                            <div class="space-y-3 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <span class="font-medium w-20">Type:</span>
                                    <span x-text="selectedFacility?.type_label"></span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium w-20">Location:</span>
                                    <span x-text="selectedFacility?.location"></span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium w-20">Rate:</span>
                                    <span x-text="selectedFacility?.hourly_rate > 0 ? '₱' + selectedFacility.hourly_rate + '/hr' : 'Free'"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Original Details</h4>
                            <div class="text-sm space-y-2">
                                <p><span class="text-gray-500">Created:</span> {{ $reservation->created_at->format('M d, Y') }}</p>
                                <p><span class="text-gray-500">Original Cost:</span> ₱{{ number_format($reservation->cost, 2) }}</p>
                                <p><span class="text-gray-500">Status:</span> 
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $reservation->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function reservationEditForm() {
    return {
        facilities: @json($facilities),
        reservationId: {{ $reservation->id }},
        selectedFacilityId: {{ old('facility_id', $reservation->facility_id) }},
        selectedFacility: null,
        facilitySearch: '',
        
        // Split Date and Time fields
        formData: {
            event_name: '{{ old('event_name', $reservation->event_name) }}',
            participants: '{{ old('participants', $reservation->participants) }}',
            notes: `{{ old('notes', $reservation->notes) }}`,
            
            // Parse existing start/end into Date and Time components
            start_date: '{{ old('start_date', $reservation->start_time->format('Y-m-d')) }}',
            start_time: '{{ old('start_time', $reservation->start_time->format('H:i')) }}',
            end_date:   '{{ old('end_date', $reservation->end_time->format('Y-m-d')) }}',
            end_time:   '{{ old('end_time', $reservation->end_time->format('H:i')) }}',
        },
        
        availabilityChecked: false,
        isAvailable: false,
        availabilityMessage: '',
        checking: false,
        estimatedCost: 0,

        init() {
            // Find and select current facility
            const facility = this.facilities.find(f => f.id === this.selectedFacilityId);
            if (facility) {
                this.selectFacility(facility, false); // false = don't check availability immediately on load
            }
            this.calculateCost(); // Initial cost calc
        },

        get filteredFacilities() {
            if (!this.facilitySearch) return this.facilities;
            const search = this.facilitySearch.toLowerCase();
            return this.facilities.filter(f => 
                f.name.toLowerCase().includes(search) ||
                f.type_label.toLowerCase().includes(search)
            );
        },

        selectFacility(facility, check = true) {
            this.selectedFacilityId = facility.id;
            this.selectedFacility = facility;
            if (check) this.checkAvailability();
            this.calculateCost();
        },

        async checkAvailability() {
            // Validate all fields are present before checking
            if (!this.selectedFacilityId || 
                !this.formData.start_date || !this.formData.start_time || 
                !this.formData.end_date || !this.formData.end_time) {
                this.availabilityChecked = false;
                this.estimatedCost = 0;
                return;
            }

            this.checking = true;
            this.availabilityMessage = 'Checking...';

            // Combine fields for API (API expects full datetime string)
            const startDateTime = `${this.formData.start_date} ${this.formData.start_time}`;
            const endDateTime = `${this.formData.end_date} ${this.formData.end_time}`;

            try {
                const response = await fetch('{{ route(auth()->user()->role . '.reservations.checkAvailability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        facility_id: this.selectedFacilityId,
                        start_time: startDateTime, // Send combined
                        end_time: endDateTime,     // Send combined
                        reservation_id: this.reservationId
                    })
                });

                const data = await response.json();
                this.isAvailable = data.available;
                this.availabilityMessage = data.message;
                this.availabilityChecked = true;
                this.calculateCost();
                
            } catch (error) {
                console.error('Error:', error);
                this.availabilityMessage = 'Error checking availability';
                this.isAvailable = false;
            } finally {
                this.checking = false;
            }
        },

        calculateCost() {
            if (!this.selectedFacility || !this.formData.start_date || !this.formData.start_time || !this.formData.end_date || !this.formData.end_time) {
                this.estimatedCost = 0;
                return;
            }

            const start = new Date(`${this.formData.start_date}T${this.formData.start_time}`);
            const end = new Date(`${this.formData.end_date}T${this.formData.end_time}`);

            if (end > start) {
                const diffMs = end - start;
                const diffHrs = Math.ceil(diffMs / (1000 * 60 * 60)); // Round up to nearest hour
                this.estimatedCost = diffHrs * (this.selectedFacility.hourly_rate || 0);
            } else {
                this.estimatedCost = 0;
            }
        },

        get canSubmit() {
            return this.selectedFacilityId && 
                   this.formData.event_name && 
                   this.formData.start_date && this.formData.start_time &&
                   this.formData.end_date && this.formData.end_time &&
                   !this.checking &&
                   (this.isAvailable); // Must be available to submit
        }
    }
}
</script>
@endsection