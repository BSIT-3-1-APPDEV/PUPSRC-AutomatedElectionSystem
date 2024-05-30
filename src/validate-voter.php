<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

	$voter_id = $_GET['voter_id'];

	// ------ SESSION EXCHANGE
	include 'includes/session-exchange.php';
	// ------ END OF SESSION EXCHANGE

	$conn = DatabaseConnection::connect();
	$voter_query = "SELECT * FROM voter WHERE voter_id = $voter_id";
	$result = $conn->query($voter_query);
	$row = $result->fetch_assoc();

	if ($row['account_status'] != 'verified' && $row['account_status'] != 'invalid') {
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
		<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

		<!-- Styles -->
		<link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
		<link rel="stylesheet" href="styles/style.css" />
		<link rel="stylesheet" href="styles/core.css" />
		<link rel="stylesheet" href="styles/manage-voters.css" />
		<link rel="stylesheet" href="styles/validate-voter.css" />
		<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

	</head>

	<body>

		<?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

		<div class="main">
			<!-- Breadcrumbs -->
			<div class="container navigation">
				<div class="row justify-content-center mb-5 ml-10">
					<div class="col-md-11">
						<div class="breadcrumbs d-flex">
							<button type="button" class=" btn-white d-flex align-items-center spacing-8 fs-8">
								<i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
							</button>
							<button type="button" class="btn-back spacing-8 fs-8"
								onclick="redirectToPage('manage-voters.php')">VOTERS' ACCOUNTS</button>
							<button type="button" class="btn btn-current rounded-pill spacing-8 fs-8">VALIDATE
								ACCOUNT</button>
						</div>
					</div>
				</div>
			</div>

			<div class="container-wrapper">
				<div class="container mt-xl-3">
					<div class="container-fluid">
						<div class="row justify-content-center">
							<div class="col-md-11">
								<div class="card-box manage-voters">
									<div class="row information">

										<!-- FIRST COLUMN -->
										<div class="col-md-7 p-sm-5">
											<!-- Header of Left Column -->
											<div class="row">
												<!-- COR Name -->
												<div class="col-6 d-flex flex-row">
													<p class="fw-bold fs-7">
														<i class="fas fa-paperclip fa-sm"></i>
														<span class="ps-sm-1 spacing-5"><?php echo $row["cor"] ?></span>
													</p>
												</div>
												<!-- Download + Full Screen Name -->
												<div class="col-6 d-flex flex-row-reverse">
													<div class="row funcs">
														<div class="col-9">
															<!-- Download -->
															<a href="<?php echo "user_data/$org_name/cor/" . $row['cor']; ?>"
																download>
																<p class="fs-7 d-flex align-items-center">
																	<i data-feather="download" class="feather-sm"></i>
																	<span
																		class="ps-sm-2 spacing-5 fw-medium">Download</span>
																</p>
															</a>
														</div>
														<div class="col-1">
															<!-- Full Screen -->
															<div class="fullscreen-icon">
																<i class="fa-solid fa-expand fa-sm"></i>
															</div>
														</div>
													</div>
												</div>
											</div>

											<!-- PDF Container -->
											<div class="d-flex justify-content-center" style="height: 50vh;">
												<iframe id="pdfViewer"
													src="<?php echo "user_data/$org_name/cor/" . $row['cor']; ?>"
													width="100%" height="100%" frameborder="0" class="cor"></iframe>
											</div>
										</div>
										<!-- SECOND COLUMN -->
										<div class="col-md-5 p-sm-5">
											<!-- Header -->
											<section>
												<div class="row">
													<div class="col-md-12 text-center">
														<!-- Title -->
														<p class="fw-bold fs-3 main-color spacing-4 title">Validate Account
														</p>
														</p>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12 d-flex justify-content-center">
														<!-- Divider -->
														<div class="text-center horizontal-line"></div>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12 pt-sm-4">
														<!-- Description -->
														<p class="fw-medium fs-7 spacing-6 sub-title">Please review the
															provided
															information before validating the account registration.</p>
													</div>
												</div>
											</section>

											<!-- Student Information -->
											<section>
												<div class="row pt-sm-4">
													<div class="col-md-12">
														<!-- Email -->
														<p class="fw-bold fs-6 main-color spacing-4">Email Address</p>
														<p class="fw-medium fs-6 pt-sm-2 text-truncate">
															<?php echo $row["email"] ?>
														</p>
													</div>
												</div>

												<div class="row pt-sm-4">
													<div class="col-md-12">
														<!-- Status -->
														<p class="fw-bold fs-6 main-color spacing-4">Status</p>
														<p class="fw-medium fs-6 pt-sm-2">
															<?php
															if ($row["account_status"] === 'for_verification') {
																echo 'For Verification';
															} else {
																echo ucfirst($row["account_status"]); // Capitalize the first letter of the status
															}
															?>
														</p>
														</p>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12 pt-sm-4">
														<!-- Date -->
														<p class="fw-bold fs-6 main-color spacing-4">Date Registered</p>
														<p class="fw-medium fs-6 pt-sm-2">
															<?php
															$date = new DateTime($row["acc_created"]);
															echo $date->format('F j, Y');
															?>
														</p>
													</div>
												</div>
											</section>
											<!-- Buttons -->
											<section>
												<div class="row pt-sm-5 buttons-cont">
													<div class="col-6 text-end buttons">
														<button class="btn btn-danger px-5 btn-sm fw-bold fs-6 spacing-6"
															id="reject-btn" data-toggle="modal"
															data-target="#rejectModal">Reject</button>
													</div>

													<div class="col-6 text-start buttons">
														<form id="validateAcc">
															<input type="hidden" id="voter_id" name="voter_id"
																value="<?php echo $voter_id; ?>">
															<button
																class="btn btn-success px-5 btn-sm px-2 fw-bold fs-6 spacing-6"
																type="submit" id="approve" value="approve">Approve</button>
														</form>
													</div>
												</div>
											</section>
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

		<!-- Approval Modal -->
		<div class="modal" id="approvalModal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="d-flex justify-content-end">
							<i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray"
								onclick="redirectToPage('voter-details.php?voter_id=<?php echo htmlspecialchars($row["voter_id"]); ?>')">
							</i>
						</div>
						<div class="text-center">
							<div class="col-md-12">
								<img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
							</div>

							<div class="row">
								<div class="col-md-12 pb-3">
									<p class="fw-bold fs-3 success-color spacing-4">Account Approved!</p>
									<p class="fw-medium spacing-5">An email confirming the account approval has been
										sent.
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Confirm Reject Modal -->
		<div class="modal" id="rejectModal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row p-4">
							<div class="col-md-12 pb-3">
								<p class="fw-bold fs-3 danger spacing-4">Reason for rejection</p>
								<form id="rejectForm" action="#" method="post">
									<input type="radio" id="reason1" name="reason" value="reason1">
									<label for="reason1" class="pt-2 fw-medium">Student is not part of the
										organization</label><br>

									<input type="radio" id="reason2" name="reason" value="reason2">
									<label for="reason2" class="pt-2 fw-medium">The PDF is low quality and
										illegible</label><br>

									<input type="radio" id="others" name="reason" value="others">
									<label for="others" class="pt-2 fw-medium">Others (Please specify)</label><br>

									<div id="otherReason" style="display: none;">
										<textarea class="form-control bg-primary my-3 text-black" id="other"
											name="otherReason" rows="3" maxlength="200"></textarea>
										<p class="fs-7">Note: Only up to 200 characters are allowed</p>
									</div>
									<script>
										document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
											radio.addEventListener('change', function () {
												if (this.value === 'others' && this.checked) {
													document.getElementById('otherReason').style.display = 'block';
												} else {
													document.getElementById('otherReason').style.display = 'none';
												}
											});
										});
									</script>
									<div class="col-md-12 pt-3 text-end">
										<div class="d-inline-block">
											<button class="btn btn-light px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
												onClick="closeModal()" aria-label="Close">Cancel</button>
										</div>
										<div class="d-inline-block">
											<input type="hidden" id="voter_id" name="voter_id"
												value="<?php echo $voter_id; ?>">
											<button class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
												type="submit" id="send-reject">Reject</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- Rejected Successfully Modal -->
		<div class="modal" id="rejectDone" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="d-flex justify-content-end">
							<i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray"
								onclick="redirectToPage('manage-voters.php')">
							</i>
						</div>
						<div class="text-center p-4">
							<div class="row">
								<div class="col-md-12">
									<p class="fw-bold fs-3 danger spacing-4">Registration Rejected</p>
									<p class="fw-medium spacing-5">An email regarding the rejection of the account
										registration has been sent.
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
		<script src="scripts/script.js"></script>
		<script src="scripts/manage-voters.js"></script>
		<script src="scripts/feather.js"></script>


	</body>


	</html>

	<?php

} else {
	header("Location: manage-voters.php");
}
} else {
	header("Location: landing-page.php");
}
?>