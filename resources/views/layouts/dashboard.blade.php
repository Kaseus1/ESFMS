<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ucfirst(auth()->user()->role) }} Dashboard | {{ config('app.name', 'ESFMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire -->
    @livewireStyles
    
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            background-color: #f9fafb;
        }
        .sidebar {
            background-color: #002147;
            color: #fff;
            width: 240px;
            position: fixed;
            top: 0;
            bottom: 0;
            padding-top: 1rem;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .sidebar a {
            color: #d1d5db;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #1e3a8a;
            color: #fff;
        }
        .sidebar i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 240px;
            padding: 1.5rem;
            min-height: 100vh;
        }
        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logout-btn {
            background-color: #dc2626;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: 0.2s;
        }
        .logout-btn:hover {
            background-color: #b91c1c;
        }
    </style>
</head>

<body class="font-sans antialiased">
    @php
        $role = auth()->user()->role;
        $routePrefix = $role;
    @endphp

    <div class="sidebar">
        <h2 class="text-center text-xl font-semibold text-white mb-4">
            ESFMS {{ ucfirst($role) }}
        </h2>

        <a href="{{ route($routePrefix . '.dashboard') }}" 
           class="{{ request()->is($role . '/dashboard') ? 'active' : '' }}">
           <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>

        @if($role === 'admin')
            <a href="{{ route('admin.users.index') }}" 
               class="{{ request()->is('admin/users*') ? 'active' : '' }}">
               <i class="fa-solid fa-users"></i> User Management
            </a>
            
            <a href="{{ route('admin.guests.index') }}" 
               class="{{ request()->is('admin/guests*') ? 'active' : '' }}">
               <i class="fa-solid fa-user-group"></i> Guest Management
            </a>
        @endif

        <a href="{{ route($routePrefix . '.facilities.index') }}" 
           class="{{ request()->is($role . '/facilities*') ? 'active' : '' }}">
           <i class="fa-solid fa-building"></i> Facilities
        </a>

        <a href="{{ route($routePrefix . '.reservations.index') }}" 
           class="{{ request()->is($role . '/reservations*') ? 'active' : '' }}">
           <i class="fa-solid fa-calendar-check"></i> {{ $role === 'admin' ? 'Reservations' : 'My Reservations' }}
        </a>

        @if($role !== 'admin')
            <a href="{{ route($routePrefix . '.reservations.create') }}" 
               class="{{ request()->is($role . '/reservations/create') ? 'active' : '' }}">
               <i class="fa-solid fa-plus-circle"></i> New Reservation
            </a>
        @endif

        <a href="{{ route($routePrefix . '.profile.edit') }}" 
           class="{{ request()->is($role . '/profile*') ? 'active' : '' }}">
           <i class="fa-solid fa-user"></i> Profile
        </a>

        <div class="mt-auto mb-4 px-4">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="w-full text-left logout-btn block">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1 class="text-lg font-semibold text-gray-800">
                @yield('title', ucfirst($role) . ' Dashboard')
            </h1>
            <div>
                <span class="text-gray-700 font-medium">
                    {{ Auth::user()->name }}
                </span>
            </div>
        </div>

        <div class="p-4">
            {{-- Page-specific content --}}
            @yield('content')
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @livewireScripts
    @stack('scripts')
</body>
</html>