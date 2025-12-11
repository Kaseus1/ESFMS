@extends('layouts.admin')
@section('title', 'Edit Facility - ' . $facility->name)
@section('content')
<div class="container mx-auto px-4 py-8">
<!-- Header -->
<div class="flex justify-between items-center mb-6">
<div class="flex items-center space-x-4">
<a href="{{ route('admin.facilities.index') }}" 
class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center">
<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
</svg>
Back to Facilities
</a>
<a href="{{ route('admin.facilities.show', $facility) }}" 
class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
</svg>
View Facility
</a>
<div>
<h1 class="text-3xl font-bold text-gray-900">Edit Facility</h1>
<p class="text-gray-600 mt-1">{{ $facility->name }}</p>
</div>
</div>
</div>
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 flex items-center">
<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
</svg>
{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 flex items-center">
<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
</svg>
{{ session('error') }}
</div>
@endif
<!-- Validation Errors -->
@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
<h4 class="font-semibold mb-2">Please correct the following errors:</h4>
<ul class="list-disc list-inside space-y-1">
@foreach($errors->all() as $error)
<li class="text-sm">{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
<form method="POST" action="{{ route('admin.facilities.update', $facility) }}" enctype="multipart/form-data">
@csrf
@method('PUT')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Main Form -->
<div class="lg:col-span-2 space-y-6">
<!-- Basic Information -->
<div class="bg-white rounded-lg shadow-lg p-6">
<h2 class="text-xl font-bold text-gray-900 mb-6">Basic Information</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="md:col-span-2">
<label for="name" class="block text-sm font-medium text-gray-700 mb-2">
Facility Name <span class="text-red-500">*</span>
</label>
<input type="text" 
id="name" 
name="name" 
value="{{ old('name', $facility->name) }}" 
required
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
placeholder="Enter facility name">
@error('name')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div>
<label for="type" class="block text-sm font-medium text-gray-700 mb-2">
Facility Type <span class="text-red-500">*</span>
</label>
<select id="type" 
name="type" 
required
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror">
<option value="">Select a type</option>
<option value="classroom" {{ old('type', $facility->type) === 'classroom' ? 'selected' : '' }}>
Classroom
</option>
<option value="hall" {{ old('type', $facility->type) === 'hall' ? 'selected' : '' }}>
Hall
</option>
<option value="sports_facility" {{ old('type', $facility->type) === 'sports_facility' ? 'selected' : '' }}>
Sports Facility
</option>
<option value="laboratory" {{ old('type', $facility->type) === 'laboratory' ? 'selected' : '' }}>
Laboratory
</option>
<option value="library" {{ old('type', $facility->type) === 'library' ? 'selected' : '' }}>
Library
</option>
<option value="auditorium" {{ old('type', $facility->type) === 'auditorium' ? 'selected' : '' }}>
Auditorium
</option>
<option value="conference_room" {{ old('type', $facility->type) === 'conference_room' ? 'selected' : '' }}>
Conference Room
</option>
<option value="other" {{ old('type', $facility->type) === 'other' ? 'selected' : '' }}>
Other
</option>
</select>
@error('type')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div>
<label for="location" class="block text-sm font-medium text-gray-700 mb-2">
Location <span class="text-red-500">*</span>
</label>
<input type="text" 
id="location" 
name="location" 
value="{{ old('location', $facility->location) }}" 
required
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
placeholder="Enter location">
@error('location')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div>
<label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
Capacity <span class="text-red-500">*</span>
</label>
<input type="number" 
id="capacity" 
name="capacity" 
value="{{ old('capacity', $facility->capacity) }}" 
min="1"
required
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacity') border-red-500 @enderror"
placeholder="Enter capacity">
@error('capacity')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div>
<label for="max_capacity" class="block text-sm font-medium text-gray-700 mb-2">
Maximum Capacity
</label>
<input type="number" 
id="max_capacity" 
name="max_capacity" 
value="{{ old('max_capacity', $facility->max_capacity) }}" 
min="1"
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_capacity') border-red-500 @enderror"
placeholder="Enter maximum capacity">
@error('max_capacity')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div class="md:col-span-2">
<label for="description" class="block text-sm font-medium text-gray-700 mb-2">
Description
</label>
<textarea id="description" 
name="description" 
rows="3"
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
placeholder="Enter facility description">{{ old('description', $facility->description) }}</textarea>
@error('description')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div class="md:col-span-2">
<label for="image" class="block text-sm font-medium text-gray-700 mb-2">
Facility Image
</label>
<div class="flex items-center space-x-4">
<div class="flex-shrink-0">
@if($facility->image)
<img class="h-20 w-20 rounded-lg object-cover border" 
src="{{ asset('storage/' . $facility->image) }}" 
alt="Current image">
@else
<div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center">
<svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
</svg>
</div>
@endif
</div>
<div class="flex-1">
<input type="file" 
id="image" 
name="image" 
accept="image/*"
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
<p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Leave empty to keep current image.</p>
@error('image')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
</div>
</div>
</div>
</div>
<!-- Operating Hours -->
<div class="bg-white rounded-lg shadow-lg p-6">
<h2 class="text-xl font-bold text-gray-900 mb-6">Operating Hours</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label for="opening_time" class="block text-sm font-medium text-gray-700 mb-2">
Opening Time <span class="text-red-500">*</span>
</label>
<input type="time" 
id="opening_time" 
name="opening_time" 
value="{{ old('opening_time', $facility->opening_time) }}" 
required
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('opening_time') border-red-500 @enderror">
@error('opening_time')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div>
<label for="closing_time" class="block text-sm font-medium text-gray-700 mb-2">
Closing Time <span class="text-red-500">*</span>
</label>
<input type="time" 
id="closing_time" 
name="closing_time" 
value="{{ old('closing_time', $facility->closing_time) }}" 
required
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('closing_time') border-red-500 @enderror">
@error('closing_time')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div>
<label for="buffer_time" class="block text-sm font-medium text-gray-700 mb-2">
Buffer Time (minutes)
</label>
<input type="number" 
id="buffer_time" 
name="buffer_time" 
value="{{ old('buffer_time', $facility->buffer_time) }}" 
min="0"
max="60"
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('buffer_time') border-red-500 @enderror"
placeholder="Time between bookings">
@error('buffer_time')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
</div>
</div>
<!-- Additional Settings -->
<div class="bg-white rounded-lg shadow-lg p-6">
<h2 class="text-xl font-bold text-gray-900 mb-6">Additional Settings</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="md:col-span-2">
<div class="flex items-center">
<input type="checkbox" 
id="is_public" 
name="is_public" 
value="1"
{{ old('is_public', $facility->is_public) ? 'checked' : '' }}
class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
<label for="is_public" class="ml-2 block text-sm text-gray-700">
Public Facility - Available for all users
</label>
</div>
</div>
<div class="md:col-span-2">
<label for="amenities" class="block text-sm font-medium text-gray-700 mb-2">
Amenities
</label>
<textarea id="amenities" 
name="amenities" 
rows="3"
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amenities') border-red-500 @enderror"
placeholder="List available amenities (e.g., projector, whiteboard, WiFi)">{{ old('amenities', $facility->amenities) }}</textarea>
@error('amenities')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
<div class="md:col-span-2">
<label for="rules" class="block text-sm font-medium text-gray-700 mb-2">
Facility Rules
</label>
<textarea id="rules" 
name="rules" 
rows="3"
class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rules') border-red-500 @enderror"
placeholder="List facility rules and restrictions">{{ old('rules', $facility->rules) }}</textarea>
@error('rules')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
</div>
</div>
</div>
</div>
<!-- Sidebar -->
<div class="space-y-6">
<!-- Status Card -->
<div class="bg-white rounded-lg shadow-lg p-6">
<h3 class="text-lg font-bold text-gray-900 mb-4">Facility Status</h3>
<div class="space-y-4">
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Current Status</label>
<span class="px-3 py-1 inline-flex text-sm font-bold rounded-full shadow-sm
{{ $facility->status ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
{{ $facility->status ? 'Active' : 'Inactive' }}
</span>
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Facility Type</label>
<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
{{ ucwords(str_replace('_', ' ', $facility->type)) }}
</span>
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Total Capacity</label>
<p class="text-sm text-gray-900">{{ $facility->capacity }} people</p>
@if($facility->max_capacity)
<p class="text-xs text-gray-500">Maximum: {{ $facility->max_capacity }}</p>
@endif
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Operating Hours</label>
<p class="text-sm text-gray-900">{{ $facility->opening_time }} - {{ $facility->closing_time }}</p>
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
<p class="text-sm text-gray-900">{{ $facility->updated_at->format('M d, Y - H:i') }}</p>
</div>
</div>
</div>
<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-lg p-6">
<h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
<div class="space-y-3">
<form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" class="inline w-full">
@csrf
@method('DELETE')
<button type="submit" 
class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center justify-center"
onclick="return confirm('Delete this facility? This action cannot be undone.')">
<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
</svg>
Delete Facility
</button>
</form>
</div>
</div>
<!-- Submit Button -->
<div class="bg-white rounded-lg shadow-lg p-6">
<button type="submit" 
class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center font-medium transition">
<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
</svg>
Update Facility
</button>
<p class="mt-2 text-xs text-gray-500 text-center">
Changes will be saved immediately
</p>
</div>
</div>
</div>
</form>
</div>
@endsection