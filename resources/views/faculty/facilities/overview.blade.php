@extends('layouts.faculty')

<style>
    :root {
        /* Updated color palette */
        --color-dark-navy: #172030;
        --color-royal-blue: #002366;
        --color-navy-blue: #00285C;
        --color-white: #FFFFFF;
        --color-off-white: #F8F9FA;
        --color-text-dark: #333C4D;
        --color-text-light: #1D2636;
        --color-dark-blue: #001A4A;
        --color-charcoal: #1D2636;
    }

    body {
        background: linear-gradient(135deg, var(--color-off-white) 0%, #e8eaed 50%, #d1d5db 100%);
    }
</style>

@section('content')
<div x-data="facilityDashboard()" x-init="init()" class="mb-6">

    {{-- Loading Spinner --}}
    <div x-show="loading" class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-50 z-50">
        <div class="loader ease-linear rounded-full border-4 border-t-4 h-12 w-12" style="border-color: #e8eaed; border-top-color: var(--color-royal-blue);"></div>
    </div>

    {{-- Dashboard Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <template x-for="card in cards" :key="card.title">
            <div
                class="bg-white shadow rounded-lg p-5 border-t-4 cursor-pointer transition transform hover:-translate-y-1 hover:shadow-lg"
                :class="card.colorClass"
                @click="setStatus(card.filter)"
            >
                <h3 class="text-gray-800 text-sm font-semibold" x-text="card.title"></h3>
                <p
                    :class="card.textClass"
                    class="text-2xl font-bold mt-2 transition-all duration-500"
                    x-text="card.count"
                ></p>
            </div>
        </template>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-2">
        <template x-for="filter in filterOptions" :key="filter.value">
            <a
                href="#"
                @click.prevent="setStatus(filter.value)"
                class="px-4 py-1 rounded font-medium"
                :class="status === filter.value ? 'text-white' : 'bg-gray-200 text-gray-700'"
                :style="status === filter.value ? 'background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));' : ''"
            >
                <span x-text="filter.label"></span>
            </a>
        </template>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input
            type="text"
            placeholder="Search facilities..."
            x-model="search"
            @input.debounce.500ms="fetchFacilities()"
            class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-transparent"
            style="border-color: #d1d5db; focus:ring-color: var(--color-royal-blue);"
        >
    </div>

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg p-6 border-t-4" style="border-top-color: var(--color-dark-navy);">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Facility Overview</h2>
            <a href="{{ route('facilities.create') }}"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-royal-blue)] transition"
               style="background: linear-gradient(135deg, var(--color-royal-blue), var(--color-navy-blue));">
                + Add Facility
            </a>
        </div>

        <div x-show="!hasFacilities && !loading" class="text-center py-6 text-gray-500 font-medium">
            <p x-transition.opacity.duration.500ms>No facilities match your search or filter.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" x-show="hasFacilities" x-transition.opacity.duration.500ms>
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Reservation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="row in tableRows" :key="row.id">
                        <tr class="hover:bg-gray-50" x-html="row.html"></tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4" id="pagination-links" x-html="paginationHtml"></div>
    </div>
</div>

<style>
.loader {
    animation: spin 1s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
function facilityDashboard() {
    return {
        status: '{{ request("status") ?? "" }}',
        search: '{{ request("search") ?? "" }}',
        tableRows: [],
        paginationHtml: '',
        cards: [
            { title: 'Total Facilities', count: {{ $totalFacilities }}, colorClass: 'border-[var(--color-royal-blue)]', textClass: 'text-[var(--color-royal-blue)]', filter: '' },
            { title: 'Available Facilities', count: {{ $availableFacilities }}, colorClass: 'border-green-500', textClass: 'text-green-600', filter: 'available' },
            { title: 'Booked Facilities', count: {{ $bookedFacilities }}, colorClass: 'border-yellow-500', textClass: 'text-yellow-600', filter: 'booked' }
        ],
        filterOptions: [
            { label: 'All', value: '' },
            { label: 'Available', value: 'available' },
            { label: 'Booked', value: 'booked' }
        ],
        loading: false,
        hasFacilities: true,
        interval: null,

        init() {
            this.fetchFacilities();
            // Auto-refresh every 10 seconds
            this.interval = setInterval(() => {
                this.fetchFacilities();
            }, 10000);
        },

        setStatus(filter) {
            this.status = filter;
            this.fetchFacilities();
        },

        fetchFacilities(page = 1) {
            this.loading = true;
            const params = new URLSearchParams({
                status: this.status,
                search: this.search,
                page: page
            });

            fetch('{{ route("facilities.ajax") }}?' + params.toString())
                .then(res => res.json())
                .then(data => {
                    this.tableRows = data.facilities.map(f => ({ id: f.id, html: f.html }));
                    this.paginationHtml = data.pagination;
                    this.hasFacilities = data.facilities.length > 0;

                    if (data.counts) {
                        ['total', 'available', 'booked'].forEach((key, idx) => {
                            this.cards[idx].count = data.counts[key];
                        });
                    }

                    // Rebind pagination links
                    document.querySelectorAll('#pagination-links a').forEach(link => {
                        link.addEventListener('click', e => {
                            e.preventDefault();
                            const url = new URL(e.target.href);
                            this.fetchFacilities(url.searchParams.get('page') || 1);
                        });
                    });
                })
                .finally(() => this.loading = false);
        },

        // Clean up interval when component is destroyed
        destroy() {
            if (this.interval) clearInterval(this.interval);
        }
    }
}
</script>
@endsection