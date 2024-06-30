$(document).ready(function () {
  $("#reset-password-toggle-1").click(function () {
    togglePasswordVisibility("#password", $(this));
  });

  $("#reset-password-toggle-2").click(function () {
    togglePasswordVisibility("#password_confirmation", $(this));
  });

  function togglePasswordVisibility(inputSelector, toggleElement) {
    var passwordInput = $(inputSelector);
    var eyeIcon = toggleElement.find("i");

    var type = passwordInput.attr("type") === "password" ? "text" : "password";
    passwordInput.attr("type", type);

    eyeIcon.toggleClass("fa-eye-slash fa-eye");
  }

  function preventSpaces(event) {
    var input = $(event.target);
    var value = input.val().replace(/\s/g, "");
    input.val(value);
  }

  $("#password, #password_confirmation").on("input", function (event) {
    preventSpaces(event);
    checkInputs();
  });

  // Truncate password if exceeds 20 characters
  function truncatePasswordIfExceedsMax(input) {
    var value = input.val().trim();
    if (value.length > 20) {
      value = value.slice(0, 20);
      input.val(value);
    }
  }

  function checkInputs() {
    truncatePasswordIfExceedsMax($("#password"));
    truncatePasswordIfExceedsMax($("#password_confirmation"));
    var passwordValue = $("#password").val().trim();
    var passwordConfirmationValue = $("#password_confirmation").val().trim();
    var submitButton = $("#" + ORG_NAME);
    var errorText = $("#password-mismatch-error");

    // Check if passwords match
    var passwordsMatch = passwordValue === passwordConfirmationValue;

    // Password requirements
    var hasValidLength =
      passwordValue.length >= 8 && passwordValue.length <= 20;
    var hasUpperCase = /[A-Z]/.test(passwordValue);
    var hasLowerCase = /[a-z]/.test(passwordValue);
    var hasNumber = /\d/.test(passwordValue);
    var hasSpecialChar = /[\W_]/.test(passwordValue);

    // Check if passwords match and update UI accordingly
    if (passwordConfirmationValue !== "" && passwordsMatch) {
      if (
        hasValidLength &&
        hasUpperCase &&
        hasLowerCase &&
        hasNumber &&
        hasSpecialChar
      ) {
        submitButton.prop("disabled", false);
        errorText.hide();
        $("#error-message").text("");
      } else {
        submitButton.prop("disabled", true);
        errorText.hide();
        $("#error-message").text("");
      }
    } else {
      submitButton.prop("disabled", true);
      if (passwordConfirmationValue !== "") {
        errorText.show();
        $("#error-message").text("");
      } else {
        errorText.hide();
        $("#error-message").text("");
      }
    }
  }

  checkInputs();

  $("#password, #password_confirmation").on("input", function () {
    checkInputs();
  });

  $("#password").on("input", function () {
    var value = $(this).val().trim();
    var passwordRequirements = $(".password-requirements");

    if (value) {
      passwordRequirements.addClass("show");
    } else {
      passwordRequirements.removeClass("show");
    }

    var passwordRequirementsList = $(".requirement");

    // Check password length
    passwordRequirementsList
      .eq(0)
      .toggleClass("met", value.length >= 8 && value.length <= 20);
    passwordRequirementsList
      .eq(0)
      .toggleClass("unmet", !(value.length >= 8 && value.length <= 20));

    // Check for uppercase letter
    passwordRequirementsList.eq(1).toggleClass("met", /[A-Z]/.test(value));
    passwordRequirementsList.eq(1).toggleClass("unmet", !/[A-Z]/.test(value));

    // Check for lowercase letter
    passwordRequirementsList.eq(2).toggleClass("met", /[a-z]/.test(value));
    passwordRequirementsList.eq(2).toggleClass("unmet", !/[a-z]/.test(value));

    // Check for number
    passwordRequirementsList.eq(3).toggleClass("met", /\d/.test(value));
    passwordRequirementsList.eq(3).toggleClass("unmet", !/\d/.test(value));

    // Check for special character
    passwordRequirementsList.eq(4).toggleClass("met", /[\W_]/.test(value));
    passwordRequirementsList.eq(4).toggleClass("unmet", !/[\W_]/.test(value));
  });

  $(window).on("beforeunload", function () {
    if ($("#successChangePasswordModal").is(":visible")) {
      $.ajax({
        url: "includes/voter-logout.php",
        type: "POST",
        success: function () {
          window.location.href = "landing-page.php";
        },
        error: function () {
          console.error("Error:", error);
          $("#error-message").text(
            "An error occurred. Please try again later."
          );
        },
      });
    }
  });

  // Process new password
  $("#" + ORG_NAME).click(function (event) {
    event.preventDefault();
    const password = $("#password").val();
    const password_confirmation = $("#password_confirmation").val();
    $.ajax({
      url: "includes/process-change-password.php",
      type: "POST",
      data: {
        password: password,
        password_confirmation: password_confirmation,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#successChangePasswordModal").modal("show");

          // Countdown timer
          let countdown = 10;
          const timerElement = $("#successChangePasswordModal .timer strong");
          const countdownInterval = setInterval(function () {
            countdown--;
            timerElement.text(countdown);

            if (countdown <= 0) {
              clearInterval(countdownInterval);
              $.ajax({
                url: "includes/voter-logout.php",
                type: "POST",
                success: function () {
                  window.location.href = "landing-page.php";
                },
                error: function (xhr, status, error) {
                  console.error("Error:", error);
                  $("#error-message").text(
                    "An error occurred. Please try again later."
                  );
                },
              });
            }
          }, 1000);
        } else {
          $("#error-message").text(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });
});
