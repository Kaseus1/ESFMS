@extends('layouts.auth')

@section('content')
<div class="bg-white rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl p-4 sm:p-6 md:p-8 max-w-md w-full mx-auto">
    <!-- Header with Icon -->
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-2xl sm:rounded-3xl mb-4 sm:mb-5 shadow-lg sm:shadow-xl transform hover:scale-105 transition-transform duration-300"
             style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));">
            <i class="fa-solid fa-user-graduate text-2xl sm:text-3xl md:text-4xl text-white"></i>
        </div>

        <h2 class="text-2xl sm:text-3xl font-bold" style="color: var(--color-text-dark);" mb-2 sm:mb-3 px-2>
            Student Portal
        </h2>
        <p class="text-sm sm:text-base" style="color: var(--color-text-light);" px-4>
            Sign in to manage facility reservations
        </p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start gap-2 sm:gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-exclamation-circle text-red-600 text-lg sm:text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-red-800 mb-1">Unable to Sign In</h3>
                <div class="text-xs sm:text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <p class="break-words">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5" id="loginForm">
        @csrf
        <input type="hidden" name="intended_role" value="student">

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                <i class="fa-solid fa-envelope mr-1" style="color: var(--color-royal-blue);"></i>
                Student Email
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-at text-gray-400 group-focus-within:transition-colors text-sm sm:text-base" 
                       style="color: var(--color-text-light);"></i>
                </div>
                <input 
                    id="email"
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="student@cpac.edu"
                    class="block w-full pl-9 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 border-2 rounded-lg sm:rounded-xl transition-all duration-200 text-gray-900 placeholder-gray-400 text-base"
                    style="border-color: #D1D5DB; --tw-ring-color: var(--color-royal-blue);"
                    onfocus="this.style.borderColor='var(--color-royal-blue)'"
                    onblur="this.style.borderColor='#D1D5DB'"
                />
            </div>
        </div>

        <!-- Password with Toggle -->
        <div>
            <label for="password" class="block text-sm font-semibold mb-2" style="color: var(--color-text-dark);">
                <i class="fa-solid fa-lock mr-1" style="color: var(--color-royal-blue);"></i>
                Password
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-key text-gray-400 group-focus-within:transition-colors text-sm sm:text-base" 
                       style="color: var(--color-text-light);"></i>
                </div>
                <input 
                    id="password"
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    class="block w-full pl-9 sm:pl-11 pr-11 sm:pr-12 py-3 sm:py-3.5 border-2 rounded-lg sm:rounded-xl transition-all duration-200 text-gray-900 placeholder-gray-400 text-base"
                    style="border-color: #D1D5DB;"
                    onfocus="this.style.borderColor='var(--color-royal-blue)'"
                    onblur="this.style.borderColor='#D1D5DB'"
                />
                <button 
                    type="button"
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:transition-colors"
                    style="color: var(--color-text-light);"
                    onmouseover="this.style.color='var(--color-royal-blue)'"
                    onmouseout="this.style.color='var(--color-text-light)'"
                    aria-label="Toggle password visibility"
                >
                    <i id="toggleIcon" class="fa-solid fa-eye text-sm sm:text-base"></i>
                </button>
            </div>
        </div>

        <!-- Remember & Forgot Password -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-2">
            <label class="flex items-center cursor-pointer group">
                <input 
                    id="remember_me"
                    type="checkbox" 
                    name="remember"
                    class="rounded border-gray-300 shadow-sm w-5 h-5 sm:w-4 sm:h-4 cursor-pointer"
                    style="accent-color: var(--color-royal-blue);"
                />
                <span class="ml-2 text-sm transition-colors group-hover:opacity-80" style="color: var(--color-text-light);">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold hover:underline transition-all" 
                   style="color: var(--color-royal-blue);">
                    Forgot Password?
                </a>
            @endif
        </div>

        <!-- Login Button with Loading State -->
        <button 
            type="submit" 
            id="submitBtn"
            class="w-full flex items-center justify-center gap-2 py-3.5 sm:py-4 px-4 text-white font-bold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.98] text-base sm:text-lg disabled:opacity-50 disabled:cursor-not-allowed"
            style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));"
        >
            <i id="submitIcon" class="fa-solid fa-sign-in-alt"></i>
            <span id="submitText">Sign In</span>
        </button>

        <!-- Divider -->
        <div class="relative my-5 sm:my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t" style="border-color: #D1D5DB;"></div>
            </div>
            <div class="relative flex justify-center text-xs sm:text-sm">
                <span class="px-3 sm:px-4 bg-white" style="color: var(--color-text-light);">New student?</span>
            </div>
        </div>

        <!-- Registration Link -->
        <div class="text-center">
            <a href="{{ route('student.register') }}" class="inline-flex items-center justify-center gap-2 font-semibold transition-all group text-sm sm:text-base"
               style="color: var(--color-royal-blue);">
                <i class="fa-solid fa-user-plus group-hover:scale-110 transition-transform"></i>
                <span class="border-b-2 border-transparent group-hover:border-current transition-all">Create Student Account</span>
            </a>
        </div>

        <!-- Return Link -->
        <div class="text-center pt-3 sm:pt-4 border-t" style="border-color: #E5E7EB;">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 font-medium transition-all group text-sm sm:text-base"
               style="color: var(--color-text-light);">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Back to home</span>
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

    /* Prevent iOS zoom on input focus */
    @media (max-width: 640px) {
        input[type="email"],
        input[type="password"] {
            font-size: 16px;
        }
    }
</style>

<script>
// Toggle Password Visibility
function togglePassword() {
    const p = document.getElementById('password');
    const i = document.getElementById('toggleIcon');
    
    if (p.type === 'password') {
        p.type = 'text';
        i.className = 'fa-solid fa-eye-slash text-sm sm:text-base';
    } else {
        p.type = 'password';
        i.className = 'fa-solid fa-eye text-sm sm:text-base';
    }
}

// Form Submit Loading State
document.getElementById('loginForm').onsubmit = function() {
    const btn = document.getElementById('submitBtn');
    const icon = document.getElementById('submitIcon');
    const text = document.getElementById('submitText');
    
    btn.disabled = true;
    icon.className = 'fa-solid fa-spinner animate-spin';
    text.textContent = 'Signing In...';
};
</script>
@endsection