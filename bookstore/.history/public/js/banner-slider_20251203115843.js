/**
 * Banner Slider JavaScript
 * Simple banner slider with automatic transitions
 */

(function() {
    'use strict';
    
    // Check if BannerSlider already exists to avoid redeclaration
    if (window.BannerSlider) {
        console.log('BannerSlider already exists, skipping initialization');
        return;
    }
    
    function BannerSlider() {
        this.slider = document.getElementById('bannerSlider');
        this.slides = document.querySelectorAll('.banner-slide');
        this.dots = document.querySelectorAll('.dot');
        this.prevBtn = document.getElementById('bannerPrev');
        this.nextBtn = document.getElementById('bannerNext');
        
        this.currentSlide = 0;
        this.slideInterval = null;
        this.autoSlideDelay = 5000; // 5 seconds
        
        this.init();
    }
    
    BannerSlider.prototype = {
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
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.prevSlide();
                if (e.key === 'ArrowRight') this.nextSlide();
            });
        },
        
        startAutoSlide: function() {
            this.stopAutoSlide();
            this.slideInterval = setInterval(() => {
                this.nextSlide();
            }, this.autoSlideDelay);
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
        }
    };
    
    // Export to global scope
    window.BannerSlider = BannerSlider;
    
    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.bannerSliderInstance) {
            window.bannerSliderInstance = new BannerSlider();
        }
    });
    
})();
