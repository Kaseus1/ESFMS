@extends('layouts.admin')

@section('title', 'Facilities Management')

@section('content')
<div x-data="facilityDashboard()" x-init="init()" class="mb-6">
   

    {{-- Header --}}
    

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
                        <h3 class="text-[#333C4D] text-sm font-semibold" x-text="card.title"></h3>
                        <p
                            :class="card.textClass"
                            class="text-3xl font-bold mt-2"
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
                <label class="block text-sm font-medium text-[#333C4D] mb-2">Search</label>
                <input
                    type="text"
                    placeholder="Search by name, location, or type..."
                    x-model="search"
                    @input.debounce.500ms="fetchFacilities()"
                    class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                >
            </div>

            {{-- Type Filter --}}
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-[#333C4D] mb-2">Facility Type</label>
                <select
                    x-model="type"
                    @change="fetchFacilities()"
                    class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
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
                <label class="block text-sm font-medium text-[#333C4D] mb-2">Status</label>
                <select
                    x-model="status"
                    @change="fetchFacilities()"
                    class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366]"
                >
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                </select>
            </div>

            {{-- View Toggle --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-[#333C4D] mb-2">View</label>
                <div class="flex rounded-lg border border-[#333C4D] border-opacity-20 overflow-hidden">
                    <button
                        @click="viewMode = 'grid'"
                        :class="viewMode === 'grid' ? 'bg-[#002366] text-white' : 'bg-white text-[#333C4D]'"
                        class="flex-1 px-4 py-2 transition"
                    >
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button
                        @click="viewMode = 'table'"
                        :class="viewMode === 'table' ? 'bg-[#002366] text-white' : 'bg-white text-[#333C4D]'"
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

    {{-- Facilities List --}}
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-[#172030]">Facilities</h2>
            <a href="{{ route('admin.facilities.create') }}"
               class="inline-flex items-center px-4 py-2 bg-[#002366] text-white text-sm font-semibold rounded-lg shadow hover:bg-[#00285C] transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Facility
            </a>
        </div>

        {{-- Empty State --}}
        <div x-show="!hasFacilities" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-[#333C4D] border-opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="mt-4 text-[#333C4D] font-medium">No facilities found</p>
            <p class="text-sm text-[#333C4D] border-opacity-60">Try adjusting your filters or search terms</p>
        </div>

        {{-- Grid View --}}
        <div x-show="viewMode === 'grid' && hasFacilities" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="facility in facilities" :key="facility.id">
                    <div class="bg-white border border-[#333C4D] border-opacity-20 rounded-lg overflow-hidden hover:shadow-lg transition">
                        <div class="relative h-48 bg-[#F8F9FA]">
                            <img :src="facility.image_url" :alt="facility.name" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2" x-html="facility.status_badge"></div>
                            <div class="absolute top-2 left-2" x-html="facility.type_badge"></div>
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#172030] mb-2" x-text="facility.name"></h3>
                            <div class="space-y-2 text-sm text-[#333C4D]">
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

                            <div class="mt-3 pt-3 border-t border-[#333C4D] border-opacity-20">
                                <p class="text-xs text-[#333C4D] border-opacity-60">Next Reservation:</p>
                                <p class="text-sm font-medium" x-html="facility.next_reservation"></p>
                            </div>
                        </div>

                        <div class="px-4 pb-4 flex gap-2">
                            <a :href="facility.view_url" 
                               class="flex-1 text-center px-3 py-2 bg-[#002366] bg-opacity-10 text-[#002366] text-sm font-medium rounded hover:bg-[#002366] hover:text-white transition">
                                View
                            </a>
                            <template x-if="!facility.is_deleted">
                                <a :href="facility.edit_url" 
                                   class="flex-1 text-center px-3 py-2 bg-[#333C4D] bg-opacity-10 text-[#172030] text-sm font-medium rounded border border-[#333C4D] hover:bg-[#333C4D] hover:text-white transition">
                                    Edit
                                </a>
                            </template>
                            <template x-if="facility.is_deleted">
                                <button disabled 
                                        class="flex-1 text-center px-3 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded border border-gray-300 cursor-not-allowed">
                                    Edit
                                </button>
                            </template>
                            <a :href="`/admin/reservations?facility_id=${facility.id}`" 
                               class="flex-1 text-center px-3 py-2 bg-green-100 text-green-700 text-sm font-medium rounded hover:bg-green-200 transition">
                                Bookings
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Table View --}}
        <div x-show="viewMode === 'table' && hasFacilities" x-transition class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#333C4D] border-opacity-20">
                <thead class="bg-[#F8F9FA]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase">Facility</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#333C4D] uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#333C4D] border-opacity-20">
                    <template x-for="facility in facilities" :key="facility.id">
                        <tr class="hover:bg-[#F8F9FA]">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img :src="facility.image_url" :alt="facility.name" class="h-10 w-10 rounded object-cover">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-[#172030]" x-text="facility.name"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" x-html="facility.type_badge"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#333C4D]" x-text="facility.location"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#333C4D]" x-text="facility.capacity + (facility.max_capacity !== 'N/A' ? '-' + facility.max_capacity : '')"></td>
                            <td class="px-6 py-4 whitespace-nowrap" x-html="facility.status_badge"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a :href="facility.view_url" class="text-[#002366] hover:text-[#00285C]">View</a>
                                    <template x-if="!facility.is_deleted">
                                        <a :href="facility.edit_url" class="text-[#333C4D] hover:text-[#172030]">Edit</a>
                                    </template>
                                    <template x-if="facility.is_deleted">
                                        <span class="text-gray-400 cursor-not-allowed">Edit</span>
                                    </template>
                                    <a :href="facility.book_url" class="text-green-600 hover:text-green-800">Book</a>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6" x-html="paginationHtml" x-show="hasFacilities"></div>
    </div>
</div>

@endsection

@push('scripts')
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
                count: {{ $totalFacilities ?? 0 }}, 
                colorClass: 'border-[#002366]', 
                textClass: 'text-[#002366]', 
                filter: '',
                icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                iconBg: 'bg-[#002366] bg-opacity-10',
                iconColor: 'text-[#002366]'
            },
            { 
                title: 'Available', 
                count: {{ $availableFacilities ?? 0 }}, 
                colorClass: 'border-green-500', 
                textClass: 'text-green-600', 
                filter: 'available',
                icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                iconBg: 'bg-green-100',
                iconColor: 'text-green-600'
            },
            { 
                title: 'Booked', 
                count: {{ $bookedFacilities ?? 0 }}, 
                colorClass: 'border-yellow-500', 
                textClass: 'text-yellow-600', 
                filter: 'booked',
                icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                iconBg: 'bg-yellow-100',
                iconColor: 'text-yellow-600'
            }
        ],

        get hasFacilities() { 
            return Array.isArray(this.facilities) && this.facilities.length > 0;
        },

        init() { 
            console.log('✅ Alpine.js is working correctly');
            this.fetchFacilities();
            this.$watch('viewMode', value => {
                localStorage.setItem('facilityViewMode', value);
            });

            // Intercept pagination clicks
            document.addEventListener('click', e => {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const page = url.searchParams.get('page') || 1;
                    this.fetchFacilities(page);
                }
            });
        },

        setStatus(val) { 
            this.status = val; 
            this.fetchFacilities();
        },

        async fetchFacilities(page = 1) {
            try {
                const params = new URLSearchParams({ 
                    status: this.status, 
                    search: this.search,
                    type: this.type,
                    page: page
                });
                
                console.log('Fetching facilities...', params.toString());
                
                const res = await fetch(`{{ route('admin.facilities.ajax') }}?${params}`);
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                console.log('Facilities data received:', data);
                
                this.facilities = Array.isArray(data.facilities) ? data.facilities : [];
                this.paginationHtml = data.pagination?.links || '';
                
                // Update card counts
                if (data.counts) {
                    this.cards[0].count = data.counts.total;
                    this.cards[1].count = data.counts.available;
                    this.cards[2].count = data.counts.booked;
                }
                
                console.log('Facilities loaded:', this.facilities.length);
                
            } catch(e) { 
                console.error('Error fetching facilities:', e);
                this.facilities = [];
                alert('Failed to load facilities. Please check console for details.');
            }
        }
    }
}
</script>
@endpush