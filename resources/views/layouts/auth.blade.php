<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') | {{ config('app.name', 'ESFMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Define colors from the palette for easy reference in CSS */
        :root {
            --color-white: #FFFFFF; /* White */
            --color-deep-gray-blue: #333C4D; /* Deep Gray-Blue */
            --color-off-white: #F8F9FA; /* Off-White */
            --color-dark-navy-gray: #172030; /* Dark Navy-Gray (for bold text) */
            --color-deep-blue: #001A4A; /* Dark Blue */
            --color-deep-royal-blue: #002366; /* Deep Royal Blue (Primary Accent) */
            --color-navy-blue: #00285C; /* Navy Blue */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* Background changed from light blue to a gradient using Off-White and Dark Navy-Gray as base */
            background: linear-gradient(135deg, var(--color-off-white) 0%, var(--color-deep-gray-blue) 100%);
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
        }

        /* Clean Header */
        .auth-navbar {
            /* Header gradient changed from green to Deep Royal Blue/Navy Blue */
            background: linear-gradient(135deg, var(--color-deep-royal-blue) 0%, var(--color-navy-blue) 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2); /* Slightly darker shadow for a darker header */
            padding: 1rem 2rem;
        }

        .auth-navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .auth-brand:hover {
            transform: scale(1.02);
        }

        .brand-logo {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            backdrop-filter: blur(10px);
        }

        .brand-text h1 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--color-white);
            letter-spacing: 0.5px;
            line-height: 1;
        }

        .brand-text p {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-top: 0.25rem;
            line-height: 1;
        }

        /* Main Content */
        .auth-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
            min-height: calc(100vh - 78px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Alerts - Balanced: Simple but with subtle animation */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideIn 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success Alert (Changed from green to deep blue theme) */
        .alert-success {
            /* Background changed to light blue/off-white gradient */
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); 
            /* Border and text color changed to Deep Royal Blue */
            border-left: 4px solid var(--color-deep-royal-blue);
            color: var(--color-deep-blue);
        }

        /* Error Alert (Kept standard red) */
        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }

        /* Info Alert (Changed from standard blue to Deep Royal Blue theme) */
        .alert-info {
            /* Background changed to Off-White/Deep Gray-Blue gradient */
            background: linear-gradient(135deg, var(--color-off-white) 0%, #dee7f1 100%); 
            /* Border and text color changed to Deep Blue/Dark Navy-Gray */
            border-left: 4px solid var(--color-deep-blue);
            color: var(--color-dark-navy-gray);
        }

        /* Warning Alert (Kept standard yellow/orange) */
        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }

        .alert i {
            font-size: 1.2rem;
            flex-shrink: 0;
            /* Icons for Success and Info will inherit color from their respective alerts */
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .alert-message {
            font-size: 0.875rem;
            opacity: 0.95;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .auth-navbar {
                padding: 1rem;
            }

            .brand-logo {
                width: 42px;
                height: 42px;
                font-size: 1.3rem;
            }

            .brand-text h1 {
                font-size: 1.25rem;
            }
            
            .brand-text p {
                font-size: 0.75rem;
            }

            .auth-container {
                padding: 1.5rem 1rem;
                min-height: calc(100vh - 75px);
            }

            .alert {
                padding: 0.875rem 1rem;
            }
        }

        @media (max-width: 480px) {
            .brand-text p {
                display: none;
            }
        }
    </style>
    @stack('head-scripts')
</head>

<body>
    <nav class="auth-navbar">
        <div class="auth-navbar-container">
            <a href="{{ route('home') }}" class="auth-brand">
                <div class="brand-logo">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="brand-text">
                    <h1>ESFMS</h1>
                    <p>Facility Management System</p>
                </div>
            </a>
        </div>
    </nav>

    <div class="auth-container">
        <div style="width: 100%; max-width: 500px;">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div class="alert-content">
                        <div class="alert-title">Success!</div>
                        <div class="alert-message">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div class="alert-content">
                        <div class="alert-title">Error!</div>
                        <div class="alert-message">{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-info">
                    <i class="fa-solid fa-circle-info"></i>
                    <div class="alert-content">
                        <div class="alert-title">Information</div>
                        <div class="alert-message">{{ session('status') }}</div>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div class="alert-content">
                        <div class="alert-title">Warning!</div>
                        <div class="alert-message">{{ session('warning') }}</div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>