@extends('layouts.admin')

@section('title', 'Facilities Management')

@section('content')
<div x-data="facilityDashboard()" x-init="init()" class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#172030]">Facilities Management</h1>
                <p class="text-[#333C4D] mt-2 opacity-75">Manage campus facilities, resources, and availability</p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <a href="{{ route('admin.facilities.create') }}" 
                   class="px-4 py-2 bg-[#002366] text-white rounded-lg hover:bg-[#001A4A] flex items-center justify-center shadow-md transition-all w-full md:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Facility
                </a>
            </div>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <template x-for="card in cards" :key="card.title">
                <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 cursor-pointer transition transform hover:-translate-y-1 hover:shadow-xl h-full"
                     :class="card.colorClass"
                     @click="setStatus(card.filter)">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-[#333C4D] text-sm font-medium uppercase truncate" x-text="card.title"></h3>
                            <p class="text-3xl font-bold mt-2 break-words" 
                               :class="card.textClass" 
                               x-text="card.count"></p>
                        </div>
                        <div class="flex-shrink-0 rounded-lg p-3" :class="card.iconBg">
                            <svg class="w-8 h-8" :class="card.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Filters & Search Bar --}}
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                {{-- Search --}}
                <div class="w-full lg:w-1/3">
                    <label class="block text-sm font-medium text-[#333C4D] mb-2">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#333C4D] opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               placeholder="Search by name..."
                               x-model="search"
                               @input.debounce.500ms="fetchFacilities()"
                               class="w-full pl-10 pr-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366] text-[#172030]">
                    </div>
                </div>

                {{-- Type Filter --}}
                <div class="w-full md:w-1/2 lg:w-1/4">
                    <label class="block text-sm font-medium text-[#333C4D] mb-2">Facility Type</label>
                    <select x-model="type"
                            @change="fetchFacilities()"
                            class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366] bg-white text-[#172030]">
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
                <div class="w-full md:w-1/2 lg:w-1/4">
                    <label class="block text-sm font-medium text-[#333C4D] mb-2">Status</label>
                    <select x-model="status"
                            @change="fetchFacilities()"
                            class="w-full px-4 py-2 border border-[#333C4D] border-opacity-20 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002366] bg-white text-[#172030]">
                        <option value="">All Status</option>
                        <option value="available">Available</option>
                        <option value="booked">Booked</option>
                    </select>
                </div>

                {{-- View Toggle --}}
                <div class="w-full lg:w-auto lg:flex-1">
                    <label class="block text-sm font-medium text-[#333C4D] mb-2">View</label>
                    <div class="flex rounded-lg border border-[#333C4D] border-opacity-20 overflow-hidden h-[42px]">
                        <button @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-[#002366] text-white' : 'bg-white text-[#333C4D] hover:bg-gray-50'"
                                class="flex-1 px-4 transition flex justify-center items-center h-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button @click="viewMode = 'table'"
                                :class="viewMode === 'table' ? 'bg-[#002366] text-white' : 'bg-white text-[#333C4D] hover:bg-gray-50'"
                                class="flex-1 px-4 transition flex justify-center items-center h-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Facilities List --}}
        <div class="bg-white shadow-lg rounded-lg p-6 min-h-[400px]">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#333C4D] border-opacity-10">
                <h2 class="text-lg font-bold text-[#172030]">Facility List</h2>
                <div class="text-sm text-[#333C4D] opacity-75" x-show="hasFacilities">
                    Showing <span x-text="facilities.length"></span> results
                </div>
            </div>

            {{-- Empty State --}}
            <div x-show="!hasFacilities" class="text-center py-16">
                <div class="flex flex-col items-center justify-center text-[#333C4D] opacity-50">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-lg font-medium text-[#172030]">No facilities found</h3>
                    <p class="text-sm">Try adjusting your filters or search terms</p>
                </div>
            </div>

            {{-- Grid View --}}
            <div x-show="viewMode === 'grid' && hasFacilities" x-transition>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="facility in facilities" :key="facility.id">
                        <div class="bg-white border border-[#333C4D] border-opacity-20 rounded-xl overflow-hidden hover:shadow-lg transition group">
                            <div class="relative h-48 bg-[#F8F9FA] overflow-hidden">
                                <img :src="facility.image_url" :alt="facility.name" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 right-3" x-html="facility.status_badge"></div>
                                <div class="absolute top-3 left-3" x-html="facility.type_badge"></div>
                            </div>

                            <div class="p-5">
                                <h3 class="text-lg font-bold text-[#172030] mb-3 truncate" x-text="facility.name"></h3>
                                
                                <div class="space-y-2.5 text-sm text-[#333C4D]">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2.5 text-[#002366] opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span x-text="facility.location" class="truncate"></span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2.5 text-[#002366] opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span x-text="facility.capacity + (facility.max_capacity !== 'N/A' ? ' - ' + facility.max_capacity : '') + ' people'"></span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2.5 text-[#002366] opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span x-text="facility.opening_time + ' - ' + facility.closing_time"></span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-[#333C4D] border-opacity-10">
                                    <p class="text-xs uppercase font-bold text-[#333C4D] opacity-60 mb-1">Next Reservation</p>
                                    <p class="text-sm font-medium text-[#172030]" x-html="facility.next_reservation"></p>
                                </div>
                            </div>

                            <div class="px-5 pb-5 flex gap-2">
                                <a :href="facility.view_url" 
                                   class="flex-1 text-center px-3 py-2 bg-[#002366] bg-opacity-10 text-[#002366] text-sm font-semibold rounded hover:bg-[#002366] hover:text-white transition">
                                    View
                                </a>
                                <template x-if="!facility.is_deleted">
                                    <a :href="facility.edit_url" 
                                       class="flex-1 text-center px-3 py-2 bg-[#333C4D] bg-opacity-10 text-[#172030] text-sm font-semibold rounded border border-[#333C4D] border-opacity-20 hover:bg-[#333C4D] hover:text-white transition">
                                        Edit
                                    </a>
                                </template>
                                <template x-if="facility.is_deleted">
                                    <button disabled 
                                            class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-400 text-sm font-semibold rounded cursor-not-allowed">
                                        Edit
                                    </button>
                                </template>
                                <a :href="`/admin/reservations?facility_id=${facility.id}`" 
                                   class="flex-1 text-center px-3 py-2 bg-[#10B981] bg-opacity-10 text-[#10B981] text-sm font-semibold rounded hover:bg-[#10B981] hover:text-white transition">
                                    Bookings
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Table View --}}
            <div x-show="viewMode === 'table' && hasFacilities" x-transition class="overflow-x-auto rounded-lg border border-[#333C4D] border-opacity-20">
                <table class="min-w-full divide-y divide-[#333C4D] divide-opacity-20">
                    <thead class="bg-[#F8F9FA]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Facility</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Capacity</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#333C4D] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#333C4D] divide-opacity-10">
                        <template x-for="facility in facilities" :key="facility.id">
                            <tr class="hover:bg-[#F8F9FA] transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img :src="facility.image_url" :alt="facility.name" class="h-10 w-10 rounded-lg object-cover">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-[#172030]" x-text="facility.name"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" x-html="facility.type_badge"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#333C4D]" x-text="facility.location"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#333C4D]" x-text="facility.capacity + (facility.max_capacity !== 'N/A' ? ' - ' + facility.max_capacity : '')"></td>
                                <td class="px-6 py-4 whitespace-nowrap" x-html="facility.status_badge"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-3">
                                        <a :href="facility.view_url" class="text-[#002366] hover:text-[#001A4A] font-medium">View</a>
                                        <template x-if="!facility.is_deleted">
                                            <a :href="facility.edit_url" class="text-[#172030] hover:text-[#1D2636] font-medium">Edit</a>
                                        </template>
                                        <a :href="facility.book_url" class="text-[#10B981] hover:text-[#059669] font-medium">Book</a>
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
                colorClass: 'border-[#10B981]', 
                textClass: 'text-[#10B981]', 
                filter: 'available',
                icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                iconBg: 'bg-[#10B981] bg-opacity-10',
                iconColor: 'text-[#10B981]'
            },
            { 
                title: 'Booked', 
                count: {{ $bookedFacilities ?? 0 }}, 
                colorClass: 'border-[#F59E0B]', 
                textClass: 'text-[#F59E0B]', 
                filter: 'booked',
                icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                iconBg: 'bg-[#F59E0B] bg-opacity-10',
                iconColor: 'text-[#F59E0B]'
            }
        ],

        get hasFacilities() { 
            return Array.isArray(this.facilities) && this.facilities.length > 0;
        },

        init() { 
            this.fetchFacilities();
            this.$watch('viewMode', value => {
                localStorage.setItem('facilityViewMode', value);
            });

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
                
                const res = await fetch(`{{ route('admin.facilities.ajax') }}?${params}`);
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                this.facilities = Array.isArray(data.facilities) ? data.facilities : [];
                this.paginationHtml = data.pagination?.links || '';
                
                if (data.counts) {
                    this.cards[0].count = data.counts.total;
                    this.cards[1].count = data.counts.available;
                    this.cards[2].count = data.counts.booked;
                }
                
            } catch(e) { 
                console.error('Error fetching facilities:', e);
                this.facilities = [];
            }
        }
    }
}
</script>
@endpush