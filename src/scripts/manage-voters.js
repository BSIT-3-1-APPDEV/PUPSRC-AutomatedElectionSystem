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
    $.ajax({
      url: "submission_handlers/validate-acc.php",
      type: "POST",
      data: { voter_id: voter_id, action: "approve" },
      success: function (response) {
        $("#approvalModal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });

  // Reject Account Modal
  $("#send-reject").click(function (event) {
    event.preventDefault();
    var voter_id = $("#voter_id").val();
    $.ajax({
      url: "submission_handlers/validate-acc.php",
      type: "POST",
      data: { voter_id: voter_id, action: "reject" },
      success: function (response) {
        closeModal();
        $("#rejectDone").modal("show");
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });

  // Confirm Delete Modal
  $("#confirm-delete").click(function (event) {
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


// ----- MODALS -----
$(document).ready(function () {
  $("#reject-btn").click(function (event) {
    $("#rejectModal").modal("show");
  });
});

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

// Validate Delete Confirmation
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

// ---- End of: MODALS ----




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
