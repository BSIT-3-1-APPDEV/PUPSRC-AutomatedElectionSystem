<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';
require_once 'includes/classes/session-manager.php';

if (isset($_SESSION['voter_id'])) {

	// ------ SESSION EXCHANGE
	include 'includes/session-exchange.php';
	// ------ END OF SESSION EXCHANGE

	$conn = DatabaseConnection::connect();
	$voter_query = "SELECT voter_id, email, acc_created FROM voter";
	$result = $conn->query($voter_query);
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
		<link rel="stylesheet" href="styles/manage-voters.css" />
		<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	</head>

	<body>

		<?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

		<div class="main">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-10 card-box">
						<div class="container-fluid">
							<div class="card-box">
								<div class="row">
									<div class="content">
										<div class="table-wrapper">
											<div class="table-title">
												<div class="row">
													<div class="col-sm-6">
														<p class="fs-3 main-color fw-semibold ls-10">Manage <span
																class="fw-bold">Accounts</span></p>
													</div>
													<div class="col-sm-6">
														<button type="button" class="btn btn-danger" data-bs-toggle="modal"
															data-bs-target="#deleteEmployeeModal">
															<span>Delete</span></a>
													</div>
												</div>
											</div>
											<table class=table table-striped table-hover">
												<thead>
													<tr>
														<th class="col-xs-3"></th>
														<span class="custom-checkbox">
															<input type="checkbox" id="selectAll">
															<label for="selectAll"></label>
														</span>
														</th>
														<th class="col-xs-6">Email</th>
														<th class="col-xs-3">Date</th>
													</tr>
												</thead>
												<tbody>
													<?php
													if ($result->num_rows > 0) {
														while ($row = $result->fetch_assoc()) {
															?>
															<tr>
																<td>
																	<span class="custom-checkbox">
																		<input type="checkbox"
																			id="checkbox<?php echo $row["voter_id"]; ?>"
																			name="options[]"
																			value="<?php echo $row["voter_id"]; ?>">
																		<label
																			for="checkbox<?php echo $row["voter_id"]; ?>"></label>
																	</span>
																</td>
																<td><a href="validate-voter.php?voter_id=<?php echo $row["voter_id"]; ?>"><?php echo $row["email"]; ?></a>
																</td>
																<td><?php echo $row["acc_created"]; ?></td>
															</tr>
															<?php
														}
													} else {
														?>
														<tr>
															<td colspan="3">No data found</td>
														</tr>
														<?php
													}
													?>

												</tbody>
											</table>
											<div class="clearfix col-xs-12">
												<ul class="pagination">
													<li class="fas fa-chevron-left"><a href="#"></a></li>
													<li class="page-item"><a href="#" class="page-link">1</a></li>
													<li class="page-item"><a href="#" class="page-link">2</a></li>
													<li class="page-item active main-color"><a href="#"
															class="page-link">3</a>
													</li>
													<li class="page-item"><a href="#" class="page-link">4</a></li>
													<li class="page-item"><a href="#" class="page-link">5</a></li>
													<li class="fas fa-chevron-right ps-xl-3"><a href="#"></a></li>
												</ul>
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


		<!-- Delete Modal HTML -->
		<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<form>
						<div class="modal-header">
							<h4 class="modal-title">Delete Account</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to delete these Records?</p>
							<p class="text-warning"><small>This action cannot be undone.</small></p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-danger">Delete</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script src="scripts/script.js"></script>
		<script src="scripts/feather.js"></script>


	</body>


	</html>

	<?php
} else {
	header("Location: landing-page.php");
}
?>