@extends('layouts.auth')
@section('content')
<div class="bg-white rounded-2xl shadow-2xl p-8">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl mb-4 shadow-lg">
            <i class="fa-solid fa-user-plus text-3xl text-white"></i>
        </div>

        <h2 class="text-3xl font-bold text-gray-800 mb-2">
            Guest Registration 🎓
        </h2>
        <p class="text-gray-600 text-sm">
            Request access to the facility booking system
        </p>
    </div>

    <!-- Info Banner -->
    <div class="mb-5 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 mb-1">Registration Process</h3>
                <p class="text-xs text-blue-700">
                    Your registration will be reviewed by our administrators. You'll receive an email notification once your account is approved.
                </p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-exclamation-circle text-red-600 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-red-800 mb-1">Please fix the following errors:</h3>
                <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Registration Form -->
    <form method="POST" action="{{ route('guest.register.store') }}" class="space-y-5">
        @csrf

        <!-- Full Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                Full Name <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-gray-400"></i>
                </div>
                <input 
                    id="name"
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}"
                    required 
                    autofocus
                    placeholder="Juan Dela Cruz"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition @error('name') border-red-500 @enderror"
                />
            </div>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                Email Address <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-gray-400"></i>
                </div>
                <input 
                    id="email"
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    autocomplete="username"
                    placeholder="guest@example.com"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition @error('email') border-red-500 @enderror"
                />
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Number -->
        <div>
            <label for="contact_number" class="block text-sm font-semibold text-gray-700 mb-2">
                Contact Number <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-phone text-gray-400"></i>
                </div>
                <input 
                    id="contact_number"
                    type="tel" 
                    name="contact_number" 
                    value="{{ old('contact_number') }}"
                    required
                    placeholder="+63 912 345 6789"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition @error('contact_number') border-red-500 @enderror"
                />
            </div>
            @error('contact_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Organization (Optional) -->
        <div>
            <label for="organization" class="block text-sm font-semibold text-gray-700 mb-2">
                Organization/Company <span class="text-gray-400 text-xs">(Optional)</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-building text-gray-400"></i>
                </div>
                <input 
                    id="organization"
                    type="text" 
                    name="organization" 
                    value="{{ old('organization') }}"
                    placeholder="ABC Corporation"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition @error('organization') border-red-500 @enderror"
                />
            </div>
            @error('organization')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Purpose -->
        <div>
            <label for="purpose" class="block text-sm font-semibold text-gray-700 mb-2">
                Purpose of Access <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute top-3 left-3 pointer-events-none">
                    <i class="fa-solid fa-comment-dots text-gray-400"></i>
                </div>
                <textarea 
                    id="purpose"
                    name="purpose" 
                    required
                    rows="4"
                    placeholder="Please describe why you need access to the facility booking system..."
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition resize-none @error('purpose') border-red-500 @enderror"
                >{{ old('purpose') }}</textarea>
            </div>
            <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
            @error('purpose')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input 
                    id="password"
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    placeholder="••••••••••••"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition @error('password') border-red-500 @enderror"
                />
            </div>
            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                Confirm Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input 
                    id="password_confirmation"
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="••••••••••••"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                />
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input 
                    id="terms"
                    type="checkbox" 
                    name="terms"
                    required
                    class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500 w-4 h-4"
                />
            </div>
            <label for="terms" class="ml-3 text-sm text-gray-600">
                I agree to the <a href="#" class="text-teal-600 hover:text-teal-800 font-medium">Terms of Service</a> and <a href="#" class="text-teal-600 hover:text-teal-800 font-medium">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5"
        >
            <i class="fa-solid fa-paper-plane"></i>
            <span>Submit Registration</span>
        </button>

        <!-- Already have account -->
        <div class="text-center text-sm text-gray-600 mt-6 pt-6 border-t border-gray-200">
            Already have an account? 
            <a href="{{ route('guest.login') }}" class="text-teal-600 font-semibold hover:text-teal-800 hover:underline ml-1">
                Sign In
            </a>
        </div>

        <!-- Return to Home -->
        <div class="text-center text-sm text-gray-500">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 hover:underline inline-flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i>
                Back to home
            </a>
        </div>
    </form>
</div>
@endsection