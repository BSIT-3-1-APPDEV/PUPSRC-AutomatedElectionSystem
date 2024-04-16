<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" type="image/x-icon" href="../../src/images/resc/ivote-favicon.png">
	<title>Blank Page</title>

	<!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<link rel="stylesheet" href="../../src/styles/style.css" />
	<link rel="stylesheet" href="../../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

</head>

<body>

	<!---------- SIDEBAR + HEADER START ------------>
	<!-- IMPORTANT: Replace the sidebar/header's block of code with: <?php // include_once __DIR__ . '/includes/components/sidebar.php'; ?> -->

	<nav class="sidebar">
		<div class="d-flex align-items-center">
			<img src="../../src/images/logos/jpia.png" alt="" class="org-logo">
		</div>
		<div class="org-sub-name text-center">
			<div class="d-inline-block align-middle">JUNIOR PHILIPPINE INSTITUTE OF ACCOUNTANTS</div>
		</div>

		<div class="menu-content">
			<ul class="menu-items ms-xl-1">
				<li class="item">
					<a href="#" class="active"><i data-feather="home" class="white me-xl-3 mb-xl-1"></i>Home</a>
				</li>

				<li class="item">
					<a href="#"><i data-feather="bar-chart-2" class="white me-xl-3 mb-xl-1"></i>Reports</a>
				</li>

				<li class="item">
					<div class="submenu-item" data-bs-toggle="collapse" href="#firstSubmenu" id="submenuToggle"
						data-bs-parent="false">
						<span class="fw-semibold"><i data-feather="user-plus" class="white me-xl-3 mb-xl-1"></i>Add
							Users</span>
						<i class="fas fa-chevron-right" id="submenuIcon"></i>
					</div>
					<ul class="menu-items submenu collapse" id="firstSubmenu">
						<li class="item">
							<a href="#">Admin Account</a>
						</li>
					</ul>
				</li>

				<li class="item">
					<a href="#"><i data-feather="users" class="white me-xl-3 mb-xl-1"></i>Manage Voters</a>
				</li>

				<li class="item">
					<a href="#"><i data-feather="archive" class="white me-xl-3 mb-xl-1"></i>Archive</a>
				</li>

				<li class="item">
					<a href="#"><i data-feather="settings" class="white me-xl-3 mb-xl-1"></i>Configuration</a>
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
				<img src="../../src/images/resc/ivote-logo.png" class="me-xl-3" style="height:35px">
			</div>

			<div class="header-right">
				<div class="dropdown">
					<button class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
						<img src="../../src/images/logos/jpia.png" class="profile-icon me-xl-3"><i
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
	<!---------- SIDEBAR + HEADER END ------------>


	<div class="main">

		<!-- Use this cardbox -->
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10 card-box">
					<div class="container-fluid">
						<div class="card-box">
							<div class="row">
								<div class="content">
									<!-- CONTENT TO BE PUT HERE -->
									<p class="head fs-2 fw-bold main-color pt-xl-3">Put Contents Here</p>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc id dictum nulla.
										Fusce facilisis consectetur risus, sit amet aliquet metus mattis et. Aenean et
										pharetra urna. Class aptent taciti sociosqu ad litora torquent per conubia
										nostra, per inceptos himenaeos. Donec nunc dolor, fringilla a lobortis id,
										rutrum tincidunt neque. Mauris tortor ligula, iaculis a tempor vel, ultrices
										quis dui. Aenean aliquet eu mi sit amet volutpat.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<script src="../../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../../src/scripts/script.js"></script>
	<script src="../../src/scripts/feather.js"></script>

</body>

</html>