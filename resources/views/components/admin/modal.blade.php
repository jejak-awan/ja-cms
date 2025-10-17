{{-- Alpine.js Interactive Modal Component --}}
<div 
    x-data="modal('{{ $id ?? 'modal-' . \Illuminate\Support\Str::random(6) }}')"
    x-show="open"
    @keydown.escape="close()"
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div 
        @click="close()"
        class="absolute inset-0 bg-black/50 dark:bg-black/70 transition-opacity"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    {{-- Modal Dialog --}}
    <div 
        @click.stop
        class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title ?? 'Dialog' }}
            </h3>
            <button 
                @click="close()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                title="Close"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-6 text-gray-600 dark:text-gray-400">
            {{ $slot }}
        </div>

        {{-- Footer (if actions provided) --}}
        @if(isset($footer) || isset($confirmText) || isset($cancelText))
        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            @if(isset($cancelText))
                <button 
                    @click="close()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition"
                >
                    {{ $cancelText }}
                </button>
            @endif

            @if(isset($confirmText))
                <button 
                    @click="handleConfirm()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"
                >
                    {{ $confirmText }}
                </button>
            @endif

            @isset($footer)
                {{ $footer }}
            @endisset
        </div>
        @endif
    </div>
</div>

<script>
function modal(id) {
    return {
        id: id,
        open: false,

        /**
         * Open the modal
         */
        open() {
            this.open = true;
            document.body.style.overflow = 'hidden';
            this.$dispatch('modal-opened', { id: this.id });
        },

        /**
         * Close the modal
         */
        close() {
            this.open = false;
            document.body.style.overflow = 'auto';
            this.$dispatch('modal-closed', { id: this.id });
        },

        /**
         * Toggle modal state
         */
        toggle() {
            this.open ? this.close() : this.open();
        },

        /**
         * Handle confirm action
         */
        handleConfirm() {
            this.$dispatch('modal-confirm', { id: this.id });
            this.close();
        },

        /**
         * Listen for open event
         */
        init() {
            document.addEventListener(`open-modal-${this.id}`, () => this.open());
            document.addEventListener(`close-modal-${this.id}`, () => this.close());
        },

        destroy() {
            document.removeEventListener(`open-modal-${this.id}`, () => this.open());
            document.removeEventListener(`close-modal-${this.id}`, () => this.close());
        }
    };
}

// Global helper functions
window.openModal = function(id) {
    document.dispatchEvent(new CustomEvent(`open-modal-${id}`));
};

window.closeModal = function(id) {
    document.dispatchEvent(new CustomEvent(`close-modal-${id}`));
};
</script>
