{{-- Admin Layout Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        {{-- Toggle Dropdown Function --}}
        window.toggleDropdown = function(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(dropdownId + '-arrow');
            
            if (!dropdown) return;
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        };
        
        {{-- Logout Confirmation --}}
        window.confirmLogout = function() {
            if (confirm('{{ __("auth.confirm_logout") }}')) {
                document.getElementById('logout-form').submit();
            }
        };

        {{-- Get DOM Elements --}}
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebarElement = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const themeToggle = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;
        const notificationsButton = document.getElementById('notifications-button');
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        {{-- Mobile Menu Toggle --}}
        if (mobileMenuButton && sidebarElement) {
            mobileMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebarElement.classList.toggle('open');
                mobileOverlay?.classList.toggle('hidden');
            });

            mobileOverlay?.addEventListener('click', function() {
                sidebarElement.classList.remove('open');
                mobileOverlay.classList.add('hidden');
            });
        }

        {{-- Sidebar Toggle (Collapse/Expand) --}}
        if (sidebarToggle && sidebarElement) {
            // Restore sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                sidebarElement.classList.add('collapsed');
            }

            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebarElement.classList.toggle('collapsed');
                
                // Save state to localStorage
                const isCollapsed = sidebarElement.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                // Close all dropdowns when collapsing
                if (isCollapsed) {
                    document.querySelectorAll('[id$="-dropdown"]').forEach(dropdown => {
                        if (!dropdown.id.includes('notifications')) {
                            dropdown.classList.add('hidden');
                        }
                    });
                }
            });
        }

        {{-- Auto-expand sidebar when clicking menu icons in collapsed state --}}
        if (sidebarElement) {
            // Get all menu items in sidebar
            const sidebarMenuItems = sidebarElement.querySelectorAll('.sidebar-nav a, .sidebar-nav button');
            
            sidebarMenuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // Check if sidebar is collapsed
                    const isCollapsed = sidebarElement.classList.contains('collapsed');
                    
                    if (isCollapsed) {
                        // If it's a dropdown button, expand sidebar first
                        if (this.tagName === 'BUTTON') {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Expand sidebar
                            sidebarElement.classList.remove('collapsed');
                            localStorage.setItem('sidebarCollapsed', 'false');
                            
                            // Then open the dropdown after a short delay
                            setTimeout(() => {
                                const dropdownId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                                toggleDropdown(dropdownId);
                            }, 100);
                        }
                        // If it's a link (not dashboard), expand sidebar
                        else if (!this.href.endsWith('/admin')) {
                            // Expand sidebar
                            sidebarElement.classList.remove('collapsed');
                            localStorage.setItem('sidebarCollapsed', 'false');
                        }
                    }
                });
            });
        }

        {{-- Theme Toggle (Dark Mode) --}}
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });

            {{-- Restore theme from localStorage --}}
            if (localStorage.getItem('theme') === 'dark') {
                htmlElement.classList.add('dark');
            }
        }

        {{-- Notifications Dropdown Toggle --}}
        if (notificationsButton && notificationsDropdown) {
            notificationsButton.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationsDropdown.classList.toggle('hidden');
                
                // Close user profile dropdown when opening notifications
                const userProfileDropdown = document.getElementById('user-profile-dropdown');
                if (userProfileDropdown) {
                    userProfileDropdown.classList.add('hidden');
                }
            });

            notificationsDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            document.addEventListener('click', function() {
                notificationsDropdown.classList.add('hidden');
            });
        }

        {{-- User Profile Dropdown Toggle --}}
        const userProfileButton = document.getElementById('user-profile-button');
        const userProfileDropdown = document.getElementById('user-profile-dropdown');

        if (userProfileButton && userProfileDropdown) {
            userProfileButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userProfileDropdown.classList.toggle('hidden');
                
                // Close notifications dropdown when opening user profile
                if (notificationsDropdown) {
                    notificationsDropdown.classList.add('hidden');
                }
            });

            userProfileDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            document.addEventListener('click', function() {
                userProfileDropdown.classList.add('hidden');
            });
        }

        {{-- Prevent Dropdown Close when Clicking Inside --}}
        document.querySelectorAll('[id$="-dropdown"]').forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        {{-- Auto-expand active dropdown on page load --}}
        function autoExpandActiveDropdown() {
            // Get all dropdown containers
            const dropdowns = document.querySelectorAll('[id$="-dropdown"]');
            
            dropdowns.forEach(dropdown => {
                // Check if dropdown contains an active link
                const activeLink = dropdown.querySelector('.bg-blue-600');
                
                if (activeLink) {
                    // Show the dropdown
                    dropdown.classList.remove('hidden');
                    
                    // Rotate the arrow
                    const dropdownId = dropdown.id;
                    const arrow = document.getElementById(dropdownId + '-arrow');
                    if (arrow) {
                        arrow.style.transform = 'rotate(180deg)';
                    }
                }
            });
        }

        // Run on page load
        autoExpandActiveDropdown();

        {{-- Global Keyboard Navigation --}}
        document.addEventListener('keydown', function(e) {
            // Skip if user is typing in input/textarea
            const activeElement = document.activeElement;
            const isTyping = activeElement.tagName === 'INPUT' || 
                            activeElement.tagName === 'TEXTAREA' || 
                            activeElement.isContentEditable;
            
            // ?: Show keyboard shortcuts help
            if (e.key === '?' && !isTyping) {
                e.preventDefault();
                if (typeof showKeyboardShortcuts === 'function') {
                    showKeyboardShortcuts();
                }
            }

            // Ctrl/Cmd + K: Focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"], input[placeholder*="Search"], input[placeholder*="search"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }

            // Ctrl/Cmd + B: Toggle sidebar
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                if (sidebarToggle) {
                    sidebarToggle.click();
                }
            }

            // Ctrl/Cmd + D: Toggle dark mode
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                if (themeToggle) {
                    themeToggle.click();
                }
            }

            // Ctrl/Cmd + H: Go to home/dashboard
            if ((e.ctrlKey || e.metaKey) && e.key === 'h') {
                e.preventDefault();
                window.location.href = '/admin';
            }

            // Ctrl/Cmd + N: Create new (if on list page)
            if ((e.ctrlKey || e.metaKey) && e.key === 'n' && !isTyping) {
                e.preventDefault();
                const createButton = document.querySelector('[href*="/create"]') || 
                                   document.querySelector('button[x-on\\:click*="create"]') ||
                                   document.querySelector('a:contains("Create"), a:contains("Add New")');
                if (createButton) {
                    createButton.click();
                }
            }

            // Ctrl/Cmd + S: Save form
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                const submitButton = document.querySelector('button[type="submit"]') ||
                                   document.querySelector('button:contains("Save")');
                if (submitButton) {
                    submitButton.click();
                }
            }

            // Alt + N: Toggle notifications
            if (e.altKey && e.key === 'n') {
                e.preventDefault();
                if (notificationsButton) {
                    notificationsButton.click();
                }
            }

            // Alt + U: Toggle user menu
            if (e.altKey && e.key === 'u') {
                e.preventDefault();
                const userProfileButton = document.getElementById('user-profile-button');
                if (userProfileButton) {
                    userProfileButton.click();
                }
            }

            // Escape: Close all dropdowns and modals
            if (e.key === 'Escape') {
                // Close notifications
                if (notificationsDropdown) {
                    notificationsDropdown.classList.add('hidden');
                }
                
                // Close user profile
                const userProfileDropdown = document.getElementById('user-profile-dropdown');
                if (userProfileDropdown) {
                    userProfileDropdown.classList.add('hidden');
                }

                // Close keyboard shortcuts modal
                if (typeof closeKeyboardShortcuts === 'function') {
                    closeKeyboardShortcuts();
                }

                // Close any Alpine modal
                const alpineModals = document.querySelectorAll('[x-show="open"]');
                alpineModals.forEach(modal => {
                    if (modal.__x) {
                        modal.__x.$data.open = false;
                    }
                });

                // Remove focus from search
                const searchInput = document.querySelector('input[type="search"]');
                if (searchInput && document.activeElement === searchInput) {
                    searchInput.blur();
                }
            }

            // Arrow keys for sidebar navigation
            const focusedElement = document.activeElement;
            if (focusedElement && focusedElement.closest('.sidebar-nav')) {
                const links = Array.from(document.querySelectorAll('.sidebar-nav a, .sidebar-nav button'));
                const currentIndex = links.indexOf(focusedElement);

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextIndex = (currentIndex + 1) % links.length;
                    links[nextIndex].focus();
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevIndex = (currentIndex - 1 + links.length) % links.length;
                    links[prevIndex].focus();
                }

                if (e.key === 'Enter' || e.key === ' ') {
                    if (focusedElement.tagName === 'BUTTON') {
                        e.preventDefault();
                        focusedElement.click();
                    }
                }
            }

            // Table navigation (Arrow keys in datatable)
            if (focusedElement && focusedElement.closest('table')) {
                const table = focusedElement.closest('table');
                const focusableElements = Array.from(table.querySelectorAll('a, button, input[type="checkbox"]'));
                const currentIndex = focusableElements.indexOf(focusedElement);

                if (e.key === 'ArrowRight' && currentIndex < focusableElements.length - 1) {
                    e.preventDefault();
                    focusableElements[currentIndex + 1].focus();
                }

                if (e.key === 'ArrowLeft' && currentIndex > 0) {
                    e.preventDefault();
                    focusableElements[currentIndex - 1].focus();
                }
            }

            // Ctrl/Cmd + A: Select all checkboxes (in tables)
            if ((e.ctrlKey || e.metaKey) && e.key === 'a' && !isTyping) {
                const table = document.querySelector('table');
                if (table) {
                    e.preventDefault();
                    const checkboxes = table.querySelectorAll('input[type="checkbox"]:not([disabled])');
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    checkboxes.forEach(cb => cb.checked = !allChecked);
                    
                    // Trigger change event for Alpine
                    checkboxes.forEach(cb => cb.dispatchEvent(new Event('change')));
                }
            }

            // Delete key: Delete selected items (if any)
            if (e.key === 'Delete' && !isTyping) {
                const deleteButton = document.querySelector('button[x-on\\:click*="deleteSelected"]') ||
                                   document.querySelector('button:contains("Delete Selected")');
                if (deleteButton && !deleteButton.disabled) {
                    e.preventDefault();
                    deleteButton.click();
                }
            }
        });

        {{-- Smooth Page Transitions --}}
        // Add smooth transition effect when navigating
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            
            // Only for internal navigation links
            if (link && 
                link.href && 
                link.href.startsWith(window.location.origin) && 
                !link.hasAttribute('target') &&
                !link.hasAttribute('download') &&
                !link.href.includes('#')) {
                
                // Check if not in sidebar when collapsed (already handled)
                const inSidebar = link.closest('.sidebar-nav');
                const sidebarCollapsed = sidebarElement?.classList.contains('collapsed');
                
                // Skip if sidebar link when collapsed (already has auto-expand)
                if (inSidebar && sidebarCollapsed) {
                    return;
                }
                
                // Add smooth transition
                e.preventDefault();
                document.body.classList.add('page-transitioning');
                
                setTimeout(() => {
                    window.location.href = link.href;
                }, 150);
            }
        });

        // Remove transition class when page loads
        window.addEventListener('pageshow', function() {
            document.body.classList.remove('page-transitioning');
        });

        {{-- Add keyboard shortcuts hint (optional) --}}
        console.log('%c⌨️ Keyboard Shortcuts', 'font-size: 14px; font-weight: bold; color: #3B82F6');
        console.log('%cCtrl/Cmd + K', 'font-weight: bold', '→ Focus Search');
        console.log('%cCtrl/Cmd + B', 'font-weight: bold', '→ Toggle Sidebar');
        console.log('%cCtrl/Cmd + H', 'font-weight: bold', '→ Go to Dashboard');
        console.log('%cAlt + N', 'font-weight: bold', '→ Toggle Notifications');
        console.log('%cAlt + U', 'font-weight: bold', '→ Toggle User Menu');
        console.log('%cEscape', 'font-weight: bold', '→ Close All Dropdowns');
        console.log('%cArrow Keys', 'font-weight: bold', '→ Navigate Sidebar (when focused)');
    });
</script>
