<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'faculty Portal') | {{ config('app.name', 'CPAC ESFMS') }}</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
:root {
    --cpac-primary: #002366;
    --cpac-secondary: #00285C;
    --cpac-accent: #001A4A;
    --cpac-dark: #172030;
    --cpac-light: #F8F9FA;
    --cpac-text-primary: #333C4D;
    --cpac-text-secondary: #6B7280;
    --cpac-text-muted: #9CA3AF;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
html { height: 100%; }
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #F8F9FA 0%, #F3F4F6 50%, #E5E7EB 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
    color: #333C4D;
}
body::before {
    content: '';
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: 
    radial-gradient(circle at 20% 50%, rgba(0, 35, 102, 0.05) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(0, 40, 92, 0.05) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
}
.main-wrapper { 
    position: relative; 
    z-index: 1; 
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    width: 100%;
}
.navbar {
    background: linear-gradient(135deg, var(--cpac-primary) 0%, var(--cpac-dark) 100%);
    box-shadow: 0 4px 20px rgba(0, 35, 102, 0.15);
    backdrop-filter: blur(10px);
    position: sticky;
    top: 0;
    z-index: 1000;
}
.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 75px;
}
.navbar-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    transition: transform 0.2s;
}
.navbar-brand:hover { transform: scale(1.02); }
.brand-logo {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.brand-text h1 {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    letter-spacing: -0.5px;
}
.brand-text p {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.navbar-menu {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}
.nav-item {
    color: rgba(255, 255, 255, 0.95);
    padding: 0.75rem 1.25rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
}
/* Desktop hover line */
@media (min-width: 769px) {
    .nav-item::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: white;
        transform: translateX(-50%);
        transition: width 0.3s ease;
    }
    .nav-item:hover::after { width: 70%; }
}
.nav-item:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
}
.nav-item.active {
    background-color: rgba(255, 255, 255, 0.25);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.nav-badge {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 0.15rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 800;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
    animation: pulse-badge 2s infinite;
}
@keyframes pulse-badge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
.user-profile-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    backdrop-filter: blur(10px);
}
.user-profile-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.1rem;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.dropdown-menu {
    position: absolute;
    top: calc(100% + 1rem);
    right: 0;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1001;
}
.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}
.dropdown-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #F8F9FA, #F3F4F6);
    border-bottom: 2px solid #E5E7EB;
}
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    color: #333C4D;
    text-decoration: none;
    transition: all 0.2s;
    font-weight: 500;
}
.dropdown-item:hover {
    background: linear-gradient(135deg, #F8F9FA, #F3F4F6);
    color: var(--cpac-primary);
}
.dropdown-item i {
    width: 24px;
    text-align: center;
    font-size: 1.2rem;
    color: var(--cpac-primary);
}
.main-container {
    flex: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 2.5rem 2rem;
    width: 100%;
}
.welcome-section {
    background: linear-gradient(135deg, var(--cpac-primary), var(--cpac-dark));
    border-radius: 24px;
    padding: 3rem;
    color: white;
    margin-bottom: 2.5rem;
    box-shadow: 0 20px 40px rgba(0, 35, 102, 0.2);
    position: relative;
    overflow: hidden;
}
.welcome-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    border-radius: 50%;
}
.welcome-section::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
    border-radius: 50%;
}
.welcome-content {
    position: relative;
    z-index: 1;
}
.welcome-title {
    font-size: 2.75rem;
    font-weight: 900;
    margin-bottom: 1rem;
    line-height: 1.1;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    font-size: 0.95rem;
    font-weight: 600;
    margin-top: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}
.action-card {
    background: white;
    border-radius: 20px;
    padding: 2.25rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}
.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--cpac-primary), var(--cpac-secondary));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}
.action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: var(--cpac-accent);
}
.action-card:hover::before { transform: scaleX(1); }
.action-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.25rem;
    color: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    transition: transform 0.3s ease;
}
.action-card:hover .action-icon {
    transform: scale(1.1) rotate(5deg);
}
.alert {
    padding: 1.5rem;
    border-radius: 16px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    animation: slideInDown 0.4s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-left: 4px solid;
}
@keyframes slideInDown {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-color: #10b981;
    color: #065f46;
}
.alert-error {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-color: #ef4444;
    color: #991b1b;
}

