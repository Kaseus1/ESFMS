@extends('layouts.auth')

@section('content')
<div class="bg-white rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl p-4 sm:p-6 md:p-8 max-w-md w-full mx-auto">
    <div class="text-center mb-6 sm:mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gradient-to-br from-[#002366] via-[#001A4A] to-[#00285C] rounded-2xl sm:rounded-3xl mb-4 sm:mb-5 shadow-lg sm:shadow-xl transform hover:scale-105 transition-transform duration-300">
            <i class="fa-solid fa-chalkboard-teacher text-2xl sm:text-3xl md:text-4xl text-white"></i>
        </div>

        <h2 class="text-2xl sm:text-3xl font-bold text-[#172030] mb-2 sm:mb-3 px-2">
            Faculty Portal
        </h2>
        <p class="text-sm sm:text-base text-[#333C4D] px-4">
            Sign in to manage facility reservations
        </p>
    </div>

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

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5" id="loginForm">
        @csrf
        <input type="hidden" name="intended_role" value="faculty">

        <div>
            <label for="email" class="block text-sm font-semibold text-[#333C4D] mb-2">
                <i class="fa-solid fa-envelope text-[#002366] mr-1"></i> 
                Faculty Email
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-at text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm sm:text-base"></i>
                </div>
                <input 
                    id="email"
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="faculty@cpac.edu"
                    class="block w-full pl-9 sm:pl-11 pr-3 sm:pr-4 py-3 sm:py-3.5 border-2 border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all duration-200 text-gray-900 placeholder-gray-400 text-base"
                />
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-[#333C4D] mb-2">
                <i class="fa-solid fa-lock text-[#002366] mr-1"></i>
                Password
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-key text-gray-400 group-focus-within:text-[#002366] transition-colors text-sm sm:text-base"></i>
                </div>
                <input 
                    id="password"
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    class="block w-full pl-9 sm:pl-11 pr-11 sm:pr-12 py-3 sm:py-3.5 border-2 border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#002366] focus:border-[#002366] transition-all duration-200 text-gray-900 placeholder-gray-400 text-base"
                />
                <button 
                    type="button"
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:text-[#002366] transition-colors"
                    aria-label="Toggle password visibility"
                >
                    <i id="toggleIcon" class="fa-solid fa-eye text-sm sm:text-base"></i>
                </button>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-2">
            <label class="flex items-center cursor-pointer group">
                <input 
                    id="remember_me"
                    type="checkbox" 
                    name="remember"
                    class="rounded border-gray-300 text-[#002366] shadow-sm focus:ring-[#002366] w-5 h-5 sm:w-4 sm:h-4 cursor-pointer"
                />
                <span class="ml-2 text-sm text-[#333C4D] group-hover:text-[#172030] transition-colors">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-[#002366] hover:text-[#001A4A] font-semibold hover:underline transition-all">
                    Forgot Password?
                </a>
            @endif
        </div>

        <button 
            type="submit" 
            id="submitBtn"
            class="w-full flex items-center justify-center gap-2 py-3.5 sm:py-4 px-4 bg-gradient-to-r from-[#002366] via-[#001A4A] to-[#00285C] hover:from-[#001A4A] hover:via-[#002366] hover:to-[#00285C] active:from-[#001A4A] active:via-[#002366] active:to-[#00285C] text-white font-bold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.98] text-base sm:text-lg disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <i id="submitIcon" class="fa-solid fa-sign-in-alt"></i>
            <span id="submitText">Sign In</span>
        </button>

        <div class="relative my-5 sm:my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-xs sm:text-sm">
                <span class="px-3 sm:px-4 bg-white text-[#333C4D]">New faculty member?</span>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('faculty.register') }}" class="inline-flex items-center justify-center gap-2 text-[#002366] font-semibold hover:text-[#001A4A] transition-colors group text-sm sm:text-base">
                <i class="fa-solid fa-user-plus group-hover:scale-110 transition-transform"></i>
                <span class="border-b-2 border-transparent group-hover:border-[#002366] transition-all">Create Faculty Account</span>
            </a>
        </div>

        <div class="text-center pt-3 sm:pt-4 border-t border-gray-200">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 text-[#333C4D] hover:text-[#172030] font-medium transition-all group text-sm sm:text-base">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Back to home</span>
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