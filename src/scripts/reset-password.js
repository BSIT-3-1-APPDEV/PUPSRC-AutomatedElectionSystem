// Updated script for password toggle
document.addEventListener("DOMContentLoaded", function () {
  const togglePassword1 = document.querySelector("#reset-password-toggle-1");
  const togglePassword2 = document.querySelector("#reset-password-toggle-2");
  const passwordInput1 = document.querySelector("#password");
  const passwordInput2 = document.querySelector("#password_confirmation");
  const eyeIcon1 = togglePassword1.querySelector("i");
  const eyeIcon2 = togglePassword2.querySelector("i");

  togglePassword1.addEventListener("click", function () {
    const type =
      passwordInput1.getAttribute("type") === "password" ? "text" : "password";
    passwordInput1.setAttribute("type", type);

    // Toggle eye icon classes
    eyeIcon1.classList.toggle("fa-eye-slash");
    eyeIcon1.classList.toggle("fa-eye");
  });

  togglePassword2.addEventListener("click", function () {
    const type =
      passwordInput2.getAttribute("type") === "password" ? "text" : "password";
    passwordInput2.setAttribute("type", type);

    // Toggle eye icon classes
    eyeIcon2.classList.toggle("fa-eye-slash");
    eyeIcon2.classList.toggle("fa-eye");
  });
});
