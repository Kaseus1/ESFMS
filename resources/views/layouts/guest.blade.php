<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'guest Portal') | {{ config('app.name', 'CPAC ESFMS') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root { --cpac-primary: #002366; --text-dark: #172030; --bg-light: #F8F9FA; }
        [x-cloak] { display: none !important; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-light); min-height: 100vh; display: flex; flex-direction: column; color: var(--text-dark); }
        
        /* Navbar */
        .navbar { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 1000; height: 70px; }
        .navbar-container { max-width: 1400px; margin: 0 auto; padding: 0 1.5rem; display: flex; align-items: center; justify-content: space-between; height: 100%; }
        
        /* Brand */
        .navbar-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: var(--cpac-primary); flex-shrink: 0; }
        .brand-logo { width: 40px; height: 40px; background: var(--cpac-primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; }
        .brand-text h1 { font-size: 1.1rem; font-weight: 800; }

        /* Desktop Nav Links */
        .desktop-nav { display: flex; gap: 1.5rem; align-items: center; margin-left: auto; margin-right: 1.5rem; }
        .nav-link { color: #4B5563; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: color 0.2s; position: relative; padding: 0.5rem 0; white-space: nowrap; cursor: pointer; }
        .nav-link:hover { color: var(--cpac-primary); }
        .nav-link.active { color: var(--cpac-primary); }
        .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--cpac-primary); border-radius: 2px; }
        
        /* Desktop Logout Button */
        .desktop-logout { color: #EF4444; display: flex; align-items: center; gap: 0.4rem; background: none; border: none; font-weight: 600; font-size: 0.9rem; padding: 0.5rem 0; transition: color 0.2s; }
        .desktop-logout:hover { color: #DC2626; }
        
        /* Divider */
        .nav-divider { width: 1px; height: 24px; background: #E5E7EB; margin: 0 0.5rem; }

        /* Profile Button */
        .profile-btn { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        
        /* Desktop Dropdown (User Info Only) */
        .desktop-dropdown { position: absolute; top: 100%; right: 0; margin-top: 0.75rem; width: 280px; background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid rgba(0,0,0,0.05); z-index: 1001; display: none; transform-origin: top right; }

        /* Mobile Drawer (User Info + Features) */
        .mobile-menu-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; display: none; backdrop-filter: blur(4px); }
        .mobile-menu-drawer { position: fixed; top: 0; right: 0; bottom: 0; width: 85%; max-width: 320px; background: white; z-index: 2001; box-shadow: -5px 0 20px rgba(0,0,0,0.1); display: flex; flex-direction: column; transform: translateX(100%); transition: transform 0.3s ease-out; }
        
        /* Menu Content Styles */
        .menu-header { padding: 1.5rem; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; text-align: center; }
        .menu-grid { display: grid; grid-template-columns: 1fr 1fr; padding: 1rem; gap: 0.5rem; overflow-y: auto; }
        .menu-item { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1rem; border-radius: 12px; text-decoration: none; color: var(--text-dark); background: white; border: 1px solid #f1f5f9; text-align: center; gap: 0.5rem; height: 90px; }
        .menu-item:hover { background: #F1F5F9; border-color: #e2e8f0; }
        .menu-footer { padding: 1rem; border-top: 1px solid #E2E8F0; margin-top: auto; }

        /* Responsive Logic */
        @media (min-width: 769px) { 
            .desktop-dropdown[x-show="true"] { display: block; } 
            .mobile-menu-drawer { display: none !important; }
        }
        @media (max-width: 768px) { 
            .desktop-nav { display: none; } /* Hide navbar links on mobile */
            .navbar { height: 60px; padding: 0; }
            .navbar-container { padding: 0 1rem; }
            .desktop-dropdown { display: none !important; } 
            .mobile-menu-overlay[x-show="true"] { display: block; } 
            .mobile-menu-drawer.open { transform: translateX(0); } 
        }

        .main-container { flex: 1; max-width: 1400px; margin: 0 auto; padding: 2.5rem 2rem; width: 100%; }
        /* Footer is handled by the partial now */
    </style>
    @stack('head-scripts')
</head>

<body x-data="{ menuOpen: false }">

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="navbar-container">
                <a href="{{ route('guest.dashboard') }}" class="navbar-brand">
                    <div class="brand-logo"><i class="fa-solid fa-graduation-cap"></i></div>
                    <div class="brand-text"><h1>CPAC ESFMS</h1><p class="hidden sm:block text-xs opacity-70">guest Portal</p></div>
                </a>

                <div class="desktop-nav">
                    <a href="{{ route('guest.dashboard') }}" class="nav-link {{ request()->routeIs('guest.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('guest.facilities.index') }}" class="nav-link {{ request()->routeIs('guest.facilities.*') ? 'active' : '' }}">Facilities</a>
                    <a href="{{ route('guest.reservations.index') }}" class="nav-link {{ request()->routeIs('guest.reservations.*') ? 'active' : '' }}">Reservations</a>
                    <a href="{{ route('guest.wallet.index') }}" class="nav-link {{ request()->routeIs('guest.wallet.*') ? 'active' : '' }}">Wallet</a>
                    
                    <div class="nav-divider"></div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="nav-link desktop-logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>

                <div style="position: relative;">
                    <button class="profile-btn" @click="menuOpen = !menuOpen">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>

                    <div class="desktop-dropdown" x-show="menuOpen" @click.outside="menuOpen = false" x-transition x-cloak>
                        <div class="menu-header">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white flex items-center justify-center text-2xl font-bold mx-auto mb-2 shadow-md">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="font-bold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 mb-3">{{ Auth::user()->email }}</div>
                            <a href="{{ route('guest.profile.edit') }}" class="block w-full py-2 border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 mb-2">Manage Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="mobile-menu-overlay" x-show="menuOpen" @click="menuOpen = false" x-transition.opacity x-cloak></div>
        <div class="mobile-menu-drawer" :class="{ 'open': menuOpen }">
            <div class="flex justify-end p-4"><button @click="menuOpen = false" class="text-gray-500 hover:text-red-500"><i class="fa-solid fa-xmark text-2xl"></i></button></div>
            
            <div class="menu-content-wrapper h-full flex flex-col">
                <div class="menu-header">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white flex items-center justify-center text-2xl font-bold mx-auto mb-2 shadow-md">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="font-bold text-gray-900 text-lg">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500 mb-3">{{ Auth::user()->email }}</div>
                    <a href="{{ route('guest.profile.edit') }}" class="inline-block px-4 py-1.5 border border-gray-300 rounded-full text-xs font-bold text-gray-600 hover:bg-gray-50">Manage Account</a>
                </div>

                <div class="menu-grid">
                    <a href="{{ route('guest.dashboard') }}" class="menu-item"><i class="fa-solid fa-house text-xl text-[#002366]"></i><span class="text-xs font-bold text-gray-700">Dashboard</span></a>
                    <a href="{{ route('guest.facilities.index') }}" class="menu-item"><i class="fa-solid fa-building text-xl text-[#002366]"></i><span class="text-xs font-bold text-gray-700">Facilities</span></a>
                    <a href="{{ route('guest.reservations.index') }}" class="menu-item"><i class="fa-solid fa-calendar-check text-xl text-[#002366]"></i><span class="text-xs font-bold text-gray-700">Reservations</span></a>
                    <a href="{{ route('guest.wallet.index') }}" class="menu-item"><i class="fa-solid fa-wallet text-xl text-[#10B981]"></i><span class="text-xs font-bold text-gray-700">Wallet</span></a>
                </div>

                <div class="menu-footer">
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full py-2.5 bg-red-50 text-red-500 font-bold rounded-lg hover:bg-red-100 transition flex items-center justify-center gap-2"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</button></form>
                </div>
            </div>
        </div>

        <div class="main-container">
            @if(session('success')) <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg flex items-center gap-2"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div> @endif
            @if(session('error')) <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div> @endif
            @yield('content')
        </div>

        @include('layouts.partials.footer')
    </div>
    @stack('scripts')
</body>
</html>