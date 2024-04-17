<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/Path.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/page-head-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/user.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/page-router.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/db-config.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/db-connector.php');

$_SESSION['organization'] = "jpia";
$_SESSION['user'] = "user";

$user = new User(1, 'Admin', 'jpia', 'Doe', 'John', 'Michael', 'Jr.', '12', 'A', 'john.doe@example.com', 'Active', 'Voted');

$_SESSION['user'] = $user;
$org_name = $_SESSION['organization'];

if (!(isset($_SESSION['user']) && $_SESSION['user']->getUserType() === 'Admin')) {
    die;
}

echo "
<style>
    :root{
        --primary-color: var(--{$org_name});
    }
</style>
";

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
	<link rel="stylesheet" href="styles/<?php echo $org_name; ?>.css">
	<link rel="stylesheet" href="styles/style.css" />
	<link rel="stylesheet" href="styles/manage-acc.css" />
	<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

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
													<h2>Manage <b>Accounts</b></h2>
												</div>
												<div class="col-sm-6">
													<button type="button" class="btn btn-danger" data-bs-toggle="modal"
														data-bs-target="#deleteEmployeeModal">
														<span>Delete</span></a>
												</div>


											</div>
										</div>
										<table class="table table-striped table-hover">
											<thead>
												<tr>
													<th>
														<span class="custom-checkbox">
															<input type="checkbox" id="selectAll">
															<label for="selectAll"></label>
														</span>
													</th>
													<th>Email</th>
													<th>Course</th>
													<th>Date</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<span class="custom-checkbox">
															<input type="checkbox" id="checkbox1" name="options[]"
																value="1">
															<label for="checkbox1"></label>
														</span>
													</td>
													<td>Daven Del Puerto</td>
													<td>Information Technology</td>
													<td>April 24, 2024</td>
												</tr>
												<tr>
													<td>
														<span class="custom-checkbox">
															<input type="checkbox" id="checkbox2" name="options[]"
																value="1">
															<label for="checkbox2"></label>
														</span>
													</td>
													<td>Trizia Carpena</td>
													<td>Information Technology</td>
													<td>April 24, 2024</td>
									
												</tr>
												<tr>
													<td>
														<span class="custom-checkbox">
															<input type="checkbox" id="checkbox3" name="options[]"
																value="1">
															<label for="checkbox3"></label>
														</span>
													</td>
													<td>Bobby Morante</td>
													<td>Information Technology</td>
													<td>April 24, 2024</td>
												</tr>
											</tbody>
										</table>
										<div class="clearfix">
											<div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
											<ul class="pagination">
												<li class="page-item disabled"><a href="#">Previous</a></li>
												<li class="page-item"><a href="#" class="page-link">1</a></li>
												<li class="page-item"><a href="#" class="page-link">2</a></li>
												<li class="page-item active"><a href="#" class="page-link">3</a>
												</li>
												<li class="page-item"><a href="#" class="page-link">4</a></li>
												<li class="page-item"><a href="#" class="page-link">5</a></li>
												<li class="page-item"><a href="#" class="page-link">Next</a></li>
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


	<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="scripts/script.js"></script>
	<script src="scripts/feather.js"></script>

</body>

</html>