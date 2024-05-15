// Updated script for password toggle
document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.querySelector("#password-toggle");
  const passwordInput = document.querySelector("#Password");

  togglePassword.addEventListener("click", function () {
    const type =
      passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);

    // Change button text
    togglePassword.textContent = type === "password" ? "Show" : "Hide";
  });
});

// Disallow whitespaces from input fields
function avoidSpace(event) {
  if (event.key === " ") {
    event.preventDefault();
  }
}

(() => {
  "use strict";

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });
})();
