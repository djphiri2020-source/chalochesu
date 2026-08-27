// assets/js/slider.js

class SafariSlider {
    constructor(containerSelector, options = {}) {
        this.container = document.querySelector(containerSelector);
        if (!this.container) return;
        
        this.options = {
            autoplay: true,
            interval: 5000,
            transition: 'slide',
            showDots: true,
            showArrows: true,
            infinite: true,
            ...options
        };
        
        this.slides = this.container.querySelectorAll('.slide');
        this.currentSlide = 0;
        this.isAnimating = false;
        this.autoplayInterval = null;
        
        this.init();
    }
    
    init() {
        // Create slider wrapper if needed
        if (!this.container.classList.contains('slider-container')) {
            this.createSliderStructure();
        }
        
        // Set up slides
        this.setupSlides();
        
        // Create navigation
        if (this.options.showDots) this.createDots();
        if (this.options.showArrows) this.createArrows();
        
        // Start autoplay
        if (this.options.autoplay) this.startAutoplay();
        
        // Add event listeners
        this.addEventListeners();
        
        // Show first slide
        this.showSlide(this.currentSlide);
    }
    
    createSliderStructure() {
        const slides = Array.from(this.slides);
        
        // Create wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'slider-wrapper';
        
        // Move slides into wrapper
        slides.forEach(slide => {
            slide.classList.add('slide');
            wrapper.appendChild(slide);
        });
        
        // Clear container and add wrapper
        this.container.innerHTML = '';
        this.container.appendChild(wrapper);
        this.container.classList.add('slider-container');
        
        // Update slides reference
        this.slides = this.container.querySelectorAll('.slide');
    }
    
    setupSlides() {
        this.slides.forEach((slide, index) => {
            slide.style.position = 'absolute';
            slide.style.top = '0';
            slide.style.left = '0';
            slide.style.width = '100%';
            slide.style.height = '100%';
            slide.style.opacity = '0';
            slide.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            slide.style.zIndex = '1';
            
            if (index === 0) {
                slide.style.opacity = '1';
                slide.style.zIndex = '2';
            }
        });
    }
    
    createDots() {
        const dotsContainer = document.createElement('div');
        dotsContainer.className = 'slider-dots';
        
        for (let i = 0; i < this.slides.length; i++) {
            const dot = document.createElement('button');
            dot.className = 'slider-dot';
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
            
            if (i === 0) dot.classList.add('active');
            
            dot.addEventListener('click', () => this.goToSlide(i));
            
            dotsContainer.appendChild(dot);
        }
        
        this.container.appendChild(dotsContainer);
        this.dots = this.container.querySelectorAll('.slider-dot');
    }
    
    createArrows() {
        // Previous arrow
        const prevArrow = document.createElement('button');
        prevArrow.className = 'slider-arrow slider-arrow-prev';
        prevArrow.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevArrow.setAttribute('aria-label', 'Previous slide');
        
        // Next arrow
        const nextArrow = document.createElement('button');
        nextArrow.className = 'slider-arrow slider-arrow-next';
        nextArrow.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextArrow.setAttribute('aria-label', 'Next slide');
        
        this.container.appendChild(prevArrow);
        this.container.appendChild(nextArrow);
        
        this.prevArrow = prevArrow;
        this.nextArrow = nextArrow;
    }
    
