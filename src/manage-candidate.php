	<?php

	include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

	include_once FileUtils::normalizeFilePath('includes/error-reporting.php');
	require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
	require_once FileUtils::normalizeFilePath('includes/session-handler.php');
	require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');

	if (isset($_SESSION['voter_id'])) {

		include FileUtils::normalizeFilePath('includes/session-exchange.php');

		// Check if the user's role is either 'Committee Member' or 'Admin Member'
		$allowedRoles = array('head_admin', 'admin');
		if (in_array($_SESSION['role'], $allowedRoles)) {
			include FileUtils::normalizeFilePath('submission_handlers/manage-candidates.php');
			?>

			<!DOCTYPE html>
			<html lang="en">

			<head>
				<meta charset="UTF-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
				<title>Manage Account</title>

				<!-- Icons -->
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
				<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
				<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

				<!-- Styles -->
				<link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
				<link rel="stylesheet" href="styles/style.css" />
				<link rel="stylesheet" href="styles/core.css" />
				<link rel="stylesheet" href="styles/tables.css" />
				<link rel="stylesheet" href="styles/manage-committee.css" />
				<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


			</head>

			<body>


				<?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

				<div class="main">

					<div class="container mb-5 pl-5">
						<div class="row justify-content-center">
							<div class="col-md-11">
								<div class="breadcrumbs d-flex">
									<button type="button" class="btn btn-lvl-white d-flex align-items-center spacing-8 fs-8">
										<i data-feather="users" class="white im-cust feather-2xl"></i> CANDIDATES
									</button>
									<button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">MANAGE CANDIDATES</button>
									<div class = "align-items-end ms-auto me-4 mx-a">
										<a href="add-candidate.php">
											<button type="button" class="btn btn-lvl-current rounded-2 fs-7" >
											<i class = "bi bi-plus-circle me-3"></i>Add New Candidate
											</button>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="container">
						<div class="row justify-content-center">
							<!-- VERIFIED TABLE -->
							<div class="row justify-content-center">
								<div class="col-md-10 card-box  mt-md-10">
									<div class="container-fluid">
										<div class="card-box">
											<div class="row">
												<div class="content">
													<div class="table-wrapper">

														<div class="table-title">
															<div class="row">
																<!-- Table Header -->
																<div class="col-sm-6">
																	<p class="fs-3 main-color fw-bold ls-10 spacing-6">Manage Candidates</p>
																</div>
																<div class="col-sm-6">
																	<div class="row">			
																		<div class="col-md-12 text-end flex-end">
																			<!-- Delete -->
																			<div class="d-inline-block">
																				<button class="delete-btn fs-7 spacing-3 fw-medium"
																					type="button" id="dropdownMenuButton"
																					data-bs-toggle="dropdown" aria-haspopup="true"
																					aria-expanded="false">
																					<i class="fa-solid fa-trash-can fa-sm"></i>
																					Delete
																				</button>
																			</div>		
																			<span
																					class="light-gray-accent fw-bold ps-3">|</span>				
																			<!-- Filters -->
																			<div class="d-inline-block ps-3">
																				<form class="d-inline-block" method="get">
																					<div class="dropdown sort-by">
																						<button class="sortby-tbn fs-7 spacing-6 fw-medium" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																							<i data-feather="filter" class="feather-xs im-cust-2"></i> Filter
																						</button>
																						<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="padding: 0.5rem">
																							<!-- Checkbox Items -->
																							<?php
																							foreach ($positions as $position) {
																							?>
																								<li class="dropdown-item ps-3 fs-7 fw-medium">
																									<label>
																										<input type="checkbox" name="filter[]" value="<?php echo $position['title']; ?>" <?php if (isset($_GET['filter']) && in_array($position['title'], $_GET['filter'])) echo 'checked'; ?> onchange="this.form.submit()">
                            																			<?php echo ucfirst($position['title']); ?>
																									</label>
																								</li>
																							<?php } ?>
																						</div>
																					</div>
																				</form>
																			</div>

																			<!-- Sort By -->
																			<div class="d-inline-block ps-3">
																				<form class="d-inline-block">
																					<div class="dropdown sort-by">
																						<button
																							class="sortby-tbn fs-7 spacing-6 fw-medium"
																							type="button" id="dropdownMenuButton"
																							data-bs-toggle="dropdown"
																							aria-haspopup="true"
																							aria-expanded="false">
																							<i
																								class="fa-solid fa-arrow-down-wide-short fa-sm"></i>
																							Sort by
																						</button>
																						<div class="dropdown-menu dropdown-menu-end"
																							aria-labelledby="dropdownMenuButton"
																							style="padding: 0.5rem">
																							<!-- Dropdown items -->
																							<li
																								class="dropdown-item ps-3 fs-7 fw-medium">
																								<a
																									href="?sort=acc_created&order=desc">Newest
																									to Oldest</a>
																							</li>
																							<li
																								class="dropdown-item ps-3 fs-7 fw-medium">
																								<a
																									href="?sort=acc_created&order=asc">Oldest
																									to Newest</a>
																							</li>
																							<li
																								class="dropdown-item ps-3 fs-7 fw-medium">
																								<a
																									href="?sort=first_name&order=asc">A
																									to Z (Ascending)</a>
																							</li>
																							<li
																								class="dropdown-item ps-3 fs-7 fw-medium">
																								<a
																									href="?sort=first_name&order=desc">Z
																									to A (Descending)</a>
																							</li>
																						</div>
																					</div>
																				</form>
																			</div>

																			<!-- Search -->
																			<div class="ps-3">
																				<i data-feather="search"
																					class="feather-xs im-cust-2"
																					style="color: black"></i>
																				<input class="search-input fs-7 spacing-6 fw-medium"
																					type="text" placeholder=" Search..."
																					id="searchInput" style="width: 100px">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<?php if ($verified_tbl->num_rows > 0) { ?>
																<!-- Table Contents -->
																<table class= "table table-striped table-hover" id="voterTable">
																	<thead class="tl-header">
																		<tr>
																			<th
																				class="col-md-3 tl-left text-center fs-7 fw-bold spacing-5">
																				<i data-feather="user"
																					class="feather-xs im-cust"></i>Full Name
																			</th>

																			<th class="col-md-3 text-center fs-7 fw-bold spacing-5">
																				<i data-feather="star"
																					class="feather-xs im-cust"></i>Candidacy Position
																			</th>
																			<th
																				class="col-md-3 tl-right text-center fs-7 fw-bold spacing-5">
																				<i data-feather="calendar"
																					class="feather-xs im-cust"></i>Date Created
																			</th>
																		</tr>
																	</thead>
																		<tbody>
																		<?php while ($row = $verified_tbl->fetch_assoc()) { ?>
																			<tr>
																				<td class="col-md-3 text-center">
																					<a href="candidate-details.php?candidate_id=<?php echo $row["candidate_id"]; ?>"><?php echo $row["first_name"] . ' ' . $row["middle_name"] . ' ' . $row["last_name"] . ' ' . $row["suffix"]; ?></a>
																				</td>
																				<td class="col-md-3 text-center">
																					<?php echo $row["position"]; ?>
																				</td>
																				<td class="col-md-3 text-center">
																					<?php echo date('F j, Y', strtotime($row['candidate-creation'])); ?>
																				</td>
																			</tr>
																		<?php } ?>
																	</tbody>
																</table>

																<!-- Pagination -->

																<div class="clearfix col-xs-12">
																	<ul class="pagination">
																		<?php
																		// Generate the filter parameters for the link
																		$filterParams = '';
																		if (!empty($filter)) {
																			foreach ($filter as $f) {
																				$filterParams .= '&filter[]=' . urlencode($f);
																			}
																		}

																		if ($current_page > 1) { ?>
																			<li class="fas fa-chevron-left black"><a
																					href="?page=<?php echo $current_page - 1 . $filterParams; ?>"></a>
																			</li>
																		<?php } ?>

																		<?php for ($i = 1; $i <= $total_pages; $i++) { ?>
																			<li
																				class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
																				<a href="?page=<?php echo $i . $filterParams; ?>"
																					class="page-link"><?php echo $i; ?></a>
																			</li>
																		<?php } ?>

																		<?php if ($current_page < $total_pages) { ?>
																			<li class="fas fa-chevron-right ps-xl-3 black"><a
																					href="?page=<?php echo $current_page + 1 . $filterParams; ?>"></a>
																			</li>
																		<?php } ?>
																	</ul>
																</div>


																<!-- If verified table is empty, show empty state -->
															<?php } else { ?>

																<div class="table-title">
																	<table class= "table table-striped table-hover" id="voterTable">
																		<thead class="tl-header">
																			<tr>
																				<th
																					class="col-md-3 tl-left text-center fs-7 fw-bold spacing-5">
																					<i data-feather="user"
																						class="feather-xs im-cust"></i>Full Name
																				</th>

																				<th class="col-md-3 text-center fs-7 fw-bold spacing-5">
																					<i data-feather="star"
																						class="feather-xs im-cust"></i>Candidacy Position
																				</th>
																				<th
																					class="col-md-3 tl-right text-center fs-7 fw-bold spacing-5">
																					<i data-feather="calendar"
																						class="feather-xs im-cust"></i>Date Created
																				</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td colspan="3" class="no-border">
																					<div class="col-md-12 no-registration text-center">
																						<img src="images/resc/folder-empty.png"
																							class="illus">
																						<p class="fw-bold spacing-6 black">No records
																							found</p>
																						<p class="spacing-3 pt-1 black fw-medium">Adjust
																							filter or try a different search term</p>
																					</div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																<?php } ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>

				<?php include_once __DIR__ . '/includes/components/footer.php'; ?>
				<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
				<script src="scripts/script.js"></script>
				<script src="scripts/feather.js"></script>




			</body>


			</html>

			<?php
		} else {
			header("Location: landing-page.php");
		}
	} else {
		header("Location: landing-page.php");
	}
	?>