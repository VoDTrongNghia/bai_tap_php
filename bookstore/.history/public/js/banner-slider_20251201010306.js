/**
 * Banner Slider JavaScript
 * Handles automatic slide transitions, navigation arrows, and dot indicators
 */

(function() {
    'use strict';
    
    // Check if class already exists and return it
    if (typeof window.BannerSlider !== 'undefined') {
        console.log('BannerSlider already exists, using existing instance');
        return;
    }
    
    // Create class using function expression
    window.BannerSlider = function() {
        this.slider = document.getElementById('bannerSlider');
        this.slides = document.querySelectorAll('.banner-slide');
        this.dots = document.querySelectorAll('.dot');
        this.prevBtn = document.getElementById('bannerPrev');
        this.nextBtn = document.getElementById('bannerNext');
        
        this.currentSlide = 0;
        this.slideInterval = null;
        this.autoSlideDelay = 4000; // 4 seconds
        
        this.init();
    };
    
    // Add methods to prototype
    window.BannerSlider.prototype.init = function() {
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
    };
    
    window.BannerSlider.prototype.startAutoSlide = function() {
        // Tạm tắt auto-slide để kiểm tra giật giao diện
        // this.stopAutoSlide(); // Clear any existing interval
        // this.slideInterval = setInterval(() => {
        //     this.nextSlide();
        // }, this.autoSlideDelay);
    };
    
    window.BannerSlider.prototype.stopAutoSlide = function() {
        if (this.slideInterval) {
            clearInterval(this.slideInterval);
            this.slideInterval = null;
        }
    };
    
    window.BannerSlider.prototype.nextSlide = function() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.updateSlide();
    };
    
    window.BannerSlider.prototype.prevSlide = function() {
        this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
        this.updateSlide();
    };
    
    window.BannerSlider.prototype.goToSlide = function(index) {
        this.currentSlide = index;
        this.updateSlide();
    };
    
    window.BannerSlider.prototype.updateSlide = function() {
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
    };
    
    window.BannerSlider.prototype.addTouchSupport = function() {
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
    };

    // Initialize banner slider when DOM is loaded (chỉ một lần)
    document.addEventListener('DOMContentLoaded', () => {
        // Kiểm tra xem đã có instance chưa
        if (!window.bannerSliderInstance) {
            window.bannerSliderInstance = new window.BannerSlider();
        }
    });
    
})();
