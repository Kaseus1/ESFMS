@extends('layouts.faculty')

@section('content')
<div x-data="reservationForm()" x-init="init()" class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Wallet Balance Header -->
        <div class="mb-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between text-white">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-100">Your Wallet Balance</p>
                        <p class="text-2xl font-bold">{{ auth()->user()->formatted_balance }}</p>
                    </div>
                </div>
                <a href="{{ route('faculty.wallet.index') }}" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm transition">
                    View Wallet
                </a>
            </div>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Reservation</h1>
                    <p class="mt-2 text-gray-600">Book a facility for your event</p>
                </div>
                <a href="{{ route('faculty.facilities.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-red-800 font-medium">There were some errors with your submission:</h3>
                    <ul class="mt-2 text-red-700 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('faculty.reservations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Event Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Event Information</h2>

                        <!-- Event Name -->
                        <div class="mb-6">
                            <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Event Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="event_name"
                                   id="event_name"
                                   value="{{ old('event_name') }}"
                                   x-model="formData.event_name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., Team Meeting, Workshop, Conference"
                                   required>
                        </div>

                        <!-- Participants -->
                        <div class="mb-6">
                            <label for="participants" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Participants
                            </label>
                            <input type="number"
                                   name="participants"
                                   id="participants"
                                   value="{{ old('participants') }}"
                                   x-model="formData.participants"
                                   min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Expected number of attendees">
                            <p class="mt-1 text-sm text-gray-500">Leave blank if unknown</p>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="4"
                                      x-model="formData.notes"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Any special requirements or notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Facility Selection -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Select Facility</h2>

                        <input type="hidden" name="facility_id" x-model="selectedFacilityId">

                        <!-- Search Facilities -->
                        <div class="mb-4">
                            <input type="text"
                                   x-model="facilitySearch"
                                   placeholder="Search facilities by name, type, or location..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Facility Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                            <template x-for="facility in filteredFacilities" :key="facility.id">
                                <div @click="selectFacility(facility)"
                                     :class="selectedFacilityId === facility.id ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200'"
                                     class="border-2 rounded-lg p-3 cursor-pointer hover:border-blue-300 transition">
                                    <div class="flex items-start space-x-3">
                                        <img :src="facility.image_url"
                                             :alt="facility.name"
                                             class="w-16 h-16 rounded object-cover">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 truncate" x-text="facility.name"></h3>
                                            <p class="text-xs text-gray-500" x-text="facility.type_label"></p>
                                            <!-- Hourly Rate Badge -->
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    <span x-text="'₱' + parseFloat(facility.hourly_rate || 0).toFixed(2) + '/hour'"></span>
                                                </span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-600">
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    <span x-text="facility.location"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <p x-show="!selectedFacilityId" class="mt-4 text-sm text-red-500">
                            * Please select a facility
                        </p>
                    </div>

                    <!-- Date & Time -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Date & Time</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Start Time -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local"
                                       name="start_time"
                                       id="start_time"
                                       x-model="formData.start_time"
                                       @change="checkAvailability()"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local"
                                       name="end_time"
                                       id="end_time"
                                       x-model="formData.end_time"
                                       @change="checkAvailability()"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <!-- Availability Status -->
                        <div x-show="availabilityChecked"
                             x-transition
                             class="p-4 rounded-lg mb-4"
                             :class="isAvailable ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                            <div class="flex items-center">
                                <svg x-show="isAvailable" class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <svg x-show="!isAvailable" class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span :class="isAvailable ? 'text-green-800' : 'text-red-800'"
                                      class="font-medium"
                                      x-text="availabilityMessage"></span>
                            </div>
                        </div>

                        <!-- Recurring Options -->
                        <div class="border-t pt-4">
                            <div class="flex items-center mb-4">
                                <input type="checkbox"
                                       name="is_recurring"
                                       id="is_recurring"
                                       value="1"
                                       x-model="formData.is_recurring"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_recurring" class="ml-2 text-sm font-medium text-gray-700">
                                    Make this a recurring reservation
                                </label>
                            </div>

                            <div x-show="formData.is_recurring" x-transition class="ml-6">
                                <label for="recurring_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Repeat
                                </label>
                                <select name="recurring_type"
                                        id="recurring_type"
                                        x-model="formData.recurring_type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="daily">Daily (5 occurrences)</option>
                                    <option value="weekly">Weekly (5 occurrences)</option>
                                    <option value="monthly">Monthly (5 occurrences)</option>
                                </select>
                                <p class="mt-2 text-sm text-gray-500">
                                    This will create up to 5 recurring reservations if time slots are available
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('faculty.facilities.index') }}"
                           class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                                :disabled="!canSubmit"
                                :class="canSubmit ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
                                class="px-6 py-2 text-white rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Reservation
                        </button>
                    </div>
                </div>

                <!-- Right Column - Selected Facility Info & Cost Preview -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Selected Facility Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Selected Facility</h3>

                        <div x-show="!selectedFacility" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No facility selected</p>
                        </div>

                        <div x-show="selectedFacility" x-transition>
                            <img :src="selectedFacility?.image_url"
                                 :alt="selectedFacility?.name"
                                 class="w-full h-40 object-cover rounded-lg mb-4">

                            <h4 class="font-semibold text-gray-900 mb-2" x-text="selectedFacility?.name"></h4>

                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span x-text="selectedFacility?.type_label"></span>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span x-text="selectedFacility?.location"></span>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span x-text="selectedFacility?.capacity_text"></span>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="selectedFacility?.hours_text"></span>
                                </div>

                                <div x-show="selectedFacility?.description" class="pt-2 border-t">
                                    <p class="text-gray-600 text-sm" x-text="selectedFacility?.description"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cost Preview Card -->
                    <div x-show="selectedFacility && formData.start_time && formData.end_time"
                         x-transition
                         class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Cost Preview
                        </h3>

                        <div class="space-y-3">
                            <!-- Hourly Rate -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Hourly Rate</span>
                                <span class="font-medium" x-text="'₱' + parseFloat(selectedFacility?.hourly_rate || 0).toFixed(2)"></span>
                            </div>

                            <!-- Duration -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Duration</span>
                                <span class="font-medium" x-text="estimatedHours.toFixed(1) + ' hour(s)'"></span>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-gray-200 my-2"></div>

                            <!-- Total Cost -->
                            <div class="flex justify-between">
                                <span class="text-gray-900 font-semibold">Total Cost</span>
                                <span class="text-xl font-bold text-green-600" x-text="'₱' + estimatedCost.toFixed(2)"></span>
                            </div>

                            <!-- Wallet Balance -->
                            <div class="flex justify-between text-sm pt-2 border-t">
                                <span class="text-gray-600">Your Balance</span>
                                <span class="font-medium">{{ auth()->user()->formatted_balance }}</span>
                            </div>

                            <!-- Balance After -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">After Booking</span>
                                <span class="font-medium"
                                      :class="hasSufficientBalance ? 'text-green-600' : 'text-red-600'"
                                      x-text="'₱' + (walletBalance - estimatedCost).toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Insufficient Balance Warning -->
                        <div x-show="!hasSufficientBalance && estimatedCost > 0"
                             x-transition
                             class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800">Insufficient Balance</p>
                                    <p class="text-xs text-red-600 mt-1">
                                        You need ₱<span x-text="(estimatedCost - walletBalance).toFixed(2)"></span> more to complete this booking.
                                    </p>
                                    <a href="{{ route('faculty.wallet.index') }}"
                                       class="inline-block mt-2 text-xs text-red-700 hover:text-red-800 underline">
                                        Top up your wallet
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Free Reservation Notice -->
                        <div x-show="estimatedCost === 0 && selectedFacility"
                             x-transition
                             class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium text-green-800">This facility is free to use!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function reservationForm() {
    return {
        facilities: @json($facilities),
        selectedFacilityId: {{ $selectedFacilityId ?? 'null' }},
        selectedFacility: null,
        facilitySearch: '',
        walletBalance: {{ auth()->user()->wallet_balance ?? 0 }},
        formData: {
            event_name: '',
            participants: '',
            notes: '',
            start_time: '',
            end_time: '',
            is_recurring: false,
            recurring_type: 'weekly'
        },
        availabilityChecked: false,
        isAvailable: false,
        availabilityMessage: '',
        checking: false,

        init() {
            // Set minimum datetime to now
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            const minDateTime = now.toISOString().slice(0, 16);
            
            document.getElementById('start_time').min = minDateTime;
            document.getElementById('end_time').min = minDateTime;

            // Watch for start_time changes
            this.$watch('formData.start_time', (value) => {
                if (value) {
                    // Update end_time minimum to match start_time
                    document.getElementById('end_time').min = value;
                    
                    // Auto-set end_time to start_time + 1 hour if:
                    // 1. End time is empty
                    // 2. End time is before or equal to start time
                    if (!this.formData.end_time || this.formData.end_time <= value) {
                        const startDate = new Date(value);
                        startDate.setHours(startDate.getHours() + 1);
                        this.formData.end_time = startDate.toISOString().slice(0, 16);
                    }
                    
                    this.checkAvailability();
                }
            });

            // Watch for end_time changes to validate
            this.$watch('formData.end_time', (value) => {
                if (value && this.formData.start_time && value <= this.formData.start_time) {
                    // If end time is before start time, adjust it
                    const startDate = new Date(this.formData.start_time);
                    startDate.setHours(startDate.getHours() + 1);
                    this.formData.end_time = startDate.toISOString().slice(0, 16);
                }
            });

            // Pre-select facility if ID provided
            if (this.selectedFacilityId) {
                const facility = this.facilities.find(f => f.id === this.selectedFacilityId);
                if (facility) {
                    this.selectFacility(facility);
                }
            }
        },

        get filteredFacilities() {
            if (!this.facilitySearch) return this.facilities;
            const search = this.facilitySearch.toLowerCase();
            return this.facilities.filter(f => 
                f.name.toLowerCase().includes(search) ||
                f.type_label.toLowerCase().includes(search) ||
                f.location.toLowerCase().includes(search)
            );
        },

        get estimatedHours() {
            if (!this.formData.start_time || !this.formData.end_time) return 0;
            const start = new Date(this.formData.start_time);
            const end = new Date(this.formData.end_time);
            const diffMs = end - start;
            const hours = diffMs / (1000 * 60 * 60);
            return hours > 0 ? hours : 0;
        },

        get estimatedCost() {
            if (!this.selectedFacility) return 0;
            const hourlyRate = parseFloat(this.selectedFacility.hourly_rate) || 0;
            return this.estimatedHours * hourlyRate;
        },

        get hasSufficientBalance() {
            return this.walletBalance >= this.estimatedCost;
        },

        selectFacility(facility) {
            this.selectedFacilityId = facility.id;
            this.selectedFacility = facility;
            this.checkAvailability();
        },

        async checkAvailability() {
            if (!this.selectedFacilityId || !this.formData.start_time || !this.formData.end_time) {
                this.availabilityChecked = false;
                return;
            }

            this.checking = true;

            try {
                const response = await fetch('/faculty/reservations/check-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        facility_id: this.selectedFacilityId,
                        start_time: this.formData.start_time,
                        end_time: this.formData.end_time
                    })
                });

                const data = await response.json();
                this.isAvailable = data.available;
                this.availabilityMessage = data.message;
                this.availabilityChecked = true;
            } catch (error) {
                console.error('Error checking availability:', error);
                this.availabilityMessage = 'Error checking availability';
                this.isAvailable = false;
                this.availabilityChecked = true;
            } finally {
                this.checking = false;
            }
        },

        get canSubmit() {
            return this.selectedFacilityId && 
                   this.formData.event_name && 
                   this.formData.start_time && 
                   this.formData.end_time && 
                   !this.checking && 
                   this.hasSufficientBalance && 
                   (!this.availabilityChecked || this.isAvailable);
        }
    }
}
</script>
@endsection