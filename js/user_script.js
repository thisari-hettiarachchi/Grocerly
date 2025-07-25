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
            slideInterval = setInterval(nextSlide, 4000); 
        }

        function stopAutoSlide() {
            clearInterval(slideInterval);
        }

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

        showSlide(currentSlide);
        startAutoSlide();
    });



//about page java script part//

// Fade in elements on scroll
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
    }
  });
});

document.querySelectorAll('.box-left, .box-right, .grocerly-content').forEach((el) => {
  el.classList.add('hidden');
  observer.observe(el);
});

// Scroll to top smoothly
const toTopBtn = document.createElement('button');
toTopBtn.innerHTML = '⬆️';
toTopBtn.id = 'scrollTopBtn';
document.body.appendChild(toTopBtn);

toTopBtn.style.cssText = `
  position: fixed;
  bottom: 30px;
  right: 30px;
  background: #2b8a3e;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 8px;
  font-size: 18px;
  cursor: pointer;
  display: none;
  z-index: 999;
`;

window.addEventListener('scroll', () => {
  toTopBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
});

toTopBtn.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Show/hide the back to top button
window.addEventListener('scroll', () => {
  const btn = document.getElementById("scrollTopBtn");
  if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
    btn.style.display = "block";
  } else {
    btn.style.display = "none";
  }
});

// Scroll to top when clicked
document.getElementById("scrollTopBtn").addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

function changeImage(element) {
    const mainImage = document.getElementById("mainImage");
    mainImage.src = element.src;
}
