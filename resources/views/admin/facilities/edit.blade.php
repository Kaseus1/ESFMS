@extends('layouts.admin')

@section('title', 'Edit Facility - ' . ($facility->name ?? 'Unknown'))

@section('content')
<div class="mb-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
           
        </div>
        <a href="{{ route('admin.facilities.show', $facility) }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-[#333C4D] border-opacity-20 text-[#333C4D] text-sm font-semibold rounded-lg shadow hover:bg-[#F8F9FA] transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Details
        </a>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" enctype="multipart/form-data" class="bg-white shadow rounded-lg">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                    <h3 class="text-lg font-semibold text-[#172030]">Facility Information</h3>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#333C4D] mb-2">Facility Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $facility->name) }}"
                               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                    </div>

                    {{-- Type & Location --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-[#333C4D] mb-2">Type <span class="text-red-500">*</span></label>
                            <select name="type" id="type" class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                                <option value="">Select Type</option>
                                @foreach(['sports_facility', 'meeting_room', 'conference_hall', 'conference_room', 'office_space', 'classroom', 'training_room', 'auditorium', 'laboratory', 'other'] as $type)
                                    <option value="{{ $type }}" {{ old('type', $facility->type) == $type ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-[#333C4D] mb-2">Location <span class="text-red-500">*</span></label>
                            <input type="text" name="location" id="location" value="{{ old('location', $facility->location) }}"
                                   class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                        </div>
                    </div>

                    {{-- Hourly Rate --}}
                    <div>
                        <label for="hourly_rate" class="block text-sm font-medium text-[#333C4D] mb-2">Hourly Rate <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#333C4D] font-medium">₱</span>
                            <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $facility->hourly_rate ?? 0) }}" min="0" step="0.01"
                                   class="w-full pl-10 pr-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                        </div>
                    </div>

                    {{-- Capacity --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-[#333C4D] mb-2">Capacity <span class="text-red-500">*</span></label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $facility->capacity) }}" min="1"
                                   class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                        </div>
                        <div>
                            <label for="max_capacity" class="block text-sm font-medium text-[#333C4D] mb-2">Max Capacity</label>
                            <input type="number" name="max_capacity" id="max_capacity" value="{{ old('max_capacity', $facility->max_capacity) }}" min="1"
                                   class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]">
                        </div>
                    </div>

                    {{-- Hours --}}
                   <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="opening_time" class="block text-sm font-medium text-[#333C4D] mb-2">Opening Time <span class="text-red-500">*</span></label>
        <input type="time" name="opening_time" id="opening_time" 
               value="{{ old('opening_time', \Carbon\Carbon::parse($facility->opening_time)->format('H:i')) }}"
               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
    </div>
    <div>
        <label for="closing_time" class="block text-sm font-medium text-[#333C4D] mb-2">Closing Time <span class="text-red-500">*</span></label>
        <input type="time" name="closing_time" id="closing_time" 
               value="{{ old('closing_time', \Carbon\Carbon::parse($facility->closing_time)->format('H:i')) }}"
               class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
    </div>
</div>

                    {{-- Buffer, Status, Public --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="buffer_time" class="block text-sm font-medium text-[#333C4D] mb-2">Buffer Time (min) <span class="text-red-500">*</span></label>
                            <input type="number" name="buffer_time" id="buffer_time" value="{{ old('buffer_time', $facility->buffer_time ?? 0) }}" min="0"
                                   class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-[#333C4D] mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                                <option value="1" {{ old('status', $facility->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $facility->status) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label for="is_public" class="block text-sm font-medium text-[#333C4D] mb-2">Visibility <span class="text-red-500">*</span></label>
                            <select name="is_public" id="is_public" class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]" required>
                                <option value="1" {{ old('is_public', $facility->is_public) == 1 ? 'selected' : '' }}>Public</option>
                                <option value="0" {{ old('is_public', $facility->is_public) == 0 ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-[#333C4D] mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                                  placeholder="Enter facility description...">{{ old('description', $facility->description) }}</textarea>
                    </div>

                    {{-- Rules --}}
                    <div>
                        <label for="rules" class="block text-sm font-medium text-[#333C4D] mb-2">Rules</label>
                        <textarea name="rules" id="rules" rows="2"
                                  class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                                  placeholder="Enter facility rules...">{{ old('rules', $facility->rules) }}</textarea>
                    </div>

                    {{-- Image --}}
                    @if($facility->image)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-2">Current Image</label>
                            <img src="{{ Storage::disk('public')->url($facility->image) }}" alt="{{ $facility->name }}" class="h-32 w-32 object-cover rounded-lg border border-[#333C4D] border-opacity-20">
                        </div>
                    @endif
                    <div>
                        <label for="image" class="block text-sm font-medium text-[#333C4D] mb-2">Upload New Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full text-sm text-[#333C4D] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#002366] file:text-white hover:file:bg-[#00285C]">
                        <p class="mt-1 text-xs text-[#333C4D] opacity-60">JPEG, PNG, JPG, GIF, WebP - Max 5MB</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="px-6 py-4 bg-[#F8F9FA] border-t border-[#333C4D] border-opacity-20 flex justify-end gap-4">
                    <a href="{{ route('admin.facilities.show', $facility) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-[#333C4D] border-opacity-20 text-[#172030] text-sm font-semibold rounded-lg hover:bg-[#F8F9FA] transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Facility
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-[#002366] text-white text-sm font-semibold rounded-lg shadow hover:bg-[#00285C] transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Update Facility
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Facility Info --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                    <h3 class="text-lg font-semibold text-[#172030]">Quick Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-sm font-medium text-[#333C4D]">ID:</span>
                        <p class="text-sm text-[#172030]">#{{ $facility->id }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-[#333C4D]">Created:</span>
                        <p class="text-sm text-[#172030]">{{ $facility->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-[#333C4D]">Last Updated:</span>
                        <p class="text-sm text-[#172030]">{{ $facility->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-[#333C4D]">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $facility->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $facility->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-[#333C4D]">Type:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#002366] bg-opacity-10 text-[#002366]">
                            {{ ucwords(str_replace('_', ' ', $facility->type)) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white shadow rounded-lg border-t-4 border-red-500">
                <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-20">
                    <h3 class="text-lg font-semibold text-red-700">Danger Zone</h3>
                    <p class="text-sm text-[#333C4D] opacity-60">These actions cannot be undone</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this facility?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Facility
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection