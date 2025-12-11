@extends('layouts.auth')

@section('content')
<div class="bg-white rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl p-4 sm:p-6 md:p-8 max-w-2xl w-full mx-auto">
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gradient-to-br from-[#002366] via-[#001A4A] to-[#00285C] rounded-2xl sm:rounded-3xl mb-4 sm:mb-5 shadow-lg sm:shadow-xl transform hover:scale-105 transition-transform duration-300">
            <i class="fa-solid fa-chalkboard-teacher text-2xl sm:text-3xl md:text-4xl text-white"></i>
        </div>

        <h2 class="text-2xl sm:text-3xl font-bold text-[#172030] mb-2 sm:mb-3 px-2">
            Faculty Registration
        </h2>
        <p class="text-sm sm:text-base text-[#333C4D] px-4">
            Create your faculty account - approval required
        </p>
    </div>

    <div class="mb-4 sm:mb-5 bg-[#F8F9FA] border-l-4 border-[#002366] p-3 sm:p-4 rounded-lg">
        <div class="flex items-start gap-2 sm:gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-info-circle text-[#002366] text-base sm:text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs sm:text-sm text-[#001A4A]">
                    Your registration will be reviewed by an administrator. You'll receive an email once approved.
                </p>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 sm:mb-5 bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start gap-2 sm:gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-exclamation-circle text-red-600 text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-red-800 mb-1">Registration Error</h3>
                <div class="text-xs sm:text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <p class="break-words">• {{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('faculty.register.store') }}" class="space-y-4 sm:space-y-5" id="registerForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-semibold text-[#333C4D] mb-2">
                    <i class="fa-solid fa-user text-[#002366] mr-1"></i>
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-signature text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="name"
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        required 
                        autofocus
                        placeholder="Dr. John Smith"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all text-gray-900 text-base @error('name') border-red-500 @enderror"
                    />
                </div>
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-[#333C4D] mb-2">
                    <i class="fa-solid fa-envelope text-[#002366] mr-1"></i>
                    Faculty Email <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-at text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="email"
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        placeholder="faculty@cpac.edu"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all text-gray-900 text-base @error('email') border-red-500 @enderror"
                    />
                </div>
                <p class="mt-1 text-xs text-[#333C4D]">Use your official CPAC email</p>
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="school_id" class="block text-sm font-semibold text-[#333C4D] mb-2">
                    <i class="fa-solid fa-id-card text-[#002366] mr-1"></i>
                    Employee Number <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-hashtag text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="school_id"
                        type="text" 
                        name="school_id" 
                        value="{{ old('school_id') }}"
                        required
                        placeholder="2024-12345"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all text-gray-900 text-base @error('school_id') border-red-500 @enderror"
                    />
                </div>
                @error('school_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="department" class="block text-sm font-semibold text-[#333C4D] mb-2">
                    <i class="fa-solid fa-building text-[#002366] mr-1"></i>
                    Department <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-briefcase text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="department"
                        type="text" 
                        name="department" 
                        value="{{ old('department') }}"
                        required
                        placeholder="Computer Science"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all text-gray-900 text-base @error('department') border-red-500 @enderror"
                    />
                </div>
                @error('department')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="phone" class="block text-sm font-semibold text-[#333C4D] mb-2">
                <i class="fa-solid fa-phone text-[#002366] mr-1"></i>
                Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-mobile-alt text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm"></i>
                </div>
                <input 
                    id="phone"
                    type="text" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    placeholder="+63 912 345 6789"
                    class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all text-gray-900 text-base"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-semibold text-[#333C4D] mb-2">
                    <i class="fa-solid fa-lock text-[#002366] mr-1"></i>
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-key text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="password"
                        type="password" 
                        name="password" 
                        required
                        placeholder="Enter password"
                        class="block w-full pl-9 sm:pl-11 pr-11 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all text-gray-900 text-base @error('password') border-red-500 @enderror"
                    />
                    <button 
                        type="button"
                        onclick="togglePassword('password', 'toggleIcon1')"
                        class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:text-[#002366] transition-colors"
                    >
                        <i id="toggleIcon1" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-[#333C4D]">Minimum 8 characters</p>
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-[#333C4D] mb-2">
                    <i class="fa-solid fa-lock text-[#002366] mr-1"></i>
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-key text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm"></i>
                    </div>
                    <input 
                        id="password_confirmation"
                        type="password" 
                        name="password_confirmation" 
                        required
                        placeholder="Confirm password"
                        class="block w-full pl-9 sm:pl-11 pr-11 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all text-gray-900 text-base"
                    />
                    <button 
                        type="button"
                        onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                        class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:text-[#002366] transition-colors"
                    >
                        <i id="toggleIcon2" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div>
            <label class="flex items-start cursor-pointer group">
                <input 
                    type="checkbox" 
                    name="terms"
                    required
                    class="rounded border-gray-300 text-[#002366] focus:ring-[#002366] w-4 h-4 mt-1 cursor-pointer"
                />
                <span class="ml-2 text-xs sm:text-sm text-[#333C4D] group-hover:text-[#172030]">
                    I agree to the <a href="#" class="text-[#002366] hover:underline font-semibold">Terms of Service</a> and <a href="#" class="text-[#002366] hover:underline font-semibold">Privacy Policy</a>
                </span>
            </label>
        </div>

        <button 
            type="submit" 
            id="submitBtn"
            class="w-full flex items-center justify-center gap-2 py-3 sm:py-3.5 px-4 bg-gradient-to-r from-[#002366] via-[#001A4A] to-[#00285C] hover:from-[#001A4A] hover:via-[#002366] hover:to-[#00285C] text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.98] text-base disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <i id="submitIcon" class="fa-solid fa-user-plus"></i>
            <span id="submitText">Submit Registration</span>
        </button>

        <div class="text-center text-xs sm:text-sm text-[#333C4D] mt-4 pt-4 border-t border-gray-200">
            Already have an account? 
            <a href="{{ route('faculty.login') }}" class="text-[#002366] font-semibold hover:text-[#001A4A] hover:underline">
                Sign in here
            </a>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm text-[#333C4D] hover:text-[#172030] transition-all">
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
    input[type="password"] {
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