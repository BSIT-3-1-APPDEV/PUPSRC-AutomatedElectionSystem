document.getElementById("cancelReset").addEventListener("click", function () {
  resetForgotPasswordForm();
});

function resetForgotPasswordForm() {
  // Clear invalid/valid messages
  document.querySelector("#email-error").textContent = "";
  document.querySelector("#email").classList.remove("is-invalid");
  document.querySelector("#email-valid").textContent = "";
  document.querySelector("#email").classList.remove("is-valid");
  document.querySelector("#email").classList.remove("was-validated");

  // Reset button state
  document.querySelector("#" + ORG_NAME).disabled = true;

  // Reset the form fields
  document.getElementById("forgot-password-form").reset();
}

(() => {
  "use strict";

  const forms = document.querySelectorAll(".needs-validation");

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

// Updated script for password toggle
document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.querySelector("#password-toggle");
  const passwordInput = document.querySelector("#Password");
  const sendButton = document.querySelector("#" + ORG_NAME);
  const emailInput = document.querySelector("#email");
  const emailLogin = document.querySelector("#Email");

  // Toggle password visibility
  togglePassword.addEventListener("click", function () {
    const type =
      passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);
    togglePassword.textContent = type === "password" ? "Show" : "Hide";
  });

  // Disallow whitespaces from input fields
  const avoidSpace = (event) => {
    if (event.key === " ") {
      event.preventDefault();
    }
  };

  document.querySelectorAll("input").forEach((input) => {
    input.addEventListener("keydown", avoidSpace);
  });

  // Disable the Send button initially
  sendButton.disabled = true;

  // Email validation function
  const validateEmail = (
    email,
    emailErrorElement,
    emailValidElement,
    isLogin = false
  ) => {
    const emailValue = email.value.trim();
    const isValid = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(
      emailValue
    );
    const user = user_data[emailValue]; // Getting user data from user_data

    if (!isValid) {
      email.classList.remove("is-valid", "was-validated");
      email.classList.add("is-invalid");
      emailErrorElement.textContent = "Please provide a valid email.";
      emailValidElement.textContent = "";
    } else if (!isLogin && !user) {
      email.classList.remove("is-valid", "was-validated");
      email.classList.add("is-invalid");
      emailErrorElement.textContent = "User with this email does not exist.";
      emailValidElement.textContent = "";
    } else if (!isLogin && user === "invalid") {
      email.classList.remove("is-valid", "was-validated");
      email.classList.add("is-invalid");
      emailErrorElement.textContent = "This account was rejected.";
      emailValidElement.textContent = "";
    } else {
      email.classList.remove("is-invalid");
      email.classList.add("is-valid", "was-validated");
      emailErrorElement.textContent = "";
      emailValidElement.textContent = "Looks right!";
    }

    // Enable send button if email is valid and exists
    if (!isLogin) {
      sendButton.disabled = !(isValid && user && user !== "invalid");
    }
  };

  // Event listeners for email validation
  emailInput.addEventListener("change", () =>
    validateEmail(
      emailInput,
      document.querySelector("#email-error"),
      document.querySelector("#email-valid")
    )
  );

  emailLogin.addEventListener("change", () =>
    validateEmail(
      emailLogin,
      document.querySelector("#email-login-error"),
      document.querySelector("#email-login-valid"),
      true
    )
  );

  // Password Reset Link
  $("#" + ORG_NAME).click(function (event) {
    event.preventDefault();
    const email = $("#email").val();
    if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)) {
      // Basic email format validation
      const emailError = document.querySelector("#email-error");
      emailError.textContent = "Please provide a valid email address.";
      emailInput.classList.add("is-invalid");
      return;
    }

    // Hide forgot password modal
    $("#forgot-password-modal").modal("hide");

    // Show the emailSending modal
    $("#emailSending").modal("show");

    sendButton.disabled = true;

    $.ajax({
      url: "includes/send-password-reset.php",
      type: "POST",
      data: { email: email },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          // Hide email sending modal
          $("#emailSending").modal("hide");

          // Show success modal
          $("#successResetPasswordLinkModal")
            .modal("show")
            .on("hidden.bs.modal", function () {
              resetForgotPasswordForm();
            });
        } else {
          // Hide email sending modal
          $("#emailSending").modal("hide");

          // Show forgot password modal
          $("#forgot-password-modal").modal("show");

          const emailError = document.querySelector("#email-error");
          emailError.textContent = response.message;
          emailInput.classList.add("is-invalid");
          sendButton.disabled = false; // Re-enable the button if there's an error
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        sendButton.disabled = false; // Re-enable the button if there's an error
      },
    });
  });

  // Disable the "Send" button after it's clicked
  $("#" + ORG_NAME).click(function () {
    $(this).prop("disabled", true);
  });
});
