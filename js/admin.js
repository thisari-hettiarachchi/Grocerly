document.addEventListener("DOMContentLoaded", () => {
    window.togglePassword = function (inputId, button) {
      const passwordInput = document.getElementById(inputId);
      const icon = button.querySelector('i');
  
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    };
    
  });
  
  document.addEventListener("DOMContentLoaded", () => {
    const userBtn = document.getElementById("user-btn");
    const profile = document.querySelector(".profile-detail");
  
    if (userBtn && profile) {
      userBtn.addEventListener("click", () => {
        profile.classList.toggle("active");
        console.log("Toggled profile section");
      });
    } else {
      console.warn("user-btn or profile-detail not found");
    }
  });
  
  