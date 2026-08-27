// assets/js/main.js

// Preloader
const preloader = document.getElementById('preloader');
if (preloader) {
    window.addEventListener('load', function () {
        preloader.classList.add('hidden');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Hero tagline typing animation
    const animatedTagline = document.getElementById('hero-tagline-animated');
    if (animatedTagline) {
        const text = animatedTagline.getAttribute('data-text');
        let i = 0;
        animatedTagline.textContent = ''; // Clear initial text

        function typeWriter() {
            if (i < text.length) {
                animatedTagline.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 75); // Adjust typing speed here
            }
        }

        // Start typing after a short delay
        setTimeout(typeWriter, 1000);
    }


    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileClose = document.querySelector('.mobile-close');
    const mobileNav = document.querySelector('.mobile-nav');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function () {
            mobileNav.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', function () {
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Close mobile menu when clicking on links
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function () {
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Mobile Dropdown Toggle
    const mobileDropdowns = document.querySelectorAll('.mobile-nav .has-dropdown');
    mobileDropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function (e) {
            e.preventDefault();
            const parentLi = this.parentElement;
            const submenu = parentLi.querySelector('.mobile-submenu');

            parentLi.classList.toggle('open');

            if (parentLi.classList.contains('open')) {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
            } else {
                submenu.style.maxHeight = '0';
            }
        });
    });

    // Back to Top Button
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', function () {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Active Navigation Link
    const currentPage = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');

    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');

        if (currentPage === linkPath ||
            (currentPage.includes(linkPath) && linkPath !== '/')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Newsletter Form Submission
    const newsletterForm = document.getElementById('newsletterForm');
    const newsletterMessage = document.getElementById('newsletterMessage');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const email = formData.get('email');

            // Simple validation
            if (!validateEmail(email)) {
                showMessage('Please enter a valid email address.', 'error');
                return;
            }

            // Simulate submission
            showMessage('Subscribing...', 'info');

            setTimeout(() => {
                showMessage('Thank you for subscribing to our newsletter!', 'success');
                newsletterForm.reset();
            }, 1500);
        });
    }

    // Image Lazy Loading
    const images = document.querySelectorAll('img[data-src]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for older browsers
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    }

    // Counter Animation (for statistics section if added later)
    const counters = document.querySelectorAll('.counter');

    if (counters.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    const duration = 2000; // 2 seconds
                    const increment = target / (duration / 16); // 60fps
                    let current = 0;

                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target.toLocaleString();
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current).toLocaleString();
                        }
                    }, 16);

                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    }

    // Helper Functions
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showMessage(message, type) {
        if (newsletterMessage) {
            newsletterMessage.textContent = message;
            newsletterMessage.className = 'form-message';
            newsletterMessage.classList.add(`alert-${type}`);

            setTimeout(() => {
                newsletterMessage.textContent = '';
                newsletterMessage.className = 'form-message';
            }, 5000);
        }
    }

    // Parallax Effect for Hero Section
    const heroSection = document.querySelector('.hero-section');

    if (heroSection) {
        window.addEventListener('scroll', function () {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;

            heroSection.style.transform = `translate3d(0px, ${rate}px, 0px)`;
        });
    }

    // Add CSS for animated elements
    const style = document.createElement('style');
    style.textContent = `
        .testimonial {
            transition: all 0.5s ease;
        }
        
        .counter {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--sunset-orange);
        }
        
        .icon-large {
            font-size: 3rem;
            color: var(--sunset-orange);
            margin-bottom: 1rem;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: var(--radius-lg);
        }
        
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 1rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }
        
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--sunset-orange);
            transform: translateX(-50%);
        }
        
        .timeline-item {
            display: flex;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .timeline-item:nth-child(odd) {
            flex-direction: row-reverse;
        }
        
        .timeline-time {
            flex: 1;
            padding: 0 2rem;
            text-align: right;
            color: var(--sunset-orange);
            font-weight: bold;
        }
        
        .timeline-item:nth-child(odd) .timeline-time {
            text-align: left;
        }
        
        .timeline-content {
            flex: 2;
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            position: relative;
        }
        
        .timeline-content::before {
            content: '';
            position: absolute;
            top: 20px;
            width: 20px;
            height: 20px;
            background: var(--white);
            transform: rotate(45deg);
        }
        
        .timeline-item:nth-child(even) .timeline-content::before {
            left: -10px;
        }
        
        .timeline-item:nth-child(odd) .timeline-content::before {
            right: -10px;
        }
        
        @media (max-width: 768px) {
            .timeline::before {
                left: 30px;
            }
            
            .timeline-item {
                flex-direction: column !important;
                margin-left: 60px;
            }
            
            .timeline-time {
                text-align: left !important;
                padding: 0;
                margin-bottom: 0.5rem;
            }
            
            .timeline-content::before {
                left: -10px !important;
                right: auto !important;
            }
        }
    `;

    document.head.appendChild(style);
});