@extends('layouts.student')

@section('title', 'Create Facility')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="facilityForm()" x-init="init()">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold" style="color: var(--color-text-dark);">Create New Facility</h1>
                    <p class="mt-2" style="color: var(--color-text-light);">Add a new facility to the system</p>
                </div>
                <a href="{{ route('.facilities.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg transition"
                   style="background-color: #E5E7EB; color: var(--color-text-dark);"
                   onmouseover="this.style.backgroundColor='#D1D5DB'"
                   onmouseout="this.style.backgroundColor='#E5E7EB'">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="mb-6 border-l-4 rounded relative" role="alert" style="background-color: #D1FAE5; border-color: #10B981; color: #065F46;">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 border-l-4 rounded relative" role="alert" style="background-color: #FEE2E2; border-color: #EF4444; color: #991B1B;">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Loading Overlay -->
        <div x-show="isSubmitting" x-cloak class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-8 text-center max-w-sm shadow-2xl">
                <svg class="animate-spin h-16 w-16 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 class="text-lg font-semibold mb-2" style="color: var(--color-text-dark);">Creating Facility...</h3>
                <p class="text-sm" style="color: var(--color-text-light);">Please wait while we process your request</p>
                <p x-show="uploadProgress" x-text="uploadProgress" class="text-xs mt-2" style="color: var(--color-royal-blue);"></p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('.facilities.store') }}" method="POST" enctype="multipart/form-data" @submit="handleSubmit">
                @csrf

                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 pb-2 border-b flex items-center" style="color: var(--color-text-dark); border-color: #D1D5DB;">
                        <svg class="w-6 h-6 mr-2" style="color: var(--color-royal-blue);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Basic Information
                    </h2>
                    
                    <!-- Facility Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                            Facility Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               x-model="formData.name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2 border rounded-lg text-base @error('name') border-red-500 @enderror"
                               style="border-color: #D1D5DB; color: var(--color-text-dark);"
                               onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                               onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                               placeholder="e.g., Study Room A, Conference Room B"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  x-model="formData.description"
                                  rows="4"
                                  maxlength="1000"
                                  class="w-full px-4 py-2 border rounded-lg text-base @error('description') border-red-500 @enderror"
                                  style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                  onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                  onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                  placeholder="Brief description of the facility, amenities, and features...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-sm" style="color: var(--color-text-light);">Maximum 1000 characters</p>
                            <p class="text-sm" style="color: var(--color-text-light);" x-show="formData.description">
                                <span x-text="formData.description.length"></span>/1000
                            </p>
                        </div>
                    </div>

                    <!-- Type and Location Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                Facility Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" 
                                    id="type"
                                    x-model="formData.type"
                                    class="w-full px-4 py-2 border rounded-lg text-base @error('type') border-red-500 @enderror"
                                    style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                    onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                    onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                    required>
                                <option value="">Select Type</option>
                                <option value="study_room" {{ old('type') == 'study_room' ? 'selected' : '' }}>Study Room</option>
                                <option value="meeting_room" {{ old('type') == 'meeting_room' ? 'selected' : '' }}>Meeting Room</option>
                                <option value="computer_lab" {{ old('type') == 'computer_lab' ? 'selected' : '' }}>Computer Lab</option>
                                <option value="group_study" {{ old('type') == 'group_study' ? 'selected' : '' }}>Group Study Room</option>
                                <option value="library_space" {{ old('type') == 'library_space' ? 'selected' : '' }}>Library Space</option>
                                <option value="student_center" {{ old('type') == 'student_center' ? 'selected' : '' }}>Student Center</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="location" 
                                   id="location"
                                   x-model="formData.location"
                                   value="{{ old('location') }}"
                                   class="w-full px-4 py-2 border rounded-lg text-base @error('location') border-red-500 @enderror"
                                   style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                   onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                   onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                   placeholder="e.g., Student Center, Floor 2, Room 205"
                                   required>
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-6">
                        <label for="image" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                            Facility Image
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg hover:transition"
                             style="border-color: #D1D5DB;"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="handleDrop"
                             :class="{ 'border-blue-400 bg-blue-50': isDragging }"
                             onmouseover="if(!isDragging) this.style.borderColor='var(--color-royal-blue)'; this.style.backgroundColor='rgba(0, 35, 102, 0.02)';"
                             onmouseout="if(!isDragging) this.style.borderColor='#D1D5DB'; this.style.backgroundColor='transparent';">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12" style="color: var(--color-text-light);" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm" style="color: var(--color-text-light);">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium transition-all"
                                           style="color: var(--color-royal-blue);"
                                           onmouseover="this.style.textDecoration='underline'"
                                           onmouseout="this.style.textDecoration='none'">
                                        <span>Upload a file</span>
                                        <input id="image" 
                                               name="image" 
                                               type="file" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif"
                                               class="sr-only"
                                               @change="handleImageSelect">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs" style="color: var(--color-text-light);">PNG, JPG, GIF up to 5MB (will be optimized automatically)</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div x-show="imagePreview" x-cloak class="mt-4">
                            <div class="relative inline-block">
                                <img :src="imagePreview" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                                <button type="button" 
                                        @click="clearImage"
                                        class="absolute -top-2 -right-2 text-white rounded-full p-1 hover:opacity-80 transition"
                                        style="background-color: #EF4444;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="imageSizeInfo" x-html="imageSizeInfo" class="mt-2 text-sm"></div>
                        </div>

                        <!-- Compression Status -->
                        <div x-show="isCompressing" x-cloak class="mt-2">
                            <div class="flex items-center text-sm" style="color: var(--color-royal-blue);">
                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Optimizing image...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Capacity & Hours -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 pb-2 border-b flex items-center" style="color: var(--color-text-dark); border-color: #D1D5DB;">
                        <svg class="w-6 h-6 mr-2" style="color: var(--color-royal-blue);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Capacity & Operating Hours
                    </h2>
                    
                    <!-- Capacity Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="capacity" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                Minimum Capacity
                            </label>
                            <input type="number" 
                                   name="capacity" 
                                   id="capacity"
                                   x-model.number="formData.capacity"
                                   value="{{ old('capacity') }}"
                                   min="1"
                                   class="w-full px-4 py-2 border rounded-lg text-base @error('capacity') border-red-500 @enderror"
                                   style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                   onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                   onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                   placeholder="e.g., 1">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_capacity" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                Maximum Capacity
                            </label>
                            <input type="number" 
                                   name="max_capacity" 
                                   id="max_capacity"
                                   x-model.number="formData.maxCapacity"
                                   value="{{ old('max_capacity') }}"
                                   min="1"
                                   class="w-full px-4 py-2 border rounded-lg text-base @error('max_capacity') border-red-500 @enderror"
                                   style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                   onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                   onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                   placeholder="e.g., 8">
                            @error('max_capacity')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p x-show="capacityError" x-text="capacityError" class="mt-1 text-sm text-red-500"></p>
                        </div>
                    </div>

                    <!-- Operating Hours Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="opening_time" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                Opening Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="opening_time" 
                                   id="opening_time"
                                   x-model="formData.openingTime"
                                   value="{{ old('opening_time', '07:00') }}"
                                   class="w-full px-4 py-2 border rounded-lg text-base @error('opening_time') border-red-500 @enderror"
                                   style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                   onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                   onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                   required>
                            @error('opening_time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="closing_time" class="block text-sm font-medium mb-2" style="color: var(--color-text-dark);">
                                Closing Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="closing_time" 
                                   id="closing_time"
                                   x-model="formData.closingTime"
                                   value="{{ old('closing_time', '22:00') }}"
                                   class="w-full px-4 py-2 border rounded-lg text-base @error('closing_time') border-red-500 @enderror"
                                   style="border-color: #D1D5DB; color: var(--color-text-dark);"
                                   onfocus="this.style.borderColor='var(--color-royal-blue)'; this.style.boxShadow='0 0 0 2px rgba(0, 35, 102, 0.1)';"
                                   onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                                   required>
                            @error('closing_time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p x-show="hoursError" x-text="hoursError" class="mt-1 text-sm text-red-500"></p>
                        </div>
                    </div>
                </div>

                <!-- Availability -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 pb-2 border-b flex items-center" style="color: var(--color-text-dark); border-color: #D1D5DB;">
                        <svg class="w-6 h-6 mr-2" style="color: var(--color-royal-blue);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Availability
                    </h2>
                    
                    <div class="flex items-start p-4 rounded-lg" style="background-color: var(--color-off-white);">
                        <div class="flex items-center h-5">
                            <input id="is_public" 
                                   name="is_public" 
                                   type="checkbox" 
                                   value="1"
                                   x-model="formData.isPublic"
                                   {{ old('is_public', true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded focus:ring-2"
                                   style="border-color: #D1D5DB; accent-color: var(--color-royal-blue);">
                        </div>
                        <div class="ml-3">
                            <label for="is_public" class="font-medium" style="color: var(--color-text-dark);">Public Facility</label>
                            <p class="text-sm" style="color: var(--color-text-light);">Make this facility available for public booking</p>
                        </div>
                    </div>
                    @error('is_public')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t" style="border-color: #D1D5DB;">
                    <a href="{{ route('.facilities.index') }}" 
                       class="px-6 py-2 rounded-lg transition"
                       style="background-color: #E5E7EB; color: var(--color-text-dark);"
                       onmouseover="this.style.backgroundColor='#D1D5DB'"
                       onmouseout="this.style.backgroundColor='#E5E7EB'">
                        Cancel
                    </a>
                    <button type="submit" 
                            :disabled="isSubmitting || isCompressing"
                            :class="{ 'opacity-50 cursor-not-allowed': isSubmitting || isCompressing }"
                            class="px-6 py-2 text-white rounded-lg transition flex items-center"
                            style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));"
                            onmouseover="if(!isSubmitting && !isCompressing) { this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0, 35, 102, 0.3)'; }"
                            onmouseout="if(!isSubmitting && !isCompressing) { this.style.transform='translateY(0)'; this.style.boxShadow='none'; }">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="isSubmitting ? 'Creating...' : 'Create Facility'"></span>
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

    [x-cloak] { 
        display: none !important; 
    }
</style>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>
<script>
function facilityForm() {
    return {
        formData: {
            name: '',
            description: '',
            type: '',
            location: '',
            capacity: null,
            maxCapacity: null,
            openingTime: '07:00',
            closingTime: '22:00',
            isPublic: true
        },
        imagePreview: null,
        imageSizeInfo: '',
        isCompressing: false,
        isSubmitting: false,
        uploadProgress: '',
        isDragging: false,
        capacityError: '',
        hoursError: '',
        
        init() {
            this.$watch('formData.capacity', (value) => this.validateCapacity());
            this.$watch('formData.maxCapacity', (value) => this.validateCapacity());
            this.$watch('formData.openingTime', () => this.validateHours());
            this.$watch('formData.closingTime', () => this.validateHours());
        },
        
        validateCapacity() {
            const min = parseInt(this.formData.capacity) || 0;
            const max = parseInt(this.formData.maxCapacity) || 0;
            
            if (min && max && max < min) {
                this.capacityError = 'Maximum capacity must be greater than or equal to minimum capacity';
                return false;
            }
            this.capacityError = '';
            return true;
        },
        
        validateHours() {
            if (this.formData.openingTime && this.formData.closingTime) {
                if (this.formData.closingTime <= this.formData.openingTime) {
                    this.hoursError = 'Closing time must be after opening time';
                    return false;
                }
            }
            this.hoursError = '';
            return true;
        },
        
        handleImageSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.compressImage(file);
            }
        },
        
        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                document.getElementById('image').files = event.dataTransfer.files;
                this.compressImage(file);
            }
        },
        
        async compressImage(file) {
            this.isCompressing = true;
            const originalSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            
            try {
                const result = await new Promise((resolve, reject) => {
                    new Compressor(file, {
                        quality: 0.7,
                        maxWidth: 1200,
                        maxHeight: 1200,
                        mimeType: 'image/jpeg',
                        success: resolve,
                        error: reject
                    });
                });
                
                // Create compressed file
                const compressedFile = new File([result], file.name.replace(/\.\w+$/, '.jpg'), {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });
                
                // Update file input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                document.getElementById('image').files = dataTransfer.files;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(result);
                
                // Show compression info
                const compressedSizeMB = (result.size / (1024 * 1024)).toFixed(2);
                const reduction = ((1 - result.size / file.size) * 100).toFixed(0);
                
                this.imageSizeInfo = `
                    <span class="font-medium" style="color: #10B981;">✓ Optimized:</span> 
                    ${originalSizeMB}MB → ${compressedSizeMB}MB 
                    <span style="color: #10B981;">(${reduction}% smaller)</span>
                `;
                
            } catch (error) {
                console.error('Compression error:', error);
                
                // Fallback to original
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
                
                this.imageSizeInfo = `<span style="color: #F59E0B;">⚠ Using original (${originalSizeMB}MB)</span>`;
            } finally {
                this.isCompressing = false;
            }
        },
        
        clearImage() {
            this.imagePreview = null;
            this.imageSizeInfo = '';
            document.getElementById('image').value = '';
        },
        
        handleSubmit(event) {
            // Prevent if still compressing
            if (this.isCompressing) {
                event.preventDefault();
                alert('Please wait for image optimization to complete');
                return;
            }
            
            // Validate capacity
            if (!this.validateCapacity()) {
                event.preventDefault();
                alert('Please fix the capacity validation errors');
                return;
            }
            
            // Validate hours
            if (!this.validateHours()) {
                event.preventDefault();
                alert('Please fix the operating hours validation errors');
                return;
            }
            
            // Show loading
            this.isSubmitting = true;
            const fileInput = document.getElementById('image');
            if (fileInput.files.length > 0) {
                this.uploadProgress = 'Uploading optimized image...';
            }
        }
    }
}
</script>
@endpush
@endsection