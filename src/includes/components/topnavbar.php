<nav class="navbar navbar-expand-lg navbar-dark bg-white">
  <div class="container-fluid py-1">
    <div class="ps-0 ps-lg-5">
      <img src="../src/images/resc/ivote-logo.png" alt="Logo" width="50px">
    </div>
    <div class="dropdown ms-auto">
      <a class="nav-link d-flex align-items-center main-color pe-0 pe-lg-5" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="d-none d-lg-block" style="font-size: 14px;"><b>Hello,&nbsp;<?php echo $org_personality ?></b></span>
        <i class="fas fa-user-circle main-color ps-3" style="font-size: 25px;"></i>
        <i id="dropdown-chevron" class="fas fa-chevron-down ps-1"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end px-2 py-3" style="font-size: 12px; font-weight:500;" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="user-setting-information"><span class="main-color pe-3"><i data-feather="settings"></i></span>Settings</a></li>
        <li><a class="dropdown-item" href="voter-faqs"><span class="main-color pe-3"><i data-feather="help-circle"></i></span>FAQs</a></li>
        <li><a class="dropdown-item" href="includes/voter-logout.php"><span class="main-color pe-3"><i data-feather="log-out"></i></span>Log Out</a></li>
      </ul>
    </div>
  </div>
</nav>

<script src="scripts/feather.js"></script>
