<?php

/**
 * Includes header and side navigation bar.
 * 
 * NOTE: Change/Add links if needed.
 * 
 */
?>

<nav class="sidebar">
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
				<a href="result-generation.php"
					class="<?php echo basename($_SERVER['PHP_SELF']) == 'result-generation.php' ? 'active' : ''; ?>"><i
						data-feather="bar-chart-2" class="white mb-xl-1"></i><span
						style="padding-left: 0.7rem;">Reports</span></a>
			</li>

			<li class="item">
				<div class="submenu-item <?php echo

						# ---- MANAGE ACCOUNTS PAGES
						(basename($_SERVER['PHP_SELF']) == 'manage-voters.php' ||
						basename($_SERVER['PHP_SELF']) == 'validate-voter.php' ||
						basename($_SERVER['PHP_SELF']) == 'manage-committee.php' ||
						basename($_SERVER['PHP_SELF']) == 'admin-creation.php')
						? 'active' : ''; ?>" data-bs-toggle="collapse" href="#manageAccounts" id="submenuToggle" data-bs-parent="false">
					
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
						basename($_SERVER['PHP_SELF']) == 'validate-voter.php')
						? 'active-sub fw-bold' : ''; ?>">
						Voters</a>

					</li>

					<li class="item">
						<a href="admin-creation.php"
							class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-committee.php' ? 'active-sub fw-bold' : ''; ?>">Committee</a>
					</li>
					<li class="item">
						<a href="admin-creation.php"
							class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-committee.php' ? 'active-sub fw-bold' : ''; ?>">Add
							Committee</a>
					</li>
				</ul>
			</li>

			<li class="item">
				<div class="submenu-item <?php echo

						# ---- MANAGE CANDIDATES PAGES
						(basename($_SERVER['PHP_SELF']) == 'manage-candidates.php' ||
						basename($_SERVER['PHP_SELF']) == 'add-candidate.php')
						? 'active' : ''; ?>"
						
					data-bs-toggle="collapse" href="#manageCandidates" id="submenuToggle" data-bs-parent="false">

					<div class="submenu-content">
						<i data-feather="user-plus" class="white mb-xl-1"></i>
						<span style="padding-left: 0.7rem;">Candidates</span>
					</div>

					<i class="fas fa-chevron-right" id="submenuIcon"></i>
				</div>
				<ul class="menu-items submenu collapse" id="manageCandidates">
					<li class="item">
						<a href="manage-voters.php"
							class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-candidate.php' ? 'active-sub fw-bold' : ''; ?>">Add
							Candidate</a>
					</li>
					<li class="item">
						<a href="admin-creation.php"
							class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-candidate.php' ? 'active-sub fw-bold' : ''; ?>">Manage
							Candidates</a>
					</li>
				</ul>
			</li>

			<li class="item">
				<a href="archive.php"
					class="<?php echo basename($_SERVER['PHP_SELF']) == 'archive.php' ? 'active' : ''; ?>"><i
						data-feather="archive" class="white mb-xl-1"></i><span
						style="padding-left: 1rem">Archive</span></a>
			</li>

			<li class="item">
				<a href="configuration.php"
					class="<?php echo basename($_SERVER['PHP_SELF']) == 'configuration.php' ? 'active' : ''; ?>"><i
						data-feather="settings" class="white mb-xl-1"></i><span
						style="padding-left: 1rem">Configuration</span></a>
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
				<ul class="dropdown-menu dropdown-menu-end main-color" aria-labelledby="dropdownMenuButton">
					<li class="px-xl-2"><a class="dropdown-item" href="profile.php">Profile</a></li>
					<li class="px-xl-2"><a class="dropdown-item" href="voter-logout.php">Log Out</a></li>
				</ul>
			</div>
		</div>
	</div>
</nav>