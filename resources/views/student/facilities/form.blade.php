@extends('layouts.student')

@section('title', 'Book a Facility')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="bookingForm()" x-init="init()">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold" style="color: var(--color-text-dark);">Book a Facility</h1>
                    <p class="mt-2" style="color: var(--color-text-light);">Reserve a facility for your event or activity</p>
                </div>
                <a href="{{ route('facilities.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg transition"
                   style="background-color: #E5E7EB; color: var(--color-text-dark);"
                   onmouseover="this.style.backgroundColor='#D1D5DB'"
                   onmouseout="this.style.backgroundColor='#E5E7EB'">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Facilities
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="mb-6 border rounded relative" role="alert" style="background-color: #D1FAE5; border-color: #10B981; color: #065F46;">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 border rounded relative" role="alert" style="background-color: #FEE2E2; border-color: #EF4444; color: #991B1B;">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Availability Alert -->
        <div x-show="availabilityMessage" 
             x-transition
             :class="isAvailable ? 'border-green-400 text-green-700' : 'border-red-400 text-red-700'"
             class="mb-6 border px-4 py-3 rounded relative" 
             role="alert"
             :style="isAvailable ? 'background-color: #D1FAE5;' : 'background-color: #FEE2E2;'">
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
                        <div class="flex items-center" :class="step >= 1 ? '' : ''" style="color: step >= 1 ? 'var(--color-royal-blue)' : '#9CA3AF';">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition" 
                                 :style="step >= 1 ? 'border-color: var(--color-royal-blue); background-color: var(--color-royal-blue); color: white;' : 'border-color: #D1D5DB; background-color: white; color: #9CA3AF;'">
                                <span x-show="step > 1">✓</span>
                                <span x-show="step === 1">1</span>
                                <span x-show="step < 1">1</span>
                            </div>
                            <span class="ml-2 font-medium" style="color: step >= 1 ? 'var(--color-text-dark)' : '#9CA3AF';">Select Facility</span>
                        </div>
                        <div class="flex-1 h-1 mx-4 transition" :style="step >= 2 ? 'background-color: var(--color-royal-blue);' : 'background-color: #D1D5DB;'"></div>
                        <div class="flex items-center" :style="step >= 2 ? 'color: var(--color-royal-blue);' : 'color: #9CA3AF;'">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition"
                                 :style="step >= 2 ? 'border-color: var(--color-royal-blue); background-color: var(--color-royal-blue); color: white;' : 'border-color: #D1D5DB; background-color: white; color: #9CA3AF;'">
                                <span x-show="step > 2">✓</span>
                                <span x-show="step === 2">2</span>
                                <span x-show="step < 2">2</span>
                            </div>
                            <span class="ml-2 font-medium" style="color: step >= 2 ? 'var(--color-text-dark)' : '#9CA3AF';">Date & Time</span>
                        </div>
                        <div class="flex-1 h-1 mx-4 transition" :style="step >= 3 ? 'background-color: var(--color-royal-blue);' : 'background-color: #D1D5DB;'"></div>
                        <div class="flex items-center" :style="step >= 3 ? 'color: var(--color-royal-blue);' : 'color: #9CA3AF;'">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition"
                                 :style="step >= 3 ? 'border-color: var(--color-royal-blue); background-color: var(--color-royal-blue); color: white;' : 'border-color: #D1D5DB; background-color: white; color: #9CA3AF;'">
                                3
                            </div>
                            <span class="ml-2 font-medium" style="color: step >= 3 ? 'var(--color-text-dark)' : '#9CA3AF';">Event Details</span>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Facility Selection -->
                <div x-show="step === 1" x-transition class="space-y-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: var(--color-text-dark);">Choose a Facility</h2>

                    <!-- Search and Filter -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <input type="text" 
                               x-model="searchQuery"
                               @input.debounce.300ms="filterFacilities"
                               placeholder="Search facilities..."
                               class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                               style="border-color: #D1D5DB; color: var(--color-text-dark);"
                               onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                               onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';">
                        
                        <select x-model="filterType" @change="filterFacilities"
                                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';">
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
                                 :class="selectedFacility?.id === facility.id ? 'ring-2 border-2' : 'border-2'"
                                 class="cursor-pointer hover:shadow-md transition rounded-lg p-4"
                                 :style="selectedFacility?.id === facility.id ? 'ring-color: var(--color-royal-blue); border-color: var(--color-royal-blue);' : 'border-color: #E5E7EB;'"
                                 onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.15)';"
                                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                <div class="flex items-start">
                                    <img :src="facility.image_url" 
                                         :alt="facility.name"
                                         class="w-20 h-20 rounded object-cover">
                                    <div class="ml-4 flex-1">
                                        <h3 class="font-semibold" style="color: var(--color-text-dark);" x-text="facility.name"></h3>
                                        <p class="text-sm mt-1" style="color: var(--color-text-light);">
                                            <span class="inline-block mr-2">📍 <span x-text="facility.location"></span></span>
                                        </p>
                                        <p class="text-sm" style="color: var(--color-text-light);">
                                            <span class="inline-block mr-2">👥 <span x-text="facility.capacity_text"></span></span>
                                        </p>
                                        <p class="text-sm mt-1" style="color: var(--color-text-light);">
                                            🕒 <span x-text="facility.hours_text"></span>
                                        </p>
                                    </div>
                                    <div x-show="selectedFacility?.id === facility.id">
                                        <svg class="w-6 h-6" style="color: var(--color-royal-blue);" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredFacilities.length === 0" class="text-center py-8" style="color: var(--color-text-light);">
                        No facilities found matching your criteria.
                    </div>

                    <input type="hidden" name="facility_id" :value="selectedFacility?.id">
                    @error('facility_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Step 2: Date & Time Selection -->
                <div x-show="step === 2" x-transition class="space-y-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: var(--color-text-dark);">Select Date & Time</h2>

                    <!-- Selected Facility Info -->
                    <div x-show="selectedFacility" class="border rounded-lg p-4 mb-4" style="background-color: #EFF6FF; border-color: var(--color-royal-blue);">
                        <div class="flex items-center">
                            <img :src="selectedFacility?.image_url" 
                                 :alt="selectedFacility?.name"
                                 class="w-16 h-16 rounded object-cover">
                            <div class="ml-4">
                                <h3 class="font-semibold" style="color: var(--color-text-dark);" x-text="selectedFacility?.name"></h3>
                                <p class="text-sm" style="color: var(--color-text-light);" x-text="selectedFacility?.location"></p>
                                <p class="text-sm font-medium mt-1" style="color: var(--color-royal-blue);">
                                    Operating Hours: <span x-text="selectedFacility?.hours_text"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div>
                        <label for="date" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="date"
                               id="date"
                               x-model="bookingDate"
                               @change="checkAvailability"
                               :min="minDate"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                               style="border-color: #D1D5DB; color: var(--color-text-dark);"
                               onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                               onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                               required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="start_time_only"
                                   id="start_time"
                                   x-model="startTime"
                                   @change="checkAvailability"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                   onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                   onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                   required>
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="end_time_only"
                                   id="end_time"
                                   x-model="endTime"
                                   @change="checkAvailability"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                   onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                   onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                   required>
                        </div>
                    </div>

                    <!-- Hidden datetime fields for form submission -->
                    <input type="hidden" name="start_time" :value="getFullDateTime(bookingDate, startTime)">
                    <input type="hidden" name="end_time" :value="getFullDateTime(bookingDate, endTime)">

                    <!-- Duration Display -->
                    <div x-show="bookingDate && startTime && endTime" class="rounded-lg p-4" style="background-color: var(--color-off-white);">
                        <p class="text-sm" style="color: var(--color-text-dark);">
                            <span class="font-medium">Duration:</span> 
                            <span x-text="calculateDuration()"></span>
                        </p>
                    </div>

                    <!-- Loading State -->
                    <div x-show="checkingAvailability" class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-t-2" style="border-color: var(--color-off-white); border-top-color: var(--color-royal-blue);"></div>
                        <span class="ml-3" style="color: var(--color-text-light);">Checking availability...</span>
                    </div>
                </div>

                <!-- Step 3: Event Details -->
                <div x-show="step === 3" x-transition class="space-y-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: var(--color-text-dark);">Event Details</h2>

                    <!-- Booking Summary -->
                    <div class="border rounded-lg p-4 mb-6" style="background-color: #EFF6FF; border-color: var(--color-royal-blue);">
                        <h3 class="font-semibold mb-3" style="color: var(--color-text-dark);">Booking Summary</h3>
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
                        <label for="event_name" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                            Event Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="event_name" 
                               id="event_name"
                               value="{{ old('event_name') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent @error('event_name') border-red-500 @enderror"
                               style="border-color: #D1D5DB; color: var(--color-text-dark);"
                               onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                               onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                               placeholder="e.g., Team Meeting, Workshop, Training Session"
                               required>
                        @error('event_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Number of Participants -->
                    <div>
                        <label for="participants" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                            Number of Participants
                        </label>
                        <input type="number" 
                               name="participants" 
                               id="participants"
                               value="{{ old('participants') }}"
                               min="1"
                               :max="selectedFacility?.max_capacity || 1000"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent @error('participants') border-red-500 @enderror"
                               style="border-color: #D1D5DB; color: var(--color-text-dark);"
                               onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                               onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                               placeholder="Expected number of attendees">
                        <p class="mt-1 text-sm" style="color: var(--color-text-light);">
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
                        <label for="notes" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                            Additional Notes
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4"
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent @error('notes') border-red-500 @enderror"
                                  style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                  onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                  onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                  placeholder="Any special requirements or additional information...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm" style="color: var(--color-text-light);">Maximum 1000 characters</p>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="rounded-lg p-4" style="background-color: var(--color-off-white);">
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   id="terms"
                                   x-model="agreedToTerms"
                                   class="mt-1 w-4 h-4 rounded focus:ring-2"
                                   style="border-color: #D1D5DB; accent-color: var(--color-royal-blue);">
                            <label for="terms" class="ml-3 text-sm" style="color: var(--color-text-dark);">
                                I understand that this reservation is subject to approval by the facility administrator and agree to follow all facility rules and regulations.
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t" style="border-color: #D1D5DB;">
                    <button type="button"
                            x-show="step > 1"
                            @click="prevStep"
                            class="px-6 py-2 rounded-lg transition"
                            style="background-color: #E5E7EB; color: var(--color-text-dark);"
                            onmouseover="this.style.backgroundColor='#D1D5DB'"
                            onmouseout="this.style.backgroundColor='#E5E7EB'">
                        ← Previous
                    </button>
                    
                    <div class="flex-1"></div>

                    <button type="button"
                            x-show="step < 3"
                            @click="nextStep"
                            :disabled="!canProceed()"
                            :class="canProceed() ? 'hover:transform hover:-translate-y-1' : 'opacity-50 cursor-not-allowed'"
                            class="px-6 py-2 text-white rounded-lg transition flex items-center"
                            style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));">
                        Next →
                    </button>

                    <button type="submit"
                            x-show="step === 3"
                            :disabled="!agreedToTerms"
                            :class="agreedToTerms ? 'hover:transform hover:-translate-y-1' : 'opacity-50 cursor-not-allowed'"
                            class="px-6 py-2 text-white rounded-lg transition flex items-center"
                            style="background: linear-gradient(135deg, #10B981, #059669);">
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

<style>
    :root {
        /* Color palette */
        --color-dark-navy: #172030;
        --color-royal-blue: #002366;
        --color-navy-blue: #00285C;
        --color-white: #FFFFFF;
        --color-off-white: #F8F9FA;
        --color-text-dark: #333C4D;
        --color-text-light: #1D2636;
        --color-dark-blue: #001A4A;
        --color-charcoal: #1D2636;
    }
</style>

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