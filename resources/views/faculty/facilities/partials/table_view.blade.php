{{-- resources/views/facilities/partials/table_view.blade.php --}}
<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Location</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Capacity</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(facility, index) in facilities" :key="facility.id">
                <tr class="border-t border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-4 py-2 text-sm text-gray-600" x-text="index + 1"></td>
                    <td class="px-4 py-2 text-sm text-gray-800" x-text="facility.name"></td>
                    <td class="px-4 py-2 text-sm text-gray-800 capitalize" x-text="facility.type.replace('_', ' ')"></td>
                    <td class="px-4 py-2 text-sm text-gray-800" x-text="facility.location"></td>
                    <td class="px-4 py-2 text-sm font-semibold">
                        <span 
                            x-text="facility.status"
                            :class="facility.status === 'available' ? 'text-green-600' : 'text-yellow-600'"
                        ></span>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800" x-text="facility.capacity"></td>
                    <td class="px-4 py-2 text-sm">
                        <a 
                            :href="`/dashboard/admin/facilities/${facility.id}/edit`" 
                            class="text-blue-600 hover:underline mr-2">Edit</a>
                        <button 
                            @click="deleteFacility(facility.id)" 
                            class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            </template>
            <tr x-show="facilities.length === 0">
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">No facilities found.</td>
            </tr>
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4" x-html="paginationHtml"></div>
</div>
