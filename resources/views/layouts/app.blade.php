<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <title>{{ config('app.name', 'ESFMS') }}</title>

    <!-- ✅ Favicon (optional) -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <!-- ✅ Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- ✅ FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-om0pCPHPuJMQ4Hb5yYoQ42yWDUmBflNObZQjqK9e1bUWy8JxlnZ5LBfbB6Ttpu/hY1GgLxZ1j6k9YJbyB5yFhA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- ✅ FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">

    <!-- ✅ Vite (Tailwind, Alpine, etc.) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ✅ Livewire Styles -->
    @livewireStyles

    <!-- ✅ Global Inline Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .transition {
            transition: all 0.2s ease-in-out;
        }

        /* Smooth page fade effect */
        .fade-enter-active, .fade-leave-active {
            transition: opacity .3s ease;
        }

        .fade-enter, .fade-leave-to {
            opacity: 0;
        }

        /* Enhanced Modal Styles */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.75);
        }
        
        .modal-content {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid #e5e7eb;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 0.75rem 0.75rem 0 0;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 0 0 0.75rem 0.75rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        
        /* Error/Success specific styles */
        .error-title {
            color: #dc2626;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .success-title {
            color: #059669;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .error-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        
        .error-list li:last-child {
            border-bottom: none;
        }
        
        /* Button styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-decoration: none;
        }
        
        .btn:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 0.5rem;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Capacity validation styles */
        .capacity-warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin-top: 0.5rem;
            display: none;
        }

        .capacity-warning.show {
            display: block;
        }

        /* Alert auto-hide */
        .alert-auto-hide {
            transition: opacity 0.3s ease-in-out;
        }

        /* Mobile responsive modal */
        @media (max-width: 640px) {
            .modal-content {
                margin: 0.5rem;
                width: calc(100vw - 1rem);
            }
            
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 1rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .btn:last-child {
                margin-bottom: 0;
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
        {{-- 🧭 Top Navigation Bar --}}
        @include('layouts.navigation')

        {{-- 🏷️ Page Header (optional per page) --}}
        @hasSection('header')
            <header class="bg-white shadow-md">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        {{-- 📄 Main Page Content --}}
        <main class="flex-1 p-6 bg-gray-50">
            @yield('content')
        </main>

        {{-- 🧩 Footer (optional) --}}
        <footer class="bg-white border-t text-center text-sm text-gray-600 py-4 mt-auto">
            <span>© {{ date('Y') }} Central Philippine Adventist College – ESFMS</span>
        </footer>
    </div>

    <!-- ✅ JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ✅ Livewire Scripts -->
    @livewireScripts

    <!-- ✅ Alpine.js (Enhanced with modal system) -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- ✅ Page-Specific Scripts -->
    @stack('scripts')

    <!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>

    <!-- Enhanced Modal Components -->
    @include('components.modals')

    <!-- Enhanced Modal JavaScript System -->
    <script>
        // Enhanced Modal Management System
        document.addEventListener('DOMContentLoaded', function() {
            // Global modal state management
            window.ModalManager = {
                showModal: function(modalName) {
                    // For Alpine.js modals, dispatch custom events
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: modalName }));
                },
                hideModal: function(modalName) {
                    // For Alpine.js modals, dispatch custom events
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: modalName }));
                },
                showError: function(title, message, autoClose = false) {
                    const modal = document.getElementById('errorModal');
                    if (!modal) {
                        // Fallback if modal doesn't exist
                        alert(message);
                        return;
                    }
                    
                    const titleEl = modal.querySelector('.error-title');
                    const messageEl = modal.querySelector('.error-message');
                    
                    if (titleEl) titleEl.textContent = title;
                    if (messageEl) messageEl.innerHTML = message;
                    
                    this.showModal('errorModal');
                    
                    if (autoClose) {
                        setTimeout(() => this.hideModal('errorModal'), 3000);
                    }
                },
                showSuccess: function(title, message, autoClose = true) {
                    const modal = document.getElementById('successModal');
                    if (!modal) {
                        alert(message);
                        return;
                    }
                    
                    const titleEl = modal.querySelector('.success-title');
                    const messageEl = modal.querySelector('.success-message');
                    
                    if (titleEl) titleEl.textContent = title;
                    if (messageEl) messageEl.innerHTML = message;
                    
                    this.showModal('successModal');
                    
                    if (autoClose) {
                        setTimeout(() => this.hideModal('successModal'), 2000);
                    }
                },
                showConfirm: function(message, onConfirm, onCancel = null) {
                    const modal = document.getElementById('confirmModal');
                    if (!modal) {
                        const confirmed = confirm(message);
                        if (confirmed && onConfirm) onConfirm();
                        return;
                    }
                    
                    const messageEl = modal.querySelector('.confirm-message');
                    if (messageEl) messageEl.textContent = message;
                    
                    // Store callbacks for modal buttons
                    window.confirmCallback = onConfirm;
                    window.cancelCallback = onCancel;
                    
                    this.showModal('confirmModal');
                }
            };

            // Enhanced AJAX form submission handler
            window.handleAjaxForm = function(form, onSuccess = null, onError = null) {
                const submitBtn = form.querySelector('[type="submit"]');
                if (!submitBtn) return;
                
                const originalText = submitBtn.textContent;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
                
                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                
                if (csrfToken) {
                    formData.append('_token', csrfToken.content);
                }
                
                fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (onSuccess) {
                            onSuccess(data);
                        } else {
                            window.ModalManager.showSuccess('Success', data.message || 'Operation completed successfully!');
                        }
                        
                        // Reset form if specified
                        if (form.classList.contains('reset-form')) {
                            form.reset();
                        }
                        
                        // Redirect if specified
                        if (data.redirect) {
                            setTimeout(() => window.location.href = data.redirect, 1500);
                        }
                    } else {
                        if (data.errors) {
                            let errorHtml = '<ul class="error-list">';
                            Object.keys(data.errors).forEach(field => {
                                data.errors[field].forEach(error => {
                                    errorHtml += `<li>${error}</li>`;
                                });
                            });
                            errorHtml += '</ul>';
                            
                            window.ModalManager.showError('Validation Error', errorHtml);
                        } else if (data.message) {
                            window.ModalManager.showError('Error', data.message);
                        } else {
                            window.ModalManager.showError('Error', 'An unexpected error occurred.');
                        }
                        
                        if (onError) {
                            onError(data);
                        }
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    window.ModalManager.showError('Error', 'Network error. Please check your connection and try again.');
                    
                    if (onError) {
                        onError(error);
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            };

            // Enhanced real-time capacity validation
            window.initCapacityValidation = function(facilitySelectId, capacityInputId) {
                const facilitySelect = document.getElementById(facilitySelectId);
                const capacityInput = document.getElementById(capacityInputId);
                const capacityDisplay = document.getElementById('capacity-display') || 
                                      document.getElementById('max-capacity');
                
                if (!facilitySelect || !capacityInput) return;
                
                function updateCapacityDisplay() {
                    const selectedOption = facilitySelect.options[facilitySelect.selectedIndex];
                    if (!selectedOption) return;
                    
                    const maxCapacity = selectedOption.getAttribute('data-max-capacity') || 
                                      selectedOption.getAttribute('data-capacity');
                    
                    if (maxCapacity && capacityDisplay) {
                        capacityDisplay.textContent = `Max Capacity: ${maxCapacity} participants`;
                        capacityDisplay.classList.add('show');
                    } else if (capacityDisplay) {
                        capacityDisplay.classList.remove('show');
                    }
                }
                
                function validateCapacity() {
                    const selectedOption = facilitySelect.options[facilitySelect.selectedIndex];
                    if (!selectedOption) return true;
                    
                    const maxCapacity = selectedOption.getAttribute('data-max-capacity') || 
                                      selectedOption.getAttribute('data-capacity');
                    const currentValue = parseInt(capacityInput.value) || 0;
                    
                    if (maxCapacity && currentValue > maxCapacity) {
                        capacityInput.classList.add('border-red-500', 'bg-red-50');
                        capacityInput.setCustomValidity(`Maximum capacity is ${maxCapacity}`);
                        return false;
                    } else {
                        capacityInput.classList.remove('border-red-500', 'bg-red-50');
                        capacityInput.setCustomValidity('');
                        return true;
                    }
                }
                
                facilitySelect.addEventListener('change', function() {
                    updateCapacityDisplay();
                    validateCapacity();
                });
                
                capacityInput.addEventListener('input', validateCapacity);
                capacityInput.addEventListener('blur', validateCapacity);
                
                // Initialize on page load
                updateCapacityDisplay();
                validateCapacity();
            };

            // Modal utility functions
            window.ModalUtils = {
                showConfirm: function(message, callback) {
                    window.ModalManager.showConfirm(message, callback);
                },
                
                showInfo: function(title, message) {
                    const modal = document.getElementById('infoModal');
                    if (!modal) {
                        alert(message);
                        return;
                    }
                    
                    const titleEl = modal.querySelector('.custom-title');
                    const messageEl = modal.querySelector('.info-message');
                    
                    if (titleEl) titleEl.textContent = title;
                    if (messageEl) messageEl.textContent = message;
                    
                    window.ModalManager.showModal('infoModal');
                },
                
                showLoading: function(message = 'Loading...') {
                    const modal = document.getElementById('loadingModal');
                    if (!modal) return;
                    
                    const messageEl = modal.querySelector('.loading-message');
                    if (messageEl) messageEl.textContent = message;
                    
                    window.ModalManager.showModal('loadingModal');
                },
                
                hideLoading: function() {
                    window.ModalManager.hideModal('loadingModal');
                },
                
                showCapacityWarning: function(facility, currentCapacity, maxCapacity, callback) {
                    const modal = document.getElementById('capacityWarningModal');
                    if (!modal) {
                        const proceed = confirm(`Warning: ${facility} can only accommodate ${maxCapacity} participants. You entered ${currentCapacity}. Proceed anyway?`);
                        if (proceed && callback) callback();
                        return;
                    }
                    
                    const contentEl = modal.querySelector('.capacity-warning-content');
                    if (contentEl) {
                        contentEl.innerHTML = `
                            <p class="mb-3">The facility <strong>${facility}</strong> has a maximum capacity of <strong>${maxCapacity}</strong> participants.</p>
                            <p>You are attempting to set <strong>${currentCapacity}</strong> participants.</p>
                            <p class="text-amber-700 font-medium mt-3">Are you sure you want to proceed? This may cause facility overcrowding.</p>
                        `;
                    }
                    
                    window.capacityCallback = callback;
                    window.ModalManager.showModal('capacityWarningModal');
                }
            };

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Auto-initialize capacity validation for reservation forms
            if (document.querySelector('.reservation-form, .facility-form')) {
                window.initCapacityValidation('facility_id', 'participants');
            }
        });

        // Handle modal button callbacks
        document.addEventListener('click', function(e) {
            // Confirm modal buttons
            if (e.target.matches('[data-modal-confirm]')) {
                e.preventDefault();
                if (window.confirmCallback) {
                    window.confirmCallback();
                }
            }
            
            if (e.target.matches('[data-modal-cancel]')) {
                e.preventDefault();
                if (window.cancelCallback) {
                    window.cancelCallback();
                }
            }
            
            // Capacity warning modal buttons
            if (e.target.matches('[data-capacity-proceed]')) {
                e.preventDefault();
                if (window.capacityCallback) {
                    window.capacityCallback();
                }
            }
        });
    </script>
    
    <!-- Alert Messages Container -->
    <div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2">
        @if (session('success'))
            <div class="alert-auto-hide bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert-auto-hide bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
        
        @if (session('warning'))
            <div class="alert-auto-hide bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                {{ session('warning') }}
            </div>
        @endif
    </div>
</body>
</html>