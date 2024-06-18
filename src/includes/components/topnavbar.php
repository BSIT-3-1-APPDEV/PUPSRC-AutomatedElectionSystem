<nav class="navbar navbar-expand-lg navbar-dark bg-white">
  <div class="container-fluid py-1">
    <div class="ps-0 ps-lg-5">
      <img src="../src/images/resc/ivote-logo.png" alt="Logo" width="50px">
    </div>
    <div class="dropdown ms-auto">
      <a class="nav-link dropdown-toggle d-flex align-items-center main-color pe-0 pe-lg-5" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="d-none d-lg-block" style="font-size: 14px;"><b>Hello,&nbsp;<?php echo $org_personality ?></b></span>
        <i class="fas fa-user-circle main-color ps-3" style="font-size: 25px;"></i>
        <i id="dropdown-chevron" class="fas fa-chevron-down ps-1"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" style="font-size: 14px;" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item main-color" href="user-setting-information.php">Settings</a></li>
        <li><a class="dropdown-item main-color" href="includes/voter-logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
  const dropdownToggle = document.getElementById('navbarDropdown');
  const chevronIcon = document.getElementById('dropdown-chevron');

  dropdownToggle.addEventListener('click', function() {
    // Check if the dropdown is currently shown
    const isDropdownShown = dropdownToggle.getAttribute('aria-expanded') === 'true';

    if (isDropdownShown) {
      chevronIcon.classList.remove('fa-chevron-down');
      chevronIcon.classList.add('fa-chevron-up');
    } else {
      chevronIcon.classList.remove('fa-chevron-up');
      chevronIcon.classList.add('fa-chevron-down');
    }
  });

  // Handle clicking outside the dropdown to close it and reset the icon
  document.addEventListener('click', function(event) {
    if (!dropdownToggle.contains(event.target) && !document.querySelector('.dropdown-menu').contains(event.target)) {
      chevronIcon.classList.remove('fa-chevron-up');
      chevronIcon.classList.add('fa-chevron-down');
    }
  });
});
</script>