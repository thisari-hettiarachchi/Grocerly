function togglePassword(inputId, button) {
    const passwordInput = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (!passwordInput || !icon) return; 

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const userBtn = document.getElementById("user-btn");
    const profileDetail = document.getElementById("profile-detail");

    if (userBtn && profileDetail) {
        userBtn.addEventListener("click", () => {
            profileDetail.classList.toggle("active");
        });

        document.addEventListener("click", (event) => {
            if (!userBtn.contains(event.target) && !profileDetail.contains(event.target)) {
                profileDetail.classList.remove("active");
            }
        });
    }

});

    document.addEventListener("DOMContentLoaded", function () {
        const sliderBoxes = document.querySelectorAll('.sliderBox');
        const prevBtn = document.querySelector('.slider-prev');
        const nextBtn = document.querySelector('.slider-next');

        let currentSlide = 0;
        const totalSlides = sliderBoxes.length;
        let slideInterval;

        function showSlide(index) {
            sliderBoxes.forEach((box, i) => {
                box.classList.remove('active');
                if (i === index) box.classList.add('active');
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        function startAutoSlide() {
            slideInterval = setInterval(nextSlide, 4000); // Change every 4 seconds
        }

        function stopAutoSlide() {
            clearInterval(slideInterval);
        }

        // Manual controls
        nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });

        prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });

        // Start slider
        showSlide(currentSlide);
        startAutoSlide();
    });


//about page java script part

window.addEventListener('scroll', function () {
    const section = document.querySelector('.who-we-are');
    const position = section.getBoundingClientRect().top;
    const screenPosition = window.innerHeight / 1.2;

    if (position < screenPosition) {
        section.classList.add('active');
    }
});

