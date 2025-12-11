<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>System Administrator Access - ESFMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-navy': '#002147',
                        'secondary-navy': '#003366',
                        'accent-gold': '#d4af37',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-primary-navy to-gray-800 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Warning Banner -->
        <div class="bg-red-900/50 border border-red-500 rounded-lg p-4 mb-6 text-center">
            <p class="text-red-200 text-sm font-semibold">
                🔐 RESTRICTED ACCESS - AUTHORIZED PERSONNEL ONLY
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary-navy rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">System Administrator</h1>
                <p class="text-gray-600 mt-2">ESFMS Control Panel</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="intended_role" value="admin">

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Administrator Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-navy focus:border-transparent transition"
                        placeholder="admin@example.com"
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-navy focus:border-transparent transition"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Error Display -->
                @error('role')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
                    </div>
                @enderror

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-primary-navy hover:bg-secondary-navy text-white font-semibold py-3 px-4 rounded-lg transition duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy"
                >
                    🔐 Authenticate as Administrator
                </button>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-yellow-700 text-xs text-center">
                    <strong>Security Notice:</strong> This portal is monitored. All access attempts are logged.
                </p>
            </div>
        </div>
    </div>
</body>
</html>