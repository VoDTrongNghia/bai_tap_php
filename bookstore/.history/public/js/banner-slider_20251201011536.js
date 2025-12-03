/**
 * Banner Slider JavaScript
 * Handles automatic slide transitions, navigation arrows, and dot indicators
 */

// DEBUG: Log file loading start
console.log('[BANNER-SLIDER DEBUG] File loading started...');
console.log('[BANNER-SLIDER DEBUG] Current timestamp:', new Date().toISOString());
console.log('[BANNER-SLIDER DEBUG] Window location:', window.location.href);
console.log('[BANNER-SLIDER DEBUG] Script src:', document.currentScript ? document.currentScript.src : 'unknown');

// Check what's already in global scope
console.log('[BANNER-SLIDER DEBUG] Checking global scope BEFORE loading:');
console.log('[BANNER-SLIDER DEBUG] window.BannerSlider exists:', typeof window.BannerSlider);
console.log('[BANNER-SLIDER DEBUG] window.BookstoreBannerSlider exists:', typeof window.BookstoreBannerSlider);
console.log('[BANNER-SLIDER DEBUG] window.bannerSliderInstance exists:', typeof window.bannerSliderInstance);
console.log('[BANNER-SLIDER DEBUG] window.BookstoreBannerSliderInstance exists:', typeof window.BookstoreBannerSliderInstance);

// Check all scripts that have loaded
console.log('[BANNER-SLIDER DEBUG] Checking all loaded scripts:');
const scripts = document.querySelectorAll('script');
scripts.forEach((script, index) => {
    if (script.src && script.src.includes('banner-slider')) {
        console.log(`[BANNER-SLIDER DEBUG] Script ${index}:`, script.src);
    }
});

// Wrap everything in try-catch to catch any errors
try {
    console.log('[BANNER-SLIDER DEBUG] Entering try-catch block...');
    
    // Use unique namespace to avoid conflicts
    (function(global) {
        'use strict';
        
        console.log('[BANNER-SLIDER DEBUG] Entering IIFE...');
        
        // Unique namespace to avoid any conflicts
        const NAMESPACE = 'BookstoreBannerSlider';
        
        console.log('[BANNER-SLIDER DEBUG] Namespace:', NAMESPACE);
        console.log('[BANNER-SLIDER DEBUG] Checking if global[NAMESPACE] exists:', typeof global[NAMESPACE]);
        
        // Private constructor with unique name
        function BookstoreBannerSliderConstructor() {
            console.log('[BANNER-SLIDER DEBUG] Constructor called');
            this.slider = document.getElementById('bannerSlider');
            this.slides = document.querySelectorAll('.banner-slide');
            this.dots = document.querySelectorAll('.dot');
            this.prevBtn = document.getElementById('bannerPrev');
            this.nextBtn = document.getElementById('bannerNext');
            
            this.currentSlide = 0;
            this.slideInterval = null;
            this.autoSlideDelay = 4000; // 4 seconds
            
            this.init();
        }
    
    // Private methods
    BookstoreBannerSliderConstructor.prototype = {
        init: function() {
            if (!this.slider || this.slides.length === 0) return;
            
            // Start auto-slide
            this.startAutoSlide();
            
            // Event listeners for navigation
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.prevSlide());
            }
            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.nextSlide());
            }
            
            // Event listeners for dots
            this.dots.forEach((dot, index) => {
                dot.addEventListener('click', () => this.goToSlide(index));
            });
            
            // Pause auto-slide on hover
            this.slider.addEventListener('mouseenter', () => this.stopAutoSlide());
            this.slider.addEventListener('mouseleave', () => this.startAutoSlide());
            
            // Touch/swipe support for mobile
            this.addTouchSupport();
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.prevSlide();
                if (e.key === 'ArrowRight') this.nextSlide();
            });
        },
        
        startAutoSlide: function() {
            // Tạm tắt auto-slide để kiểm tra giật giao diện
            // this.stopAutoSlide(); // Clear any existing interval
            // this.slideInterval = setInterval(() => {
            //     this.nextSlide();
            // }, this.autoSlideDelay);
        },
        
        stopAutoSlide: function() {
            if (this.slideInterval) {
                clearInterval(this.slideInterval);
                this.slideInterval = null;
            }
        },
        
        nextSlide: function() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            this.updateSlide();
        },
        
        prevSlide: function() {
            this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
            this.updateSlide();
        },
        
        goToSlide: function(index) {
            this.currentSlide = index;
            this.updateSlide();
        },
        
        updateSlide: function() {
            // Remove active class from all slides and dots
            this.slides.forEach(slide => slide.classList.remove('active'));
            this.dots.forEach(dot => dot.classList.remove('active'));
            
            // Add active class to current slide and dot
            if (this.slides[this.currentSlide]) {
                this.slides[this.currentSlide].classList.add('active');
            }
            if (this.dots[this.currentSlide]) {
                this.dots[this.currentSlide].classList.add('active');
            }
        },
        
        addTouchSupport: function() {
            let startX = 0;
            let endX = 0;
            let isDragging = false;
            
            this.slider.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                this.stopAutoSlide();
            });
            
            this.slider.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
            });
            
            this.slider.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                
                endX = e.changedTouches[0].clientX;
                const diffX = startX - endX;
                
                // Minimum swipe distance
                if (Math.abs(diffX) > 50) {
                    if (diffX > 0) {
                        this.nextSlide();
                    } else {
                        this.prevSlide();
                    }
                }
                
                isDragging = false;
                this.startAutoSlide();
            });
        }
    };
    
    // Factory function to create instances
    function createBannerSlider() {
        console.log('[BANNER-SLIDER DEBUG] createBannerSlider called');
        return new BookstoreBannerSliderConstructor();
    }
    
    // Export to global scope with unique namespace
    console.log('[BANNER-SLIDER DEBUG] About to export to global scope...');
    console.log('[BANNER-SLIDER DEBUG] global[NAMESPACE] before export:', typeof global[NAMESPACE]);
    
    if (!global[NAMESPACE]) {
        console.log('[BANNER-SLIDER DEBUG] Exporting to unique namespace:', NAMESPACE);
        global[NAMESPACE] = createBannerSlider;
    } else {
        console.log('[BANNER-SLIDER DEBUG] Unique namespace already exists, skipping export');
    }
    
    // Also export as BannerSlider for compatibility (only if not exists)
    console.log('[BANNER-SLIDER DEBUG] Checking BannerSlider compatibility export...');
    console.log('[BANNER-SLIDER DEBUG] global.BannerSlider before export:', typeof global.BannerSlider);
    
    if (!global.BannerSlider) {
        console.log('[BANNER-SLIDER DEBUG] Exporting BannerSlider for compatibility');
        global.BannerSlider = createBannerSlider;
    } else {
        console.log('[BANNER-SLIDER DEBUG] BannerSlider already exists, skipping compatibility export');
    }
    
    // Initialize banner slider when DOM is loaded (chỉ một lần)
    document.addEventListener('DOMContentLoaded', () => {
        console.log('[BANNER-SLIDER DEBUG] DOMContentLoaded fired');
        // Use unique namespace for instance
        if (!global[NAMESPACE + 'Instance']) {
            console.log('[BANNER-SLIDER DEBUG] Creating new instance');
            global[NAMESPACE + 'Instance'] = createBannerSlider();
        } else {
            console.log('[BANNER-SLIDER DEBUG] Instance already exists, skipping creation');
        }
    });
    
    console.log('[BANNER-SLIDER DEBUG] IIFE setup completed');
    
})(typeof window !== 'undefined' ? window : this);

