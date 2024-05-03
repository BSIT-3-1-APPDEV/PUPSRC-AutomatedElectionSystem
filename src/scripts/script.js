// Sidebar Functionality
// ----------------------

// Submenu dropdown toggle
  var sidebar = document.querySelector(".sidebar");
  var sidebarClose = document.getElementById("sidebar-close");

  if (sidebar && sidebarClose) {
    // Function to toggle sidebar visibility
    function toggleSidebar() {
      if (sidebar.classList.contains("open")) {
        sidebar.classList.remove("open");
        sidebar.classList.add("close");
      } else {
        sidebar.classList.remove("close");
        sidebar.classList.add("open");
      }
    }

    sidebarClose.addEventListener("click", toggleSidebar);
  } else {
    console.error("Sidebar or sidebar close button not found.");
  }


menuItems.forEach(function (item) {
  item.addEventListener("click", function () {
    toggleSidebar();

    var submenu = item.querySelector(".submenu");
    $(submenu).collapse("toggle");

    menuItems.forEach(function (otherItem) {
      if (otherItem !== item) {
        var otherSubmenu = otherItem.querySelector(".submenu");
        $(otherSubmenu).collapse("hide");
      }
    });
  });
});

subMenuTitles.forEach((title) => {
  title.addEventListener("click", () => {
    menu.classList.remove("submenu-active");
  });
});



// Checkbox Table Functionality
// ----------------------

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  var checkbox = $('table tbody input[type="checkbox"]');
  $("#selectAll").click(function () {
    if (this.checked) {
      checkbox.each(function () {
        this.checked = true;
      });
    } else {
      checkbox.each(function () {
        this.checked = false;
      });
    }
  });
  checkbox.click(function () {
    if (!this.checked) {
      $("#selectAll").prop("checked", false);
    }
  });
});
