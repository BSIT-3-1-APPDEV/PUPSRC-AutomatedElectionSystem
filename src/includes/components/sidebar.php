<?php

/**
 * Includes header and side navigation bar.
 * 
 * NOTE: Change/Add links if needed.
 * 
 */
?>

<nav class="sidebar open">
	<div class="d-flex align-items-center">
		<img src="images/logos/<?php echo $org_name ?>.png" alt="" class="org-logo">
	</div>
	<div class="org-sub-name text-center">
		<div class="d-inline-block align-middle main-color"><?php echo strtoupper($org_full_name) ?></div>
	</div>

	<div class="menu-content">
		<ul class="menu-items ms-xl-1">
			<li class="item">
				<a href="admindashboard.php"
					class="<?php echo basename($_SERVER['PHP_SELF']) == 'admindashboard.php' ? 'active' : ''; ?>"><i
						data-feather="home" class="white mb-xl-1"></i><span style="padding-left: 1rem">Home</span></a>
			</li>

			<li class="item">
				<a href="includes/generate-json.php"
					class="<?php echo basename($_SERVER['PHP_SELF']) == 'result-generation.php' ? 'active' : ''; ?>"><i
						data-feather="bar-chart-2" class="white mb-xl-1"></i><span
						style="padding-left: 0.7rem;">Election Reports</span></a>
			</li>

			<li class="item">
				<div id="submenuToggle2" class="submenu-item <?php echo

						# ---- MANAGE CANDIDATES PAGES
					(basename($_SERVER['PHP_SELF']) == 'manage-candidate.php' ||
						basename($_SERVER['PHP_SELF']) == 'add-candidate.php')
					? 'active' : ''; ?>" data-bs-toggle="collapse" href="#manageCandidates" data-bs-parent="false">

					<div class="submenu-content">
						<i data-feather="user-plus" class="white mb-xl-1"></i>
						<span style="padding-left: 0.7rem;">Candidates</span>
					</div>

					<i class="fas fa-chevron-right" id="submenuIcon2"></i>

					<script>
						document.addEventListener("DOMContentLoaded", function () {
							var submenuToggle = document.getElementById("submenuToggle2");
							var submenuIcon = document.getElementById("submenuIcon2");

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
					</script>

				</div>
				<ul class="menu-items submenu collapse" id="manageCandidates">
					<li class="item">
						<a href="add-candidate.php"
							class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-candidate.php' ? 'active-sub fw-bold' : ''; ?>">Add
							Candidate</a>
					</li>
					<li class="item">
						<a href="manage-candidate.php"
							class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-candidate.php' ? 'active-sub fw-bold' : ''; ?>">Manage
							Candidates</a>
					</li>
				</ul>
			</li>

			<li class="item">
				<?php if ($_SESSION['role'] === 'head_admin'): ?>
					<div id="submenuToggle" class="submenu-item <?php echo
							# ---- MANAGE ACCOUNTS PAGES
						(basename($_SERVER['PHP_SELF']) == 'manage-voters.php' ||
							basename($_SERVER['PHP_SELF']) == 'validate-voter.php' ||
							basename($_SERVER['PHP_SELF']) == 'manage-committee.php' ||
							basename($_SERVER['PHP_SELF']) == 'voter-details.php' ||
							basename($_SERVER['PHP_SELF']) == 'admin-creation.php' ||
							basename($_SERVER['PHP_SELF']) == 'account-details.php')
						? 'active' : ''; ?>" data-bs-toggle="collapse" href="#manageAccounts" data-bs-parent="false">

						<div class="submenu-content">
							<i data-feather="users" class="white mb-xl-1"></i>
							<span style="padding-left: 0.7rem;">Manage Users</span>
						</div>

						<i class="fas fa-chevron-right" id="submenuIcon"></i>
					</div>
					<ul class="menu-items submenu collapse" id="manageAccounts">
						<li class="item">
							<a href="manage-voters.php" class="<?php echo
									# ---- VOTERS PAGES
								(basename($_SERVER['PHP_SELF']) == 'manage-voters.php' ||
									basename($_SERVER['PHP_SELF']) == 'validate-voter.php' ||
									basename($_SERVER['PHP_SELF']) == 'voter-details.php')
								? 'active-sub fw-bold' : ''; ?>">
								Voters' Accounts</a>
						</li>
						<li class="item">
							<a href="manage-committee.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage-committee.php' ||
								basename($_SERVER['PHP_SELF']) == 'account-details.php')
								? 'active-sub fw-bold' : ''; ?>">Admin
								Accounts</a>
						</li>
						<li class="item">
							<a href="admin-creation.php"
								class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin-creation.php' ? 'active-sub fw-bold' : ''; ?>">
								Add Admin
							</a>
						</li>
					</ul>
				<?php elseif ($_SESSION['role'] === 'admin'): ?>
					<a href="manage-voters.php"
						class="submenu-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage-voters.php' ? 'active' : ''; ?>">
						<div class="submenu-content">
							<i data-feather="users" class="white mb-xl-1"></i>
							<span style="padding-left: 0.7rem;">Manage Voters</span>
						</div>
					</a>
				<?php endif; ?>
			</li>



			<li class="item">
				<a href="configuration.php" class="<?=

						# ---- CONFIGURATION PAGES
					(basename($_SERVER['PHP_SELF']) == 'configuration.php' ||
						basename($_SERVER['PHP_SELF']) == 'ballot-form' ||
						basename($_SERVER['PHP_SELF']) == 'vote-schedule' ||
						basename($_SERVER['PHP_SELF']) == 'election-year' ||
						basename($_SERVER['PHP_SELF']) == 'vote-guidelines' ||
						basename($_SERVER['PHP_SELF']) == 'positions')
					? 'active' : ''; ?>
				">
					<i data-feather="settings" class="white mb-xl-1"></i><span
						style="padding-left: 1rem">Configuration</span>
				</a>
			</li>
		</ul>
	</div>
</nav>


<nav class="navbar">
	<div class="container-fluid">
		<div class="header-left main-color">
			<i class="fas fa-bars " id="sidebar-close"></i>
		</div>

		<div class="pe-none">
			<img src="images/resc/ivote-logo.png" class="me-xl-3" style="height:35px">
		</div>

		<div class="header-right">
			<div class="dropdown user-profile">

				<button class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="images/logos/<?php echo $org_name ?>.png" class="profile-icon me-xl-3"><i
						class="fas fa-chevron-down main-color fs-6"></i>
				</button>
				<ul class="dropdown-menu dropdown-menu-end main-color p-3" aria-labelledby="dropdownMenuButton">
					<li class="px-xl-2 py-xl-1"><a class="dropdown-item" href="profile.php"><i data-feather="user"
								class="fs-12 main-color mb-xl-1"></i><span style="padding-left: .8rem">Profile</a></li>
					<li class="px-xl-2"><a class="dropdown-item" href="recycle-bin.php"><i data-feather="trash-2"
								class="fs-11 main-color mb-xl-1"></i><span style="padding-left: .8rem">Recycle Bin</a>
					</li>
					<li class="px-xl-2 py-xl-1"><a class="dropdown-item" href="includes/voter-logout.php"><i
								data-feather="log-out" class="fs-11 main-color mb-xl-1"></i><span
								style="padding-left: .8rem"></span>Log Out</a></li>
				</ul>
			</div>
		</div>
	</div>
</nav>

<style>
	.dropdown-item:hover svg,
	.dropdown-item:focus svg {
		color: var(--bs-white);
	}
</style>
<script src="scripts/submenu-head-admin.js"></script>