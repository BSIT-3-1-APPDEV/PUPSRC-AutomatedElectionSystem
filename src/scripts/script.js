
// Sidebar Functionality
// ----------------------

// Submenu dropdown toggle
var sidebar = document.querySelector(".sidebar");
var sidebarClose = document.querySelector("#sidebar-close");
var menu = document.querySelector(".menu-content");
var menuItems = document.querySelectorAll(".submenu-item");
var subMenuTitles = document.querySelectorAll(".submenu .menu-title");

sidebarClose.addEventListener("click", () => sidebar.classList.toggle("close"));

menuItems.forEach((item, index) => {
  item.addEventListener("click", () => {
    item.classList.toggle("show-submenu");
    
    let submenu = item.querySelector('.submenu');
    $(submenu).collapse('toggle');
    
    menuItems.forEach((item2, index2) => {
      if (index !== index2) {
        item2.classList.remove("show-submenu");
        let otherSubmenu = item2.querySelector('.submenu');
        $(otherSubmenu).collapse('hide'); 
      }
    });
  });
});

subMenuTitles.forEach((title) => {
  title.addEventListener("click", () => {
    menu.classList.remove("submenu-active");
  });
});

// Changing of toggle icon of submenus
document.addEventListener('DOMContentLoaded', function () {
  var submenuToggle = document.getElementById('submenuToggle');
  var submenuIcon = document.getElementById('submenuIcon');

  submenuToggle.addEventListener('click', function () {
      if (submenuIcon.classList.contains('fa-chevron-right')) {
          submenuIcon.classList.remove('fa-chevron-right');
          submenuIcon.classList.add('fa-chevron-down');
      } else {
          submenuIcon.classList.remove('fa-chevron-down');
          submenuIcon.classList.add('fa-chevron-right');
      }
      submenuIcon.style.transition = 'transform 0.5s ease';
  });
});

// Checkbox Table Functionality
// ----------------------

$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	
	var checkbox = $('table tbody input[type="checkbox"]');
	$("#selectAll").click(function(){
		if(this.checked){
			checkbox.each(function(){
				this.checked = true;                        
			});
		} else{
			checkbox.each(function(){
				this.checked = false;                        
			});
		} 
	});
	checkbox.click(function(){
		if(!this.checked){
			$("#selectAll").prop("checked", false);
		}
	});
});