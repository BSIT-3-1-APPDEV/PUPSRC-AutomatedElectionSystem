<?php

/**
 * Includes header and side navigation bar.
 *
 */
?>

<nav class="sidebar">
	<div class="d-flex align-items-center">
		<img src="images/logos/jpia.png" alt="" class="org-logo">
	</div>
	<div class="org-sub-name text-center">
		<div class="d-inline-block align-middle">JUNIOR PHILIPPINE INSTITUTE OF ACCOUNTANTS</div>
	</div>

	<div class="menu-content">
		<ul class="menu-items ms-xl-1">
			<li class="item">
				<a href="src/admindashboard.php" class="active"><i data-feather="home" class="white me-xl-3 mb-xl-1"></i>Home</a>
			</li>

			<li class="item">
				<a href="result-generation.php"><i data-feather="bar-chart-2" class="white me-xl-3 mb-xl-1"></i>Reports</a>
			</li>

			<li class="item">
				<div class="submenu-item" data-bs-toggle="collapse" href="#firstSubmenu" id="submenuToggle"
					data-bs-parent="false">
					<span class="fw-semibold"><i data-feather="users" class="white me-xl-3 mb-xl-1"></i>Manage Users</span>
					<i class="fas fa-chevron-right" id="submenuIcon"></i>
				</div>
				<ul class="menu-items submenu collapse" id="firstSubmenu">
					<li class="item">
						<a href="#">Voters</a>
					</li>
				</ul>
				<ul class="menu-items submenu collapse" id="firstSubmenu">
					<li class="item">
						<a href="admin-creation.php">Committee Member</a>
					</li>
				</ul>
			</li>

			<li class="item">
				<a href="#"><i data-feather="user-plus" class="white me-xl-3 mb-xl-1"></i>Add Candidate</a>
			</li>

			<li class="item">
				<a href="archive.php"><i data-feather="archive" class="white me-xl-3 mb-xl-1"></i>Archive</a>
			</li>

			<li class="item">
				<a href="configuration.php"><i data-feather="settings" class="white me-xl-3 mb-xl-1"></i>Configuration</a>
			</li>
		</ul>
	</div>
</nav>

<nav class="navbar">
	<div class="container-fluid">
		<div class="header-left">
			<i class="fas fa-bars" id="sidebar-close"></i>
		</div>

		<div>
			<img src="images/resc/ivote-logo.png" class="me-xl-3" style="height:35px">
		</div>

		<div class="header-right">
			<div class="dropdown">
				<button class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="images/logos/jpia.png" class="profile-icon me-xl-3"><i
						class="fas fa-chevron-down main-color fs-6"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
					<li><a class="dropdown-item" href="#">Profile</a></li>
					<li><a class="dropdown-item" href="#">Log Out</a></li>
				</ul>
			</div>
		</div>

	</div>
</nav>