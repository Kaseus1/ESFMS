    @extends('layouts.admin')

    @section('content')
    <div class="space-y-6">
        
        {{-- Header with Date Filter --}}
        <div class="bg-[#FFFFFF] shadow-lg rounded-xl p-6 border-l-4 border-[#002366]">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-[#172030] flex items-center gap-3">
                        <svg class="w-8 h-8 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analytics Dashboard
                    </h1>
                    <p class="text-[#333C4D] mt-1">Comprehensive insights and statistics</p>
                </div>
                
                {{-- Date Range Filter --}}
                <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                            class="border border-[#333C4D] border-opacity-20 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#002366] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#333C4D] mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                            class="border border-[#333C4D] border-opacity-20 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#002366] focus:border-transparent">
                    </div>
                    <button type="submit" class="bg-[#002366] hover:bg-[#001A4A] text-white px-6 py-2 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                        Apply Filter
                    </button>
                </form>
            </div>
        </div>

        {{-- Overview Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-[#002366] to-[#00285C] rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2 opacity-90">Total Reservations</h3>
                <p class="text-4xl font-bold">{{ number_format($totalReservations) }}</p>
                <p class="text-blue-100 text-sm mt-2">In selected period</p>
            </div>

            <div class="bg-gradient-to-br from-[#002366] via-[#00285C] to-[#001A4A] rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2 opacity-90">Total Facilities</h3>
                <p class="text-4xl font-bold">{{ number_format($totalFacilities) }}</p>
                <p class="text-blue-100 text-sm mt-2">Available spaces</p>
            </div>

            <div class="bg-gradient-to-br from-[#172030] to-[#1D2636] rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2 opacity-90">Total Users</h3>
                <p class="text-4xl font-bold">{{ number_format($totalUsers) }}</p>
                <p class="text-gray-100 text-sm mt-2">Registered members</p>
            </div>

            <div class="bg-gradient-to-br from-[#1D2636] to-[#333C4D] rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2 opacity-90">Active Users</h3>
                <p class="text-4xl font-bold">{{ number_format($activeUsers) }}</p>
                <p class="text-gray-100 text-sm mt-2">Approved accounts</p>
            </div>
        </div>

        {{-- Charts Row 1: Monthly Trend & Status Distribution --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Monthly Reservations Chart --}}
            <div class="bg-[#FFFFFF] shadow-xl rounded-xl p-6 border-t-4 border-[#002366]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-[#172030] flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                        Monthly Trend (Last 12 Months)
                    </h3>
                </div>
                <div style="height: 300px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            {{-- Reservation Status Pie Chart --}}
            <div class="bg-[#FFFFFF] shadow-xl rounded-xl p-6 border-t-4 border-[#002366]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-[#172030] flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                        Reservation Status Breakdown
                    </h3>
                </div>
                <div style="height: 300px;" class="flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Charts Row 2: Weekly Trend & Role Distribution --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Weekly Trend --}}
            <div class="bg-[#FFFFFF] shadow-xl rounded-xl p-6 border-t-4 border-[#172030]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-[#172030] flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#172030]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Weekly Activity (Last 7 Days)
                    </h3>
                </div>
                <div style="height: 300px;">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            {{-- Reservations by Role --}}
            <div class="bg-[#FFFFFF] shadow-xl rounded-xl p-6 border-t-4 border-[#1D2636]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-[#172030] flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#1D2636]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Reservations by User Role
                    </h3>
                </div>
                <div style="height: 300px;" class="flex items-center justify-center">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Facilities Table --}}
        <div class="bg-[#FFFFFF] shadow-xl rounded-xl p-6 border-t-4 border-[#002366]">
            <h3 class="text-xl font-bold text-[#172030] mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Top 5 Most Booked Facilities
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#333C4D] divide-opacity-10">
                    <thead class="bg-[#F8F9FA]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Facility Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Capacity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Total Bookings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase tracking-wider">Popularity</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#FFFFFF] divide-y divide-[#333C4D] divide-opacity-10">
                        @forelse($topFacilities as $index => $facility)
                        <tr class="hover:bg-[#F8F9FA] transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($index === 0)
                                        <span class="text-2xl">🥇</span>
                                    @elseif($index === 1)
                                        <span class="text-2xl">🥈</span>
                                    @elseif($index === 2)
                                        <span class="text-2xl">🥉</span>
                                    @else
                                        <span class="text-lg font-semibold text-[#333C4D]">#{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-[#172030]">{{ $facility->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#333C4D]">
                                {{ $facility->location ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#333C4D]">
                                {{ $facility->capacity ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-[#002366] bg-opacity-10 text-[#002366]">
                                    {{ $facility->reservations_count }} bookings
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-[#333C4D] bg-opacity-20 rounded-full h-2.5 mr-2" style="min-width: 100px;">
                                        <div class="bg-[#002366] h-2.5 rounded-full" style="width: {{ $topFacilities->max('reservations_count') > 0 ? ($facility->reservations_count / $topFacilities->max('reservations_count')) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-[#333C4D]">
                                        {{ $topFacilities->max('reservations_count') > 0 ? round(($facility->reservations_count / $topFacilities->max('reservations_count')) * 100) : 0 }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#333C4D]">
                                <svg class="mx-auto h-12 w-12 text-[#333C4D] opacity-40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                No facility data available for the selected period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Monthly Reservations Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: 'Reservations',
                        data: @json($monthlyData),
                        borderColor: '#002366',
                        backgroundColor: 'rgba(0, 35, 102, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: '#333C4D'
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: { 
                                stepSize: 1,
                                precision: 0,
                                color: '#333C4D'
                            },
                            grid: {
                                color: 'rgba(51, 60, 77, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#333C4D'
                            },
                            grid: {
                                color: 'rgba(51, 60, 77, 0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Status Pie Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $reservationStats['pending'] }},
                            {{ $reservationStats['approved'] }},
                            {{ $reservationStats['rejected'] }},
                            {{ $reservationStats['cancelled'] }}
                        ],
                        backgroundColor: ['#F59E0B', '#10B981', '#EF4444', '#6B7280'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#333C4D'
                            }
                        }
                    }
                }
            });
        }

        // Weekly Chart
        const weeklyCtx = document.getElementById('weeklyChart');
        if (weeklyCtx) {
            new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: @json($weeklyLabels),
                    datasets: [{
                        label: 'Daily Reservations',
                        data: @json($weeklyData),
                        backgroundColor: '#172030',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: '#333C4D'
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: { 
                                stepSize: 1,
                                precision: 0,
                                color: '#333C4D'
                            },
                            grid: {
                                color: 'rgba(51, 60, 77, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#333C4D'
                            },
                            grid: {
                                color: 'rgba(51, 60, 77, 0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Role Chart
        const roleCtx = document.getElementById('roleChart');
        if (roleCtx) {
            new Chart(roleCtx, {
                type: 'pie',
                data: {
                    labels: @json($reservationsByRole->pluck('role')->map(fn($role) => ucfirst($role))),
                    datasets: [{
                        data: @json($reservationsByRole->pluck('total')),
                        backgroundColor: ['#1D2636', '#172030', '#002366'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#333C4D'
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endpush