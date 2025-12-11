@extends('layouts.admin')

@section('title', 'Create New Guest')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.guests.index') }}" 
               class="px-4 py-2 bg-[#333C4D] bg-opacity-10 text-[#172030] rounded-lg hover:bg-opacity-20 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Guests
            </a>
            <div>
                <h1 class="text-3xl font-bold text-[#172030]">Create New Guest</h1>
                <p class="text-[#333C4D] mt-1">Manually add a guest account to the system</p>
            </div>
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

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="mb-4 p-4 bg-[#EF4444] bg-opacity-10 border border-[#EF4444] border-opacity-20 rounded-lg text-[#EF4444]">
            <h4 class="font-semibold mb-2">Please correct the following errors:</h4>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.guests.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-[#172030] mb-6">Personal Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Full Name <span class="text-[#EF4444]">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('name') border-[#EF4444] @enderror"
                                   placeholder="Enter guest's full name">
                            @error('name')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Email Address <span class="text-[#EF4444]">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('email') border-[#EF4444] @enderror"
                                   placeholder="Enter email address">
                            @error('email')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Contact Number
                            </label>
                            <input type="text" 
                                   id="contact_number" 
                                   name="contact_number" 
                                   value="{{ old('contact_number') }}"
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('contact_number') border-[#EF4444] @enderror"
                                   placeholder="Enter contact number">
                            @error('contact_number')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="organization" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Organization
                            </label>
                            <input type="text" 
                                   id="organization" 
                                   name="organization" 
                                   value="{{ old('organization') }}"
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('organization') border-[#EF4444] @enderror"
                                   placeholder="Enter organization name">
                            @error('organization')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="purpose" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Purpose of Visit
                            </label>
                            <textarea id="purpose" 
                                      name="purpose" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('purpose') border-[#EF4444] @enderror"
                                      placeholder="Describe the purpose of the guest's visit">{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-[#172030] mb-6">Account Settings</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Initial Status <span class="text-[#EF4444]">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    required
                                    class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('status') border-[#EF4444] @enderror">
                                <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                                    Pending - Awaiting approval
                                </option>
                                <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>
                                    Approved - Active immediately
                                </option>
                                <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>
                                    Rejected - Account declined
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Account Role
                            </label>
                            <select id="role" 
                                    name="role" 
                                    disabled
                                    class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg bg-[#F8F9FA] cursor-not-allowed text-[#333C4D]">
                                <option value="guest" selected>
                                    Guest
                                </option>
                            </select>
                            <p class="mt-1 text-xs text-[#333C4D]">Guest role is automatically assigned</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="admin_notes" class="block text-sm font-medium text-[#333C4D] mb-2">
                                Admin Notes
                            </label>
                            <textarea id="admin_notes" 
                                      name="admin_notes" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('admin_notes') border-[#EF4444] @enderror"
                                      placeholder="Internal notes for admin use only">{{ old('admin_notes') }}</textarea>
                            @error('admin_notes')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <div class="bg-[#002366] bg-opacity-10 border border-[#002366] border-opacity-20 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-[#002366] mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-[#002366]">Password Information</h4>
                                        <p class="text-sm text-[#333C4D] mt-1">
                                            No password is required for guest accounts. Guests will use the email verification system to activate their accounts if approved.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Preview -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#172030] mb-4">Account Preview</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-[#002366] to-[#00285C] flex items-center justify-center text-white font-bold text-lg shadow-md">
                                ?
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#172030]" id="preview-name">Guest Name</p>
                                <p class="text-xs text-[#333C4D]" id="preview-email">guest@example.com</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-[#333C4D] border-opacity-10 pt-4 space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-[#333C4D] mb-1">Role</label>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#002366] bg-opacity-10 text-[#002366]">
                                    Guest
                                </span>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-[#333C4D] mb-1">Initial Status</label>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#F59E0B] bg-opacity-10 text-[#F59E0B]" id="preview-status">
                                    Pending
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-[#333C4D] mb-1">Organization</label>
                                <p class="text-xs text-[#172030]" id="preview-org">Not specified</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#172030] mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] flex items-center justify-center font-medium transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Guest Account
                        </button>
                        
                        <a href="{{ route('admin.guests.index') }}" 
                           class="w-full px-4 py-2 bg-[#333C4D] bg-opacity-10 text-[#172030] rounded-lg hover:bg-opacity-20 flex items-center justify-center font-medium transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#172030] mb-4">Help</h3>
                    <div class="text-sm text-[#333C4D] space-y-3">
                        <div>
                            <h4 class="font-medium text-[#172030]">Initial Status</h4>
                            <p>Choose whether the guest account should be active immediately (Approved) or pending your review (Pending).</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-[#172030]">Email Verification</h4>
                            <p>Guest accounts use email verification. Users will receive a verification email to activate their accounts.</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-[#172030]">Role Restrictions</h4>
                            <p>Manually created guests are automatically assigned the "guest" role and cannot be changed later.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Live preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const organizationInput = document.getElementById('organization');
        const statusSelect = document.getElementById('status');

        const previewName = document.getElementById('preview-name');
        const previewEmail = document.getElementById('preview-email');
        const previewOrg = document.getElementById('preview-org');
        const previewStatus = document.getElementById('preview-status');

        function updatePreview() {
            previewName.textContent = nameInput.value || 'Guest Name';
            previewEmail.textContent = emailInput.value || 'guest@example.com';
            previewOrg.textContent = organizationInput.value || 'Not specified';
            
            const statusOptions = {
                'pending': { text: 'Pending', class: 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B]' },
                'approved': { text: 'Approved', class: 'bg-[#10B981] bg-opacity-10 text-[#10B981]' },
                'rejected': { text: 'Rejected', class: 'bg-[#EF4444] bg-opacity-10 text-[#EF4444]' }
            };
            
            const status = statusOptions[statusSelect.value];
            previewStatus.textContent = status.text;
            previewStatus.className = `px-2 py-1 text-xs font-semibold rounded-full ${status.class}`;
        }

        nameInput.addEventListener('input', updatePreview);
        emailInput.addEventListener('input', updatePreview);
        organizationInput.addEventListener('input', updatePreview);
        statusSelect.addEventListener('change', updatePreview);
        
        updatePreview(); // Initial call
    });
</script>
@endsection
