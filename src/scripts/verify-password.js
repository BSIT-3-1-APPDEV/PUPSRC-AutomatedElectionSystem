$(document).ready(function () {
  const submitButton = $("#delete-button");
  const togglePassword = $("#password-toggle-1");
  const passwordInput = $("#change-password");
  const eyeIcon = togglePassword.find("i");

  togglePassword.on("click", function () {
    const type =
      passwordInput.attr("type") === "password" ? "text" : "password";
    passwordInput.attr("type", type);
    eyeIcon.toggleClass("fa-eye-slash fa-eye");
  });

  function preventSpaces(event) {
    let input = event.target;
    let value = $(input).val();
    value = value.replace(/\s/g, "");
    $(input).val(value);
  }

  $("#change-password").on("input", function (event) {
    preventSpaces(event);
  });

  function resetModal() {
    $("#change-password").val("");
    $("#error-message").text("");
    $("#success-message").text("");
    $("#change-password").removeClass(
      "is-valid is-invalid border border-danger"
    );
    $("#password-toggle-1").removeClass(
      "is-valid is-invalid border border-danger"
    );
    submitButton.prop("disabled", false);
  }

  $("#cancel-modal").on("click", function () {
    resetModal();
  });

  $(window).on("beforeunload", function () {
    if ($("#maxLimitReachedModal").is(":visible")) {
      $.ajax({
        url: "includes/voter-logout.php",
        type: "POST",
        success: function () {
          window.location.href = "landing-page";
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

  // Ajax request to destroy the session of a user
  $("#closeMaxLimitReachedModal").on("click", function () {
    $.ajax({
      url: "includes/voter-logout.php",
      type: "POST",
      success: function () {
        window.location.href = "landing-page";
      },
      error: function () {
        console.error("Error:", error);
        $("#error-message").text("An error occurred. Please try again later.");
      },
    });
  });

  $("#currentPasswordForm").on("submit", function (event) {
    event.preventDefault();
    submitButton.prop("disabled", true);
    const currentPassword = passwordInput.val().replace(/\s/g, "");

    if (currentPassword) {
      $.ajax({
        url: "includes/verify-password.php",
        type: "POST",
        data: { password: currentPassword },
        dataType: "json",
        success: function (response) {
          if (response.maxLimit) {
            $("#changePasswordModal").modal("hide");
            resetModal();
            $("#maxLimitReachedModal").modal("show");
          } else if (response.success) {
            window.location.href = "change-password";
            resetModal();
          } else {
            $("#error-message").text(response.message);
            $("#change-password").addClass("border border-danger");
            $("#password-toggle-1").addClass("is-invalid border border-danger");
            submitButton.prop("disabled", false);
          }
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
          $("#error-message").text(
            "An error occurred. Please try again later."
          );
          submitButton.prop("disabled", false);
        },
      });
    } else {
      $("#error-message").text("Password cannot be empty.");
      $("#change-password").addClass("border border-danger");
      $("#password-toggle-1").addClass("is-invalid border border-danger");
      submitButton.prop("disabled", false);
    }
  });
});
