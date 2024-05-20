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

    eyeIcon1.classList.toggle("fa-eye-slash");
    eyeIcon1.classList.toggle("fa-eye");
  });

  togglePassword2.addEventListener("click", function () {
    const type =
      passwordInput2.getAttribute("type") === "password" ? "text" : "password";
    passwordInput2.setAttribute("type", type);

    eyeIcon2.classList.toggle("fa-eye-slash");
    eyeIcon2.classList.toggle("fa-eye");
  });
});






















// Disabling the submit button when fields are empty
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const submitButton = document.getElementById('SCO-login-button');
    const errorText = document.getElementById('password-mismatch-error');

    function checkInputs() {
        const passwordValue = passwordInput.value.trim();
        const passwordConfirmationValue = passwordConfirmationInput.value.trim();

        if (passwordConfirmationValue === '') {
            submitButton.disabled = true;
            errorText.style.display = 'none';
        } else if (passwordValue === passwordConfirmationValue) {
            submitButton.disabled = false;
            errorText.style.display = 'none';
        } else {
            submitButton.disabled = true;
            errorText.style.display = 'block';
        }
    }

    checkInputs();

    passwordConfirmationInput.addEventListener('input', checkInputs);
});





















// For the password requirements
document.addEventListener('DOMContentLoaded', function() {
  const passwordInput = document.getElementById('password');
  const passwordRequirements = document.querySelector('.password-requirements');

  passwordInput.addEventListener('input', function() {
      const value = passwordInput.value.trim();

      if (value) {
          passwordRequirements.classList.add('show');
      } else {
          passwordRequirements.classList.remove('show');
      }

      const passwordRequirementsList = document.querySelectorAll('.requirement');

      // Check password length
      if (value.length >= 8 && value.length <= 20) {
          passwordRequirementsList[0].classList.add('met');
          passwordRequirementsList[0].classList.remove('unmet');
      } else {
          passwordRequirementsList[0].classList.add('unmet');
          passwordRequirementsList[0].classList.remove('met');
      }

      // Check for uppercase letter
      if (/[A-Z]/.test(value)) {
          passwordRequirementsList[1].classList.add('met');
          passwordRequirementsList[1].classList.remove('unmet');
      } else {
          passwordRequirementsList[1].classList.add('unmet');
          passwordRequirementsList[1].classList.remove('met');
      }

      // Check for lowercase letter
      if (/[a-z]/.test(value)) {
          passwordRequirementsList[2].classList.add('met');
          passwordRequirementsList[2].classList.remove('unmet');
      } else {
          passwordRequirementsList[2].classList.add('unmet');
          passwordRequirementsList[2].classList.remove('met');
      }

      // Check for number
      if (/\d/.test(value)) {
          passwordRequirementsList[3].classList.add('met');
          passwordRequirementsList[3].classList.remove('unmet');
      } else {
          passwordRequirementsList[3].classList.add('unmet');
          passwordRequirementsList[3].classList.remove('met');
      }

      // Check for special character
      if (/[\W_]/.test(value)) {
          passwordRequirementsList[4].classList.add('met');
          passwordRequirementsList[4].classList.remove('unmet');
      } else {
          passwordRequirementsList[4].classList.add('unmet');
          passwordRequirementsList[4].classList.remove('met');
      }
  });
});