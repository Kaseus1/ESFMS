@extends('layouts.admin')

@section('title', 'Create Facility')

@section('content')
<div x-data="facilityForm()" x-init="init()" class="mb-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#172030]">Create New Facility</h1>
            <p class="mt-1 text-sm text-[#333C4D]">Add a new facility to the system</p>
        </div>
        <a href="{{ route('admin.facilities.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-[#333C4D] border-opacity-20 text-[#333C4D] text-sm font-semibold rounded-lg shadow hover:bg-[#F8F9FA] transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div x-show="isSubmitting" x-cloak class="fixed inset-0 bg-[#172030] bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 text-center max-w-sm shadow-2xl">
            <svg class="animate-spin h-16 w-16 text-[#002366] mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-[#172030] mb-2">Creating Facility...</h3>
            <p class="text-sm text-[#333C4D]">Please wait while we process your request</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data" @submit="handleSubmit">
            @csrf

            {{-- Basic Information --}}
            <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                <h3 class="text-lg font-semibold text-[#172030] flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Basic Information
                </h3>
            </div>
            <div class="p-6 space-y-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-[#333C4D] mb-2">Facility Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" x-model="formData.name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366] @error('name') border-red-500 @enderror"
                           placeholder="e.g., Conference Room A" required>
                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Type & Location --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-[#333C4D] mb-2">Facility Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type" x-model="formData.type"
                                class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                            <option value="">Select Type</option>
                            <option value="classroom" {{ old('type') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                            <option value="conference_room" {{ old('type') == 'conference_room' ? 'selected' : '' }}>Conference Room</option>
                            <option value="auditorium" {{ old('type') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
                            <option value="laboratory" {{ old('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                            <option value="sports_facility" {{ old('type') == 'sports_facility' ? 'selected' : '' }}>Sports Facility</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-[#333C4D] mb-2">Location <span class="text-red-500">*</span></label>
                        <input type="text" name="location" id="location" x-model="formData.location" value="{{ old('location') }}"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                               placeholder="e.g., Building A, Floor 2" required>
                        @error('location')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Hourly Rate --}}
                <div>
                    <label for="hourly_rate" class="block text-sm font-medium text-[#333C4D] mb-2">Hourly Rate <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#333C4D] font-medium">₱</span>
                        <input type="number" name="hourly_rate" id="hourly_rate" x-model.number="formData.hourlyRate" value="{{ old('hourly_rate', 0) }}" min="0" step="0.01"
                               class="w-full pl-10 pr-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                               placeholder="0.00" required>
                    </div>
                    <p class="mt-1 text-xs text-[#333C4D] opacity-60">Cost per hour for booking this facility</p>
                    @error('hourly_rate')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-[#333C4D] mb-2">Description</label>
                    <textarea name="description" id="description" x-model="formData.description" rows="3" maxlength="1000"
                              class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                              placeholder="Brief description of the facility...">{{ old('description') }}</textarea>
                    <p class="mt-1 text-xs text-[#333C4D] opacity-60"><span x-text="formData.description?.length || 0"></span>/1000 characters</p>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-medium text-[#333C4D] mb-2">Facility Image</label>
                    <div class="border-2 border-dashed border-[#333C4D] border-opacity-20 rounded-lg p-6 text-center hover:border-[#002366] transition"
                         :class="{ 'border-[#002366] bg-[#002366] bg-opacity-5': isDragging }"
                         @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop">
                        <svg class="mx-auto h-12 w-12 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="mt-4">
                            <label for="image" class="cursor-pointer text-[#002366] font-medium hover:underline">Upload a file</label>
                            <input id="image" name="image" type="file" accept="image/*" class="sr-only" @change="handleImageSelect">
                            <span class="text-[#333C4D]"> or drag and drop</span>
                        </div>
                        <p class="text-xs text-[#333C4D] opacity-60 mt-2">PNG, JPG, GIF up to 5MB</p>
                    </div>
                    
                    <div x-show="imagePreview" x-cloak class="mt-4 relative inline-block">
                        <img :src="imagePreview" class="max-h-48 rounded-lg shadow-md">
                        <button type="button" @click="clearImage" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div x-show="isCompressing" x-cloak class="mt-2 text-sm text-[#002366]">
                        <svg class="animate-spin inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Optimizing image...
                    </div>
                </div>
            </div>

            {{-- Capacity & Hours --}}
            <div class="px-6 py-4 border-t border-b border-[#333C4D] border-opacity-20 bg-[#F8F9FA]">
                <h3 class="text-lg font-semibold text-[#172030] flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Capacity & Operating Hours
                </h3>
            </div>
            <div class="p-6 space-y-6">
                {{-- Capacity --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-[#333C4D] mb-2">Minimum Capacity</label>
                        <input type="number" name="capacity" id="capacity" x-model.number="formData.capacity" value="{{ old('capacity') }}" min="1"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                               placeholder="e.g., 10">
                        @error('capacity')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="max_capacity" class="block text-sm font-medium text-[#333C4D] mb-2">Maximum Capacity</label>
                        <input type="number" name="max_capacity" id="max_capacity" x-model.number="formData.maxCapacity" value="{{ old('max_capacity') }}" min="1"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                               placeholder="e.g., 50">
                        @error('max_capacity')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        <p x-show="capacityError" x-text="capacityError" class="mt-1 text-sm text-red-500"></p>
                    </div>
                </div>

                {{-- Operating Hours --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="opening_time" class="block text-sm font-medium text-[#333C4D] mb-2">Opening Time <span class="text-red-500">*</span></label>
                        <input type="time" name="opening_time" id="opening_time" x-model="formData.openingTime" value="{{ old('opening_time', '08:00') }}"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                        @error('opening_time')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="closing_time" class="block text-sm font-medium text-[#333C4D] mb-2">Closing Time <span class="text-red-500">*</span></label>
                        <input type="time" name="closing_time" id="closing_time" x-model="formData.closingTime" value="{{ old('closing_time', '18:00') }}"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                        @error('closing_time')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        <p x-show="hoursError" x-text="hoursError" class="mt-1 text-sm text-red-500"></p>
                    </div>
                </div>

                {{-- Public Toggle --}}
                <div class="flex items-start p-4 bg-[#F8F9FA] rounded-lg border border-[#333C4D] border-opacity-20">
                    <div class="flex items-center h-5">
                        <input id="is_public" name="is_public" type="checkbox" value="1" x-model="formData.isPublic" {{ old('is_public', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-[#002366] border-[#333C4D] border-opacity-20 rounded focus:ring-[#002366]">
                    </div>
                    <div class="ml-3">
                        <label for="is_public" class="font-medium text-[#172030]">Public Facility</label>
                        <p class="text-sm text-[#333C4D] opacity-60">Make this facility available for public booking</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 bg-[#F8F9FA] border-t border-[#333C4D] border-opacity-20 flex justify-end gap-4">
                <a href="{{ route('admin.facilities.index') }}" 
                   class="px-6 py-2 bg-white border border-[#333C4D] border-opacity-20 text-[#172030] text-sm font-semibold rounded-lg hover:bg-[#F8F9FA] transition">
                    Cancel
                </a>
                <button type="submit" :disabled="isSubmitting || isCompressing" :class="{ 'opacity-50 cursor-not-allowed': isSubmitting || isCompressing }"
                        class="inline-flex items-center px-6 py-2 bg-[#002366] text-white text-sm font-semibold rounded-lg shadow hover:bg-[#00285C] transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="isSubmitting ? 'Creating...' : 'Create Facility'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>
<script>
function facilityForm() {
    return {
        formData: { name: '', description: '', type: '', location: '', capacity: null, maxCapacity: null, openingTime: '08:00', closingTime: '18:00', isPublic: true, hourlyRate: 0 },
        imagePreview: null, isCompressing: false, isSubmitting: false, isDragging: false, capacityError: '', hoursError: '',
        
        init() {
            this.$watch('formData.capacity', () => this.validateCapacity());
            this.$watch('formData.maxCapacity', () => this.validateCapacity());
            this.$watch('formData.openingTime', () => this.validateHours());
            this.$watch('formData.closingTime', () => this.validateHours());
        },
        
        validateCapacity() {
            const min = parseInt(this.formData.capacity) || 0, max = parseInt(this.formData.maxCapacity) || 0;
            this.capacityError = (min && max && max < min) ? 'Maximum capacity must be ≥ minimum capacity' : '';
            return !this.capacityError;
        },
        
        validateHours() {
            this.hoursError = (this.formData.openingTime && this.formData.closingTime && this.formData.closingTime <= this.formData.openingTime) ? 'Closing time must be after opening time' : '';
            return !this.hoursError;
        },
        
        handleImageSelect(e) { if (e.target.files[0]) this.compressImage(e.target.files[0]); },
        handleDrop(e) { this.isDragging = false; const f = e.dataTransfer.files[0]; if (f?.type.startsWith('image/')) { document.getElementById('image').files = e.dataTransfer.files; this.compressImage(f); } },
        
        async compressImage(file) {
            this.isCompressing = true;
            try {
                const result = await new Promise((res, rej) => new Compressor(file, { quality: 0.7, maxWidth: 1200, maxHeight: 1200, mimeType: 'image/jpeg', success: res, error: rej }));
                const dt = new DataTransfer(); dt.items.add(new File([result], file.name.replace(/\.\w+$/, '.jpg'), { type: 'image/jpeg' }));
                document.getElementById('image').files = dt.files;
                const reader = new FileReader(); reader.onload = (e) => this.imagePreview = e.target.result; reader.readAsDataURL(result);
            } catch { const reader = new FileReader(); reader.onload = (e) => this.imagePreview = e.target.result; reader.readAsDataURL(file); }
            this.isCompressing = false;
        },
        
        clearImage() { this.imagePreview = null; document.getElementById('image').value = ''; },
        
        handleSubmit(e) {
            if (this.isCompressing) { e.preventDefault(); alert('Please wait for image optimization'); return; }
            if (!this.validateCapacity() || !this.validateHours()) { e.preventDefault(); return; }
            this.isSubmitting = true;
        }
    }
}
</script>
@endpush

<style>[x-cloak] { display: none !important; }</style>
@endsection