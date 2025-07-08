function togglePassword(inputId, button) {
    const passwordInput = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (!passwordInput || !icon) return; // Safety check

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
