@extends('layouts.guest')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-6">Reservation Analytics</h1>

    <div class="bg-white shadow rounded p-6">
        <canvas id="reservationChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('reservationChart').getContext('2d');
    const reservationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('F'))),
            datasets: [{
                label: 'Reservations per Month',
                data: @json($data),
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
@endsection
