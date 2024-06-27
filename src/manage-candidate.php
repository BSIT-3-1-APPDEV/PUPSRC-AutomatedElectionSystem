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
				<link rel="stylesheet" href="styles/candidate-table.css" />
				<link rel="stylesheet" href="styles/loader.css" />
				<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
				<script src="scripts/loader.js" defer></script>


			</head>

			<body>


				<?php
				include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
				include FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
				?>

				<div class="main">

					<div class="container mb-5 pl-5">
						<div class="row justify-content-center">
							<div class="col-md-11">
								<div class="breadcrumbs d-flex">
									<button type="button" class="btn btn-lvl-white d-flex align-items-center spacing-8 fs-8">
										<i data-feather="users" class="white im-cust feather-2xl"></i> CANDIDATES
									</button>
									<button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">MANAGE CANDIDATES</button>
									<div class="align-items-end ms-auto me-4 mx-a">
										<a href="add-candidate.php">
											<button type="button" class="button-add rounded-2 fs-7">
												<i class="bi bi-plus-circle me-3"></i>Add New Candidate
											</button>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="container">
						<div class="row justify-content-center">
							<!-- CANDIDATES TABLE -->
							<div class="row justify-content-center mx-auto">
								<div class="col-md-10 card-box mt-md-10">
									<div class="container-fluid">
										<div class="card-box">
											<div class="row">
												<div class="content">
													<div class="table-wrapper">

														<div class="table-title">
															<div class="row">
																<!-- Table Header -->
																<div class="col-sm-6">
																	<p class="fs-3 main-color fw-bold ls-10 spacing-6 mx-auto">Manage Candidates</p>
																</div>
																<div class="col-sm-6">
																	<div class="row">
																		<div class="col-md-12 text-end flex-end">
																			<!-- Delete -->
																			<div class="d-inline-block mx-auto">
																				<button class="delete-btn fs-7 spacing-3 fw-medium" type="button" id="deleteBtn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					<i class="fa-solid fa-trash-can fa-sm"></i>
																					Delete
																				</button>
																			</div>
																			
																			<span class="light-gray-accent fw-bold ps-3 mx-auto">|</span>

																			<!-- Filters -->
																			
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

																			<!-- Sort By -->
																			<div class="d-inline-block ps-3 mx-auto">
																				<form class="d-inline-block">
																					<div class="dropdown sort-by">
																						<button class="sortby-tbn fs-7 spacing-6 fw-medium" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																							<i class="fa-solid fa-arrow-down-wide-short fa-sm"></i>
																							Sort by
																						</button>
																						<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="padding: 0.5rem">
																							<!-- Dropdown items -->
																							<li class="dropdown-item ps-3 fs-7 fw-medium">
																								<a href="?sort=acc_created&order=desc">Newest to Oldest</a>
																							</li>
																							<li class="dropdown-item ps-3 fs-7 fw-medium">
																								<a href="?sort=acc_created&order=asc">Oldest to Newest</a>
																							</li>
																							<li class="dropdown-item ps-3 fs-7 fw-medium">
																								<a href="?sort=first_name&order=asc">A to Z (Ascending)</a>
																							</li>
																							<li class="dropdown-item ps-3 fs-7 fw-medium">
																								<a href="?sort=first_name&order=desc">Z to A (Descending)</a>
																							</li>
																						</div>
																					</div>
																				</form>
																			</div>

																			<!-- Search -->
																			<div class="search-container ps-3 mx-auto">
																				<input class="search-input fs-7 spacing-6 fw-medium" type="text" name="search" placeholder="    Search" id="searchInput" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" onkeyup="searchFunction()">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<?php if ($verified_tbl->num_rows > 0) { ?>
																<!-- Table Contents -->
																<table class="table table-hover" id="voterTable">
																	<thead class="tl-header">
																		<tr>
																			<th class="col-md-1 text-center fs-7 fw-bold spacing-5 checkbox-th">
																				<?php if (isset($row) && is_array($row) && array_key_exists("candidate_id", $row)) : ?>
																					<input type="checkbox" name="selectedVoters[]" value="<?php echo $row["candidate_id"]; ?>" class="voterCheckbox" style="display: none;">
																				<?php endif; ?>
																			</th>
																			<th class="col-md-3 tl-left text-center fs-7 fw-bold spacing-5">
																				<i data-feather="user" class="feather-xs im-cust"></i>Full Name
																			</th>

																			<th class="col-md-3 text-center fs-7 fw-bold spacing-5">
																				<i data-feather="star" class="feather-xs im-cust"></i>Candidacy Position
																			</th>
																			<th class="col-md-3 tl-right text-center fs-7 fw-bold spacing-5">
																				<i data-feather="calendar" class="feather-xs im-cust"></i>Date Created
																			</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php while ($row = $verified_tbl->fetch_assoc()) { ?>
																			<tr>
																				<td class="col-md-1 text-center checkbox-td">
																					<input type="checkbox" name="selectedVoters[]" value="<?php echo $row["candidate_id"]; ?>" class="voterCheckbox" style="display: none;">
																				</td>
																				<td class="col-md-3 text-center">
																					<a href="candidate-details.php?candidate_id=<?php echo $row["candidate_id"]; ?>"><?php echo $row["first_name"] . ' ' . $row["middle_name"] . ' ' . $row["last_name"] . ' ' . $row["suffix"]; ?></a>
																				</td>
																				<td class="col-md-3 text-center">
																					<?php echo $row["position"]; ?>
																				</td>
																				<td class="col-md-3 text-center">
																					<?php echo date('F j, Y', strtotime($row['candidate_creation'])); ?>
																				</td>
																			</tr>
																		<?php } ?>
																	</tbody>
																</table>

																<!-- Pagination -->

																<div class="clearfix col-xs-12">
																	<div class="d-flex justify-content-end align-items-center">
																		<div class="delete-actions me-auto" style="display: none;">
																			<button class="btn btn-light px-sm-3 py-sm-1-5 btn-sm fw-bold fs-7 spacing-6" id="cancelBtn" disabled>Cancel</button>
																			<button class="btn btn-danger px-sm-3 py-sm-1-5 btn-sm fw-bold fs-7 spacing-6" id="deleteSelectedBtn" disabled>Delete Selected</button>
																		</div>
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
																				<li class="fas fa-chevron-left black"><a href="?page=<?php echo $current_page - 1 . $filterParams; ?>"></a></li>
																			<?php } ?>

																			<?php for ($i = 1; $i <= $total_pages; $i++) { ?>
																				<li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
																					<a href="?page=<?php echo $i . $filterParams; ?>" class="page-link"><?php echo $i; ?></a>
																				</li>
																			<?php } ?>

																			<?php if ($current_page < $total_pages) { ?>
																				<li class="fas fa-chevron-right ps-xl-3 black"><a href="?page=<?php echo $current_page + 1 . $filterParams; ?>"></a></li>
																			<?php } ?>
																		</ul>
																	</div>
																</div>

																<!-- If verified table is empty, show empty state -->
															<?php } else { ?>

																<div class="table-title">
																	<table class="table table-hover" id="voterTable">
																		<thead class="tl-header">
																			<tr>
																				<th class="col-md-1 text-center fs-7 fw-bold spacing-5 checkbox-th">
																					<?php if (isset($row) && is_array($row) && array_key_exists("candidate_id", $row)) : ?>
																						<input type="checkbox" name="selectedVoters[]" value="<?php echo $row["candidate_id"]; ?>" class="voterCheckbox" style="display: none;">
																					<?php endif; ?>
																				</th>
																				<th class="col-md-3 tl-left text-center fs-7 fw-bold spacing-5">
																					<i data-feather="user" class="feather-xs im-cust"></i>Full Name
																				</th>
																				<th class="col-md-3 text-center fs-7 fw-bold spacing-5">
																					<i data-feather="star" class="feather-xs im-cust"></i>Candidacy Position
																				</th>
																				<th class="col-md-3 tl-right text-center fs-7 fw-bold spacing-5">
																					<i data-feather="calendar" class="feather-xs im-cust"></i>Date Created
																				</th>
																			</tr>
																		</thead>
																		<tbody class = "fs-7">
																			<tr>
																				<td colspan="3" class="no-border">
																					<div class="col-md-12 no-registration text-center">
																						<img src="images/resc/folder-empty.png" class="illus">
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

				<!-- Delete Confirmation Modal -->
				<div class="modal-overlay"></div>
				<!-- delete Modal -->
				<div class="modal" id="rejectModal" tabindex="-1" role="dialog">
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
													<p class="pt-2 fw-medium spacing-5">The account(s) will be deleted and moved to Recycle Bin.
														Are you sure you want to delete?
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-12 pt-3 text-center">
										<div class="d-inline-block">
											<button class="btn btn-light px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" onClick="closeModal()" aria-label="Close">Cancel</button>
										</div>
										<div class="d-inline-block">
											<form class="d-inline-block">
												<button class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" type="submit" id="confirm-delete" value="delete">Delete</button>
											</form>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Rejected Successfully Modal -->
				<div class="modal" id="deleteDone" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="d-flex justify-content-end">
									<i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray" onclick="redirectToPage('manage-committee.php')">
									</i>
								</div>
								<div class="text-center p-4">
									<div class="row">
										<div class="col-md-12">
											<p class="fw-bold fs-3 successs spacing-4">Deleted Successsfully</p>
											<p class="fw-medium spacing-5">The deleted account have been moved to the Recycle Bin.
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<script>
					document.getElementById('deleteSelectedBtn').addEventListener('click', function() {
						var selected = [];
						document.querySelectorAll('.voterCheckbox:checked').forEach(function(checkbox) {
							selected.push(checkbox.value);
						});
						if (selected.length > 0) {
							$('#deleteConfirmationModal').modal('show');
						}
					});

					document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
						// Perform the delete action
						$('#deleteConfirmationModal').modal('hide');
					});

					function searchFunction() {
						var input, filter, table, tr, td, i, j, txtValue;
						input = document.getElementById("searchInput");
						filter = input.value.toLowerCase();
						table = document.getElementById("voterTable");
						tr = table.getElementsByTagName("tr");
						for (i = 1; i < tr.length; i++) {
							tr[i].style.display = "none";
							td = tr[i].getElementsByTagName("td");
							for (j = 0; j < td.length; j++) {
								if (td[j]) {
									txtValue = td[j].textContent || td[j].innerText;
									if (txtValue.toLowerCase().indexOf(filter) > -1) {
										tr[i].style.display = "";
										break;
									}
								}
							}
						}
					}
				</script>

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
	} else {
		header("Location: landing-page.php");
	}
	?>