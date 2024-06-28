window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('page') === '2') {
      // Get the new content for section 2
      var newContent = document.getElementById('section-2').innerHTML;
  
      // Replace section-1 content with section-2 content
      var section1 = document.getElementById('section-1');
      section1.innerHTML = newContent;
    }
  };

function updateSubmitButton() {
    var orgValue = document.getElementById("org").value.trim();
    var fileNameDisplayValue = document.getElementById("corFileName").value.trim();
    var submitBtn = document.getElementById("transferBtn");
    
    // Enable submit button if both inputs have a value
    if (orgValue !== "" && fileNameDisplayValue !== "") {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

function displaySelectedOption() {
    var selectedOption = document.getElementById("organization").value;
    document.getElementById("org").value = selectedOption;

    // Call updateSubmitButton after updating org value
    updateSubmitButton();
}

function displayFileName(input) {
    const fileNameDisplay = document.getElementById('corFileName');
    if (input.files.length > 0) {
        fileNameDisplay.value = input.files[0].name;
    } else {
        fileNameDisplay.value = '';
    }

    // Call updateSubmitButton after displaying filename
    updateSubmitButton();
}

feather.replace();
  
    function handleInput() {
        var passwordInput = document.querySelector('.password-input');
        var toggleIcon = document.querySelector('.toggle-password');

        if (passwordInput.value.trim() === '') {
            toggleIcon.style.display = 'none';
        } else {
            toggleIcon.style.display = 'inline-block';

        }
    }

   function togglePasswordVisibility() {
    var passwordInput = document.querySelector('.password-input');
    var toggleIcon = document.querySelector('.toggle-password i');

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    }
}


$(document).ready(function() {
    console.log('Document ready'); // Check if document ready event fires

    $('#transferBtn').on('click', function(event) {
        console.log('transfer button clicked'); // Check 
        event.preventDefault();
        $('#transferOrgModal').modal('show');
    });

    $('#proceedBtn').on('click', function(event) {
        event.preventDefault();
        $('#transferOrgModal').modal('hide');
        $('#confirmPassModal').modal('show');
    });

    $("#realSubmitBtn").on("click", function (event) {
      event.preventDefault();

      // Perform password verification first
      var formData = new FormData($("#confirmPasswordForm")[0]);

      $.ajax({
        type: "POST",
        url: "includes/verify-password.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            // Proceed with organization change
            var formDataOrg = new FormData($("#changeOrgForm")[0]);

            $.ajax({
              type: "POST",
              url: "includes/change-org.php",
              data: formDataOrg,
              processData: false,
              contentType: false,
              success: function (response) {
                $("#confirmPassModal").modal("hide");
                $("#transferSuccessModal").modal("show");
                setTimeout(function () {
                  window.location.href = "includes/voter-logout";
                }, 5000); // Redirect after 5 seconds
              },
              error: function (xhr, status, error) {
                console.error(
                  "Change organization AJAX Error:",
                  xhr.responseText
                ); // Log organization change error
              },
            });
          } else if (response.maxLimit) {
            // Maximum attempts exceeded
            $("#confirmPassModal").modal("hide");
            $("#maximumAttemptsModal").modal("show");
          } else {
            // Password verification failed
            var attemptsLeft = response.message.split("Attempts left: ")[1];
            $("#errorMessage").text(response.message).show();
            $("#password").addClass("error-border");
          }
        },
        error: function (xhr, status, error) {
          console.error("Verify Password AJAX Error:", xhr.responseText); // Log verify password error
          // Handle verify password error
        },
      });
    });

    // Ajax request to destroy the session of a user
    $("#closeMaximumAttemptsModal").on("click", function () {
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
    });


    // Optional: Handle input error removal
    $('#password').on('input', function() {
        $(this).removeClass('error-border');
        $('#errorMessage').hide();
    });
});