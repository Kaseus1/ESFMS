@extends('layouts.student')

@section('title', 'Edit Facility')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Facility</h1>
                    <p class="mt-2 text-gray-600">Update facility information</p>
                </div>
                <a href="{{ route('facilities.show', $facility) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('facilities.update', $facility) }}" method="POST" enctype="multipart/form-data" id="facilityForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Basic Information</h2>
                    
                    <!-- Facility Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Facility Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $facility->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of the facility...">{{ old('description', $facility->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                    </div>

                    <!-- Type and Location Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Facility Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" 
                                    id="type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror"
                                    required>
                                <option value="">Select Type</option>
                                <option value="classroom" {{ old('type', $facility->type) == 'classroom' ? 'selected' : '' }}>Classroom</option>
                                <option value="conference_room" {{ old('type', $facility->type) == 'conference_room' ? 'selected' : '' }}>Conference Room</option>
                                <option value="auditorium" {{ old('type', $facility->type) == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
                                <option value="laboratory" {{ old('type', $facility->type) == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                <option value="sports_facility" {{ old('type', $facility->type) == 'sports_facility' ? 'selected' : '' }}>Sports Facility</option>
                                <option value="other" {{ old('type', $facility->type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="location" 
                                   id="location"
                                   value="{{ old('location', $facility->location) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                                   required>
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Image & Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Facility Image
                        </label>
                        
                        @if($facility->image)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                <img src="{{ $facility->getImageUrl() }}" 
                                     alt="{{ $facility->name }}" 
                                     class="max-h-48 rounded-lg shadow-md">
                            </div>
                        @endif
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span>{{ $facility->image ? 'Upload new image' : 'Upload a file' }}</span>
                                        <input id="image" 
                                               name="image" 
                                               type="file" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif"
                                               class="sr-only"
                                               onchange="previewImage(event)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        
                        <!-- New Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                            <img src="" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                        </div>
                    </div>
                </div>

                <!-- Capacity & Hours -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Capacity & Operating Hours</h2>
                    
                    <!-- Capacity Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Minimum Capacity
                            </label>
                            <input type="number" 
                                   name="capacity" 
                                   id="capacity"
                                   value="{{ old('capacity', $facility->capacity) }}"
                                   min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacity') border-red-500 @enderror"
                                   placeholder="e.g., 10">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Maximum Capacity
                            </label>
                            <input type="number" 
                                   name="max_capacity" 
                                   id="max_capacity"
                                   value="{{ old('max_capacity', $facility->max_capacity) }}"
                                   min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_capacity') border-red-500 @enderror"
                                   placeholder="e.g., 50">
                            @error('max_capacity')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Operating Hours Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="opening_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Opening Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="opening_time" 
                                   id="opening_time"
                                   value="{{ old('opening_time', $facility->opening_time ? \Carbon\Carbon::parse($facility->opening_time)->format('H:i') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('opening_time') border-red-500 @enderror"
                                   required>
                            @error('opening_time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="closing_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Closing Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="closing_time" 
                                   id="closing_time"
                                   value="{{ old('closing_time', $facility->closing_time ? \Carbon\Carbon::parse($facility->closing_time)->format('H:i') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('closing_time') border-red-500 @enderror"
                                   required>
                            @error('closing_time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Availability -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 pb-2 border-b">Availability</h2>
                    
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_public" 
                                   name="is_public" 
                                   type="checkbox" 
                                   value="1"
                                   {{ old('is_public', $facility->is_public) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </div>
                        <div class="ml-3">
                            <label for="is_public" class="font-medium text-gray-700">Public Facility</label>
                            <p class="text-sm text-gray-500">Make this facility available for public booking</p>
                        </div>
                    </div>
                    @error('is_public')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('facilities.show', $facility) }}" 
                       class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Facility
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

// Validate capacity
document.getElementById('facilityForm').addEventListener('submit', function(e) {
    const capacity = parseInt(document.getElementById('capacity').value) || 0;
    const maxCapacity = parseInt(document.getElementById('max_capacity').value) || 0;
    
    if (capacity && maxCapacity && maxCapacity < capacity) {
        e.preventDefault();
        alert('Maximum capacity must be greater than or equal to minimum capacity');
        document.getElementById('max_capacity').focus();
    }
});
</script>
@endpush
@endsection