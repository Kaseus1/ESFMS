@extends('layouts.admin')

@section('title', 'Analytics & Reports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#172030]">Analytics Overview</h1>
            <p class="text-[#333C4D] mt-2 opacity-75">Insights into facility usage, reservations, and user activity</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <input type="date" 
                       name="start_date" 
                       value="{{ $startDate }}" 
                       class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg text-sm focus:ring-2 focus:ring-[#002366] w-full sm:w-auto">
                <input type="date" 
                       name="end_date" 
                       value="{{ $endDate }}" 
                       class="px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg text-sm focus:ring-2 focus:ring-[#002366] w-full sm:w-auto">
                <button type="submit" 
                        class="px-6 py-2 bg-[#172030] text-white rounded-lg hover:bg-[#1D2636] transition shadow-sm font-medium w-full sm:w-auto">
                    Filter
                </button>
            </form>
            
            <a href="{{ route('admin.export.index') }}" 
               class="px-6 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] flex items-center justify-center shadow-md transition-all font-medium w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#002366] hover:shadow-xl transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Total Reservations</p>
                    <h3 class="text-3xl font-bold text-[#172030] mt-1">{{ number_format($totalReservations) }}</h3>
                    <p class="text-xs text-[#333C4D] opacity-60 mt-2">In selected period</p>
                </div>
                <div class="p-3 bg-[#002366] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#002366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#10B981] hover:shadow-xl transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Total Facilities</p>
                    <h3 class="text-3xl font-bold text-[#172030] mt-1">{{ number_format($totalFacilities) }}</h3>
                    <p class="text-xs text-[#10B981] mt-2 font-medium">Available for booking</p>
                </div>
                <div class="p-3 bg-[#10B981] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#F59E0B] hover:shadow-xl transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Active Users</p>
                    <h3 class="text-3xl font-bold text-[#172030] mt-1">{{ number_format($activeUsers) }}</h3>
                    <p class="text-xs text-[#F59E0B] mt-2 font-medium">
                        Out of {{ number_format($totalUsers) }} total
                    </p>
                </div>
                <div class="p-3 bg-[#F59E0B] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#8B5CF6] hover:shadow-xl transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-[#333C4D] uppercase opacity-70">Pending Requests</p>
                    <h3 class="text-3xl font-bold text-[#172030] mt-1">{{ number_format($reservationStats['pending'] ?? 0) }}</h3>
                    <p class="text-xs text-[#333C4D] opacity-60 mt-2">Requires action</p>
                </div>
                <div class="p-3 bg-[#8B5CF6] bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6 text-[#8B5CF6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-[#333C4D] border-opacity-5">
            <h3 class="text-lg font-bold text-[#172030] mb-4">Monthly Reservations</h3>
            <div class="relative h-64 w-full">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-[#333C4D] border-opacity-5">
            <h3 class="text-lg font-bold text-[#172030] mb-4">Reservations by Role</h3>
            <div class="relative h-64 w-full">
                <canvas id="roleChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-[#333C4D] border-opacity-5 overflow-hidden">
        <div class="px-6 py-4 border-b border-[#333C4D] border-opacity-10 bg-[#F8F9FA] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h3 class="text-lg font-bold text-[#172030]">Top 5 Booked Facilities</h3>
            <a href="{{ route('admin.facilities.index') }}" class="text-sm text-[#002366] font-medium hover:underline">Manage Facilities &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#333C4D] divide-opacity-10">
                <thead class="bg-[#F8F9FA]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Facility Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Total Bookings</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-[#333C4D] uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#333C4D] divide-opacity-10">
                    @forelse($topFacilities as $facility)
                        <tr class="hover:bg-[#F8F9FA] transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded bg-[#E5E7EB] flex items-center justify-center text-[#333C4D] font-bold text-xs mr-3">
                                        {{ substr($facility->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-semibold text-[#172030]">{{ $facility->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#333C4D]">{{ $facility->location }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#002366]">{{ $facility->reservations_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Active</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-[#333C4D] opacity-60">
                                No data available for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Monthly Reservations Chart ---
    const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Reservations',
                data: @json($monthlyData),
                borderColor: '#002366',
                backgroundColor: 'rgba(0, 35, 102, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#002366'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#172030',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#e5e7eb' } },
                x: { grid: { display: false } }
            }
        }
    });

    // --- Role Distribution Chart ---
    const roleData = @json($reservationsByRole); // Ensure this matches variable passed from controller
    const labels = roleData.map(item => item.role.charAt(0).toUpperCase() + item.role.slice(1));
    const data = roleData.map(item => item.total);

    const ctxRole = document.getElementById('roleChart').getContext('2d');
    new Chart(ctxRole, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#002366', // Faculty/Admin
                    '#10B981', // Student
                    '#F59E0B'  // Guest
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });
});
</script>
@endpush
@endsection