/* Footer Styles */
.site-footer {
    background: linear-gradient(135deg, #172030 0%, #1D2636 100%);
    color: white;
    margin-top: auto;
    position: relative;
    overflow: hidden;
}
.site-footer::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
}
.footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 3rem 2rem 1.5rem;
}
.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    margin-bottom: 3rem;
}
.footer-section { animation: fadeInUp 0.6s ease; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.footer-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}
.footer-brand i {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}
.footer-description {
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}
.footer-social { display: flex; gap: 0.75rem; }
.social-link {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}
.social-link:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-3px);
}
.footer-heading {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    position: relative;
    padding-bottom: 0.75rem;
}
.footer-heading::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: linear-gradient(90deg, white, transparent);
    border-radius: 2px;
}
.footer-links { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 0.75rem; }
.footer-links a {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    font-size: 0.95rem;
}
.footer-links a:hover { color: white; padding-left: 0.5rem; }
.footer-links i { font-size: 0.7rem; opacity: 0.7; }
.footer-contact { list-style: none; padding: 0; margin: 0; }
.footer-contact li {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.25rem;
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.95rem;
    line-height: 1.6;
}
.footer-contact i {
    width: 20px;
    flex-shrink: 0;
    margin-top: 0.15rem;
    color: rgba(255, 255, 255, 0.9);
}
.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    padding-top: 2rem;
}
.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}
.footer-bottom-links { display: flex; gap: 1rem; align-items: center; }
.footer-bottom-links a { color: rgba(255, 255, 255, 0.85); text-decoration: none; transition: color 0.2s ease; }
.footer-bottom-links a:hover { color: white; }
.footer-bottom-links span { color: rgba(255, 255, 255, 0.5); }
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
}

/* --- Responsive Design Updates --- */

@media (max-width: 1024px) {
    .navbar-container { padding: 0 1.5rem; height: 70px; }
    .brand-logo { width: 45px; height: 45px; font-size: 1.5rem; }
    .brand-text h1 { font-size: 1.3rem; }
    .brand-text p { font-size: 0.75rem; }
    .nav-item { padding: 0.6rem 1rem; font-size: 0.9rem; }
    .main-container { padding: 2rem 1.5rem; }
    .welcome-section { padding: 2.5rem; margin-bottom: 2rem; }
    .welcome-title { font-size: 2.25rem; }
    .footer-container { padding: 2.5rem 1.5rem 1rem; }
}

