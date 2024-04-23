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

// ----- FORM -----

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
      data: { voter_id: voter_id },
      success: function (response) {
        $("#approvalModal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });
  $("#reject").click(function () {});
});
