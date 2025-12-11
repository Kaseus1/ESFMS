{{-- Success Modal --}}
<div id="successModal" x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail === 'successModal' ? show = true : null"
     x-on:close-modal.window="$event.detail === 'successModal' ? show = false : null"
     x-on:keydown.escape.window="show = false"
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div x-show="show" 
             x-transition
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900 success-title">Success</h3>
            </div>
            <p class="text-gray-600 success-message">Operation completed successfully.</p>
            <div class="mt-4 flex justify-end">
                <button @click="show = false" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Error Modal --}}
<div id="errorModal" x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail === 'errorModal' ? show = true : null"
     x-on:close-modal.window="$event.detail === 'errorModal' ? show = false : null"
     x-on:keydown.escape.window="show = false"
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div x-show="show" 
             x-transition
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900 error-title">Error</h3>
            </div>
            <div class="text-gray-600 error-message">An error occurred.</div>
            <div class="mt-4 flex justify-end">
                <button @click="show = false" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Modal --}}
<div id="confirmModal" x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail === 'confirmModal' ? show = true : null"
     x-on:close-modal.window="$event.detail === 'confirmModal' ? show = false : null"
     x-on:keydown.escape.window="show = false"
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div x-show="show" 
             x-transition
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900">Confirm Action</h3>
            </div>
            <p class="text-gray-600 confirm-message">Are you sure?</p>
            <div class="mt-4 flex justify-end space-x-3">
                <button @click="show = false; if(window.cancelCallback) window.cancelCallback();" data-modal-cancel class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                <button @click="show = false; if(window.confirmCallback) window.confirmCallback();" data-modal-confirm class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Confirm</button>
            </div>
        </div>
    </div>
</div>

{{-- Info Modal --}}
<div id="infoModal" x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail === 'infoModal' ? show = true : null"
     x-on:close-modal.window="$event.detail === 'infoModal' ? show = false : null"
     x-on:keydown.escape.window="show = false"
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div x-show="show" 
             x-transition
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900 custom-title">Information</h3>
            </div>
            <p class="text-gray-600 info-message">Here is some information.</p>
            <div class="mt-4 flex justify-end">
                <button @click="show = false" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Got it</button>
            </div>
        </div>
    </div>
</div>

{{-- Loading Modal --}}
<div id="loadingModal" x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail === 'loadingModal' ? show = true : null"
     x-on:close-modal.window="$event.detail === 'loadingModal' ? show = false : null"
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div x-show="show" 
             x-transition
             class="relative bg-white rounded-lg shadow-xl max-w-sm w-full p-6 text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600 loading-message">Loading...</p>
        </div>
    </div>
</div>

{{-- Capacity Warning Modal --}}
<div id="capacityWarningModal" x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail === 'capacityWarningModal' ? show = true : null"
     x-on:close-modal.window="$event.detail === 'capacityWarningModal' ? show = false : null"
     x-on:keydown.escape.window="show = false"
     x-show="show" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div x-show="show" 
             x-transition
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900">Capacity Warning</h3>
            </div>
            <div class="text-gray-600 capacity-warning-content">Capacity exceeded.</div>
            <div class="mt-4 flex justify-end space-x-3">
                <button @click="show = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                <button @click="show = false; if(window.capacityCallback) window.capacityCallback();" data-capacity-proceed class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">Proceed Anyway</button>
            </div>
        </div>
    </div>
</div>