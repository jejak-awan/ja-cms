{{-- Keyboard Shortcuts Help Modal --}}
<div id="keyboard-shortcuts-modal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">⌨️ Keyboard Shortcuts</h2>
            </div>
            <button onclick="closeKeyboardShortcuts()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        {{-- Content --}}
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Global Navigation --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        Global Navigation
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Go to Dashboard</span>
                            <kbd class="kbd">Ctrl + H</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Focus Search</span>
                            <kbd class="kbd">Ctrl + K</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Toggle Sidebar</span>
                            <kbd class="kbd">Ctrl + B</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Show Shortcuts</span>
                            <kbd class="kbd">?</kbd>
                        </div>
                    </div>
                </div>

                {{-- UI Controls --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        UI Controls
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Toggle Notifications</span>
                            <kbd class="kbd">Alt + N</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Toggle User Menu</span>
                            <kbd class="kbd">Alt + U</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Close All Dropdowns</span>
                            <kbd class="kbd">Esc</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Toggle Dark Mode</span>
                            <kbd class="kbd">Ctrl + D</kbd>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Navigation --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        Sidebar Navigation
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Navigate Down</span>
                            <kbd class="kbd">↓</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Navigate Up</span>
                            <kbd class="kbd">↑</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Activate Item</span>
                            <kbd class="kbd">Enter</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Next Item</span>
                            <kbd class="kbd">Tab</kbd>
                        </div>
                    </div>
                </div>

                {{-- Content Actions --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Content Actions
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Create New</span>
                            <kbd class="kbd">Ctrl + N</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Save</span>
                            <kbd class="kbd">Ctrl + S</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Select All</span>
                            <kbd class="kbd">Ctrl + A</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Delete Selected</span>
                            <kbd class="kbd">Delete</kbd>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pro Tips
                </h4>
                <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-2">
                    <li>• Press <kbd class="kbd-sm">?</kbd> anywhere to show this help</li>
                    <li>• Use <kbd class="kbd-sm">Tab</kbd> to navigate through focusable elements</li>
                    <li>• Press <kbd class="kbd-sm">Esc</kbd> to close any modal or dropdown</li>
                    <li>• Most shortcuts work globally across all pages</li>
                </ul>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="flex items-center justify-between p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <kbd class="kbd">Cmd</kbd> = <kbd class="kbd">Ctrl</kbd> on Windows/Linux
            </div>
            <button onclick="closeKeyboardShortcuts()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Got it!
            </button>
        </div>
    </div>
</div>

<style>
.kbd {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-family: monospace;
    font-weight: 600;
    color: #374151;
    background-color: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.dark .kbd {
    color: #d1d5db;
    background-color: #374151;
    border-color: #4b5563;
}

.kbd-sm {
    padding: 0.125rem 0.375rem;
    font-size: 0.625rem;
}
</style>

<script>
function showKeyboardShortcuts() {
    const modal = document.getElementById('keyboard-shortcuts-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeKeyboardShortcuts() {
    const modal = document.getElementById('keyboard-shortcuts-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

// Close on outside click
document.getElementById('keyboard-shortcuts-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeKeyboardShortcuts();
    }
});
</script>

