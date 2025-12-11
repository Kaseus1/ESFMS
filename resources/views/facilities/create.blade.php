    @extends('layouts.admin')

    @section('title', 'Create Facility')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create New Facility</h1>
                        <p class="mt-2 text-gray-600">Add a new facility to the system</p>
                    </div>
                    <a href="{{ route('admin.facilities.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Loading Overlay -->
            <div id="uploadingOverlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-8 text-center max-w-sm">
                    <svg class="animate-spin h-16 w-16 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Creating Facility...</h3>
                    <p class="text-sm text-gray-600">Please wait while we process your request</p>
                    <p id="uploadProgress" class="text-xs text-blue-600 mt-2"></p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data" id="facilityForm">
                    @csrf

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
                                value="{{ old('name') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="e.g., Conference Room A, Main Auditorium"
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
                                    placeholder="Brief description of the facility, amenities, and features...">{{ old('description') }}</textarea>
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
                                    <option value="classroom" {{ old('type') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                                    <option value="conference_room" {{ old('type') == 'conference_room' ? 'selected' : '' }}>Conference Room</option>
                                    <option value="auditorium" {{ old('type') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
                                    <option value="laboratory" {{ old('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                    <option value="sports_facility" {{ old('type') == 'sports_facility' ? 'selected' : '' }}>Sports Facility</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                    value="{{ old('location') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                                    placeholder="e.g., Building A, Floor 2"
                                    required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Facility Image
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="image" 
                                                name="image" 
                                                type="file" 
                                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                                class="sr-only"
                                                onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB (will be optimized)</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-4 hidden">
                                <div class="relative inline-block">
                                    <img src="" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                                    <div id="imageSizeInfo" class="mt-2 text-xs text-gray-600"></div>
                                </div>
                            </div>

                            <!-- Compression Status -->
                            <div id="compressionStatus" class="mt-2 hidden">
                                <div class="flex items-center text-sm text-blue-600">
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
                                    value="{{ old('capacity') }}"
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
                                    value="{{ old('max_capacity') }}"
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
                                    value="{{ old('opening_time', '08:00') }}"
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
                                    value="{{ old('closing_time', '18:00') }}"
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
                                    {{ old('is_public', true) ? 'checked' : '' }}
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
                        <a href="{{ route('admin.facilities.index') }}" 
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                id="submitBtn"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Facility
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>
    <script>
    let isCompressing = false;

    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');
        const sizeInfo = document.getElementById('imageSizeInfo');
        const compressionStatus = document.getElementById('compressionStatus');
        const submitBtn = document.getElementById('submitBtn');
        
        if (!file) {
            preview.classList.add('hidden');
            return;
        }

        // Show compression status
        compressionStatus.classList.remove('hidden');
        isCompressing = true;
        submitBtn.disabled = true;

        // Display original file size
        const originalSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        console.log(`Original image size: ${originalSizeMB}MB`);

        // Compress image
        new Compressor(file, {
            quality: 0.7,
            maxWidth: 1200,
            maxHeight: 1200,
            mimeType: 'image/jpeg',
            success(result) {
                // Create a new File object from the compressed blob
                const compressedFile = new File([result], file.name.replace(/\.\w+$/, '.jpg'), {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });
                
                // Update the file input with compressed file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                event.target.files = dataTransfer.files;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(result);
                
                // Show compression results
                const compressedSizeMB = (result.size / (1024 * 1024)).toFixed(2);
                const reduction = ((1 - result.size / file.size) * 100).toFixed(0);
                
                sizeInfo.innerHTML = `
                    <span class="text-green-600">✓ Optimized</span>: 
                    ${originalSizeMB}MB → ${compressedSizeMB}MB 
                    <span class="text-green-600">(${reduction}% smaller)</span>
                `;
                
                console.log(`Compressed size: ${compressedSizeMB}MB (${reduction}% reduction)`);
                
                // Hide compression status
                compressionStatus.classList.add('hidden');
                isCompressing = false;
                submitBtn.disabled = false;
            },
            error(err) {
                console.error('Compression error:', err.message);
                
                // Fallback to original file
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
                
                sizeInfo.innerHTML = `<span class="text-yellow-600">⚠ Using original (${originalSizeMB}MB)</span>`;
                
                // Hide compression status
                compressionStatus.classList.add('hidden');
                isCompressing = false;
                submitBtn.disabled = false;
            }
        });
    }

    // Form validation and submission
    document.getElementById('facilityForm').addEventListener('submit', function(e) {
        // Check if still compressing
        if (isCompressing) {
            e.preventDefault();
            alert('Please wait for image optimization to complete');
            return;
        }

        // Validate capacity
        const capacity = parseInt(document.getElementById('capacity').value) || 0;
        const maxCapacity = parseInt(document.getElementById('max_capacity').value) || 0;
        
        if (capacity && maxCapacity && maxCapacity < capacity) {
            e.preventDefault();
            alert('Maximum capacity must be greater than or equal to minimum capacity');
            document.getElementById('max_capacity').focus();
            return;
        }
        
        // Show loading overlay if image is being uploaded
        const fileInput = document.getElementById('image');
        if (fileInput.files.length > 0) {
            document.getElementById('uploadingOverlay').classList.remove('hidden');
            document.getElementById('uploadProgress').textContent = 'Uploading optimized image...';
        }
    });
    </script>
    @endpush
    @endsection