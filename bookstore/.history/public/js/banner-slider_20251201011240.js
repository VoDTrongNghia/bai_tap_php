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
        return new BookstoreBannerSliderConstructor();
    }
    
    // Export to global scope with unique namespace
    if (!global[NAMESPACE]) {
        global[NAMESPACE] = createBannerSlider;
    }
    
    // Also export as BannerSlider for compatibility (only if not exists)
    if (!global.BannerSlider) {
        global.BannerSlider = createBannerSlider;
    }
    
    // Initialize banner slider when DOM is loaded (chỉ một lần)
    document.addEventListener('DOMContentLoaded', () => {
        // Use unique namespace for instance
        if (!global[NAMESPACE + 'Instance']) {
            global[NAMESPACE + 'Instance'] = createBannerSlider();
        }
    });
    
})(typeof window !== 'undefined' ? window : this);