    addEventListeners() {
        // Arrow events
        if (this.options.showArrows) {
            this.prevArrow.addEventListener('click', () => this.prevSlide());
            this.nextArrow.addEventListener('click', () => this.nextSlide());
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.container.matches(':hover')) return;
            
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
            if (e.key === 'Home') this.goToSlide(0);
            if (e.key === 'End') this.goToSlide(this.slides.length - 1);
        });
        
        // Pause autoplay on hover
        if (this.options.autoplay) {
            this.container.addEventListener('mouseenter', () => this.pauseAutoplay());
            this.container.addEventListener('mouseleave', () => this.startAutoplay());
        }
        
        // Touch/swipe support
        this.addTouchSupport();
    }
    
    addTouchSupport() {
        let touchStartX = 0;
        let touchEndX = 0;
        
        this.container.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        this.container.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe(touchStartX, touchEndX);
        }, { passive: true });
    }
    
    handleSwipe(startX, endX) {
        const threshold = 50;
        const diff = startX - endX;
        
        if (Math.abs(diff) > threshold) {
            if (diff > 0) {
                this.nextSlide();
            } else {
                this.prevSlide();
            }
        }
    }
    
    showSlide(index) {
        if (this.isAnimating || index === this.currentSlide) return;
        
        this.isAnimating = true;
        
        // Calculate new index for infinite sliding
        let newIndex = index;
        if (this.options.infinite) {
            if (index < 0) newIndex = this.slides.length - 1;
            if (index >= this.slides.length) newIndex = 0;
        } else {
            newIndex = Math.max(0, Math.min(index, this.slides.length - 1));
        }
        
        const currentSlide = this.slides[this.currentSlide];
        const nextSlide = this.slides[newIndex];
        
        // Apply transition based on type
        switch (this.options.transition) {
            case 'fade':
                this.applyFadeTransition(currentSlide, nextSlide, newIndex);
                break;
            case 'slide':
                this.applySlideTransition(currentSlide, nextSlide, newIndex, index);
                break;
            default:
                this.applyFadeTransition(currentSlide, nextSlide, newIndex);
        }
        
        // Update current slide
        this.currentSlide = newIndex;
        
        // Update dots
        if (this.options.showDots) this.updateDots();
        
        // Dispatch custom event
        this.container.dispatchEvent(new CustomEvent('slideChange', {
            detail: { currentSlide: this.currentSlide }
        }));
        
        // Reset animation flag
        setTimeout(() => {
            this.isAnimating = false;
        }, 500);
    }
    
    applyFadeTransition(currentSlide, nextSlide, newIndex) {
        // Hide current slide
        currentSlide.style.opacity = '0';
        currentSlide.style.zIndex = '1';
        
        // Show next slide
        nextSlide.style.opacity = '1';
        nextSlide.style.zIndex = '2';
    }
    
    applySlideTransition(currentSlide, nextSlide, newIndex, targetIndex) {
        const direction = targetIndex > this.currentSlide ? 'next' : 'prev';
        
        // Position slides
        currentSlide.style.transform = `translateX(${direction === 'next' ? '-100%' : '100%'})`;
        nextSlide.style.transform = 'translateX(0)';
        nextSlide.style.opacity = '1';
        nextSlide.style.zIndex = '2';
        
        // Reset after animation
        setTimeout(() => {
            currentSlide.style.transform = '';
            currentSlide.style.opacity = '0';
            currentSlide.style.zIndex = '1';
        }, 500);
    }
    
    updateDots() {
        if (!this.dots) return;
        
        this.dots.forEach((dot, index) => {
            if (index === this.currentSlide) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }
    
    nextSlide() {
        this.showSlide(this.currentSlide + 1);
    }
    
    prevSlide() {
        this.showSlide(this.currentSlide - 1);
    }
    
    goToSlide(index) {
        this.showSlide(index);
    }
    
    startAutoplay() {
        if (this.autoplayInterval) clearInterval(this.autoplayInterval);
        
        this.autoplayInterval = setInterval(() => {
            this.nextSlide();
        }, this.options.interval);
    }
    
    pauseAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }
    
    destroy() {
        this.pauseAutoplay();
        
        // Remove event listeners
        if (this.prevArrow) this.prevArrow.remove();
        if (this.nextArrow) this.nextArrow.remove();
        
        // Remove dots
        const dotsContainer = this.container.querySelector('.slider-dots');
        if (dotsContainer) dotsContainer.remove();
        
        // Reset slides
        this.slides.forEach(slide => {
            slide.style.position = '';
            slide.style.top = '';
            slide.style.left = '';
            slide.style.width = '';
            slide.style.height = '';
            slide.style.opacity = '';
            slide.style.transition = '';
            slide.style.zIndex = '';
            slide.style.transform = '';
        });
    }
}

// Initialize sliders on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hero slider if exists
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        new SafariSlider('.hero-slider', {
            autoplay: true,
            interval: 8000,
            transition: 'fade',
            showDots: true,
            showArrows: true,
            infinite: true
        });
    }
    
    // Initialize testimonial slider if exists
    const testimonialSlider = document.querySelector('.testimonial-slider');
    if (testimonialSlider) {
        new SafariSlider('.testimonial-slider', {
            autoplay: true,
            interval: 6000,
            transition: 'slide',
            showDots: true,
            showArrows: false,
            infinite: true
        });
    }
    
    // Initialize gallery slider if exists
    const gallerySlider = document.querySelector('.gallery-slider');
    if (gallerySlider) {
        new SafariSlider('.gallery-slider', {
            autoplay: false,
            transition: 'slide',
            showDots: true,
            showArrows: true,
            infinite: true
        });
    }
});

// Add slider styles
const sliderStyle = document.createElement('style');
sliderStyle.textContent = `
    .slider-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }
    
    .slider-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
    }
    
    .slider-arrow:hover {
        background: var(--sunset-orange);
        transform: translateY(-50%) scale(1.1);
    }
    
    .slider-arrow-prev {
        left: 20px;
    }
    
    .slider-arrow-next {
        right: 20px;
    }
    
    .slider-dots {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 10px;
        z-index: 10;
    }
    
    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        padding: 0;
        transition: all 0.3s ease;
    }
    
    .slider-dot:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: scale(1.2);
    }
    
    .slider-dot.active {
        background: var(--sunset-orange);
        transform: scale(1.2);
    }
    
    /* Hero slider specific */
    .hero-slider .slide {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .hero-slider .slider-arrow {
        background: rgba(217, 108, 41, 0.8);
    }
    
    .hero-slider .slider-arrow:hover {
        background: var(--sunset-orange);
    }
    
    /* Testimonial slider specific */
    .testimonial-slider {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .testimonial-slider .slide {
        padding: 2rem;
        text-align: center;
    }
    
    /* Gallery slider specific */
    .gallery-slider {
        height: 500px;
    }
    
    .gallery-slider .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    @media (max-width: 768px) {
        .slider-arrow {
            width: 40px;
            height: 40px;
        }
        
        .slider-arrow-prev {
            left: 10px;
        }
        
        .slider-arrow-next {
            right: 10px;
        }
        
        .gallery-slider {
            height: 300px;
        }
    }
`;

document.head.appendChild(sliderStyle);