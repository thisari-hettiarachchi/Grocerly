// JavaScript for all pages
document.addEventListener('DOMContentLoaded', function() {
    // Slider functionality for home page
    const sliderBoxes = document.querySelectorAll('.sliderBox');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    
    if (sliderBoxes.length > 0) {
        let currentSlide = 0;
        
        function showSlide(index) {
            sliderBoxes.forEach((box, i) => {
                box.classList.toggle('active', i === index);
            });
        }
        
        prevBtn.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + sliderBoxes.length) % sliderBoxes.length;
            showSlide(currentSlide);
        });
        
        nextBtn.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % sliderBoxes.length;
            showSlide(currentSlide);
        });
    }
    
    // Scroll to top button
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTopBtn.style.display = 'block';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        });
        
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Other common JavaScript functionality
});