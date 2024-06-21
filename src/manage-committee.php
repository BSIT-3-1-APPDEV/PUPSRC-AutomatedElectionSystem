<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');


if (isset($_SESSION['voter_id'])) {

	include FileUtils::normalizeFilePath('includes/session-exchange.php');

	// Check if the user's role is either 'admin' or 'head_admin'
	$allowedRoles = array('admin', 'head_admin');
	if (!in_array($_SESSION['role'], $allowedRoles)) {
		header("Location: landing-page.php");
		exit();
	}

	include FileUtils::normalizeFilePath('submission_handlers/manage-members.php');
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
		<link rel="stylesheet" href="styles/loader.css" />
		<link rel="stylesheet" href="styles/manage-committee.css" />
		<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="scripts/loader.js" defer></script>
	</head>

	<body>

		<?php
		include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
		include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
		?>

		<div class="main">

			<div class="container mb-5 pl-5">
				<div class="row justify-content-center">
					<div class="col-md-11">
						<div class="breadcrumbs d-flex justify-content-between">
							<div class="d-flex">
								<button type="button" class="btn btn-lvl-white d-flex align-items-center spacing-8 fs-8">
									<i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
								</button>
								<button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">COMMITTEE
									MEMBERS</button>
							</div>

							<div class="ml-auto">
								<a href="admin-creation.php">
									<button type="button" class="btn btn-lvl-white-add align-items-center spacing-8 fs-8">
										<i data-feather="plus-circle" class="white im-cust rounded-pill feather-2xl"></i>
										Add Committee Member
									</button>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<br>
			<div class="container">
				<div class="row justify-content-center">
					<!-- COMMITTEE TABLE -->
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
															<p class="fs-3 main-color fw-bold ls-10 spacing-6">Committee
																Members</p>
														</div>
														<div class="col-sm-6">
															<div class="row">
																<div class="col-md-12 text-end flex-end">
																	<!-- Delete -->
																	<div class="d-inline-block">
																		<button
																			class="delete-btn admin-delete-btn fs-7 spacing-6 fw-medium"
																			type="button" id="dropdownMenuButton"
																			data-bs-toggle="dropdown" aria-haspopup="true"
																			aria-expanded="false">
																			<i class="fa-solid fa-trash-can fa-sm"></i>
																			Delete
																		</button>
																		<span
																			class="light-gray-accent fw-bold ps-3">|</span>
																	</div>
																	<!-- Filters -->
																	<div class="d-inline-block ps-3">
																		<form id="filterForm" class="d-inline-block">
																			<div class="dropdown sort-by">
																				<button
																					class="sortby-tbn fs-7 spacing-6 fw-medium"
																					type="button" id="dropdownMenuButton"
																					data-bs-toggle="dropdown"
																					aria-haspopup="true"
																					aria-expanded="false">
																					<i data-feather="filter"
																						class="feather-xs im-cust-2"></i>
																					Filter
																				</button>
																				<div class="dropdown-menu dropdown-menu-end"
																					aria-labelledby="dropdownMenuButton"
																					style="padding: 0.5rem">
																					<li
																						class="dropdown-item ps-3 fs-7 fw-medium">
																						<label>
																							<input type="checkbox" class="filter-checkbox"
																								name="filter[]"
																								value="admin"
																								id="filterAdmin"> 
																								<span class="filter-custom-checkbox"></span>
																								Admin
																						</label>
																					</li>
																					<li
																						class="dropdown-item ps-3 fs-7 fw-medium">
																						<label>
																							<input type="checkbox" class="filter-checkbox"
																								name="filter[]"
																								value="head_admin"
																								id="filterHeadAdmin"> 
																								<span class="filter-custom-checkbox"></span>
																								Head Admin
																						</label>
																					</li>
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
																						<a href="#" data-sort="acc_created"
																							data-order="desc"
																							class="sort-link">Newest to
																							Oldest</a>
																					</li>
																					<li
																						class="dropdown-item ps-3 fs-7 fw-medium">
																						<a href="#" data-sort="acc_created"
																							data-order="asc"
																							class="sort-link">Oldest to
																							Newest</a>
																					</li>
																					<li
																						class="dropdown-item ps-3 fs-7 fw-medium">
																						<a href="#" data-sort="first_name"
																							data-order="asc"
																							class="sort-link">A to Z
																							(Ascending)</a>
																					</li>
																					<li
																						class="dropdown-item ps-3 fs-7 fw-medium">
																						<a href="#" data-sort="first_name"
																							data-order="desc"
																							class="sort-link">Z to A
																							(Descending)</a>
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
														<table class="table" id="adminTable">
															<thead class="tl-header">
																<tr>
																	<th
																		class="col-md-3 tl-left d-none checkbox-all-admin text-center">
																		<input type="checkbox" id="selectAllAdmin">
																	</th>
																	<th class="col-md-4 text-center fs-7 fw-bold spacing-5">
																		<i data-feather="user"
																			class="feather-xs im-cust"></i>Full Name
																	</th>
																	<th class="col-md-3 text-center fs-7 fw-bold spacing-5">
																		<i data-feather="star"
																			class="feather-xs im-cust"></i>Member Role
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
																	<!-- Generated in manage-committee.js -->
																<?php } ?>
															</tbody>
														</table>

														<!-- Pagination -->

														<div class="clearfix col-xs-12">
															<ul class="pagination" id="committeePagination">
																<!-- Verification pagination will be generated here -->
															</ul>

															<div class="d-flex justify-content-start pt-2">
																<button id="deleteSelectedAdmin"
																	class="btn btn-danger px-sm-4 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 final-delete-btn-admin d-none rounded-3"
																	type="button" disabled>Delete
																	Selected</button>
																<button
																	class="btn btn-light btn-cancel px-sm-4 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 rounded-3 cancel-admin d-none">Cancel</button>
															</div>
														</div>


														<!-- If admin table is empty, show empty state -->
													<?php } else { ?>

														<div class="table-title">
															<table class="table" id="adminTable">
																<thead class="tl-header">
																	<tr>
																		<th
																			class="col-md-4 tl-left text-center fs-7 fw-bold spacing-5">
																			<i data-feather="user"
																				class="feather-xs im-cust"></i>Full Name
																		</th>

																		<th
																			class="col-md-3 del-center tl-left text-center fs-7 fw-bold spacing-5">
																			<i data-feather="star"
																				class="feather-xs im-cust"></i>Committee Role
																		</th>
																		<th
																			class="col-md-3 del-center tl-left text-center fs-7 fw-bold spacing-5">
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
		<!-- Move To Trashbin Modal -->
		<div class="modal" id="rejectModal" data-bs-keyboard="false" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body">

						<div class="row p-4">
							<div class="col-md-12 pb-3">
								<div class="text-center">
									<div class="col-md-12 p-3">
										<img src="images/resc/warning.png" alt="iVote Logo">
									</div>

									<div class="row">
										<div class="col-md-12 pb-3 confirm-delete">
											<p class="fw-bold fs-3 danger spacing-4">Confirm Delete?</p>
											<p class="pt-2 fs-7 fw-medium spacing-5">The account will be deleted and
												moved
												to <span class="fw-bold">Recycle Bin</span>.</p>
											<p class="fw-medium spacing-5 pt-1 fs-7">Are you sure you want to
												delete?</p>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 pt-1 text-center">
								<div class="d-inline-block">
									<button class="btn btn-light px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
										onClick="closeModal('rejectModal')" aria-label="Close">Cancel</button>
								</div>
								<div class="d-inline-block">
									<form class="d-inline-block">
										<input type="hidden" id="voter_id" name="voter_id" value="">
										<button class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
											type="button" id="confirm-move" value="delete">Delete</button>
									</form>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Successfully Moved to Trashbin Modal -->
		<div class="modal" id="trashbinMoveDone" data-bs-keyboard="false" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body pb-5">
						<div class="d-flex justify-content-end">
							<i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray"
								onclick="closeModal('trashbinMoveDone')">
							</i>
						</div>
						<div class="text-center">
							<div class="col-md-12">
								<img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
							</div>

							<div class="row">
								<div class="col-md-12 pb-3">
									<p class="fw-bold fs-3 success-color spacing-4">Deleted successfully</p>
									<p class="fw-medium spacing-5 fs-7">The deleted account has been moved to <span
											class="fw-bold">Recycle Bin</span>.
									</p>
								</div>
							</div>

							<div class="col-md-12 pt-1 d-flex justify-content-center">
                                <button class="btn btn-success px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" aria-label="Close">
                                <a href="recycle-bin.php" style="color: white">Go To Recycle
                                Bin</a></button>
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
		<script src="scripts/manage-committee.js"></script>


	</body>


	</html>

	<?php

} else {
	header("Location: landing-page.php");
}
?>