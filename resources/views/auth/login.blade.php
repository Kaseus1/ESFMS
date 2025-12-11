<x-guest-layout>
    <div class="text-center mb-6">
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <lord-icon
            src="https://cdn.lordicon.com/hjeefwhm.json"
            trigger="loop"
            delay="1500"
            style="width:70px;height:70px"
            class="mx-auto mb-3">
        </lord-icon>

        <h2 class="text-2xl font-semibold text-primary-navy mb-1">
            Welcome Back 👋
        </h2>
        <p class="text-gray-600 text-sm">
            Sign in to access <span class="text-accent-gold font-medium">CampusReserve</span>
        </p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-5 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <lord-icon
                    src="https://cdn.lordicon.com/lomfljuq.json"
                    trigger="loop"
                    colors="primary:#22c55e"
                    style="width:24px;height:24px">
                </lord-icon>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Messages for Account Status -->
    @if($errors->any())
    <div class="mb-5 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <lord-icon
                    src="https://cdn.lordicon.com/keaiyjcx.json"
                    trigger="loop"
                    colors="primary:#eab308"
                    style="width:28px;height:28px">
                </lord-icon>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-yellow-800 mb-1">
                    @if($errors->has('email') && (str_contains($errors->first('email'), 'pending') || str_contains($errors->first('email'), 'rejected')))
                        Account Status Notice
                    @else
                        Unable to Sign In
                    @endif
                </h3>
                <div class="text-sm text-yellow-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                
                @if($errors->has('email') && str_contains($errors->first('email'), 'pending'))
                <div class="mt-3 pt-3 border-t border-yellow-200">
                    <p class="text-xs font-semibold text-yellow-800 mb-2">📋 What happens next?</p>
                    <ul class="text-xs text-yellow-700 space-y-1">
                        <li class="flex items-start">
                            <span class="text-accent-gold mr-2">•</span>
                            <span>Our administrator will review your registration</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-accent-gold mr-2">•</span>
                            <span>You'll receive an email notification once approved</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-accent-gold mr-2">•</span>
                            <span>This usually takes 1-2 business days</span>
                        </li>
                    </ul>
                </div>
                @endif

                @if($errors->has('email') && str_contains($errors->first('email'), 'rejected'))
                <div class="mt-3 pt-3 border-t border-yellow-200">
                    <p class="text-xs text-yellow-700">
                        Need assistance? Contact us at 
                        <a href="mailto:admin@campusreserve.edu" class="font-medium text-accent-gold hover:underline">
                            admin@campusreserve.edu
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Login Card -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-primary-navy" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full border-gray-300 rounded-lg focus:border-secondary-navy focus:ring-2 focus:ring-primary-navy/70 transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-primary-navy" />
            <x-text-input 
                id="password" 
                class="block mt-2 w-full border-gray-300 rounded-lg focus:border-secondary-navy focus:ring-2 focus:ring-primary-navy/70 transition" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember & Forgot Password -->
        <div class="flex items-center justify-between mt-3">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-primary-navy shadow-sm focus:ring-primary-navy" 
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a 
                    class="text-sm text-secondary-navy hover:text-primary-navy font-medium transition" 
                    href="{{ route('password.request') }}"
                >
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="pt-4">
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 py-3 bg-primary-navy hover:bg-secondary-navy text-white font-semibold rounded-lg shadow-md hover:shadow-[0_0_15px_rgba(0,33,71,0.4)] transition-all duration-300 transform hover:-translate-y-0.5"
            >
                <lord-icon
                    src="https://cdn.lordicon.com/msoeawqm.json"
                    trigger="hover"
                    colors="primary:#ffffff"
                    style="width:25px;height:25px">
                </lord-icon>
                {{ __('Log in') }}
            </button>
        </div>

        <!-- Sign up -->
        <div class="text-center text-sm text-gray-500 mt-4">
            <p>Don't have an account? 
                <a href="{{ route('register') }}" class="text-accent-gold font-medium hover:underline">Sign up</a>
            </p>
        </div>
    </form>
</x-guest-layout>