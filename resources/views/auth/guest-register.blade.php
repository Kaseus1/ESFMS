@extends('layouts.auth')

@section('content')
<div class="bg-white rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl p-4 sm:p-6 md:p-8 max-w-2xl w-full mx-auto">
    <!-- Header with Icon -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gradient-to-br from-teal-500 via-teal-600 to-cyan-600 rounded-2xl sm:rounded-3xl mb-4 sm:mb-5 shadow-lg sm:shadow-xl transform hover:scale-105 transition-transform duration-300">
            <i class="fa-solid fa-user-plus text-2xl sm:text-3xl md:text-4xl text-white"></i>
        </div>

        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-3 px-2">
            Guest Registration
        </h2>
        <p class="text-sm sm:text-base text-gray-600 px-4">
            Request access to the facility booking system
        </p>
    </div>

    <!-- Info Banner -->
    <div class="mb-4 sm:mb-5 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start gap-2 sm:gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-info-circle text-blue-600 text-base sm:text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-xs sm:text-sm font-semibold text-blue-800 mb-1">Registration Process</h3>
                <p class="text-xs text-blue-700">
                    Your registration will be reviewed by our administrators. You'll receive an email notification once approved.
                </p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-4 sm:mb-5 bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start gap-2 sm:gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-exclamation-circle text-red-600 text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-red-800 mb-1">Please fix the following errors:</h3>
                <ul class="text-xs sm:text-sm text-red-700 space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="break-words">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Registration Form -->
    <form method="POST" action="{{ route('guest.register.store') }}" class="space-y-4 sm:space-y-5" id="registerForm">
        @csrf

        <!-- Row 1: Full Name and Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-user text-teal-600 mr-1"></i>
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-signature text-gray-400 group-focus-within:text-teal-600 transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="name"
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        required 
                        autofocus
                        placeholder="Juan Dela Cruz"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 text-base @error('name') border-red-500 @enderror"
                    />
                </div>
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-envelope text-teal-600 mr-1"></i>
                    Email Address <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-at text-gray-400 group-focus-within:text-teal-600 transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="email"
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        placeholder="guest@example.com"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 text-base @error('email') border-red-500 @enderror"
                    />
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Row 2: Contact Number and Organization -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Contact Number -->
            <div>
                <label for="contact_number" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-phone text-teal-600 mr-1"></i>
                    Contact Number <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-mobile-alt text-gray-400 group-focus-within:text-teal-600 transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="contact_number"
                        type="tel" 
                        name="contact_number" 
                        value="{{ old('contact_number') }}"
                        required
                        placeholder="+63 912 345 6789"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 text-base @error('contact_number') border-red-500 @enderror"
                    />
                </div>
                @error('contact_number')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Organization -->
            <div>
                <label for="organization" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-building text-teal-600 mr-1"></i>
                    Organization <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-briefcase text-gray-400 group-focus-within:text-teal-600 transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="organization"
                        type="text" 
                        name="organization" 
                        value="{{ old('organization') }}"
                        placeholder="ABC Corporation"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 text-base"
                    />
                </div>
            </div>
        </div>

        <!-- Purpose (Full Width) -->
        <div>
            <label for="purpose" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fa-solid fa-comment-dots text-teal-600 mr-1"></i>
                Purpose of Access <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute top-3 left-3 sm:left-4 pointer-events-none">
                    <i class="fa-solid fa-pen text-gray-400 text-sm"></i>
                </div>
                <textarea 
                    id="purpose"
                    name="purpose" 
                    required
                    rows="4"
                    placeholder="Please describe why you need access to the facility booking system..."
                    class="block w-full pl-9 sm:pl-11 pr-3 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all resize-none text-gray-900 text-base @error('purpose') border-red-500 @enderror"
                >{{ old('purpose') }}</textarea>
            </div>
            <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
            @error('purpose')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Row 3: Password and Confirm Password -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-lock text-teal-600 mr-1"></i>
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-key text-gray-400 group-focus-within:text-teal-600 transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="password"
                        type="password" 
                        name="password" 
                        required
                        placeholder="Enter password"
                        class="block w-full pl-9 sm:pl-11 pr-11 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 text-base @error('password') border-red-500 @enderror"
                    />
                    <button 
                        type="button"
                        onclick="togglePassword('password', 'toggleIcon1')"
                        class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:text-teal-600 transition-colors"
                    >
                        <i id="toggleIcon1" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-lock text-teal-600 mr-1"></i>
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-key text-gray-400 group-focus-within:text-teal-600 transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="password_confirmation"
                        type="password" 
                        name="password_confirmation" 
                        required
                        placeholder="Confirm password"
                        class="block w-full pl-9 sm:pl-11 pr-11 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 text-base"
                    />
                    <button 
                        type="button"
                        onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                        class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:text-teal-600 transition-colors"
                    >
                        <i id="toggleIcon2" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Terms -->
        <div>
            <label class="flex items-start cursor-pointer group">
                <input 
                    type="checkbox" 
                    name="terms"
                    required
                    class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 w-4 h-4 mt-1 cursor-pointer"
                />
                <span class="ml-2 text-xs sm:text-sm text-gray-600 group-hover:text-gray-800">
                    I agree to the <a href="#" class="text-teal-600 hover:underline font-semibold">Terms of Service</a> and <a href="#" class="text-teal-600 hover:underline font-semibold">Privacy Policy</a>
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            id="submitBtn"
            class="w-full flex items-center justify-center gap-2 py-3 sm:py-3.5 px-4 bg-gradient-to-r from-teal-600 via-teal-700 to-cyan-700 hover:from-teal-700 hover:via-teal-800 hover:to-cyan-800 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.98] text-base disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <i id="submitIcon" class="fa-solid fa-paper-plane"></i>
            <span id="submitText">Submit Registration</span>
        </button>

        <!-- Login Link -->
        <div class="text-center text-xs sm:text-sm text-gray-600 mt-4 pt-4 border-t border-gray-200">
            Already have an account? 
            <a href="{{ route('guest.login') }}" class="text-teal-600 font-semibold hover:text-teal-800 hover:underline">
                Sign In
            </a>
        </div>

        <!-- Return Link -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm text-gray-600 hover:text-gray-800 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
                Back to home
            </a>
        </div>
    </form>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
@media (max-width: 640px) {
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"],
    textarea {
        font-size: 16px;
    }
}
</style>

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fa-solid fa-eye-slash text-sm';
    } else {
        field.type = 'password';
        icon.className = 'fa-solid fa-eye text-sm';
    }
}

document.getElementById('registerForm').onsubmit = function() {
    const btn = document.getElementById('submitBtn');
    const icon = document.getElementById('submitIcon');
    const text = document.getElementById('submitText');
    
    btn.disabled = true;
    icon.className = 'fa-solid fa-spinner animate-spin';
    text.textContent = 'Submitting...';
};
</script>
@endsection