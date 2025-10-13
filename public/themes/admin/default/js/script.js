/**
 * Default Admin Theme JavaScript
 * This file contains theme-specific functionality
 */

(function() {
    'use strict';
    
    console.log('Admin Theme: Default loaded');
    
    // Add smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[class*="bg-green-"], [class*="bg-red-"]');
        alerts.forEach(function(alert) {
            if (alert.textContent.includes('successfully') || alert.textContent.includes('error')) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }
        });
    }, 5000);
    
    // Add loading indicator for forms
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span>Processing...</span>';
                }
            });
        });
    });
    
})();
