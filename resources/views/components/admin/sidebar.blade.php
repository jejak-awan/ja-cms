{{-- Sidebar Component --}}
<aside id="sidebar" class="sidebar bg-gradient-to-b from-gray-900 to-gray-800 text-white flex-shrink-0 shadow-xl z-50 transition-all duration-300">
    {{-- Sidebar Header (Same height as navbar - 73px) --}}
    <div class="sidebar-header flex items-center justify-between px-6 border-b border-gray-700" style="height: 73px;">
        <div class="sidebar-logo flex items-center overflow-hidden">
            <h1 class="sidebar-title text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent whitespace-nowrap transition-opacity duration-300">
                Laravel CMS
            </h1>
            <span class="sidebar-icon text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent opacity-0 absolute transition-opacity duration-300">
                LC
            </span>
        </div>
        
        {{-- Toggle Sidebar Button --}}
        <button id="sidebar-toggle" class="sidebar-toggle p-2 hover:bg-gray-700 rounded-lg transition-all duration-300">
            <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>
    </div>
    
    {{-- Navigation --}}
    <nav class="sidebar-nav p-6 space-y-2 overflow-y-auto sidebar-scroll" style="height: calc(100vh - 73px)">
        {{ $slot }}
    </nav>
</aside>

<style>
/* Sidebar Styles */
.sidebar {
    width: 280px;
}

.sidebar.collapsed {
    width: 80px;
}

/* Logo transitions */
.sidebar.collapsed .sidebar-title {
    opacity: 0;
    width: 0;
}

.sidebar.collapsed .sidebar-icon {
    opacity: 1;
    position: relative;
}

/* Hide text when collapsed */
.sidebar.collapsed .sidebar-user-info {
    opacity: 0;
    width: 0;
}

/* Hide menu text but keep icons */
.sidebar.collapsed .sidebar-nav span:not(.badge) {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

/* Keep icons visible and centered when collapsed */
.sidebar.collapsed .sidebar-nav svg {
    margin: 0 auto;
}

/* Center items when collapsed */
.sidebar.collapsed .sidebar-nav a,
.sidebar.collapsed .sidebar-nav button {
    justify-content: center;
    padding-left: 0;
    padding-right: 0;
}

/* Rotate toggle icon when collapsed */
.sidebar.collapsed #sidebar-toggle svg {
    transform: rotate(180deg);
}

/* Hide dropdown arrows when collapsed */
.sidebar.collapsed .sidebar-nav svg[id$="-arrow"] {
    display: none;
}

/* Hide section headers when collapsed */
.sidebar.collapsed .sidebar-nav h3 {
    display: none;
}

/* Adjust dropdown menu when collapsed */
.sidebar.collapsed .sidebar-nav [id$="-dropdown"] {
    position: fixed;
    left: 80px;
    background: #1f2937;
    border: 1px solid #374151;
    border-radius: 0.5rem;
    padding: 0.5rem;
    min-width: 200px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    z-index: 9999;
}

.sidebar.collapsed .sidebar-nav [id$="-dropdown"] span {
    opacity: 1;
    width: auto;
}

/* Custom Scrollbar for Sidebar */
.sidebar-scroll {
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.5) transparent;
}

.sidebar-scroll::-webkit-scrollbar {
    width: 6px;
}

.sidebar-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-scroll::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.5);
    border-radius: 3px;
}

.sidebar-scroll::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.7);
}

/* Ensure icons are always visible */
.sidebar-nav svg:not([id$="-arrow"]) {
    flex-shrink: 0;
}

/* Mobile Styles */
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: -280px;
        height: 100vh;
        transition: left 0.3s ease;
    }
    
    .sidebar.open {
        left: 0;
    }
    
    .sidebar.collapsed {
        width: 280px;
    }
    
    #sidebar-toggle {
        display: none;
    }
}
</style>
