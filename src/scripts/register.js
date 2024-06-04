// MODALS TWEAK

$(document).ready(function () {
    $("#reject-btn").click(function (event) {
        $('#onlyPDFAllowedModal').modal('show');
    });

    $(".close-mark").click(function () {
        closeModal();
    });
});

function closeModal() {
    $("#onlyPDFAllowedModal").modal("hide");
}

function redirectToPage(url) {
    window.location.href = url;
}

// --- MODALS TWEAK

