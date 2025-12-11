@extends('layouts.app')

@section('title', 'Book a Facility')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="bookingForm()" x-init="init()">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#172030]">Book a Facility</h1>
                    <p class="mt-2 text-[#333C4D]">Reserve a facility for your event or activity</p>
                </div>
                <a href="{{ route('facilities.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#333C4D] border-opacity-20 border border-[#333C4D] hover:bg-[#F8F9FA] text-[#172030] rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Facilities
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="mb-6 bg-[#F8F9FA] border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-[#F8F9FA] border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Availability Alert -->
        <div x-show="availabilityMessage" 
             x-transition
             :class="isAvailable ? 'bg-[#F8F9FA] border-green-400 text-green-700' : 'bg-[#F8F9FA] border-red-400 text-red-700'"
             class="mb-6 border px-4 py-3 rounded relative" 
             role="alert">
            <div class="flex items-center">
                <svg x-show="isAvailable" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <svg x-show="!isAvailable" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="availabilityMessage"></span>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('reservations.store') }}" method="POST" id="bookingForm" @submit="validateForm">
                @csrf

                <!-- Step Indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center" :class="step >= 1 ? 'text-[#002366]' : 'text-[#333C4D]'">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition" 
                                 :class="step >= 1 ? 'border-[#002366] bg-[#002366] text-white' : 'border-[#333C4D] border-opacity-20'">
                                <span x-show="step > 1">✓</span>
                                <span x-show="step === 1">1</span>
                                <span x-show="step < 1">1</span>
                            </div>
                            <span class="ml-2 font-medium">Select Facility</span>
                        </div>
                        <div class="flex-1 h-1 mx-4 transition" :class="step >= 2 ? 'bg-[#002366]' : 'bg-[#333C4D] border-opacity-20'"></div>
                        <div class="flex items-center" :class="step >= 2 ? 'text-[#002366]' : 'text-[#333C4D]'">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition"
                                 :class="step >= 2 ? 'border-[#002366] bg-[#002366] text-white' : 'border-[#333C4D] border-opacity-20'">
                                <span x-show="step > 2">✓</span>
                                <span x-show="step === 2">2</span>
                                <span x-show="step < 2">2</span>
                            </div>
                            <span class="ml-2 font-medium">Date & Time</span>
                        </div>
                        <div class="flex-1 h-1 mx-4 transition" :class="step >= 3 ? 'bg-[#002366]' : 'bg-[#333C4D] border-opacity-20'"></div>
                        <div class="flex items-center" :class="step >= 3 ? 'text-[#002366]' : 'text-[#333C4D]'">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition"
                                 :class="step >= 3 ? 'border-[#002366] bg-[#002366] text-white' : 'border-[#333C4D] border-opacity-20'">
                                3
                            </div>
                            <span class="ml-2 font-medium">Event Details</span>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Facility Selection -->
                <div x-show="step === 1" x-transition class="space-y-6">
                    <h2 class="text-xl font-semibold text-[#172030] mb-4">Choose a Facility</h2>

                    <!-- Search and Filter -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <input type="text" 
                               x-model="searchQuery"
                               @input.debounce.300ms="filterFacilities"
                               placeholder="Search facilities..."
                               class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent">
                        
                        <select x-model="filterType" @change="filterFacilities"
                                class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent">
                            <option value="">All Types</option>
                            <option value="classroom">Classroom</option>
                            <option value="conference_room">Conference Room</option>
                            <option value="auditorium">Auditorium</option>
                            <option value="laboratory">Laboratory</option>
                            <option value="sports_facility">Sports Facility</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Facilities Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="facility in filteredFacilities" :key="facility.id">
                            <div @click="selectFacility(facility)"
                                 :class="selectedFacility?.id === facility.id ? 'ring-2 ring-[#002366] border-[#002366]' : 'border-[#333C4D] border-opacity-20'"
                                 class="border-2 rounded-lg p-4 cursor-pointer hover:shadow-md transition">
                                <div class="flex items-start">
                                    <img :src="facility.image_url" 
                                         :alt="facility.name"
                                         class="w-20 h-20 rounded object-cover">
                                    <div class="ml-4 flex-1">
                                        <h3 class="font-semibold text-[#172030]" x-text="facility.name"></h3>
                                        <p class="text-sm text-[#333C4D] mt-1">
                                            <span class="inline-block mr-2">📍 <span x-text="facility.location"></span></span>
                                        </p>
                                        <p class="text-sm text-[#333C4D]">
                                            <span class="inline-block mr-2">👥 <span x-text="facility.capacity_text"></span></span>
                                        </p>
                                        <p class="text-sm text-[#333C4D] border-opacity-60 mt-1">
                                            🕒 <span x-text="facility.hours_text"></span>
                                        </p>
                                    </div>
                                    <div x-show="selectedFacility?.id === facility.id">
                                        <svg class="w-6 h-6 text-[#002366]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredFacilities.length === 0" class="text-center py-8 text-[#333C4D]">
                        No facilities found matching your criteria.
                    </div>

                    <input type="hidden" name="facility_id" :value="selectedFacility?.id">
                    @error('facility_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Step 2: Date & Time Selection -->
                <div x-show="step === 2" x-transition class="space-y-6">
                    <h2 class="text-xl font-semibold text-[#172030] mb-4">Select Date & Time</h2>

                    <!-- Selected Facility Info -->
                    <div x-show="selectedFacility" class="bg-[#F8F9FA] border border-[#333C4D] border-opacity-20 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <img :src="selectedFacility?.image_url" 
                                 :alt="selectedFacility?.name"
                                 class="w-16 h-16 rounded object-cover">
                            <div class="ml-4">
                                <h3 class="font-semibold text-[#172030]" x-text="selectedFacility?.name"></h3>
                                <p class="text-sm text-[#333C4D]" x-text="selectedFacility?.location"></p>
                                <p class="text-sm text-[#002366] font-medium mt-1">
                                    Operating Hours: <span x-text="selectedFacility?.hours_text"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-[#333C4D] mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="date"
                               id="date"
                               x-model="bookingDate"
                               @change="checkAvailability"
                               :min="minDate"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent"
                               required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="start_time_only"
                                   id="start_time"
                                   x-model="startTime"
                                   @change="checkAvailability"
                                   class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent"
                                   required>
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-[#333C4D] mb-2">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="end_time_only"
                                   id="end_time"
                                   x-model="endTime"
                                   @change="checkAvailability"
                                   class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent"
                                   required>
                        </div>
                    </div>

                    <!-- Hidden datetime fields for form submission -->
                    <input type="hidden" name="start_time" :value="getFullDateTime(bookingDate, startTime)">
                    <input type="hidden" name="end_time" :value="getFullDateTime(bookingDate, endTime)">

                    <!-- Duration Display -->
                    <div x-show="bookingDate && startTime && endTime" class="bg-[#F8F9FA] rounded-lg p-4">
                        <p class="text-sm text-[#333C4D]">
                            <span class="font-medium">Duration:</span> 
                            <span x-text="calculateDuration()"></span>
                        </p>
                    </div>

                    <!-- Loading State -->
                    <div x-show="checkingAvailability" class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#002366]"></div>
                        <span class="ml-3 text-[#333C4D]">Checking availability...</span>
                    </div>
                </div>

                <!-- Step 3: Event Details -->
                <div x-show="step === 3" x-transition class="space-y-6">
                    <h2 class="text-xl font-semibold text-[#172030] mb-4">Event Details</h2>

                    <!-- Booking Summary -->
                    <div class="bg-[#F8F9FA] border border-[#333C4D] border-opacity-20 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-[#172030] mb-3">Booking Summary</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Facility:</span> <span x-text="selectedFacility?.name"></span></p>
                            <p><span class="font-medium">Location:</span> <span x-text="selectedFacility?.location"></span></p>
                            <p><span class="font-medium">Date:</span> <span x-text="formatDate(bookingDate)"></span></p>
                            <p><span class="font-medium">Time:</span> <span x-text="startTime + ' - ' + endTime"></span></p>
                            <p><span class="font-medium">Duration:</span> <span x-text="calculateDuration()"></span></p>
                        </div>
                    </div>

                    <!-- Event Name -->
                    <div>
                        <label for="event_name" class="block text-sm font-medium text-[#333C4D] mb-2">
                            Event Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="event_name" 
                               id="event_name"
                               value="{{ old('event_name') }}"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('event_name') border-red-500 @enderror"
                               placeholder="e.g., Team Meeting, Workshop, Training Session"
                               required>
                        @error('event_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Number of Participants -->
                    <div>
                        <label for="participants" class="block text-sm font-medium text-[#333C4D] mb-2">
                            Number of Participants
                        </label>
                        <input type="number" 
                               name="participants" 
                               id="participants"
                               value="{{ old('participants') }}"
                               min="1"
                               :max="selectedFacility?.max_capacity || 1000"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('participants') border-red-500 @enderror"
                               placeholder="Expected number of attendees">
                        <p class="mt-1 text-sm text-[#333C4D] border-opacity-60">
                            <span x-show="selectedFacility?.max_capacity">
                                Maximum capacity: <span x-text="selectedFacility?.max_capacity"></span> people
                            </span>
                        </p>
                        @error('participants')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-[#333C4D] mb-2">
                            Additional Notes
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('notes') border-red-500 @enderror"
                                  placeholder="Any special requirements or additional information...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-[#333C4D] border-opacity-60">Maximum 1000 characters</p>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="bg-[#F8F9FA] rounded-lg p-4">
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   id="terms"
                                   x-model="agreedToTerms"
                                   class="mt-1 w-4 h-4 text-[#002366] border-[#333C4D] border-opacity-20 rounded focus:ring-[#002366]">
                            <label for="terms" class="ml-3 text-sm text-[#333C4D]">
                                I understand that this reservation is subject to approval by the facility administrator and agree to follow all facility rules and regulations.
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-[#333C4D] border-opacity-20">
                    <button type="button"
                            x-show="step > 1"
                            @click="prevStep"
                            class="px-6 py-2 bg-[#333C4D] border-opacity-20 border border-[#333C4D] hover:bg-[#F8F9FA] text-[#172030] rounded-lg transition">
                        ← Previous
                    </button>
                    
                    <div class="flex-1"></div>

                    <button type="button"
                            x-show="step < 3"
                            @click="nextStep"
                            :disabled="!canProceed()"
                            :class="canProceed() ? 'bg-[#002366] hover:bg-[#00285C]' : 'bg-[#333C4D] border-opacity-30 cursor-not-allowed'"
                            class="px-6 py-2 text-white rounded-lg transition">
                        Next →
                    </button>

                    <button type="submit"
                            x-show="step === 3"
                            :disabled="!agreedToTerms"
                            :class="agreedToTerms ? 'bg-[#002366] hover:bg-[#00285C]' : 'bg-[#333C4D] border-opacity-30 cursor-not-allowed'"
                            class="px-6 py-2 text-white rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function bookingForm() {
    return {
        step: 1,
        facilities: @json($facilities),
        selectedFacility: null,
        filteredFacilities: [],
        searchQuery: '',
        filterType: '',
        bookingDate: '',
        startTime: '',
        endTime: '',
        minDate: new Date().toISOString().split('T')[0],
        checkingAvailability: false,
        isAvailable: null,
        availabilityMessage: '',
        agreedToTerms: false,

        init() {
            this.filteredFacilities = this.facilities;
            
            // Pre-select facility if passed in URL
            const urlParams = new URLSearchParams(window.location.search);
            const facilityId = urlParams.get('facility_id') || {{ $selectedFacilityId ?? 'null' }};
            
            if (facilityId) {
                const facility = this.facilities.find(f => f.id == facilityId);
                if (facility) {
                    this.selectFacility(facility);
                }
            }
        },

        selectFacility(facility) {
            this.selectedFacility = facility;
        },

        filterFacilities() {
            this.filteredFacilities = this.facilities.filter(f => {
                const matchesSearch = !this.searchQuery || 
                    f.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    f.location.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                const matchesType = !this.filterType || f.type === this.filterType;
                
                return matchesSearch && matchesType;
            });
        },

        nextStep() {
            if (this.step === 1 && !this.selectedFacility) {
                alert('Please select a facility first');
                return;
            }
            
            if (this.step === 2) {
                if (!this.bookingDate || !this.startTime || !this.endTime) {
                    alert('Please fill in all date and time fields');
                    return;
                }
                
                if (!this.isAvailable) {
                    alert('The selected time slot is not available. Please choose a different time.');
                    return;
                }
            }
            
            this.step++;
        },

        prevStep() {
            this.step--;
        },

        canProceed() {
            if (this.step === 1) {
                return this.selectedFacility !== null;
            }
            if (this.step === 2) {
                return this.bookingDate && this.startTime && this.endTime && this.isAvailable;
            }
            return true;
        },

        async checkAvailability() {
            if (!this.selectedFacility || !this.bookingDate || !this.startTime || !this.endTime) {
                return;
            }

            // Validate end time is after start time
            if (this.endTime <= this.startTime) {
                this.isAvailable = false;
                this.availabilityMessage = 'End time must be after start time';
                return;
            }

            this.checkingAvailability = true;
            this.availabilityMessage = '';

            try {
                const startDateTime = this.getFullDateTime(this.bookingDate, this.startTime);
                const endDateTime = this.getFullDateTime(this.bookingDate, this.endTime);

                const response = await fetch('{{ route("reservations.check-availability") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        facility_id: this.selectedFacility.id,
                        start_time: startDateTime,
                        end_time: endDateTime
                    })
                });

                const data = await response.json();
                this.isAvailable = data.available;
                this.availabilityMessage = data.message;
            } catch (error) {
                console.error('Error checking availability:', error);
                this.availabilityMessage = 'Error checking availability. Please try again.';
                this.isAvailable = false;
            } finally {
                this.checkingAvailability = false;
            }
        },

        getFullDateTime(date, time) {
            return `${date} ${time}:00`;
        },

        calculateDuration() {
            if (!this.startTime || !this.endTime) return '';
            
            const [startHour, startMin] = this.startTime.split(':').map(Number);
            const [endHour, endMin] = this.endTime.split(':').map(Number);
            
            const startMinutes = startHour * 60 + startMin;
            const endMinutes = endHour * 60 + endMin;
            const diffMinutes = endMinutes - startMinutes;
            
            if (diffMinutes <= 0) return 'Invalid time range';
            
            const hours = Math.floor(diffMinutes / 60);
            const minutes = diffMinutes % 60;
            
            if (hours === 0) return `${minutes} minutes`;
            if (minutes === 0) return `${hours} hour${hours > 1 ? 's' : ''}`;
            return `${hours} hour${hours > 1 ? 's' : ''} ${minutes} minutes`;
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr + 'T00:00:00');
            return date.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        },

        validateForm(e) {
            if (!this.agreedToTerms) {
                e.preventDefault();
                alert('Please agree to the terms and conditions');
                return false;
            }
            return true;
        }
    }
}
</script>
@endsection