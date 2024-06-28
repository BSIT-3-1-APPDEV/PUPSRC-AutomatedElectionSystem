document.addEventListener("DOMContentLoaded", function () {
    var submenuToggle = document.getElementById("submenuToggle");
    var submenuIcon = document.getElementById("submenuIcon");

    submenuToggle.addEventListener("click", function () {
        if (submenuIcon.classList.contains("fa-chevron-right")) {
            submenuIcon.classList.remove("fa-chevron-right");
            submenuIcon.classList.add("fa-chevron-down");
        } else {
            submenuIcon.classList.remove("fa-chevron-down");
            submenuIcon.classList.add("fa-chevron-right");
        }
        submenuIcon.style.transition = "transform 0.5s ease";
    });
});