@media (max-width: 768px) {
    .navbar-container { padding: 0 1rem; height: 65px; }
    .brand-logo { width: 40px; height: 40px; font-size: 1.25rem; }
    .brand-text h1 { font-size: 1.1rem; }
    .brand-text p { font-size: 0.7rem; }
    .welcome-title { font-size: 1.8rem; }
    .main-container { padding: 1.5rem 1rem; }
    .welcome-section { padding: 2rem 1.5rem; margin-bottom: 1.5rem; }
    .action-card { padding: 1.75rem; }
    
    /* Mobile Menu Logic */
    .mobile-menu-btn { display: block; z-index: 1002; }
    
    .navbar-menu {
        display: none; /* Hidden by default on mobile */
        flex-direction: column;
        position: fixed;
        top: 65px;
        left: 0;
        right: 0;
        bottom: 0; /* Full screen height */
        background: linear-gradient(180deg, var(--cpac-dark) 0%, var(--cpac-primary) 100%);
        padding: 1rem;
        gap: 0.5rem;
        z-index: 1001;
        overflow-y: auto;
    }
    
    .navbar-menu.mobile-open {
        display: flex;
        animation: slideDown 0.3s ease forwards;
    }

    .nav-item {
        width: 100%;
        justify-content: flex-start;
        padding: 1rem;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
    }
    
    .nav-badge { margin-left: auto; }
    
    /* Mobile User Profile */
    .user-profile-btn {
        width: 100%;
        margin-top: 1rem;
        justify-content: space-between;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
    }

    /* Mobile Dropdown - Accordion Style */
    .dropdown-menu {
        position: static;
        width: 100%;
        transform: none !important;
        opacity: 1 !important;
        visibility: visible !important;
        box-shadow: none;
        background: rgba(0,0,0,0.2);
        margin-top: 10px;
        display: none; /* Controlled by Alpine */
    }
    
    .dropdown-menu.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    .dropdown-item {
        color: rgba(255,255,255,0.8);
    }
    
    .dropdown-item:hover {
        background: rgba(255,255,255,0.05);
        color: white;
    }
    
    .dropdown-item i { color: rgba(255,255,255,0.6); }

    /* Footer Fixes */
    .footer-grid { grid-template-columns: 1fr; gap: 2.5rem; }
    .footer-bottom-content { flex-direction: column; text-align: center; gap: 1rem; }
    .footer-bottom-links { flex-wrap: wrap; justify-content: center; }
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
@stack('head-scripts')
</head>
<body x-data="{ userDropdown: false, mobileMenu: false }" @click.away="userDropdown = false">
<div class="main-wrapper">
<nav class="navbar">
<div class="navbar-container">
<a href="{{ route('faculty.dashboard') }}" class="navbar-brand">
<div class="brand-logo">
<i class="fa-solid fa-user-graduate"></i>
</div>
<div class="brand-text">
<h1>CPAC ESFMS</h1>
<p>faculty Portal</p>
</div>
</a>
<button class="mobile-menu-btn" @click="mobileMenu = !mobileMenu" aria-label="Toggle mobile menu">
<i class="fa-solid" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
</button>
<div class="navbar-menu" :class="{ 'mobile-open': mobileMenu }">
<a href="{{ route('faculty.dashboard') }}" 
class="nav-item {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
<i class="fa-solid fa-house"></i>
<span>Dashboard</span>
</a>
<a href="{{ route('faculty.facilities.index') }}" 
class="nav-item {{ request()->routeIs('faculty.facilities.*') ? 'active' : '' }}">
<i class="fa-solid fa-building"></i>
<span>Facilities</span>
</a>
<a href="{{ route('faculty.reservations.index') }}" 
class="nav-item {{ request()->routeIs('faculty.reservations.*') ? 'active' : '' }}">
<i class="fa-solid fa-calendar-check"></i>
<span>Reservations</span>
@php
$pendingCount = \App\Models\Reservation::where('user_id', auth()->id())
->where('status', 'pending')->count();
@endphp
@if($pendingCount > 0)
<span class="nav-badge">{{ $pendingCount }}</span>
@endif
</a>
<a href="{{ route('faculty.wallet.index') }}" 
class="nav-item {{ request()->routeIs('faculty.wallet.*') ? 'active' : '' }}">
<i class="fa-solid fa-wallet"></i>
<span>Wallet</span>
<span class="nav-badge" style="background: #10B981;">
₱{{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}
</span>
</a>

<div class="user-profile-btn" @click.stop="userDropdown = !userDropdown">
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <div class="user-avatar">
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="user-info">
        <span class="user-name" style="display:block; font-weight:600;">{{ Auth::user()->name }}</span>
        <span class="user-role" style="display:block; font-size:0.75rem; opacity:0.8;">faculty Account</span>
        </div>
    </div>
    <i class="fa-solid fa-chevron-down" :class="{ 'rotate-180': userDropdown }" style="transition: transform 0.2s;"></i>
    
    <div class="dropdown-menu" :class="{ 'show': userDropdown }">
        <div class="dropdown-header">
        <div style="font-weight: 700; font-size: 1.1rem; color: #002366;">{{ Auth::user()->name }}</div>
        <div style="font-size: 0.875rem; color: #6B7280; margin-top: 0.25rem;">{{ Auth::user()->email }}</div>
        </div>
        <a href="{{ route('faculty.profile.edit') }}" class="dropdown-item">
            <i class="fa-solid fa-user-gear"></i>
            <span>Profile Settings</span>
        </a>
        <div style="height: 1px; background: #E2E8F0; margin: 0.5rem 0;"></div>
        <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left; color: #DC2626;">
        <i class="fa-solid fa-right-from-bracket" style="color: #DC2626;"></i>
        <span>Logout</span>
        </button>
        </form>
    </div>
</div>
</div>
</div>
</nav>
<div class="main-container">
@if(session('success'))
<div class="alert alert-success">
<i class="fa-solid fa-circle-check" style="font-size: 1.75rem;"></i>
<div>
<div style="font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem;">Success!</div>
<div style="font-size: 0.95rem;">{{ session('success') }}</div>
</div>
</div>
@endif
@if(session('error'))
<div class="alert alert-error">
<i class="fa-solid fa-circle-exclamation" style="font-size: 1.75rem;"></i>
<div>
<div style="font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem;">Error!</div>
<div style="font-size: 0.95rem;">{{ session('error') }}</div>
</div>
</div>
@endif
@yield('content')
</div>
<footer class="site-footer">
<div class="footer-container">
<div class="footer-grid">
<div class="footer-section">
<div class="footer-brand">
<i class="fa-solid fa-user-tie"></i>
<span>CPAC ESFMS</span>
</div>
<p class="footer-description">Educational System Facility Management - Streamlining facility reservations for a better campus experience.</p>
<div class="footer-social">
<a href="#" class="social-link"><i class="fa-brands fa-facebook"></i></a>
<a href="#" class="social-link"><i class="fa-brands fa-twitter"></i></a>
<a href="#" class="social-link"><i class="fa-brands fa-instagram"></i></a>
</div>
</div>
<div class="footer-section">
<h3 class="footer-heading">Quick Links</h3>
<ul class="footer-links">
<li><a href="{{ route('faculty.dashboard') }}"><i class="fa-solid fa-chevron-right"></i> Dashboard</a></li>
<li><a href="{{ route('faculty.facilities.index') }}"><i class="fa-solid fa-chevron-right"></i> Browse Facilities</a></li>
<li><a href="{{ route('faculty.reservations.index') }}"><i class="fa-solid fa-chevron-right"></i> My Reservations</a></li>
<li><a href="{{ route('faculty.profile.edit') }}"><i class="fa-solid fa-chevron-right"></i> Profile Settings</a></li>
</ul>
</div>
<div class="footer-section">
<h3 class="footer-heading">Support</h3>
<ul class="footer-links">
<li><a href="#"><i class="fa-solid fa-chevron-right"></i> Help Center</a></li>
<li><a href="#"><i class="fa-solid fa-chevron-right"></i> FAQs</a></li>
<li><a href="#"><i class="fa-solid fa-chevron-right"></i> Booking Guidelines</a></li>
<li><a href="#"><i class="fa-solid fa-chevron-right"></i> Contact Support</a></li>
</ul>
</div>
<div class="footer-section">
<h3 class="footer-heading">Contact Us</h3>
<ul class="footer-contact">
<li>
<i class="fa-solid fa-location-dot"></i>
<span>Central Philippine Adventist College<br>Bacolod, 6129 Negros Occidental</span>
</li>
<li>
<i class="fa-solid fa-phone"></i>
<span>+63 (33) 320-0870</span>
</li>
<li>
<i class="fa-solid fa-envelope"></i>
<span>esfms@cpac.edu.ph</span>
</li>
</ul>
</div>
</div>
<div class="footer-bottom">
<div class="footer-bottom-content">
<p>&copy; {{ date('Y') }} CPAC Events Scheduling and Facilities Management. All rights reserved.</p>
<div class="footer-bottom-links">
<a href="#">Privacy Policy</a>
<span>•</span>
<a href="#">Terms of Service</a>
<span>•</span>
<a href="#">Cookie Policy</a>
</div>
</div>
</div>
</div>
</footer>
</div>
@stack('scripts')
</body>
</html>