<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') | {{ config('app.name', 'ESFMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #002366;
            --primary-dark: #001A4A;
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
            --header-height: 70px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F8F9FA; min-height: 100vh; overflow-x: hidden; }

        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            backdrop-filter: blur(2px);
        }
        .mobile-overlay.show { opacity: 1; visibility: visible; }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #172030 0%, #1D2636 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,.1);
            transition: width 0.3s, transform 0.3s;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed:not(.mobile-open) { width: var(--sidebar-collapsed); }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 0 1rem;
            height: var(--header-height);
            border-bottom: 1px solid rgba(255,255,255,.1);
            flex-shrink: 0;
        }
        .brand-logo {
            width: 42px;
            height: 42px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            flex-shrink: 0;
        }
        .brand-text { white-space: nowrap; overflow: hidden; transition: opacity .2s; opacity: 1; width: auto; }
        .sidebar.collapsed:not(.mobile-open) .brand-text { opacity: 0; width: 0; }
        
        .brand-text h1 { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: .15rem; }
        .brand-text p { font-size: .7rem; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: .5px; }

        /* Toggle Button */
        .sidebar-toggle {
            position: absolute;
            right: -10px;
            top: 25px;
            width: 20px;
            height: 20px;
            background: #fff;
            border: 2px solid var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            transition: transform .2s;
            z-index: 10;
        }
        .sidebar-toggle:hover { transform: scale(1.15); }
        .sidebar.collapsed:not(.mobile-open) .sidebar-toggle i { transform: rotate(180deg); }

        /* Navigation */
        .sidebar-nav { flex: 1; overflow-y: auto; padding: .75rem 0; overflow-x: hidden; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: rgba(255,255,255,.05); }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }

        .nav-section { padding: .5rem .75rem .25rem; }
        .nav-section-title {
            font-size: .65rem;
            font-weight: 700;
            color: rgba(255,255,255,.4);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: .4rem;
            padding-left: .75rem;
            white-space: nowrap;
            transition: opacity .2s;
            opacity: 1;
        }
        .sidebar.collapsed:not(.mobile-open) .nav-section-title { opacity: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .7rem 1rem;
            color: rgba(255,255,255,.8);
            text-decoration: none;
            transition: all .2s;
            position: relative;
            border-left: 3px solid transparent;
            white-space: nowrap;
            border-radius: 8px;
            margin: .15rem .5rem;
        }
        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; border-left-color: transparent; }
        .nav-item.active { background: var(--primary); color: #fff; border-left-color: transparent; font-weight: 600; }
        .nav-item i { width: 18px; text-align: center; font-size: 1rem; flex-shrink: 0; }
        
        .nav-item-text { flex: 1; transition: opacity .2s; font-size: .9rem; opacity: 1; display: block; }
        .sidebar.collapsed:not(.mobile-open) .nav-item-text { opacity: 0; display: none; }

        .nav-badge {
            background: #ef4444;
            color: #fff;
            padding: .15rem .4rem;
            border-radius: 10px;
            font-size: .65rem;
            font-weight: 700;
            animation: pulse 2s infinite;
        }
        .sidebar.collapsed:not(.mobile-open) .nav-badge { position: absolute; top: .4rem; right: .4rem; padding: .2rem .35rem; }

        /* User Profile */
        .sidebar-user { padding: .75rem; border-top: 1px solid rgba(255,255,255,.1); position: relative; margin-top: auto; }
        .user-profile {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .65rem;
            background: rgba(255,255,255,.08);
            border-radius: 10px;
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }
        .user-profile:hover { background: rgba(255,255,255,.12); }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
            color: #fff;
            flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; transition: opacity .2s; opacity: 1; display: block; }
        .sidebar.collapsed:not(.mobile-open) .user-info { opacity: 0; width: 0; display: none; }
        
        .user-name { display: block; color: #fff; font-weight: 600; font-size: .85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { display: block; color: rgba(255,255,255,.5); font-size: .7rem; }

        .user-profile i.fa-chevron-down { font-size: .8rem; color: rgba(255,255,255,.6); transition: transform .2s; margin-left: auto; }
        .user-profile i.rotate-180 { transform: rotate(180deg); }
        .sidebar.collapsed:not(.mobile-open) .user-profile i.fa-chevron-down { display: none; }

        /* Dropdown Menu (Sidebar) */
        .dropdown-menu {
            position: absolute;
            bottom: calc(100% + 10px);
            left: .75rem;
            right: .75rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,.15);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all .25s;
            z-index: 1002;
            pointer-events: none;
        }
        .dropdown-menu.show { opacity: 1; visibility: visible; transform: translateY(0); pointer-events: auto; }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .7rem .9rem;
            color: #333C4D;
            text-decoration: none;
            transition: background .2s;
            font-size: .85rem;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        .dropdown-item:hover { background: #f1f5f9; }
        .dropdown-item i { width: 18px; text-align: center; color: var(--primary); }
        .dropdown-item.logout { color: #ef4444; }
        .dropdown-item.logout i { color: #ef4444; }
        .dropdown-divider { height: 1px; background: #f1f5f9; margin: .25rem 0; }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left .3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar.collapsed:not(.mobile-open) + .main-content { margin-left: var(--sidebar-collapsed); }

        /* Top Bar */
        .top-bar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            height: var(--header-height);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: 0 1px 3px rgba(0,0,0,.03);
        }
        .top-bar-title { font-size: 1.4rem; font-weight: 700; color: #172030; }
        .top-bar-actions { display: flex; align-items: center; gap: .75rem; position: relative; }

        .notification-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .2s;
            border: none;
        }
        .notification-btn:hover { background: #e2e8f0; }
        .notification-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background: #ef4444;
            color: #fff;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            font-size: .65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            padding: 0 4px;
        }

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            top: calc(100% + 15px);
            right: 0;
            width: 350px;
            max-width: 90vw;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all .3s;
            z-index: 1001;
            max-height: 550px;
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
        }
        .notification-dropdown.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .notification-header { padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
        .notification-header h3 { font-size: 1rem; font-weight: 700; color: #172030; }
        .mark-all-read { font-size: .75rem; color: var(--primary); cursor: pointer; padding: .25rem .5rem; border-radius: 4px; background: none; border: none; }
        .mark-all-read:hover { background: #f1f5f9; }
        .notification-list { overflow-y: auto; max-height: 400px; padding: .5rem 0; }
        .notification-list::-webkit-scrollbar { width: 6px; }
        .notification-list::-webkit-scrollbar-track { background: #f1f5f9; }
        .notification-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .notification-item { padding: 1rem 1.25rem; border-left: 3px solid transparent; transition: all .2s; cursor: pointer; text-decoration: none; display: block; color: inherit; }
        .notification-item:hover { background: #F8F9FA; }
        .notification-item.unread { background: #f0fdf4; border-left-color: var(--primary); }
        .notification-item.type-reservation.unread { background: #fefce8; border-left-color: #eab308; }
        .notification-item.type-guest.unread { background: #eff6ff; border-left-color: #3b82f6; }
        .notification-item.type-success.unread { background: #f0fdf4; border-left-color: #10b981; }
        .notification-content { display: flex; gap: .75rem; }
        .notification-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; }
        .notification-icon.yellow { background: #fef3c7; color: #d97706; }
        .notification-icon.blue { background: #dbeafe; color: #2563eb; }
        .notification-icon.green { background: #d1fae5; color: #059669; }
        .notification-text { flex: 1; min-width: 0; }
        .notification-title { font-weight: 600; font-size: .875rem; color: #172030; margin-bottom: .25rem; }
        .notification-message { font-size: .8rem; color: #333C4D; margin-bottom: .25rem; }
        .notification-details { font-size: .75rem; color: #64748b; font-style: italic; }
        .notification-time { font-size: .7rem; color: #333C4D; opacity: 0.5; margin-top: .25rem; }
        .notification-empty { padding: 3rem 1.25rem; text-align: center; color: #333C4D; opacity: 0.6; }
        .notification-empty i { font-size: 3rem; margin-bottom: 1rem; opacity: .3; }
        .notification-footer { padding: .75rem 1.25rem; border-top: 1px solid #e2e8f0; text-align: center; }
        .notification-footer a { font-size: .8rem; color: var(--primary); text-decoration: none; font-weight: 600; }
        .notification-footer a:hover { text-decoration: underline; }
        .loading-spinner { display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f4f6; border-top-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite; }
        .notification-badge.has-unread { animation: badge-pulse 2s infinite; }

        .content-wrapper { flex: 1; padding: 1.5rem; }
        .alert { margin-bottom: 1rem; padding: 1rem; border-radius: 10px; display: flex; align-items: center; gap: .75rem; }
        .alert-success { background: #d1fae5; border-left: 4px solid var(--primary); color: #065f46; }
        .alert-error { background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; }
        .alert i { font-size: 1.1rem; }
        .alert-title { font-weight: 700; font-size: .9rem; margin-bottom: .15rem; }
        .alert-message { font-size: .85rem; }

        footer { background: #fff; border-top: 1px solid #e2e8f0; padding: 1.5rem; text-align: center; color: #333C4D; font-size: .85rem; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 280px !important; }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar.mobile-open .brand-text,
            .sidebar.mobile-open .nav-item-text,
            .sidebar.mobile-open .user-info,
            .sidebar.mobile-open .nav-section-title {
                opacity: 1 !important; display: block !important; width: auto !important;
            }
            .sidebar-toggle { display: none; }
            .main-content { margin-left: 0 !important; }
            .sidebar.collapsed + .main-content { margin-left: 0; }
            .mobile-menu-btn { display: flex; width: 38px; height: 38px; background: var(--primary); color: #fff; border: none; border-radius: 8px; cursor: pointer; align-items: center; justify-content: center; transition: background 0.2s; }
            .mobile-menu-btn:active { background: var(--primary-dark); }
            .top-bar { padding: 0 1rem; }
            .top-bar-title { font-size: 1.1rem; }
            .content-wrapper { padding: 1rem; }
            .notification-dropdown { position: fixed; top: var(--header-height); left: 0; right: 0; width: 100%; max-width: 100%; border-radius: 0; border-left: none; border-right: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        }
        @media (min-width: 769px) { .mobile-menu-btn { display: none; } }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .7; } }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes badge-pulse { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.1); opacity: .8; } }
    </style>

    @stack('head-scripts')
</head>

<body x-data="{ sidebarOpen: window.innerWidth > 768, mobileOpen: false }"
      @resize.window="if (window.innerWidth > 768) { mobileOpen = false; sidebarOpen = true; } else { sidebarOpen = false; }">
    
    <div class="mobile-overlay" :class="{'show': mobileOpen}" @click="mobileOpen = false"></div>

    <aside class="sidebar" :class="{'collapsed': !sidebarOpen, 'mobile-open': mobileOpen}">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="brand-text"><h1>ESFMS</h1><p>Admin Portal</p></div>
            <button class="sidebar-toggle" @click="sidebarOpen = !sidebarOpen"><i class="fa-solid fa-chevron-left"></i></button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main Menu</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{request()->routeIs('admin.dashboard')?'active':''}}">
                    <i class="fa-solid fa-house"></i><span class="nav-item-text">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{request()->routeIs('admin.users.*')?'active':''}}">
                    <i class="fa-solid fa-users"></i><span class="nav-item-text">Users</span>
                </a>
                <a href="{{ route('admin.guests.index') }}" class="nav-item {{request()->routeIs('admin.guests.*')?'active':''}}">
                    <i class="fa-solid fa-user-group"></i><span class="nav-item-text">Guest Management</span>
                    @php $pendingGuests = \App\Models\User::where('role','guest')->where('status','pending')->count(); @endphp
                    @if($pendingGuests > 0) <span class="nav-badge">{{$pendingGuests}}</span> @endif
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Facilities</div>
                <a href="{{ route('admin.facilities.index') }}" class="nav-item {{request()->routeIs('admin.facilities.*')?'active':''}}">
                    <i class="fa-solid fa-building"></i><span class="nav-item-text">All Facilities</span>
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="nav-item {{request()->routeIs('admin.reservations.*')?'active':''}}">
                    <i class="fa-solid fa-calendar-check"></i><span class="nav-item-text">Reservations</span>
                    @php $pendingReservations = \App\Models\Reservation::where('status','pending')->count(); @endphp
                    @if($pendingReservations > 0) <span class="nav-badge">{{$pendingReservations}}</span> @endif
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Finance</div>
                <a href="{{ route('admin.wallet.index') }}" class="nav-item {{request()->routeIs('admin.wallet.*')?'active':''}}">
                    <i class="fa-solid fa-wallet"></i><span class="nav-item-text">Wallet Management</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Reports</div>
                <a href="{{ route('admin.analytics.index') }}" class="nav-item {{request()->routeIs('admin.analytics.*')?'active':''}}">
                    <i class="fa-solid fa-chart-line"></i><span class="nav-item-text">Analytics</span>
                </a>
                <a href="{{ route('admin.export.index') }}" class="nav-item {{request()->routeIs('admin.export.*')?'active':''}}">
                    <i class="fa-solid fa-file-export"></i><span class="nav-item-text">Export Data</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-user" x-data="{ profileDropdown: false }" @click.away="profileDropdown = false">
            <div class="user-profile" @click.stop="profileDropdown = !profileDropdown">
                <div class="user-avatar">{{strtoupper(substr(Auth::user()->name,0,1))}}</div>
                <div class="user-info"><span class="user-name">{{Auth::user()->name}}</span><span class="user-role">Administrator</span></div>
                <i class="fa-solid fa-chevron-down" :class="{'rotate-180': profileDropdown}"></i>
            </div>
            <div class="dropdown-menu" :class="{'show': profileDropdown}" style="bottom: 100%; top: auto; left: 0.75rem; right: 0.75rem; margin-bottom: 10px;">
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item logout"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div style="display:flex;align-items:center;gap:.85rem">
                <button class="mobile-menu-btn" @click="mobileOpen = !mobileOpen"><i class="fa-solid fa-bars"></i></button>
                <h1 class="top-bar-title">@yield('title','Dashboard')</h1>
            </div>
            
            <div class="top-bar-actions">
                <div style="position: relative;">
      <button class="notification-btn" id="notificationButton">
    <i class="fa-solid fa-bell"></i>
    {{-- FIX: Query your custom Notification model directly by user_id --}}
    @php 
        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count(); 
    @endphp
    
    <span class="notification-badge {{ $unreadCount > 0 ? 'has-unread' : '' }}" 
          style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">
        {{ $unreadCount }}
    </span>
</button>
                    <div id="notificationDropdown" class="notification-dropdown">
                        <div class="notification-header"><h3>Notifications</h3><button id="markAllRead" class="mark-all-read">Mark all read</button></div>
                        <div id="notificationList" class="notification-list"><div style="padding:2rem;text-align:center;"><div class="loading-spinner"></div></div></div>
                        <div class="notification-footer"><a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}">View All Pending Items →</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i><div><div class="alert-title">Success!</div><div class="alert-message">{{session('success')}}</div></div></div>
            @endif
            @if(session('error'))
                <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i><div><div class="alert-title">Error!</div><div class="alert-message">{{session('error')}}</div></div></div>
            @endif
            @yield('content')
        </div>

        <footer><p>© {{date('Y')}} ESFMS. All rights reserved.</p></footer>
    </main>

    @stack('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBtn = document.getElementById('notificationButton');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationList = document.getElementById('notificationList');
        const notificationBadge = document.querySelector('.notification-badge');
        const markAllReadBtn = document.getElementById('markAllRead');
        
        let notifications = [];
        let dropdownOpen = false;

        // 1. Initial Check (Run immediately when page loads)
        checkNotifications(true);

        // 2. Auto-Check every 30 seconds (Real-time feel)
        setInterval(() => {
            if (!dropdownOpen) {
                checkNotifications(true);
            }
        }, 30000);

        notificationBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownOpen = !dropdownOpen;
            if (dropdownOpen) {
                notificationDropdown.classList.add('show');
                // When clicking, we want to show the spinner (silent = false)
                checkNotifications(false);
            } else {
                notificationDropdown.classList.remove('show');
            }
        });

        document.addEventListener('click', function(e) {
            if (dropdownOpen && !notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationDropdown.classList.remove('show');
                dropdownOpen = false;
            }
        });

        // Renamed to 'checkNotifications' and added 'silent' mode
        function checkNotifications(silent = false) {
            // Only show spinner if the user actually clicked the button (not for background checks)
            if (!silent) {
                notificationList.innerHTML = '<div style="padding:2rem;text-align:center;"><div class="loading-spinner"></div></div>';
            }

            fetch('{{ route("admin.notifications.index") }}')
                .then(response => response.json())
                .then(data => {
                    notifications = data.notifications || [];
                    renderNotifications();
                    updateBadge(data.unread_count);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    if (!silent) {
                        notificationList.innerHTML = '<div class="notification-empty"><i class="fa-solid fa-circle-exclamation"></i><p>Failed to load notifications</p></div>';
                    }
                });
        }

        function renderNotifications() {
            if (!notifications || notifications.length === 0) {
                notificationList.innerHTML = `<div class="notification-empty"><i class="fa-solid fa-bell-slash"></i><p>No notifications</p></div>`;
                return;
            }
            notificationList.innerHTML = notifications.map(n => `
                <a href="${n.url}" class="notification-item type-${n.color} ${n.read ? '' : 'unread'}" data-id="${n.id}">
                    <div class="notification-content">
                        <div class="notification-icon ${n.color}"><i class="fa-solid ${n.icon}"></i></div>
                        <div class="notification-text">
                            <div class="notification-title">${n.title}</div>
                            <div class="notification-message">${n.message}</div>
                            <div class="notification-time">${n.time}</div>
                        </div>
                    </div>
                </a>
            `).join('');
            
            // Re-attach click listeners
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() { markAsRead(this.dataset.id); });
            });
        }

        function markAsRead(id) {
            fetch('{{ route("admin.notifications.mark-read") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ id: id })
            });
            const item = notifications.find(n => n.id === id);
            if(item) item.read = true;
            renderNotifications();
            // Recalculate badge locally immediately
            const unread = notifications.filter(n => !n.read).length;
            updateBadge(unread);
        }

        markAllReadBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            fetch('{{ route("admin.notifications.mark-all-read") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(response => response.json()).then(data => {
                if(data.success) {
                    notifications.forEach(n => n.read = true);
                    renderNotifications();
                    updateBadge(0);
                }
            });
        });

        function updateBadge(count) {
            if (notificationBadge) {
                if (count > 0) {
                    notificationBadge.textContent = count;
                    notificationBadge.style.display = 'flex';
                    notificationBadge.classList.add('has-unread');
                } else {
                    notificationBadge.style.display = 'none';
                    notificationBadge.classList.remove('has-unread');
                }
            }
        }
    });
    </script>
</body>
</html>