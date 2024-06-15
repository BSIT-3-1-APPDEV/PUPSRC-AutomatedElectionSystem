<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {



	include FileUtils::normalizeFilePath('includes/session-exchange.php');
	include FileUtils::normalizeFilePath('submission_handlers/manage-acc.php');
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
		<link rel="stylesheet" href="styles/manage-voters.css" />
		<link rel="stylesheet" href="styles/loader.css" />
		<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="scripts/loader.js"></script>

	</head>

	<body>

		<?php
		include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
		include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
		?>

		<div class="main">

			<div class="container mb-5 pl-5">
				<div class="row justify-content-center">
					<div class="col-md-11">
						<div class="breadcrumbs d-flex">
							<button type="button" class="btn btn-lvl-white d-flex align-items-center spacing-8 fs-8">
								<i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
							</button>
							<button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">VOTERS'
								ACCOUNTS</button>
						</div>
					</div>
				</div>
			</div>


			<div class="container">
				<div class="row justify-content-center">
					<!-- FOR VERIFICATION TABLE -->
					<div class="col-md-10 card-box">
						<div class="container-fluid">
							<div class="card-box">
								<div class="row">
									<div class="content">
										<div class="table-wrapper">

											<!-- For empty state: see first if the table has value -->
											<?php if ($to_verify_tbl->num_rows > 0) { ?>

												<div class="table-title">
													<div class="row pending-accs-table">
														<!-- HEADER -->
														<div class="col-sm-6">
															<p class="fs-3 main-color fw-bold ls-10 spacing-6">Pending
																Registrations</p>
														</div>

														<div class="col-sm-6">
															<div class="row">
																<div class="col-md-12 text-end flex-end">
																	<!-- Delete -->
																	<div class="d-inline-block">
																		<button
																			class="delete-btn pending-delete-btn fs-7 spacing-6 fw-medium"
																			type="button" id="dropdownMenuButton"
																			data-bs-toggle="dropdown" aria-haspopup="true"
																			aria-expanded="false">
																			<i class="fa-solid fa-trash-can fa-sm"></i>
																			Delete
																		</button>
																		<span class="light-gray-accent fw-bold ps-3">|</span>
																	</div>

																	<!-- Sort By -->
																	<div class="d-inline-block ps-3">
																		<form class="d-inline-block">
																			<div class="dropdown sort-by">
																				<button
																					class="sortby-tbn fs-7 spacing-6 fw-medium"
																					type="button" id="dropdownMenuButtonPending"
																					data-bs-toggle="dropdown"
																					aria-haspopup="true" aria-expanded="false">
																					<i
																						class="fa-solid fa-arrow-down-wide-short fa-sm"></i>
																					Sort by
																				</button>
																				<div class="dropdown-menu dropdown-menu-end"
																					aria-labelledby="dropdownMenuButtonPending"
																					style="padding: 0.5rem">
																					<li class="dropdown-item ps-3 fs-7 fw-medium"
																						data-sort="newest">Newest to Oldest</li>
																					<li class="dropdown-item ps-3 fs-7 fw-medium"
																						data-sort="oldest">Oldest to Newest</li>
																					<li class="dropdown-item ps-3 fs-7 fw-medium"
																						data-sort="asc">A to Z (Ascending)</li>
																					<li class="dropdown-item ps-3 fs-7 fw-medium"
																						data-sort="desc">Z to A (Descending)
																					</li>
																				</div>
																			</div>
																		</form>
																	</div>

																	<!-- Search -->
																	<div class="ps-3">
																		<i data-feather="search" class="feather-xs im-cust-2"
																			style="color: black"></i>
																		<input class="search-input fs-7 spacing-6 fw-medium"
																			type="text" placeholder=" Search..."
																			id="searchPending" style="width: 100px">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<table class="table" id="pendingTable">
														<thead class="tl-header pending-accs-table">
															<tr>
																<th
																	class="col-md-3 text-center tl-left d-none checkbox-all-pending">
																	<input type="checkbox" id="selectAllPending">
																</th>
																<th
																	class="col-md-6 tl-left text-center del-center fs-7 fw-bold spacing-5">
																	<i data-feather="mail" class="feather-xs im-cust"></i>Email
																	Address
																</th>
																<th
																	class="col-md-6 tl-right text-center fs-7 fw-bold spacing-5">
																	<i data-feather="calendar"
																		class="feather-xs im-cust"></i>Date Registered
																</th>
															</tr>
														</thead>
														<tbody>
															<?php while ($row = $to_verify_tbl->fetch_assoc()) { ?>
																<!-- Generated in table-funcs.js -->

															<?php } ?>
														</tbody>
													</table>
													<div class="clearfix col-xs-12">

														<ul class="pagination" id="pagination">
															<!-- For Verification pagination will be generated here -->
														</ul>

														<div class="d-flex justify-content-start pt-2">
															<button id="deleteSelectedPending"
																class="btn btn-danger px-sm-4 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 final-delete-btn-pending d-none rounded-3"
																disabled>Delete
																Selected</button>

															<button
																class="btn btn-light btn-cancel px-sm-4 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 rounded-3 cancel-pending d-none">Cancel</button>
														</div>
													</div>

													<!-- If empty, show empty state -->
												<?php } else { ?>

													<div class="table-title">
														<div class="row">
															<!-- HEADER -->
															<div class="col-sm-12">
																<p class="fs-3 main-color fw-bold ls-10 spacing-6">Pending
																	Registrations</p>
															</div>
														</div>
														<div class="col-md-12 no-registration text-center">
															<img src="images/resc/folder-empty.png" class="illus">
															<p class="fw-bold spacing-6 black">No registrations yet</p>
															<p class="spacing-3 pt-1 black">You’ll find account registrations
																right
																here!
															</p>
														</div>
													<?php } ?>

												</div>

											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- VERIFIED TABLE -->
					<div class="row justify-content-center">
						<div class="col-md-10 card-box  mt-md-5">
							<div class="container-fluid">
								<div class="card-box">
									<div class="row">
										<div class="content">
											<div class="table-wrapper">
												<?php if ($verified_tbl->num_rows > 0) { ?>
													<div class="table-title">
														<div class="row verified-accs-table">
															<!-- Table Header -->
															<div class="col-sm-6">
																<p class="fs-3 main-color fw-bold ls-10 spacing-6">Voters'
																	Accounts</p>
															</div>
															<div class="col-sm-6">
																<div class="row">


																	<div class="col-md-12 text-end flex-end">
																		<!-- Delete -->
																		<div class="d-inline-block">
																			<button
																				class="delete-btn verified-delete-btn fs-7 spacing-6 fw-medium"
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

																		<!-- Sort By -->
																		<div class="d-inline-block ps-3">
																			<form class="d-inline-block">
																				<div class="dropdown sort-by">
																					<button
																						class="sortby-tbn fs-7 spacing-6 fw-medium"
																						type="button"
																						id="dropdownMenuButtonVerified"
																						data-bs-toggle="dropdown"
																						aria-haspopup="true"
																						aria-expanded="false">
																						<i
																							class="fa-solid fa-arrow-down-wide-short fa-sm"></i>
																						Sort by
																					</button>
																					<div class="dropdown-menu dropdown-menu-end"
																						aria-labelledby="dropdownMenuButtonVerified"
																						style="padding: 0.5rem">
																						<li class="dropdown-item ps-3 fs-7 fw-medium"
																							data-sort="newest">Newest to Oldest
																						</li>
																						<li class="dropdown-item ps-3 fs-7 fw-medium"
																							data-sort="oldest">Oldest to Newest
																						</li>
																						<li class="dropdown-item ps-3 fs-7 fw-medium"
																							data-sort="asc">A to Z (Ascending)
																						</li>
																						<li class="dropdown-item ps-3 fs-7 fw-medium"
																							data-sort="desc">Z to A (Descending)
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
																				id="searchVerified" style="width: 100px">
																		</div>
																	</div>

																</div>
															</div>
														</div>

														<!-- Table Contents -->
														<table class="table" id="verifiedTable">
															<thead class="tl-header verified-accs-table">
																<tr>
																	<th
																		class="col-md-3 tl-left d-none checkbox-all-verified text-center">
																		<input type="checkbox" id="selectAllVerified">
																	</th>
																	<th
																		class="col-md-3 del-center tl-left text-center fs-7 fw-bold spacing-5">
																		<i data-feather="mail"
																			class="feather-xs im-cust"></i>Email
																		Address
																	</th>

																	<th class="col-md-3 text-center fs-7 fw-bold spacing-5">
																		<i data-feather="check-circle"
																			class="feather-xs im-cust"></i>Status
																	</th>
																	<th
																		class="col-md-3 tl-right text-center fs-7 fw-bold spacing-5">
																		<i data-feather="calendar"
																			class="feather-xs im-cust"></i>Date Verified
																	</th>
																</tr>
															</thead>
															<tbody>
																<?php while ($row = $verified_tbl->fetch_assoc()) { ?>

																	<!-- Generated in table-funcs.js -->
																<?php } ?>
															</tbody>
														</table>

														<!-- Pagination -->
														<div class="clearfix col-xs-12">
															<ul class="pagination" id="verified-pagination">
																<!-- Verification pagination will be generated here -->
															</ul>

															<div class="d-flex justify-content-start pt-2">
																<button id="deleteSelectedVerified"
																	class="btn btn-danger px-sm-4 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 final-delete-btn-verified d-none rounded-3"
																	type="button" disabled>Delete
																	Selected</button>
																<button
																	class="btn btn-light btn-cancel px-sm-4 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 rounded-3 cancel-verified d-none">Cancel</button>
															</div>
														</div>


														<!-- If verified table is empty, show empty state -->
													<?php } else { ?>

														<div class="table-title">
															<div class="row">
																<!-- HEADER -->
																<div class="col-sm-12">
																	<p class="fs-3 main-color fw-bold ls-10 spacing-6">Voters'
																		Account</p>
																</div>
															</div>
															<div class="col-md-12 no-registration text-center">
																<img src="images/resc/folder-empty.png" class="illus">
																<p class="fw-bold spacing-6 black">No accounts yet</p>
																<p class="spacing-3 pt-1 black fw-medium">Why not verify the
																	pending
																	registrations above?
																</p>
															</div>
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

				<?php include_once __DIR__ . '/includes/components/footer.php'; ?>


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
												<button
													class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
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
										<button class="btn btn-success px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
											onClick="redirectToPage('trashbin.php')" aria-label="Close">Go To Recycle
											Bin</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
				<script src="scripts/script.js"></script>
				<script src="scripts/feather.js"></script>
				<script src="scripts/table-funcs.js"></script>
	</body>


	</html>

	<?php
} else {
	header("Location: landing-page.php");
}
?>