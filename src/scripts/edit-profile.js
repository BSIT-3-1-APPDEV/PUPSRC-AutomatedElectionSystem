$(() => {
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
        toggleSaveButton(); // Check save button status on submit
      },
      false
    );
  });

  const saveChangesBtn = $("#saveChanges");
  saveChangesBtn.prop("disabled", true);

  const initialValues = {
    lastName: $("#lastName").val(),
    firstName: $("#firstName").val(),
    middleName: $("#middleName").val(),
    suffix: $("#suffix").val(),
    email: $("#email").val(),
  };

  const checkChanges = () => {
    const currentValues = {
      lastName: $("#lastName").val().trim(),
      firstName: $("#firstName").val().trim(),
      middleName: $("#middleName").val().trim(),
      suffix: $("#suffix").val().trim(),
      email: $("#email").val().trim(),
    };

    return Object.keys(initialValues).some(
      (key) => initialValues[key] !== currentValues[key]
    );
  };

  const toggleSaveButton = () => {
    if (checkChanges() && $(".is-invalid").length === 0) {
      saveChangesBtn.prop("disabled", false);
    } else {
      saveChangesBtn.prop("disabled", true);
    }
  };

  function preventLeadingSpace(event) {
    const input = event.target;
    if (input.value.startsWith(" ")) {
      input.value = input.value.trim();
    }
    input.value = input.value.replace(/\s{2,}/g, " ");
  }

  const avoidSpace = (event) => {
    if (event.key === " ") {
      event.preventDefault();
    }
  };

  // Validate name fields on input
  $("#editProfile input[type='text']").on("input", function (event) {
    preventLeadingSpace(event);
    validateNameField($(this));
    toggleSaveButton();
  });

  // Validate email field on input
  $("#email").on("input", function (event) {
    preventLeadingSpace(event);
    validateEmailField($(this));
    toggleSaveButton();
  });

  $("#email").on("keydown", function (event) {
    avoidSpace(event);
  });

  $("#cancelChanges").on("click", function () {
    if (checkChanges()) {
      if (
        confirm(
          "You have unsaved changes. Are you sure you want to abandon them?"
        )
      ) {
        window.location.href = "profile.php";
      }
    } else {
      window.location.href = "profile.php";
    }
  });

  $("#saveChanges").click(function (event) {
    event.preventDefault();
    event.stopPropagation();

    const lastName = $("#lastName").val();
    const firstName = $("#firstName").val();
    const middleName = $("#middleName").val();
    const suffix = $("#suffix").val();
    const email = $("#email").val();

    $.ajax({
      url: "includes/process-profile-data.php",
      type: "POST",
      data: {
        lastName: lastName,
        firstName: firstName,
        middleName: middleName,
        suffix: suffix,
        email: email,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          resetFormState();
          initialValues.lastName = lastName;
          initialValues.firstName = firstName;
          initialValues.middleName = middleName;
          initialValues.suffix = suffix;
          initialValues.email = email;

          $("#toastBody").text(response.message);

          // Display timestamp logic
          if (response.timestamp) {
            const timestamp = response.timestamp * 1000; // Convert seconds to milliseconds
            const secondsAgo = Math.floor((Date.now() - timestamp) / 1000);
            const toastTimestamp =
              secondsAgo === 0 ? "Just Now" : `${secondsAgo} seconds ago`;

            $("#profileUpdatedToast .text-body-secondary").text(toastTimestamp);
          }

          const successToast = $("#profileUpdatedToast");
          const toast = new bootstrap.Toast(successToast);
          toast.show();
        } else {
          alert(response.message);
          resetFormState();
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        alert(
          "An error occurred while updating your profile. Please try again."
        );
      },
    });

    $(this).addClass("was-validated");
  });

  // Function to validate name fields using regex and limit input length
  const validateNameField = ($input) => {
    const fieldName = $input.attr("id");
    let fieldValue = $input.val().trim();
    let isValid = true;
    let errorMessage = "";
    let maxLength = 64; // Default max length for first and last name

    switch (fieldName) {
      case "lastName":
      case "firstName":
        maxLength = 64;
        break;
      case "middleName":
        maxLength = 50;
        break;
      case "suffix":
        maxLength = 10;
        break;
    }

    // Truncate field value if it exceeds max length
    if (fieldValue.length > maxLength) {
      fieldValue = fieldValue.slice(0, maxLength);
      $input.val(fieldValue); // Update the input field with truncated value
    }

    if (fieldName !== "middleName" && fieldName !== "suffix") {
      if (fieldValue === "") {
        isValid = false;
        errorMessage = "This field is required.";
      } else if (!/^[a-zA-Z-' ]{1,50}$/.test(fieldValue)) {
        // Regex for names
        isValid = false;
        errorMessage = "Please enter a valid name.";
      }
    }

    if (isValid) {
      $input.removeClass("is-invalid").addClass("is-valid");
      $input.siblings(".valid-feedback").text("Looks good!");
      $input.siblings(".invalid-feedback").text("");
    } else {
      $input.removeClass("is-valid").addClass("is-invalid");
      $input.siblings(".invalid-feedback").text(errorMessage);
      $input.siblings(".valid-feedback").text("");
    }
  };

  // Function to validate email field using regex and limit input length
  const validateEmailField = ($input) => {
    let email = $input.val().trim();
    let isValid = true;
    let errorMessage = "";
    const maxLength = 50; // Max length for email field

    // Truncate email if exceeds max length
    if (email.length > maxLength) {
      email = email.slice(0, maxLength);
      $input.val(email); // Update the input field with truncated value
    }

    if (email === "") {
      isValid = false;
      errorMessage = "This field is required.";
    } else if (!/^\S+@\S+\.\S+$/.test(email)) {
      // Regex for email without whitespace
      isValid = false;
      errorMessage = "Please enter a valid email address.";
    }

    if (isValid) {
      $input.removeClass("is-invalid").addClass("is-valid");
      $input.siblings(".valid-feedback").text("Looks good!");
      $input.siblings(".invalid-feedback").text("");
    } else {
      $input.removeClass("is-valid").addClass("is-invalid");
      $input.siblings(".invalid-feedback").text(errorMessage);
      $input.siblings(".valid-feedback").text("");
    }
  };

  // Reset form state after submission
  const resetFormState = () => {
    $(".is-valid").removeClass("is-valid");
    $(".was-validated").removeClass("was-validated");
    $(".valid-feedback").text("");
    saveChangesBtn.prop("disabled", true);
  };
});
