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

document.addEventListener("DOMContentLoaded", function () {
  const sendButton = document.querySelector("#" + ORG_NAME);
  const emailInput = document.querySelector("#email");

  sendButton.disabled = true; // Initially disable the Send button

  const validateEmail = () => {
    const email = emailInput.value;
    const isValid = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(
      email
    ); // Basic email format validation
    sendButton.disabled = !isValid;
    const emailError = document.querySelector("#email-error");
    if (!isValid) {
      emailError.textContent = "Please provide a valid email address.";
      emailInput.classList.add("is-invalid");
    } else {
      emailError.textContent = "";
      emailInput.classList.remove("is-invalid");
    }
  };

  // Validate the email input on input and change events
  emailInput.addEventListener("input", validateEmail);
  emailInput.addEventListener("change", validateEmail);

  // Password Reset Link
  $("#" + ORG_NAME).click(function (event) {
    event.preventDefault();
    var email = $("#email").val();
    if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)) {
      // Basic email format validation
      const emailError = document.querySelector("#email-error");
      emailError.textContent = "Please provide a valid email address.";
      emailInput.classList.add("is-invalid");
      return;
    }

    sendButton.disabled = true; // Disable the button while the request is being processed

    $.ajax({
      url: "includes/send-password-reset.php",
      type: "POST",
      data: { email: email },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#successResetPasswordLinkModal")
            .modal("show")
            .on("hidden.bs.modal", function () {
              location.reload();
            });
        } else {
          const emailError = document.querySelector("#email-error");
          emailError.textContent = response.message;
          emailInput.classList.add("is-invalid");
          sendButton.disabled = false;
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