// Final check after IIFE
console.log('[BANNER-SLIDER DEBUG] Final global scope check:');
console.log('[BANNER-SLIDER DEBUG] window.BannerSlider:', typeof window.BannerSlider);
console.log('[BANNER-SLIDER DEBUG] window.BookstoreBannerSlider:', typeof window.BookstoreBannerSlider);
console.log('[BANNER-SLIDER DEBUG] File loading completed');
    
} catch (error) {
    console.error('[BANNER-SLIDER ERROR] Caught error:', error);
    console.error('[BANNER-SLIDER ERROR] Error message:', error.message);
    console.error('[BANNER-SLIDER ERROR] Error stack:', error.stack);
    console.error('[BANNER-SLIDER ERROR] Error name:', error.name);
    
    // Try to get more context about the error
    console.error('[BANNER-SLIDER ERROR] Current script:', document.currentScript ? document.currentScript.src : 'unknown');
    console.error('[BANNER-SLIDER ERROR] Location:', window.location.href);
    console.error('[BANNER-SLIDER ERROR] Timestamp:', new Date().toISOString());
    
    // Check if it's a redeclaration error
    if (error.message && error.message.includes('already been declared')) {
        console.error('[BANNER-SLIDER ERROR] This is a redeclaration error!');
        console.error('[BANNER-SLIDER ERROR] Checking what exists in global scope:');
        console.error('[BANNER-SLIDER ERROR] window.BannerSlider:', window.BannerSlider);
        console.error('[BANNER-SLIDER ERROR] typeof window.BannerSlider:', typeof window.BannerSlider);
        
        // Try to find where BannerSlider is defined
        try {
            console.error('[BANNER-SLIDER ERROR] BannerSlider source:', window.BannerSlider.toString());
        } catch (e) {
            console.error('[BANNER-SLIDER ERROR] Cannot get BannerSlider source:', e.message);
        }
    }
}
