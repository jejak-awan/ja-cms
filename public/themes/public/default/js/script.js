/**
 * Modern Public Theme - JavaScript
 * Handles interactions, animations, and dynamic features
 */

(function() {
    'use strict';

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }

    // Mobile menu toggle
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const isOpen = mobileMenu.classList.contains('hidden');
            if (isOpen) {
                mobileMenu.classList.remove('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'true');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Read more / Read less functionality
    document.querySelectorAll('[data-read-more]').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-read-more');
            const target = document.getElementById(targetId);
            
            if (target) {
                const isExpanded = target.classList.contains('line-clamp-none');
                if (isExpanded) {
                    target.classList.remove('line-clamp-none');
                    target.classList.add('line-clamp-3');
                    this.textContent = 'Read more';
                } else {
                    target.classList.remove('line-clamp-3');
                    target.classList.add('line-clamp-none');
                    this.textContent = 'Read less';
                }
            }
        });
    });

    // Image lazy loading fallback (for browsers that don't support native lazy loading)
    if ('loading' in HTMLImageElement.prototype === false) {
        const images = document.querySelectorAll('img[loading="lazy"]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Share functionality
    document.querySelectorAll('[data-share]').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const title = this.getAttribute('data-title') || document.title;
            const text = this.getAttribute('data-text') || '';
            const url = this.getAttribute('data-url') || window.location.href;
            
            // Check if Web Share API is supported
            if (navigator.share) {
                try {
                    await navigator.share({ title, text, url });
                } catch (err) {
                    console.log('Share cancelled or failed:', err);
                }
            } else {
                // Fallback: copy to clipboard
                try {
                    await navigator.clipboard.writeText(url);
                    alert('Link copied to clipboard!');
                } catch (err) {
                    console.error('Failed to copy:', err);
                }
            }
        });
    });

    // Search functionality
    const searchToggle = document.querySelector('[data-search-toggle]');
    const searchModal = document.querySelector('[data-search-modal]');
    const searchClose = document.querySelector('[data-search-close]');
    const searchInput = document.querySelector('[data-search-input]');
    
    if (searchToggle && searchModal) {
        searchToggle.addEventListener('click', function() {
            searchModal.classList.remove('hidden');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        });
    }
    
    if (searchClose && searchModal) {
        searchClose.addEventListener('click', function() {
            searchModal.classList.add('hidden');
        });
    }
    
    // Close search modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchModal && !searchModal.classList.contains('hidden')) {
            searchModal.classList.add('hidden');
        }
    });

    // Back to top button
    const backToTop = document.querySelector('[data-back-to-top]');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 500) {
                backToTop.classList.remove('hidden');
            } else {
                backToTop.classList.add('hidden');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Newsletter form submission
    const newsletterForm = document.querySelector('[data-newsletter-form]');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            button.disabled = true;
            button.textContent = 'Subscribing...';
            
            // Simulate API call (replace with actual endpoint)
            setTimeout(() => {
                alert('Thank you for subscribing!');
                this.reset();
                button.disabled = false;
                button.textContent = originalText;
            }, 1000);
        });
    }

    // Toast notifications
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white transition-opacity duration-300 z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };

    // View counter (send view to backend)
    const articleId = document.querySelector('[data-article-id]');
    if (articleId) {
        const id = articleId.getAttribute('data-article-id');
        
        // Send view count after user has been on page for 5 seconds
        setTimeout(() => {
            fetch(`/api/articles/${id}/view`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            }).catch(err => console.log('View count failed:', err));
        }, 5000);
    }

    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('[data-animate]').forEach(el => {
        observer.observe(el);
    });

    // Initialize dark mode (if needed in future)
    const darkModeToggle = document.querySelector('[data-dark-mode-toggle]');
    if (darkModeToggle) {
        const darkMode = localStorage.getItem('darkMode') === 'true';
        
        if (darkMode) {
            document.documentElement.classList.add('dark');
        }
        
        darkModeToggle.addEventListener('click', function() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
        });
    }

    console.log('🎨 Theme loaded successfully!');
})();
