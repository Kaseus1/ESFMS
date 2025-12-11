@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#172030]">Edit User: {{ $user->name }}</h1>
            <p class="text-[#333C4D] mt-2">Update user information and settings</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="px-4 py-2 bg-[#002366] text-[#FFFFFF] rounded-lg hover:bg-[#001A4A] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View Details
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 bg-[#1D2636] text-[#FFFFFF] rounded-lg hover:bg-[#172030] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-[#10B981] bg-opacity-10 border border-[#10B981] border-opacity-20 rounded-lg text-[#10B981] flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-[#EF4444] bg-opacity-10 border border-[#EF4444] border-opacity-20 rounded-lg text-[#EF4444] flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Edit User Form -->
    <div class="bg-[#FFFFFF] rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-[#F8F9FA] border-b border-[#333C4D] border-opacity-10">
            <h2 class="text-lg font-semibold text-[#172030]">User Information</h2>
        </div>
        
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-[#333C4D] mb-2">
                        Full Name <span class="text-[#EF4444]">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           required
                           class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('name') border-[#EF4444] @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-[#333C4D] mb-2">
                        Email Address <span class="text-[#EF4444]">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('email') border-[#EF4444] @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-[#333C4D] mb-2">
                        Role <span class="text-[#EF4444]">*</span>
                    </label>
                    <select id="role" 
                            name="role" 
                            required
                            class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('role') border-[#EF4444] @enderror">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="faculty" {{ old('role', $user->role) === 'faculty' ? 'selected' : '' }}>Faculty</option>
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-[#333C4D] mb-2">
                        Status <span class="text-[#EF4444]">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('status') border-[#EF4444] @enderror">
                        <option value="">Select Status</option>
                        <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $user->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $user->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block text-sm font-medium text-[#333C4D] mb-2">
                        Contact Number
                    </label>
                    <input type="text" 
                           id="contact_number" 
                           name="contact_number" 
                           value="{{ old('contact_number', $user->contact_number) }}"
                           class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('contact_number') border-[#EF4444] @enderror">
                    @error('contact_number')
                        <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-[#333C4D] mb-2">
                        New Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent @error('password') border-[#EF4444] @enderror">
                    <p class="mt-1 text-xs text-[#333C4D] opacity-75">Leave blank to keep current password</p>
                    @error('password')
                        <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#333C4D] mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-transparent">
                </div>
            </div>

            <!-- Current User Info -->
            <div class="mt-8 p-4 bg-[#F8F9FA] rounded-lg">
                <h3 class="text-sm font-medium text-[#172030] mb-2">Current Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-[#333C4D]">
                    <div>
                        <span class="font-medium">User ID:</span> {{ $user->id }}
                    </div>
                    <div>
                        <span class="font-medium">Created:</span> {{ $user->created_at->format('M d, Y') }}
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span> {{ $user->updated_at->format('M d, Y') }}
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="px-6 py-2 bg-[#333C4D] bg-opacity-20 text-[#333C4D] rounded-lg hover:bg-[#333C4D] hover:bg-opacity-30 font-medium transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-[#002366] text-[#FFFFFF] rounded-lg hover:bg-[#001A4A] font-medium transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection