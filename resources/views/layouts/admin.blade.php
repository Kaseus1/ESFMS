<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, mobileOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') | {{ config('app.name', 'ESFMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 for calendar events -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #002366;
            --primary-dark: #001A4A;
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
        }

        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:#F8F9FA;min-height:100vh}

        /* Sidebar */
        .sidebar{position:fixed;left:0;top:0;bottom:0;width:var(--sidebar-width);background:linear-gradient(180deg,#172030 0%,#1D2636 100%);box-shadow:4px 0 15px rgba(0,0,0,.1);transition:width .3s;z-index:1000;display:flex;flex-direction:column}
        .sidebar.collapsed{width:var(--sidebar-collapsed)}

        /* Brand */
        .sidebar-brand{display:flex;align-items:center;gap:.75rem;padding:1.25rem 1rem;border-bottom:1px solid rgba(255,255,255,.1);min-height:70px}
        .brand-logo{width:42px;height:42px;background:var(--primary);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#fff;flex-shrink:0}
        .brand-text{white-space:nowrap;overflow:hidden;transition:opacity .2s}
        .sidebar.collapsed .brand-text{opacity:0;width:0}
        .brand-text h1{font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.15rem}
        .brand-text p{font-size:.7rem;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.5px}

        /* Toggle Button */
        .sidebar-toggle{position:absolute;right:-10px;top:35px;width:20px;height:20px;background:#fff;border:2px solid var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.1);transition:transform .2s;z-index:10}
        .sidebar-toggle:hover{transform:scale(1.15)}
        .sidebar-toggle i{color:var(--primary);font-size:.65rem;transition:transform .3s}
        .sidebar.collapsed .sidebar-toggle i{transform:rotate(180deg)}

        /* Navigation */
        .sidebar-nav{flex:1;overflow-y:auto;padding:.75rem 0;overflow-x:hidden}
        .sidebar-nav::-webkit-scrollbar{width:4px}
        .sidebar-nav::-webkit-scrollbar-track{background:rgba(255,255,255,.05)}
        .sidebar-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.15);border-radius:2px}

        .nav-section{padding:.5rem .75rem .25rem}
        .nav-section-title{font-size:.65rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-bottom:.4rem;padding-left:.75rem;white-space:nowrap;transition:opacity .2s}
        .sidebar.collapsed .nav-section-title{opacity:0}

        .nav-item{display:flex;align-items:center;gap:.85rem;padding:.7rem 1rem;color:rgba(255,255,255,.8);text-decoration:none;transition:all .2s;position:relative;border-left:3px solid transparent;white-space:nowrap;border-radius:8px;margin:.15rem .5rem}
        .nav-item:hover{background:rgba(255,255,255,.08);color:#fff;border-left-color:transparent}
        .nav-item.active{background:var(--primary);color:#fff;border-left-color:transparent;font-weight:600}
        .nav-item i{width:18px;text-align:center;font-size:1rem;flex-shrink:0}
        .nav-item-text{flex:1;transition:opacity .2s;font-size:.9rem}
        .sidebar.collapsed .nav-item-text{opacity:0;width:0}

        .nav-badge{background:#ef4444;color:#fff;padding:.15rem .4rem;border-radius:10px;font-size:.65rem;font-weight:700;animation:pulse 2s infinite}
        .sidebar.collapsed .nav-badge{position:absolute;top:.4rem;right:.4rem;padding:.2rem .35rem}

        /* User Profile */
        .sidebar-user{padding:.75rem;border-top:1px solid rgba(255,255,255,.1);position:relative;margin-top:auto}
        .user-profile{display:flex;align-items:center;gap:.65rem;padding:.65rem;background:rgba(255,255,255,.08);border-radius:10px;cursor:pointer;transition:all .2s;position:relative}
        .user-profile:hover{background:rgba(255,255,255,.12)}
        .user-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-dark));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;color:#fff;flex-shrink:0}
        .user-info{flex:1;min-width:0;transition:opacity .2s}
        .sidebar.collapsed .user-info{opacity:0;width:0}
        .user-name{display:block;color:#fff;font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-role{display:block;color:rgba(255,255,255,.5);font-size:.7rem}

        /* Dropdown Menu */
        .dropdown-menu{
            position:absolute;
            top:auto;
            bottom:calc(100% + 10px);
            left:.75rem;
            right:.75rem;
            background:#fff;
            border-radius:10px;
            box-shadow:0 10px 25px rgba(0,0,0,.15);
            overflow:hidden;
            opacity:0;
            visibility:hidden;
            transform:translateY(8px);
            transition:all .25s;
            z-index:1002;
            pointer-events:none;
        }
        .dropdown-menu.show{
            opacity:1;
            visibility:visible;
            transform:translateY(0);
            pointer-events:auto;
        }
        .dropdown-item{
            display:flex;
            align-items:center;
            gap:.65rem;
            padding:.7rem .9rem;
            color:#333C4D;
            text-decoration:none;
            transition:background .2s;
            font-size:.85rem;
            border:none;
            background:none;
            width:100%;
            text-align:left;
            cursor:pointer
        }
        .dropdown-item:hover{background:#f1f5f9}
        .dropdown-item i{width:18px;text-align:center;color:var(--primary)}
        .dropdown-item.logout{color:#ef4444}
        .dropdown-item.logout i{color:#ef4444}
        .dropdown-divider{height:1px;background:#f1f5f9;margin:.25rem 0}

        .user-profile i.fa-chevron-down{font-size:.8rem;color:rgba(255,255,255,.6);transition:transform .2s;margin-left:auto}
        .user-profile i.rotate-180{transform:rotate(180deg)}
        .sidebar.collapsed .user-profile i.fa-chevron-down{display:none}

        /* Main Content */
        .main-content{margin-left:var(--sidebar-width);transition:margin-left .3s;min-height:100vh;display:flex;flex-direction:column}
        .sidebar.collapsed + .main-content{margin-left:var(--sidebar-collapsed)}

        /* Top Bar */
        .top-bar{background:#fff;border-bottom:1px solid #e2e8f0;padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 3px rgba(0,0,0,.03)}
        .top-bar-title{font-size:1.4rem;font-weight:700;color:#172030}
        .top-bar-actions{display:flex;align-items:center;gap:.75rem;position:relative}

        .notification-btn{position:relative;width:38px;height:38px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;border:none}
        .notification-btn:hover{background:#e2e8f0}
        .notification-badge{position:absolute;top:-3px;right:-3px;background:#ef4444;color:#fff;min-width:18px;height:18px;border-radius:9px;font-size:.65rem;font-weight:700;display:flex;align-items:center;justify-content:center;border:2px solid #fff;padding:0 4px}

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 400px;
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
        }

        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #172030;
        }

        .mark-all-read {
            font-size: .75rem;
            color: var(--primary);
            cursor: pointer;
            padding: .25rem .5rem;
            border-radius: 4px;
            transition: background .2s;
            background: none;
            border: none;
        }

        .mark-all-read:hover {
            background: #f1f5f9;
        }

        .notification-list {
            overflow-y: auto;
            max-height: 400px;
            padding: .5rem 0;
        }

        .notification-list::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-left: 3px solid transparent;
            transition: all .2s;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .notification-item:hover {
            background: #F8F9FA;
        }

        .notification-item.unread {
            background: #f0fdf4;
            border-left-color: var(--primary);
        }

        .notification-item.type-yellow.unread {
            background: #fefce8;
            border-left-color: #eab308;
        }

        .notification-item.type-blue.unread {
            background: #eff6ff;
            border-left-color: #3b82f6;
        }

        .notification-content {
            display: flex;
            gap: .75rem;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .notification-icon.yellow {
            background: #fef3c7;
            color: #d97706;
        }

        .notification-icon.blue {
            background: #dbeafe;
            color: #2563eb;
        }

        .notification-icon.green {
            background: #d1fae5;
            color: #059669;
        }

        .notification-text {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            font-size: .875rem;
            color: #172030;
            margin-bottom: .25rem;
        }

        .notification-message {
            font-size: .8rem;
            color: #333C4D;
            margin-bottom: .25rem;
        }

        .notification-details {
            font-size: .75rem;
            color: #333C4D;
            opacity: 0.7;
        }

        .notification-time {
            font-size: .7rem;
            color: #333C4D;
            opacity: 0.5;
            margin-top: .25rem;
        }

        .notification-empty {
            padding: 3rem 1.25rem;
            text-align: center;
            color: #333C4D;
            opacity: 0.6;
        }

        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: .3;
        }

        .notification-footer {
            padding: .75rem 1.25rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .notification-footer a {
            font-size: .8rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .notification-footer a:hover {
            text-decoration: underline;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f4f6;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .notification-badge.has-unread {
            animation: badge-pulse 2s infinite;
        }

        .content-wrapper{flex:1;padding:1.5rem}

        /* Alerts */
        .alert{margin-bottom:1rem;padding:1rem;border-radius:10px;display:flex;align-items:center;gap:.75rem}
        .alert-success{background:#d1fae5;border-left:4px solid var(--primary);color:#065f46}
        .alert-error{background:#fee2e2;border-left:4px solid #ef4444;color:#991b1b}
        .alert i{font-size:1.1rem}
        .alert-title{font-weight:700;font-size:.9rem;margin-bottom:.15rem}
        .alert-message{font-size:.85rem}

        /* Footer */
        footer{background:#fff;border-top:1px solid #e2e8f0;padding:1.5rem;text-align:center;color:#333C4D;font-size:.85rem}

        /* Mobile */
        @media (max-width:768px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.mobile-open{transform:translateX(0)}
            .main-content{margin-left:0!important}
            .mobile-menu-btn{display:flex;width:38px;height:38px;background:var(--primary);color:#fff;border:none;border-radius:8px;cursor:pointer;align-items:center;justify-content:center}
            .sidebar-toggle{display:none}
            .top-bar{padding:.9rem 1rem}
            .top-bar-title{font-size:1.2rem}
            .content-wrapper{padding:1rem}
            .notification-dropdown {
                width: 100vw;
                max-width: 100vw;
                left: 0;
                right: 0;
                border-radius: 0;
            }
        }
        @media (min-width:769px){
            .mobile-menu-btn{display:none}
        }

        @keyframes pulse{
            0%,100%{opacity:1}
            50%{opacity:.7}
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes badge-pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: .8;
            }
        }
    </style>

    @stack('head-scripts')
    @push('head-scripts')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<style>
    /* Calendar Container */
    .calendar-container {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }
    
    @media (min-width: 640px) {
        .calendar-container {
            padding: 1.5rem;
        }
    }
    
    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .calendar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #172030;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    @media (min-width: 640px) {
        .calendar-title {
            font-size: 1.25rem;
        }
    }
    
    /* FullCalendar Customization */
    .fc {
        font-family: 'Inter', sans-serif;
    }
    
    .fc .fc-toolbar {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1rem !important;
        font-weight: 700 !important;
        color: #172030;
    }
    
    @media (min-width: 640px) {
        .fc .fc-toolbar-title {
            font-size: 1.15rem !important;
        }
    }
    
    .fc .fc-button {
        background: linear-gradient(135deg, #002366, #001A4A) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 0.4rem 0.75rem !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        text-transform: capitalize !important;
        box-shadow: 0 2px 8px rgba(0, 35, 102, 0.3) !important;
        transition: all 0.2s !important;
    }
    
    .fc .fc-button:hover {
        background: linear-gradient(135deg, #001A4A, #00285C) !important;
        transform: translateY(-1px);
    }
    
    .fc .fc-button-active {
        background: linear-gradient(135deg, #001A4A, #00285C) !important;
    }
    
    .fc .fc-daygrid-day {
        transition: background 0.2s;
    }
    
    .fc .fc-daygrid-day:hover {
        background: #f0fdf4;
    }
    
    .fc .fc-daygrid-day-number {
        font-weight: 600;
        color: #333C4D;
        padding: 0.5rem;
    }
    
    .fc .fc-day-today {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5) !important;
    }
    
    .fc .fc-event {
        border-radius: 6px !important;
        padding: 2px 6px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        border: none !important;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .fc .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    /* Event Status Colors */
    .fc .fc-event-approved {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    
    .fc .fc-event-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    }
    
    .fc .fc-event-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    }
    
    .fc .fc-event-cancelled {
        background: linear-gradient(135deg, #6b7280, #4b5563) !important;
    }
    
    /* Legend */
    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #333C4D;
    }
    
    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 4px;
    }
    
    /* Mobile calendar adjustments */
    @media (max-width: 640px) {
        .fc .fc-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .fc .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
        }
        
        .fc .fc-daygrid-event {
            font-size: 0.65rem !important;
            padding: 1px 3px !important;
        }
        
        .fc .fc-col-header-cell-cushion {
            font-size: 0.75rem;
        }
    }
</style>
@endpush
</head>

<body x-data="{ sidebarOpen: true, mobileOpen: false }">
    <!-- Sidebar -->
    <aside class="sidebar" :class="{'collapsed':!sidebarOpen,'mobile-open':mobileOpen}">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-logo">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="brand-text">
                <h1>ESFMS</h1>
                <p>Admin Portal</p>
            </div>
            <button class="sidebar-toggle" @click="sidebarOpen=!sidebarOpen">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main Menu</div>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{request()->routeIs('admin.dashboard')?'active':''}}">
                    <i class="fa-solid fa-house"></i>
                    <span class="nav-item-text">Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="nav-item {{request()->routeIs('admin.users.*')?'active':''}}">
                    <i class="fa-solid fa-users"></i>
                    <span class="nav-item-text">Users</span>
                </a>

                <a href="{{ route('admin.guests.index') }}" class="nav-item {{request()->routeIs('admin.guests.*')?'active':''}}">
                    <i class="fa-solid fa-user-group"></i>
                    <span class="nav-item-text">Guest Management</span>
                    @php
                        $pendingGuests = \App\Models\User::where('role','guest')->where('status','pending')->count();
                    @endphp
                    @if($pendingGuests > 0)
                        <span class="nav-badge">{{$pendingGuests}}</span>
                    @endif
                </a>
            </div>

            <div class="nav-section">
    <div class="nav-section-title">Facilities</div>
    
    <a href="{{ route('admin.facilities.index') }}" class="nav-item {{request()->routeIs('admin.facilities.*')?'active':''}}">
        <i class="fa-solid fa-building"></i>
        <span class="nav-item-text">All Facilities</span>
    </a>

    <a href="{{ route('admin.reservations.index') }}" class="nav-item {{request()->routeIs('admin.reservations.*')?'active':''}}">
        <i class="fa-solid fa-calendar-check"></i>
        <span class="nav-item-text">Reservations</span>
        @php
            $pendingReservations = \App\Models\Reservation::where('status','pending')->count();
        @endphp
        @if($pendingReservations > 0)
            <span class="nav-badge">{{$pendingReservations}}</span>
        @endif
    </a>
</div>

<!-- NEW FINANCE SECTION -->
<div class="nav-section">
    <div class="nav-section-title">Finance</div>
    
    <a href="{{ route('admin.wallet.index') }}" class="nav-item {{request()->routeIs('admin.wallet.*')?'active':''}}">
        <i class="fa-solid fa-wallet"></i>
        <span class="nav-item-text">Wallet Management</span>
    </a>
</div>

            <div class="nav-section">
                <div class="nav-section-title">Reports</div>
                
                <a href="{{ route('admin.analytics.index') }}" class="nav-item {{request()->routeIs('admin.analytics.*')?'active':''}}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span class="nav-item-text">Analytics</span>
                </a>

                <div class="nav-item" x-data="{ exportOpen: false }" @click.away="exportOpen = false">
                    <div @click="exportOpen = !exportOpen" style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;width:100%;">
                        <div style="display:flex;align-items:center;gap:.85rem;">
                            <i class="fa-solid fa-file-export"></i>
                            <span class="nav-item-text">Export Data</span>
                        </div>
                        <i class="fa-solid fa-chevron-down" :class="{'rotate-180': exportOpen}"></i>
                    </div>
                    <div class="dropdown-menu" :class="{'show': exportOpen}">
                        <a href="{{ route('admin.export.users') }}?format=csv" class="dropdown-item">
                            <i class="fa-solid fa-user"></i> Users (CSV)
                        </a>
                        <a href="{{ route('admin.export.reservations') }}?format=csv" class="dropdown-item">
                            <i class="fa-solid fa-calendar-check"></i> Reservations (CSV)
                        </a>
                        <a href="{{ route('admin.export.facilities') }}?format=csv" class="dropdown-item">
                            <i class="fa-solid fa-building"></i> Facilities (CSV)
                        </a>
                        <a href="{{ route('admin.export.guests') }}?format=csv" class="dropdown-item">
                            <i class="fa-solid fa-user-group"></i> Guest Requests (CSV)
                        </a>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-item" style="font-size:.75rem;color:#333C4D;opacity:0.7;">*Click to download CSV</span>
                    </div>
                </div>
            </div>
                   
            <!-- <div class="nav-section">
                <div class="nav-section-title">System</div>
                
                <a href="#" class="nav-item">
                    <i class="fa-solid fa-gear"></i>
                    <span class="nav-item-text">Settings</span>
                </a>

               

                <a href="#" class="nav-item">
                    <i class="fa-solid fa-book"></i>
                    <span class="nav-item-text">Activity Logs</span>
                </a>
            </div> -->
        </nav>

        <!-- User Profile & Dropdown -->
        <div class="sidebar-user" x-data="{ profileDropdown: false }" @click.away="profileDropdown = false">
            <div class="user-profile" @click.stop="profileDropdown = !profileDropdown">
                <div class="user-avatar">
                    {{strtoupper(substr(Auth::user()->name,0,1))}}
                </div>
                <div class="user-info">
                    <span class="user-name">{{Auth::user()->name}}</span>
                    <span class="user-role">Administrator</span>
                </div>
                <i class="fa-solid fa-chevron-down" :class="{'rotate-180': profileDropdown}"></i>
            </div>

            <div 
                class="dropdown-menu" 
                :class="{'show': profileDropdown}"
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 transform translate-y-2" 
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
            >
               
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div style="display:flex;align-items:center;gap:.85rem">
                <button class="mobile-menu-btn" @click="mobileOpen=!mobileOpen">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="top-bar-title">@yield('title','Dashboard')</h1>
            </div>
            
            <div class="top-bar-actions">
                <!-- Notification Button & Dropdown -->
                <div style="position: relative;">
                    <button class="notification-btn" id="notificationButton">
                        <i class="fa-solid fa-bell"></i>
                        @php
                            $totalNotifications = $pendingReservations + $pendingGuests;
                        @endphp
                        @if($totalNotifications > 0)
                            <span class="notification-badge has-unread">{{$totalNotifications}}</span>
                        @else
                            <span class="notification-badge" style="display:none;">0</span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" class="notification-dropdown">
                        <!-- Header -->
                        <div class="notification-header">
                            <h3>Notifications</h3>
                            <button id="markAllRead" class="mark-all-read">Mark all read</button>
                        </div>

                        <!-- Notification List -->
                        <div id="notificationList" class="notification-list">
                            <div style="padding:2rem;text-align:center;">
                                <div class="loading-spinner"></div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="notification-footer">
                            <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}">
                                View All Pending Items →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <div class="alert-title">Success!</div>
                        <div class="alert-message">{{session('success')}}</div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <div class="alert-title">Error!</div>
                        <div class="alert-message">{{session('error')}}</div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer>
            <p>© {{date('Y')}} ESFMS. All rights reserved.</p>
        </footer>
    </main>

    @stack('scripts')

    <!-- Notification System JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBtn = document.querySelector('.notification-btn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationList = document.getElementById('notificationList');
        const notificationBadge = document.querySelector('.notification-badge');
        const markAllReadBtn = document.getElementById('markAllRead');
        
        let notifications = [];
        let dropdownOpen = false;

        // Toggle dropdown
        notificationBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownOpen = !dropdownOpen;
            
            if (dropdownOpen) {
                notificationDropdown.classList.add('show');
                loadNotifications();
            } else {
                notificationDropdown.classList.remove('show');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (dropdownOpen && !notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationDropdown.classList.remove('show');
                dropdownOpen = false;
            }
        });

        // Load notifications from server
        function loadNotifications() {
            notificationList.innerHTML = '<div style="padding:2rem;text-align:center;"><div class="loading-spinner"></div></div>';
            
            fetch('/admin/notifications')
                .then(response => response.json())
                .then(data => {
                    notifications = data.notifications;
                    renderNotifications();
                    updateBadge(data.unread_count);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML = '<div class="notification-empty"><p>Failed to load notifications</p></div>';
                });
        }

        // Render notifications
        function renderNotifications() {
            if (notifications.length === 0) {
                notificationList.innerHTML = `
                    <div class="notification-empty">
                        <i class="fa-solid fa-bell-slash"></i>
                        <p>No notifications</p>
                    </div>
                `;
                return;
            }

            notificationList.innerHTML = notifications.map(notification => `
                <a href="${notification.url}" 
                   class="notification-item ${notification.read ? '' : 'unread'} type-${notification.color}"
                   data-id="${notification.id}">
                    <div class="notification-content">
                        <div class="notification-icon ${notification.color}">
                            <i class="fa-solid ${notification.icon}"></i>
                        </div>
                        <div class="notification-text">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-details">${notification.details}</div>
                            <div class="notification-time">${notification.time}</div>
                        </div>
                    </div>
                </a>
            `).join('');

            // Add click handlers to mark as read
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const notificationId = this.dataset.id;
                    markAsRead(notificationId);
                });
            });
        }

        // Mark notification as read
        function markAsRead(notificationId) {
            fetch('/admin/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id: notificationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notification = notifications.find(n => n.id == notificationId);
                    if (notification) notification.read = true;
                    
                    const unreadCount = notifications.filter(n => !n.read).length;
                    updateBadge(unreadCount);
                }
            })
            .catch(error => console.error('Error marking as read:', error));
        }

        // Mark all as read
        markAllReadBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notifications.forEach(n => n.read = true);
                    renderNotifications();
                    updateBadge(0);
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
        });

        // Update badge count
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

        // Auto-refresh notifications every 30 seconds
        setInterval(function() {
            if (!dropdownOpen) {
                fetch('/admin/notifications')
                    .then(response => response.json())
                    .then(data => {
                        updateBadge(data.unread_count);
                    })
                    .catch(error => console.error('Error refreshing notifications:', error));
            }
        }, 30000);

        // Initial badge update
        fetch('/admin/notifications')
            .then(response => response.json())
            .then(data => {
                updateBadge(data.unread_count);
            })
            .catch(error => console.error('Error loading initial badge:', error));
    });
    </script>

    <!-- Alpine.js Debug Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if (window.Alpine) {
                    console.log('✅ Alpine.js is working correctly');
                } else {
                    console.error('❌ Alpine.js not found - dropdown may not work');
                }
            }, 500);
        });
    </script>
</body>
</html>