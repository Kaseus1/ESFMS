@extends('layouts.dashboard')

@section('content')
<div x-data="facilityDashboard()" x-init="init()" class="mb-6">

    {{-- Loading Spinner --}}
    <div x-show="loading" class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-50 z-50">
        <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12"></div>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Facility Management</h1>
        <p class="text-gray-600 mt-2">Manage and monitor all facilities</p>
    </div>

    {{-- Dashboard Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <template x-for="card in cards" :key="card.title">
            <div
                class="bg-white shadow rounded-lg p-5 border-t-4 cursor-pointer transition transform hover:-translate-y-1 hover:shadow-lg"
                :class="card.colorClass"
                @click="setStatus(card.filter)"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600 text-sm font-semibold" x-text="card.title"></h3>
                        <p
                            :class="card.textClass"
                            class="text-3xl font-bold mt-2 transition-all duration-500"
                            x-text="card.count"
                        ></p>
                    </div>
                    <div :class="card.iconBg" class="rounded-full p-3">
                        <svg class="w-8 h-8" :class="card.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon"/>
                        </svg>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Filters & Search Bar --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            {{-- Search --}}
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input
                    type="text"
                    placeholder="Search by name, location, or type..."
                    x-model="search"
                    @input.debounce.500ms="fetchFacilities()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            {{-- Type Filter --}}
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Facility Type</label>
                <select
                    x-model="type"
                    @change="fetchFacilities()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="all">All Types</option>
                    <option value="classroom">Classroom</option>
                    <option value="conference_room">Conference Room</option>
                    <option value="auditorium">Auditorium</option>
                    <option value="laboratory">Laboratory</option>
                    <option value="sports_facility">Sports Facility</option>
                    <option value="other">Other</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select
                    x-model="status"
                    @change="fetchFacilities()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                </select>
            </div>

            {{-- View Toggle --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">View</label>
                <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                    <button
                        @click="viewMode = 'grid'"
                        :class="viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
                        class="flex-1 px-4 py-2 transition"
                    >
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button
                        @click="viewMode = 'table'"
                        :class="viewMode === 'table' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
                        class="flex-1 px-4 py-2 transition"
                    >
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Facility Button (Mobile) --}}
    @if(auth()->user()->role === 'admin')
    <div class="mb-4 md:hidden">
        <a href="{{ route('admin.facilities.create') }}"
           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Facility
        </a>
    </div>
    @endif

    {{-- Grid View --}}
    <div x-show="viewMode === 'grid'" x-transition>
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Facilities</h2>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.facilities.create') }}"
                   class="hidden md:inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Facility
                </a>
                @endif
            </div>

            <div x-show="!hasFacilities && !loading" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="mt-4 text-gray-500 font-medium">No facilities found</p>
                <p class="text-sm text-gray-400">Try adjusting your filters or search terms</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="hasFacilities">
                <template x-for="facility in facilities" :key="facility.id">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                        {{-- Image --}}
                        <div class="relative h-48 bg-gray-100">
                            <img :src="facility.image_url" :alt="facility.name" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2" x-html="facility.status_badge"></div>
                            <div class="absolute top-2 left-2" x-html="facility.type_badge"></div>
                        </div>

                        {{-- Content --}}
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2" x-text="facility.name"></h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span x-text="facility.location"></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span x-text="facility.capacity + (facility.max_capacity !== 'N/A' ? ' - ' + facility.max_capacity : '') + ' people'"></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="facility.opening_time + ' - ' + facility.closing_time"></span>
                                </div>
                            </div>

                            {{-- Next Reservation --}}
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500">Next Reservation:</p>
                                <p class="text-sm font-medium" x-html="facility.next_reservation"></p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="px-4 pb-4 flex gap-2">
                            <a :href="facility.view_url" class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200 transition">
                                View
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <a :href="facility.edit_url" class="flex-1 text-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded hover:bg-blue-200 transition">
                                Edit
                            </a>
                            @endif
                            <a :href="facility.book_url" class="flex-1 text-center px-3 py-2 bg-green-100 text-green-700 text-sm font-medium rounded hover:bg-green-200 transition">
                                Book
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-6" x-html="paginationHtml"></div>
        </div>
    </div>

    {{-- Table View --}}
    <div x-show="viewMode === 'table'" x-transition>
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Facilities</h2>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.facilities.create') }}"
                   class="hidden md:inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Facility
                </a>
                @endif
            </div>

            <div x-show="!hasFacilities && !loading" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="mt-4 text-gray-500 font-medium">No facilities found</p>
                <p class="text-sm text-gray-400">Try adjusting your filters or search terms</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" x-show="hasFacilities">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Reservation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="facility in facilities" :key="facility.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img :src="facility.image_url" :alt="facility.name" class="h-10 w-10 rounded object-cover">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="facility.name"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" x-html="facility.type_badge"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="facility.location"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="facility.capacity + (facility.max_capacity !== 'N/A' ? '-' + facility.max_capacity : '')"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div x-text="facility.opening_time"></div>
                                    <div x-text="facility.closing_time"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" x-html="facility.status_badge"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-html="facility.next_reservation"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a :href="facility.view_url" class="text-blue-600 hover:text-blue-900">View</a>
                                        @if(auth()->user()->role === 'admin')
                                        <a :href="facility.edit_url" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        @endif
                                        <a :href="facility.book_url" class="text-green-600 hover:text-green-900">Book</a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mt-4" x-html="paginationHtml"></div>
        </div>
    </div>
</div>

<style>
.loader {
    border-top-color: #3498db;
    animation: spinner 1.5s linear infinite;
}

@keyframes spinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
function facilityDashboard() {
    return {
        status: '{{ request("status") ?? "" }}',
        search: '{{ request("search") ?? "" }}',
        type: '{{ request("type") ?? "all" }}',
        viewMode: localStorage.getItem('facilityViewMode') || 'grid',
        facilities: [],
        paginationHtml: '',
        cards: [
            { 
                title: 'Total Facilities', 
                count: {{ $totalFacilities }}, 
                colorClass: 'border-blue-500', 
                textClass: 'text-blue-600', 
                filter: '',
                icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                iconBg: 'bg-blue-100',
                iconColor: 'text-blue-600'
            },
            { 
                title: 'Available', 
                count: {{ $availableFacilities }}, 
                colorClass: 'border-green-500', 
                textClass: 'text-green-600', 
                filter: 'available',
                icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                iconBg: 'bg-green-100',
                iconColor: 'text-green-600'
            },
            { 
                title: 'Booked', 
                count: {{ $bookedFacilities }}, 
                colorClass: 'border-yellow-500', 
                textClass: 'text-yellow-600', 
                filter: 'booked',
                icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                iconBg: 'bg-yellow-100',
                iconColor: 'text-yellow-600'
            }
        ],
        loading: false,
        get hasFacilities() { return this.facilities.length > 0 },
        init() { 
            this.fetchFacilities();
            this.$watch('viewMode', value => {
                localStorage.setItem('facilityViewMode', value);
            });
        },
        setStatus(val) { 
            this.status = val; 
            this.fetchFacilities();
        },
        async fetchFacilities(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({ 
                    status: this.status, 
                    search: this.search,
                    type: this.type,
                    page: page
                });
                const res = await fetch(`{{ route('admin.facilities.ajax') }}?${params}`);
                const data = await res.json();
                
                this.facilities = data.facilities;
                this.paginationHtml = data.pagination.links || '';
                
                // Update card counts
                this.cards[0].count = data.counts.total;
                this.cards[1].count = data.counts.available;
                this.cards[2].count = data.counts.booked;
            } catch(e) { 
                console.error('Error fetching facilities:', e);
            }
            this.loading = false;
        }
    }
}
</script>
@endsection