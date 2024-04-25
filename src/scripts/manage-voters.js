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

$(document).ready(function () {
  $("#approve").click(function (event) {
      event.preventDefault();
      var voter_id = $("#voter_id").val();
      $.ajax({
          url: "submission_handlers/validate-acc.php",
          type: "POST",
          data: { voter_id: voter_id, action: 'approve' },
          success: function (response) {
              $("#approvalModal").modal("show");
          },
          error: function (xhr, status, error) {
              console.error(xhr.responseText);
          },
      });
  });

  $("#send-reject").click(function (event) {
      event.preventDefault();
      var voter_id = $("#voter_id").val();
      $.ajax({
          url: "submission_handlers/validate-acc.php",
          type: "POST",
          data: { voter_id: voter_id, action: 'reject' },
          success: function (response) {
              closeModal();
              $("#rejectDone").modal("show");
          },
          error: function (xhr, status, error) {
              console.error(xhr.responseText);
          },
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
  $('#rejectModal').modal('hide');
}

// Reject Modal
document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
  radio.addEventListener('change', function () {
    if (this.value === 'others' && this.checked) {
      document.getElementById('otherReason').style.display = 'block';
    } else {
      document.getElementById('otherReason').style.display = 'none';
    }
  });
});
// ---- End of: MODALS ----