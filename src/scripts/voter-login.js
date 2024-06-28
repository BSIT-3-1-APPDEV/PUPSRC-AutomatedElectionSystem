$(document).ready(function () {
  $("#loginForm").on("submit", function (event) {
    let email = $("#Email").val().trim();
    let password = $("#Password").val().trim();
    let isValid = true;

    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!emailPattern.test(email) || email === "") {
      isValid = false;
      $("#email-login-error").text("Please provide a valid email.");
      $("#Email").addClass("is-invalid border border-danger");
    } else {
      $("#email-login-error").text("");
      $("#Email").removeClass("is-invalid border border-danger");
      $("#Email").addClass("is-valid border border-success");
    }

    if (password === "") {
      isValid = false;
      $("#password-login-error").text("Please provide a valid password.");
      $("#Password").addClass("is-invalid border border-danger");
      $("#password-toggle").addClass("is-invalid border border-danger");
    } else {
      $("#password-login-error").text("");
      $("#Password, #password-toggle").removeClass(
        "is-invalid border border-danger"
      );
      $("#Password, #password-toggle").addClass(
        "is-valid border border-success"
      );
    }

    if (!isValid) {
      event.preventDefault();
    }
  });

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
    $("#email").removeClass(
      "is-invalid is-valid was-validated border border-danger border-success"
    );
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

  $("#Password").on("change", function (event) {
    let password = $(this).val();
    if (password === "") {
      event.preventDefault();
      $("#password-login-error").text("Please provide a valid password.");
      $("#Password").addClass("is-invalid border border-danger");
      $("#password-toggle").addClass("is-invalid border border-danger");
    } else {
      $("#password-login-error").text("");
      $("#Password").removeClass("is-invalid border border-danger");
      $("#password-toggle").removeClass("is-invalid border border-danger");
      $("#Password, #password-toggle").addClass(
        "is-valid border border-success"
      );
    }
  });
  

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
    const user = user_data[emailValue];

    if (!isValid) {
      email
        .removeClass("is-valid was-validated")
        .addClass("is-invalid border border-danger");
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
      email
        .removeClass("is-invalid border border-danger")
        .addClass("is-valid was-validated border border-success");
      emailErrorElement.text("");
      // emailValidElement.text("Looks right!");
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
    // $("#blockTime").text(`${blockTime} minutes`);
    $("#maxLimitReachedModal").modal("show");
  }

  sendButton.on("click", function (event) {
    event.preventDefault();
    const email = $("#email").val();
    const emailError = $("#email-error");

    if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)) {
      emailError.text("Please provide a valid email address.");
      $("#email").addClass("is-invalid border border-danger");
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
