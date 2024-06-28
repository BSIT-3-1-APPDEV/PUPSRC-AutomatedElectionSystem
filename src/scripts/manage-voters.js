// ----- FORM SUBMISSIONS -----

const fullscreenIcon = document.querySelector(".fullscreen-icon");
const pdfContainer = document.querySelector(".cor");

fullscreenIcon.addEventListener("click", function () {
  if (
    !document.fullscreenElement &&
    !document.mozFullScreenElement &&
    !document.webkitFullscreenElement &&
    !document.msFullscreenElement
  ) {
    if (pdfContainer.requestFullscreen) {
      pdfContainer.requestFullscreen();
    } else if (pdfContainer.mozRequestFullScreen) {
      pdfContainer.mozRequestFullScreen();
    } else if (pdfContainer.webkitRequestFullscreen) {
      pdfContainer.webkitRequestFullscreen();
    } else if (pdfContainer.msRequestFullscreen) {
      pdfContainer.msRequestFullscreen();
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    }
  }

  if (document.fullscreenElement) {
    pdfContainer.style.height = "100vh !important";
  }
});

// ----- FORM SUBMISSIONS -----

function redirectToPage(url) {
  window.location.href = url;
}

// Approve Account Modal
$(document).ready(function () {
  $("#approve").click(function (event) {
    event.preventDefault();
    var voter_id = $("#voter_id").val();
    
    // Show the emailSending modal
    $("#emailSending").modal("show");

    $.ajax({
        url: "submission_handlers/validate-acc.php",
        type: "POST",
        data: { voter_id: voter_id, action: "approve" },
        success: function (response) {
            // Hide the emailSending modal
            $("#emailSending").modal("hide");

            // Show the approvalModal
            $("#approvalModal").modal("show");
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
            // Hide the emailSending modal in case of an error
            $("#emailSending").modal("hide");
        },
    });
});


  $(document).ready(function () {
    // Reject Account Modal
    $("#send-reject").click(function (event) {

      event.preventDefault();
      var voter_id = $("#voter_id").val();
      var reason = $("input[name='reason']:checked").val(); // Get the selected reason
      var otherReason = $("#other").val(); // Get the specified other reason if applicable

      console.log("Selected Reason:", reason); // Log the selected reason for debugging

      // Create the data object including the reason
      var data = {
        voter_id: voter_id,
        action: "reject",
        reason: reason, // Include the selected reason in the data
      };

      // If "Others" is selected, include the otherReason in the data
      if (reason === "others") {
        data.otherReason = otherReason;
      }

      console.log("Data:", data); // Log the data object for debugging

      closeModal();
      $("#emailSending").modal("show");

      $.ajax({
        url: "submission_handlers/validate-acc.php",
        type: "POST",
        data: data,
        success: function (response) {
          $("#emailSending").modal("hide");
          $("#rejectDone").modal("show");
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
        },
      });
    });

    // Toggle visibility of otherReason textarea based on radio button selection
    $('input[type="radio"]').change(function () {
      if (this.value === "others" && this.checked) {
        $("#otherReason").show();
      } else {
        $("#otherReason").hide();
      }
    });
  });


  // Move To Trashbin Modal Submit
  $("#confirm-move").click(function (event) {
    event.preventDefault();
    var voter_id = $("#voter_id").val();
    $.ajax({
      url: "submission_handlers/move-to-recycle-bin.php",
      type: "POST",
      data: { voter_id: voter_id },
      success: function (response) {
        closeModal();
        $("#trashbinMoveDone").modal("show");
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });


  // Change Status on Voter Details
  $(document).ready(function () {
    $("#dropdown").change(function () {
      console.log("Dropdown value changed");
      $("#status-form").submit();
    });

    $("#status-form").submit(function (event) {
      event.preventDefault();
      console.log("Form submission intercepted");

      var voter_id = $("#voter_id").val();
      console.log("Voter ID:", voter_id);

      var status = $("#dropdown").val();
      console.log("Status:", status);

      $.ajax({
        url: "submission_handlers/update-status.php",
        type: "POST",
        data: { voter_id: voter_id, status: status },
        success: function (response) {
          console.log("AJAX request successful");
          closeModal();
          $("#rejectDone").modal("show");
        },
        error: function (xhr, status, error) {
          console.error("AJAX request failed:", error);
          console.error(xhr.responseText);
        },
      });
    });
  });
});
// ---- End of: FORM SUBMISSIONS ----



// ----- MODALS BEHAVIOR TRIGGERS -----

//Show & Hide Modal Functions
$(document).ready(function () {
  $("#reject-btn").click(function (event) {
    $("#rejectModal").modal("show");
  });
});

function cancelForm(event) {
  event.preventDefault(); // Prevent the default form submission
  closeModal(); // Close the modal (assuming this function is defined elsewhere)
}

function closeModal() {
  $("#rejectModal").modal("hide");
}

// Reject Modal
document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
  radio.addEventListener("change", function () {
    if (this.value === "others" && this.checked) {
      document.getElementById("otherReason").style.display = "block";
    } else {
      document.getElementById("otherReason").style.display = "none";
    }
  });
});


// ---- End of: MODALS ----


// ----- VALIDATIONS -----

function toggleOtherReason() {
  const otherReasonInput = document.getElementById('otherReason');
  if (this.value === 'others' && this.checked) {
    otherReasonInput.style.display = 'block';
  } else {
    otherReasonInput.style.display = 'none';
    document.getElementById('other').value = ''; // Clear textarea if not selected
  }
  checkFormValidity();
}

function checkFormValidity() {
  const reasonSelected = document.querySelector('input[name="reason"]:checked') !== null;
  const otherReason = document.getElementById('other').value.trim();
  const isValid = reasonSelected && (document.getElementById('others').checked ? otherReason.length > 0 : true);
  document.getElementById('send-reject').disabled = !isValid;
}

document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
  radio.addEventListener('change', toggleOtherReason);
});

document.getElementById('other').addEventListener('input', checkFormValidity);




// ----- UNUSED/REFERENCE CODES -----
// This will be removed if no longer needeed as a reference for other modules.

// Dynamic change of dropdown edit status
$("#dropdown").change(function () {
  var selectedOption = $(this).val();
  var newClass = "";

  switch (selectedOption) {
    case "Active":
      newClass = "active-status";
      break;
    case "Disabled":
      newClass = "inactive-status";
      break;
    case "Reject":
      newClass = "rejected-status";
      break;
    default:
      newClass = "";
      break;
  }

  $(this)
    .removeClass("active-status inactive-status rejected-status")
    .addClass(newClass);
});

// TOTAL DELETION: Confirm Delete Modal
$("#totalDeleteModal").click(function (event) {
  event.preventDefault();
  var voter_id = $("#voter_id").val();
  $.ajax({
    url: "submission_handlers/delete-acc.php",
    type: "POST",
    data: { voter_id: voter_id },
    success: function (response) {
      closeModal();
      $("#deleteDone").modal("show");
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
});


// VALIDATION: Delete Confirmation
function validateConfirmation() {
  var confirmationInput = document
    .getElementById("confirm-deletion")
    .value.trim();
  var deleteButton = document.getElementById("confirm-delete");

  // Enable delete button if input matches the confirmation text
  if (confirmationInput === "Confirm Delete") {
    deleteButton.removeAttribute("disabled");
  } else {
    deleteButton.setAttribute("disabled", "disabled");
  }
}