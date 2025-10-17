{{-- Admin Layout Styles --}}
<style>
    * {
        scrollbar-width: thin;
        scrollbar-color: rgba(107, 114, 128, 0.5) transparent;
    }

    *::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    *::-webkit-scrollbar-track {
        background: transparent;
    }

    *::-webkit-scrollbar-thumb {
        background-color: rgba(107, 114, 128, 0.5);
        border-radius: 3px;
    }

    *::-webkit-scrollbar-thumb:hover {
        background-color: rgba(107, 114, 128, 0.7);
    }

    body {
        overflow-x: hidden;
    }
    
    /* Dark mode styles */
    [data-theme="dark"] {
        --bg-primary: #1a202c;
        --bg-secondary: #2d3748;
        --text-primary: #f7fafc;
        --text-secondary: #cbd5e0;
    }
    
    .dark body {
        background-color: #111827;
        color: #f3f4f6;
    }
    
    .dark .bg-white {
        background-color: #1f2937;
    }
    
    .dark .text-gray-900 {
        color: #f3f4f6;
    }
    
    .dark .text-gray-600 {
        color: #9ca3af;
    }
    
    .dark .text-gray-500 {
        color: #6b7280;
    }
    
    .dark .border-gray-200 {
        border-color: #374151;
    }
    
    .dark .bg-gray-50 {
        background-color: #111827;
    }
    
    .dark .bg-gray-100 {
        background-color: #1f2937;
    }
    
    .dark .hover\:bg-gray-200:hover {
        background-color: #374151;
    }

    /* Dark mode scrollbar */
    .dark {
        scrollbar-color: rgba(55, 65, 81, 0.7) transparent;
    }

    .dark *::-webkit-scrollbar-thumb {
        background-color: rgba(55, 65, 81, 0.7);
    }

    .dark *::-webkit-scrollbar-thumb:hover {
        background-color: rgba(75, 85, 99, 0.9);
    }
    
    /* Sidebar mobile */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        transform: translateX(-100%);
        transition: transform 0.2s ease-in-out;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
    
    @media (min-width: 769px) {
        .sidebar {
            position: relative;
            transform: translateX(0);
            overflow-y: auto;
        }
    }

    /* Hide scrollbar on main content when sidebar is present (desktop) */
    @media (min-width: 769px) {
        main {
            overflow-y: auto;
        }
    }

    /* Smoother navigation menu items */
    nav button {
        cursor: pointer;
    }

    /* Custom scrollbar for sidebar navigation */
    nav {
        scrollbar-width: thin;
        scrollbar-color: rgba(107, 114, 128, 0.3) transparent;
    }

    nav::-webkit-scrollbar {
        width: 4px;
    }

    nav::-webkit-scrollbar-thumb {
        background-color: rgba(107, 114, 128, 0.3);
        border-radius: 2px;
    }

    nav::-webkit-scrollbar-thumb:hover {
        background-color: rgba(107, 114, 128, 0.5);
    }

    .dark nav {
        scrollbar-color: rgba(55, 65, 81, 0.5) transparent;
    }

    .dark nav::-webkit-scrollbar-thumb {
        background-color: rgba(55, 65, 81, 0.5);
    }

    .dark nav::-webkit-scrollbar-thumb:hover {
        background-color: rgba(75, 85, 99, 0.7);
    }
</style>
