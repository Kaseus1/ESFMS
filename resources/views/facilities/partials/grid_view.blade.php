{{-- resources/views/facilities/partials/grid_view.blade.php --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <template x-for="facility in facilities" :key="facility.id">
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-lg transition cursor-pointer">
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-semibold text-gray-800" x-text="facility.name"></h3>
                <span 
                    class="px-2 py-1 text-xs font-semibold rounded"
                    :class="facility.status === 'available' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600'"
                    x-text="facility.status"
                ></span>
            </div>
            <p class="text-gray-600 mt-1 capitalize" x-text="facility.type.replace('_', ' ')"></p>
            <p class="text-gray-500 text-sm mt-1" x-text="facility.location"></p>
            <p class="text-gray-500 text-sm mt-1">Capacity: <span x-text="facility.capacity"></span></p>
            <div class="mt-3 flex justify-end space-x-2">
                <a 
                    :href="`/dashboard/admin/facilities/${facility.id}/edit`" 
                    class="text-blue-600 hover:underline text-sm">Edit</a>
                <button 
                    @click="deleteFacility(facility.id)" 
                    class="text-red-600 hover:underline text-sm">Delete</button>
            </div>
        </div>
    </template>
    <div x-show="facilities.length === 0" class="col-span-full text-center text-gray-500 py-10">
        No facilities found.
    </div>
</div>
