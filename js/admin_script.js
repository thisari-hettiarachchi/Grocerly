document.addEventListener("DOMContentLoaded", () => {
  // Toggle password visibility
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

  // Toggle profile dropdown
  const userBtn = document.getElementById('user-btn');
  const profileDetail = document.getElementById('profile-detail');

  if (userBtn && profileDetail) {
    userBtn.addEventListener('click', () => {
      profileDetail.classList.toggle('active');
    });

    window.addEventListener('click', (e) => {
      if (!profileDetail.contains(e.target) && e.target !== userBtn) {
        profileDetail.classList.remove('active');
      }
    });
  }

  // Toggle sidebar from menu icon
  const menuBtn = document.getElementById('menu-btn');
  const sidebar = document.getElementById('sidebar');

  if (menuBtn && sidebar) {
    menuBtn.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });
  }
});
