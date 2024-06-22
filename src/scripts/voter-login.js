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

$(document).ready(function () {
  const avoidSpace = (event) => {
    if (event.key === " ") {
      event.preventDefault();
    }
  };

  // Clears displayed messages from server-side
  $("#loginSubmitBtn").on("click", function () {
    const serverSideErrorMessage = document.querySelector(
      "#serverSideErrorMessage"
    );
    const serverSideInfoMessage = document.querySelector(
      "#serverSideInfoMessage"
    );

    if (serverSideErrorMessage) {
      serverSideErrorMessage.remove();
    }

    if (serverSideInfoMessage) {
      serverSideInfoMessage.remove();
    }
  });

  const preventSpaces = (event) => {
    let input = event.target;
    let maxLength = input.id === "Password" ? 20 : 255;
    let value = $(input).val();
    if (value.length > maxLength) {
      value = value.substring(0, maxLength);
      $(input).val(value);
    }
    value = value.replace(/\s/g, "");
    $(input).val(value);
  };

  $("input").on("keydown", avoidSpace);
  $("input").on("input", preventSpaces);

  // Function to reset forgot password form
  const resetForgotPasswordForm = () => {
    $("#email-error").text("");
    $("#email").removeClass("is-invalid is-valid was-validated");
    $("#email-valid").text("");
    $("#" + ORG_NAME).prop("disabled", true);
    $("#forgot-password-form")[0].reset();
  };

  $("#cancelReset").on("click", resetForgotPasswordForm);

  // Toggle password visibility
  $("#password-toggle").on("click", function () {
    const type =
      $("#Password").attr("type") === "password" ? "text" : "password";
    $("#Password").attr("type", type);
    $(this).text(type === "password" ? "Show" : "Hide");
  });

  const sendButton = $("#" + ORG_NAME);
  sendButton.prop("disabled", true);

  const validateEmail = (
    email,
    emailErrorElement,
    emailValidElement,
    isLogin = false
  ) => {
    const emailValue = email.val().trim();
    const isValid = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(
      emailValue
    );
    const user = user_data[emailValue]; // Assuming user_data is available

    if (!isValid) {
      email.removeClass("is-valid was-validated").addClass("is-invalid");
      emailErrorElement.text("Please provide a valid email.");
      emailValidElement.text("");
    } else if (!isLogin && !user) {
      email.removeClass("is-valid was-validated").addClass("is-invalid");
      emailErrorElement.text("User with this email does not exist.");
      emailValidElement.text("");
    } else if (!isLogin && user === "invalid") {
      email.removeClass("is-valid was-validated").addClass("is-invalid");
      emailErrorElement.text("This account was rejected.");
      emailValidElement.text("");
    } else {
      email.removeClass("is-invalid").addClass("is-valid was-validated");
      emailErrorElement.text("");
      emailValidElement.text("Looks right!");
    }

    if (!isLogin) {
      sendButton.prop("disabled", !(isValid && user && user !== "invalid"));
    }
  };

  $("#email").on("input", function () {
    validateEmail($(this), $("#email-error"), $("#email-valid"));
  });

  $("#Email").on("change", function () {
    validateEmail(
      $(this),
      $("#email-login-error"),
      $("#email-login-valid"),
      true
    );
  });

  if (maxLoginAttempts) {
    $("#maxLimitReachedModal").modal("show");
  }

  sendButton.on("click", function (event) {
    event.preventDefault();
    const email = $("#email").val();
    const emailError = $("#email-error");

    if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)) {
      emailError.text("Please provide a valid email address.");
      $("#email").addClass("is-invalid");
      return;
    }

    $("#forgot-password-modal").modal("hide");
    $("#emailSending").modal("show");
    sendButton.prop("disabled", true);

    $.ajax({
      url: "includes/send-password-reset.php",
      type: "POST",
      data: { email: email },
      dataType: "json",
      success: function (response) {
        $("#emailSending").modal("hide");

        if (response.success) {
          $("#successResetPasswordLinkModal")
            .modal("show")
            .on("hidden.bs.modal", function () {
              resetForgotPasswordForm();
            });
        } else {
          $("#forgot-password-modal").modal("show");
          emailError.text(response.message);
          $("#email").addClass("is-invalid");
          sendButton.prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        sendButton.prop("disabled", false);
      },
    });
  });
});
