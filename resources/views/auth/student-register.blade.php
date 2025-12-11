@extends('layouts.auth')

@section('content')
<div class="bg-white rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl p-4 sm:p-6 md:p-8 max-w-2xl w-full mx-auto">
    <!-- Header with Icon -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-2xl sm:rounded-3xl mb-4 sm:mb-5 shadow-lg sm:shadow-xl transform hover:scale-105 transition-transform duration-300"
             style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));">
            <i class="fa-solid fa-user-graduate text-2xl sm:text-3xl md:text-4xl text-white"></i>
        </div>

        <h2 class="text-2xl sm:text-3xl font-bold mb-2 sm:mb-3 px-2" style="color: var(--color-text-dark);">
            Student Registration
        </h2>
        <p class="text-sm sm:text-base px-4" style="color: var(--color-text-light);">
            Create your student account to get started
        </p>
    </div>

    <!-- Error Messages -->
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

    <!-- Registration Form -->
    <form method="POST" action="{{ route('student.register.store') }}" class="space-y-4 sm:space-y-5" id="registerForm">
        @csrf
        <input type="hidden" name="role" value="student">

        <!-- Row 1: Full Name and Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                    <i class="fa-solid fa-user mr-1" style="color: var(--color-royal-blue);"></i>
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-signature text-gray-400 group-focus-within:transition-colors text-sm" 
                           style="color: var(--color-text-light);"></i>
                    </div>
                    <input 
                        id="name"
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}"
                        required 
                        autofocus
                        placeholder="Jane Doe"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 rounded-lg transition-all text-gray-900 text-base"
                        style="border-color: #D1D5DB; @error('name') border-color: #EF4444; @enderror"
                        onfocus="this.style.borderColor='var(--color-royal-blue)'"
                        onblur="this.style.borderColor='#D1D5DB'"
                    />
                </div>
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                    <i class="fa-solid fa-envelope mr-1" style="color: var(--color-royal-blue);"></i>
                    Student Email <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-at text-gray-400 group-focus-within:transition-colors text-sm" 
                           style="color: var(--color-text-light);"></i>
                    </div>
                    <input 
                        id="email"
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        placeholder="student@cpac.edu"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 rounded-lg transition-all text-gray-900 text-base"
                        style="border-color: #D1D5DB; @error('email') border-color: #EF4444; @enderror"
                        onfocus="this.style.borderColor='var(--color-royal-blue)'"
                        onblur="this.style.borderColor='#D1D5DB'"
                    />
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Row 2: Student ID and Program -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Student ID -->
            <div>
                <label for="student_id" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                    <i class="fa-solid fa-id-card mr-1" style="color: var(--color-royal-blue);"></i>
                    Student ID <span style="color: #9CA3AF;" class="text-xs"></span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-hashtag text-gray-400 group-focus-within:transition-colors text-sm" 
                           style="color: var(--color-text-light);"></i>
                    </div>
                    <input 
                        id="student_id"
                        type="text" 
                        name="student_id" 
                        value="{{ old('student_id') }}"
                        placeholder="2024-12345"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 rounded-lg transition-all text-gray-900 text-base"
                        style="border-color: #D1D5DB;"
                        onfocus="this.style.borderColor='var(--color-royal-blue)'"
                        onblur="this.style.borderColor='#D1D5DB'"
                    />
                </div>
            </div>

            <!-- Program/Course -->
            <div>
                <label for="program" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                    <i class="fa-solid fa-graduation-cap mr-1" style="color: var(--color-royal-blue);"></i>
                    Program/Course <span style="color: #9CA3AF;" class="text-xs"></span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-book text-gray-400 group-focus-within:transition-colors text-sm" 
                           style="color: var(--color-text-light);"></i>
                    </div>
                    <input 
                        id="program"
                        type="text" 
                        name="program" 
                        value="{{ old('program') }}"
                        placeholder="BS Computer Science"
                        class="block w-full pl-9 sm:pl-11 pr-3 py-2.5 sm:py-3 border-2 rounded-lg transition-all text-gray-900 text-base"
                        style="border-color: #D1D5DB;"
                        onfocus="this.style.borderColor='var(--color-royal-blue)'"
                        onblur="this.style.borderColor='#D1D5DB'"
                    />
                </div>
            </div>
        </div>

        <!-- Row 3: Password and Confirm Password -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                    <i class="fa-solid fa-lock mr-1" style="color: var(--color-royal-blue);"></i>
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-key text-gray-400 group-focus-within:transition-colors text-sm" 
                           style="color: var(--color-text-light);"></i>
                    </div>
                    <input 
                        id="password"
                        type="password" 
                        name="password" 
                        required
                        placeholder="Enter password"
                        class="block w-full pl-9 sm:pl-11 pr-11 py-2.5 sm:py-3 border-2 rounded-lg transition-all text-gray-900 text-base"
                        style="border-color: #D1D5DB; @error('password') border-color: #EF4444; @enderror"
                        onfocus="this.style.borderColor='var(--color-royal-blue)'"
                        onblur="this.style.borderColor='#D1D5DB'"
                    />
                    <button 
                        type="button"
                        onclick="togglePassword('password', 'toggleIcon1')"
                        class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:transition-colors"
                        style="color: var(--color-text-light);"
                        onmouseover="this.style.color='var(--color-royal-blue)'"
                        onmouseout="this.style.color='var(--color-text-light)'"
                    >
                        <i id="toggleIcon1" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs" style="color: var(--color-text-light);">Minimum 8 characters</p>
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                    <i class="fa-solid fa-lock mr-1" style="color: var(--color-royal-blue);"></i>
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-key text-gray-400 group-focus-within:transition-colors text-sm" 
                           style="color: var(--color-text-light);"></i>
                    </div>
                    <input 
                        id="password_confirmation"
                        type="password" 
                        name="password_confirmation" 
                        required
                        placeholder="Confirm password"
                        class="block w-full pl-9 sm:pl-11 pr-11 py-2.5 sm:py-3 border-2 rounded-lg transition-all text-gray-900 text-base"
                        style="border-color: #D1D5DB;"
                        onfocus="this.style.borderColor='var(--color-royal-blue)'"
                        onblur="this.style.borderColor='#D1D5DB'"
                    />
                    <button 
                        type="button"
                        onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                        class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:transition-colors"
                        style="color: var(--color-text-light);"
                        onmouseover="this.style.color='var(--color-royal-blue)'"
                        onmouseout="this.style.color='var(--color-text-light)'"
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
                    class="rounded border-gray-300 focus:ring w-4 h-4 mt-1 cursor-pointer"
                    style="accent-color: var(--color-royal-blue);"
                />
                <span class="ml-2 text-xs sm:text-sm transition-colors group-hover:opacity-80" style="color: var(--color-text-light);">
                    I agree to the <a href="#" class="font-semibold hover:underline" style="color: var(--color-royal-blue);">Terms of Service</a> and <a href="#" class="font-semibold hover:underline" style="color: var(--color-royal-blue);">Privacy Policy</a>
                </span>
            </label>
        </div>

        <!-- Register Button -->
        <button 
            type="submit" 
            id="submitBtn"
            class="w-full flex items-center justify-center gap-2 py-3 sm:py-3.5 px-4 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.98] text-base disabled:opacity-50 disabled:cursor-not-allowed"
            style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));"
        >
            <i id="submitIcon" class="fa-solid fa-user-plus"></i>
            <span id="submitText">Create Account</span>
        </button>

        <!-- Login Link -->
        <div class="text-center text-xs sm:text-sm mt-4 pt-4 border-t" style="color: var(--color-text-light); border-color: #E5E7EB;">
            Already have an account? 
            <a href="{{ route('student.login') }}" class="font-semibold hover:underline" style="color: var(--color-royal-blue);">
                Sign in here
            </a>
        </div>

        <!-- Return Link -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm transition-all" style="color: var(--color-text-light);">
                <i class="fa-solid fa-arrow-left"></i>
                Back to home
            </a>
        </div>
    </form>
</div>

<style>
    :root {
        /* Color palette */
        --color-dark-navy: #172030;
        --color-royal-blue: #002366;
        --color-navy-blue: #00285C;
        --color-white: #FFFFFF;
        --color-off-white: #F8F9FA;
        --color-text-dark: #333C4D;
        --color-text-light: #1D2636;
        --color-dark-blue: #001A4A;
        --color-charcoal: #1D2636;
    }

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
    text.textContent = 'Creating Account...';
};
</script>
@endsection