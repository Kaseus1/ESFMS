@extends('layouts.admin')

@section('title', 'Edit Guest - ' . $guest->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.guests.show', $guest) }}" 
               class="px-4 py-2 bg-[#333C4D] bg-opacity-10 text-[#172030] rounded-lg hover:bg-opacity-20 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Guest Details
            </a>
            <div>
                <h1 class="text-3xl font-bold text-[#172030]">Edit Guest</h1>
                <p class="text-[#333C4D] mt-1">{{ $guest->name }}</p>
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

    <!-- Edit Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.guests.update', $guest) }}" class="bg-white rounded-lg shadow-lg p-6">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-[#172030] mb-4">Personal Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-[#333C4D] mb-1">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $guest->name) }}" 
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('name') border-[#EF4444] @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-[#333C4D] mb-1">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $guest->email) }}" 
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('email') border-[#EF4444] @enderror" 
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-[#333C4D] mb-1">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $guest->contact_number) }}" 
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('contact_number') border-[#EF4444] @enderror">
                            @error('contact_number')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Organization -->
                        <div>
                            <label for="organization" class="block text-sm font-medium text-[#333C4D] mb-1">Organization</label>
                            <input type="text" name="organization" id="organization" value="{{ old('organization', $guest->organization) }}" 
                                   class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('organization') border-[#EF4444] @enderror">
                            @error('organization')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Purpose -->
                        <div class="md:col-span-2">
                            <label for="purpose" class="block text-sm font-medium text-[#333C4D] mb-1">Purpose of Visit</label>
                            <textarea name="purpose" id="purpose" rows="3" 
                                      class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] @error('purpose') border-[#EF4444] @enderror">{{ old('purpose', $guest->purpose) }}</textarea>
                            @error('purpose')
                                <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.guests.show', $guest) }}" 
                       class="px-4 py-2 border border-[#333C4D] border-opacity-20 text-[#172030] rounded-lg hover:bg-[#F8F9FA]">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A]">
                        Update Guest
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#172030] mb-4">Current Status</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Status</label>
                        <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full shadow-sm
                            {{ $guest->status === 'approved' ? 'bg-[#10B981] bg-opacity-10 text-[#10B981] border border-[#10B981] border-opacity-20' : 
                               ($guest->status === 'pending' ? 'bg-[#F59E0B] bg-opacity-10 text-[#F59E0B] border border-[#F59E0B] border-opacity-20' : 
                               'bg-[#EF4444] bg-opacity-10 text-[#EF4444] border border-[#EF4444] border-opacity-20') }}">
                            {{ ucfirst($guest->status) }}
                        </span>
                    </div>
                    
                    @if($guest->status === 'approved' && $guest->approved_at)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Approved At</label>
                            <p class="text-sm text-[#172030]">{{ $guest->approved_at->format('M d, Y - H:i') }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Last Updated</label>
                        <p class="text-sm text-[#172030]">{{ $guest->updated_at->format('M d, Y - H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#172030] mb-4">Admin Notes</h3>
                <form method="POST" action="{{ route('admin.guests.notes', $guest) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="admin_notes" class="block text-sm font-medium text-[#333C4D] mb-1">Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4" 
                                  class="w-full px-3 py-2 border border-[#333C4D] border-opacity-20 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366]">{{ old('admin_notes', $guest->admin_notes) }}</textarea>
                        @error('admin_notes')
                            <p class="mt-1 text-sm text-[#EF4444]">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A]">
                        Update Notes
                    </button>
                </form>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-[#172030] mb-4">Account Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Account ID</label>
                        <p class="text-sm text-[#172030]">{{ $guest->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Role</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#002366] bg-opacity-10 text-[#002366]">
                            {{ ucfirst($guest->role) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Registered On</label>
                        <p class="text-sm text-[#172030]">{{ $guest->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-[#333C4D]">{{ $guest->created_at->diffForHumans() }}</p>
                    </div>
                    @if($guest->email_verified_at)
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Email Verified</label>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-[#10B981] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-[#10B981]">Yes</span>
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-[#333C4D] mb-1">Email Verified</label>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-[#EF4444] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-[#EF4444]">No</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
