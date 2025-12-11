@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    'title' => null,
    'icon' => null,
    'type' => 'default',
    'closable' => true,
    'closeOnBackdrop' => true,
    'closeOnEsc' => true,
    'autoFocus' => true
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];

$typeConfigs = [
    'default' => [
        'header_class' => 'border-b border-gray-200',
        'icon' => $icon ?: '',
        'title' => $title ?: 'Modal'
    ],
    'error' => [
        'header_class' => 'border-b border-red-200 bg-red-50',
        'icon' => $icon ?: 'fas fa-exclamation-triangle text-red-500',
        'title' => $title ?: 'Error'
    ],
    'success' => [
        'header_class' => 'border-b border-green-200 bg-green-50',
        'icon' => $icon ?: 'fas fa-check-circle text-green-500',
        'title' => $title ?: 'Success'
    ],
    'warning' => [
        'header_class' => 'border-b border-yellow-200 bg-yellow-50',
        'icon' => $icon ?: 'fas fa-exclamation-triangle text-yellow-500',
        'title' => $title ?: 'Warning'
    ],
    'info' => [
        'header_class' => 'border-b border-blue-200 bg-blue-50',
        'icon' => $icon ?: 'fas fa-info-circle text-blue-500',
        'title' => $title ?: 'Information'
    ],
    'loading' => [
        'header_class' => 'border-b border-gray-200 bg-gray-50',
        'icon' => $icon ?: 'fas fa-spinner fa-spin text-gray-500',
        'title' => $title ?: 'Loading'
    ]
];

$config = $typeConfigs[$type] ?? $typeConfigs['default'];
@endphp

<div
    x-data="{
        show: @js($show),
        isLoading: false,
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                .filter(el => ! el.hasAttribute('disabled') && el.offsetParent !== null)
        },
        firstFocusable() { 
            const focusables = this.focusables()
            return focusables.length > 0 ? focusables[0] : null
        },
        lastFocusable() { 
            const focusables = this.focusables()
            return focusables.length > 0 ? focusables[focusables.length - 1] : null
        },
        nextFocusable() { 
            const focusables = this.focusables()
            const currentIndex = focusables.indexOf(document.activeElement)
            const nextIndex = currentIndex === -1 ? 0 : (currentIndex + 1) % focusables.length
            return focusables.length > 0 ? focusables[nextIndex] : null
        },
        prevFocusable() { 
            const focusables = this.focusables()
            const currentIndex = focusables.indexOf(document.activeElement)
            const prevIndex = currentIndex <= 0 ? focusables.length - 1 : currentIndex - 1
            return focusables.length > 0 ? focusables[prevIndex] : null
        },
        trapFocus() {
            this.$nextTick(() => {
                const firstElement = this.firstFocusable()
                if (firstElement) {
                    setTimeout(() => firstElement.focus(), 100)
                }
            })
        },
        close() {
            this.show = false
        },
        open() {
            this.show = true
        }
    }"
    x-init="$watch('show', (value, oldValue) => {
        if (value && !oldValue) {
            document.body.classList.add('overflow-y-hidden')
            this.trapFocus()
            this.$dispatch('modal-opened', { name: '{{ $name }}' })
        } else if (!value && oldValue) {
            document.body.classList.remove('overflow-y-hidden')
            this.$dispatch('modal-closed', { name: '{{ $name }}' })
        }
    })"
    
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? open() : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? close() : null"
    x-on:open-{{ $name }}.window="open()"
    x-on:close-{{ $name }}.window="close()"
    
    @if($closable)
        x-on:close.stop="close()"
    @endif
    
    @if($closeOnBackdrop)
        x-on:click="$event.target === $el && close()"
    @endif
    
    @if($closeOnEsc)
        x-on:keydown.escape.window="close()"
    @endif
    
    x-on:keydown.tab.prevent="$event.shiftKey ? prevFocusable()?.focus() : nextFocusable()?.focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable()?.focus()"
    
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 transition-all duration-200"
    style="display: none;"
    
    id="{{ $name }}"
    data-modal-type="{{ $type }}"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $name }}-title"
>
    @if($closeOnBackdrop)
        <div
            x-show="show"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-all duration-300"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>
    @endif

    <div
        x-show="show"
        class="min-h-full flex items-center justify-center p-4 text-center sm:p-0"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div
            class="bg-white rounded-xl shadow-2xl transform transition-all sm:w-full {{ $maxWidth }} mx-auto overflow-hidden"
            @class([
                'border-l-4' => $type !== 'default',
                'border-l-red-500' => $type === 'error',
                'border-l-green-500' => $type === 'success',
                'border-l-yellow-500' => $type === 'warning',
                'border-l-blue-500' => $type === 'info',
                'border-l-gray-500' => $type === 'loading',
            ])
        >
            @if($config['title'] || $config['icon'] || $closable)
                <div class="{{ $config['header_class'] }} px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        @if($config['icon'])
                            <i class="{{ $config['icon'] }} text-lg mr-3 flex-shrink-0"></i>
                        @endif
                        @if($config['title'])
                            <h3 id="{{ $name }}-title" class="text-lg font-semibold leading-6 text-gray-900">
                                {{ $config['title'] }}
                            </h3>
                        @endif
                    </div>
                    
                    @if($closable)
                        <button
                            type="button"
                            x-on:click="close()"
                            class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-1 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-label="Close modal"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            <div class="px-6 py-4 {{ $type === 'loading' ? 'text-center' : '' }}">
                @if($type === 'loading')
                    <div class="py-8">
                        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm text-gray-500">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $slot->isEmpty() ? 'Loading...' : $slot }}
                        </div>
                    </div>
                @else
                    <div id="{{ $name }}-description" class="text-gray-600 leading-relaxed">
                        {{ $slot }}
                    </div>
                @endif
            </div>

            @if($type !== 'loading' && $closable)
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button
                        type="button"
                        x-on:click="close()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    >
                        Close
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>