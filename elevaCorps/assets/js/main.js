document.addEventListener('DOMContentLoaded', function() {

    // Mobile Menu Toggle
    const mobileMenuIcon = document.querySelector('.mobile-menu-icon');
    const mainNav = document.querySelector('.main-nav');

    mobileMenuIcon.addEventListener('click', function() {
        mainNav.classList.toggle('active');
    });

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Hero Section Video Carousel
    const videos = document.querySelectorAll('.carousel-video');
    let currentIndex = 0;

    function nextVideo() {
        videos[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % videos.length;
        videos[currentIndex].classList.add('active');
    }

    // Change video every 8 seconds
    setInterval(nextVideo, 8000);


    // Intersection Observer for Scroll Animations
    const animatedSections = document.querySelectorAll('.animated-section, .animated-hero');

    const observerOptions = {
        root: null, // viewport
        rootMargin: '0px',
        threshold: 0.2 // Trigger when 20% of the item is visible
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add the 'visible' class to trigger the animation
                entry.target.classList.add('visible');
                // Stop observing once the animation is triggered
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animatedSections.forEach(section => {
        observer.observe(section);
    });
});