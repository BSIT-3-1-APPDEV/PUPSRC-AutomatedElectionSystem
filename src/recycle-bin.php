<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

	
	include FileUtils::normalizeFilePath('includes/session-exchange.php');
	include FileUtils::normalizeFilePath('submission_handlers/recycle-bin.php');
	
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
		<link rel="stylesheet" href="styles/recycle-bin.css" />
		<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	</head>

	<body>

		<?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

		<div class="main">


		<div class="row justify-content-center">
    <div class="col-md-10 card-box mt-md-5">
        <div class="container-fluid">
            <div class="card-box p-4">
                <div class="d-flex align-items-center">
                    <i data-feather="trash-2" class="feather-xs im-cust-2" style="font-size: 30px; margin-right: 15px; color:red;"></i>
                    <h3 style="letter-spacing: 2px; margin-bottom:0px; margin-left:10px;"><b>Recently Deleted</b></h3>
                </div>
                <span style="display: block; margin-top: 10px;">
                    Recently deleted items will be permanently deleted after the days shown. After that, you won’t be able to restore them.
                </span>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center mt-5">
    <div class="col-md-10">
        <div class="row justify-content-between mb-1">
            <div class="col-33 card">
                <div class="recycle-navigation-active text-center p-2">Voter's Accounts</div>
            </div>
         
            <div class="col-33 card">
            <a href="recycle-bin-admin.php" class="recycle-navigations"> 
                <div class="recycle-navigation text-center p-2">Admin Accounts</div>
                </a>
                
            </div>
            <div class="col-33 card">
            <a href="recycle-bin-candidate.php" class="recycle-navigations"> 
                <div class="recycle-navigation text-center p-2">Candidates</div>
</a>
            </div>
        </div>
        <!-- Add a separate row for the line -->
        <div class="row mb-3">
            <div class="col-33 justify-content-center d-flex">
                <hr class="line custom-hr main-color " style="width: 50%; border-width:2px; margin:0px;">
            </div>
        </div>
    </div>
</div>
		
									

										

			<!-- VERIFIED TABLE -->
<div class="row justify-content-center">
    <div class="col-md-10 card-box mt-md-5 p-5">
        <div class="container-fluid">
            <div class="card-box">
                <div class="row">
                    <div class="content">
                      
                            <?php if ($verified_tbl->num_rows > 0) { ?>
                                <div class="table-title">
                                    <div class="row">
                                        <!-- Table Header -->
                                        <div class="col-sm-6">
                                            <p class="fs-3 main-color fw-bold ls-10 spacing-6">Voter's Accounts</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row d-flex justify-content-end align-items-center">
                                                <!-- Delete -->
                                                <div class="col-4 col-sm-3 p-0">
                                                    <button class="delete-btn border-right pe-3 fs-7 spacing-6 fw-medium" type="button" id="deleteBtn">
                                                        <i class="fa-solid fa-trash-can fa-sm"></i> Delete
                                                    </button>
                                                </div>
                                                <!-- Restore -->
                                                <div class="col-4 col-sm-3 p-0">
                                                    <button class="restore-btn fs-7 spacing-6 fw-medium" type="button" id="restoreBtn">
                                                        <i class="fa-solid fa-clock-rotate-left fa-sm"></i> Restore
                                                    </button>
                                                </div>
                                                <!-- Sort By -->
                                                <div class="col-4 col-sm-3 p-0">
                                                    <div class="dropdown sort-by ">
                                                        <button class="sortby-tbn fs-7 spacing-6 fw-medium" type="button" id="dropdownMenuButton"
                                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa-solid fa-arrow-down-wide-short fa-sm"></i> Sort by
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="dropdownMenuButton">
                                                            <li class="dropdown-item ps-3 fs-7 fw-medium">Most to Fewest Days</li>
                                                            <li class="dropdown-item ps-3 fs-7 fw-medium">Fewest to Most Days</li>
                                                            <li class="dropdown-item ps-3 fs-7 fw-medium">A to Z (Ascending)</li>
                                                            <li class="dropdown-item ps-3 fs-7 fw-medium">Z to A (Descending)</li>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Search -->
                                                <div class="col-12 col-sm-3 p-0">
                                                    <div class="search-container" style="position: relative; display: inline-block; width: 100%;">
                                                        <i data-feather="search" class="feather-xs im-cust-2 search-icon"></i>
                                                        <input class="search-input fs-7 spacing-6 fw-medium" type="text" placeholder="Search" id="searchInput" style="width: 100%; padding-left: 1.5rem;">
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

														<!-- Table Contents -->
														<table class="table">
															<thead class="tl-header">
																<tr>
																<th 	class="col-md-3 tl-left text-center fs-7 fw-bold spacing-5"><input type="checkbox" id="selectAllCheckbox"> </th>
																	<th
																		class="col-md-3 text-center fs-7 fw-bold spacing-5">
																		<i data-feather="mail"
																			class="feather-xs im-cust"></i>Email Address
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
																	<tr>
																		<td class="text-center"><input type='checkbox' class='select-checkbox' value=<?php echo $row['voter_id'] ?>></td>
																		<td class="col-md-6 text-center text-truncate">
                <a href="#" class="email-link" data-voter-id="<?php echo $row['voter_id']; ?>">
                    <?php echo $row['email']; ?>
                </a>
            </td>
																		

																		<td class="col-md-6 text-center">
																			<span
																				class=""><?php
    // Convert the status updated date to DateTime object
    $statusUpdatedDate = new DateTime($row["status_updated"]);

    // Calculate the difference between the status updated date and 30 days from it
    $endDate = $statusUpdatedDate->modify('+30 days');

    // Get the current date
    $currentDate = new DateTime();

    // Calculate the difference between the current date and the end date
    $interval = $currentDate->diff($endDate);

    // Get the remaining days
    $remainingDays = max(0, $interval->days);

    // Display the result
    echo $remainingDays . " days left";
?>


 
																		</td>
																	</tr>
																<?php } ?>
															</tbody>
														</table>
													<div class="row">
													<div class="col-6 justify-content-start d-flex">
													<button class=" btn btn-danger btn-sm  px-3 me-2" id="deleteSelectedbtn"> Delete Selected</button>
													<button class=" btn btn-secondary btn-sm cancelDelete px-3" id="cancelDelete"> Cancel</button>
													<button class=" btn btn-info btn-sm  px-3 me-2" id="restoreSelectedbtn"> Restore Selected</button>
													<button class=" btn btn-secondary btn-sm cancelDelete px-3" id="cancelRestore"> Cancel</button>
													  </div>
														<div class="col-6">
														
                                                        <div class="pagination-container">
        <a href="#" id="previous-page" class="page-link pt-1"><i class="fas fa-chevron-left"></i></a>
        <ul class="pagination" id="pagination-controls">
            <!-- Pagination controls will be dynamically added here -->
        </ul>
        <a href="#" id="next-page" class="page-link pt-1"><i class="fas fa-chevron-right"></i></a>
    </div>
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
																<p class="fw-bold spacing-6 black">No accounts deleted</p>
																<p class="spacing-3 pt-1 black fw-medium">Check again once you've deleted an account!
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
	
	<!-- Bootstrap Modal Structure -->
	<div class="modal fade" id="voterDetailsModal" tabindex="-1" aria-labelledby="voterDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 1000px;">
        <div class="modal-content px-5 py-4">
            <div class="modal-header">
         
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="col-md-7 pe-5">
                <iframe id="pdfViewer" width="100%" height="300"></iframe>
				</div>
				<div class="col-md-5">
				<p class="fs-4 main-color fw-bold ls-10 spacing-6 text-center" style="border-bottom: 1px solid #ccc;">Account Details</p>
				<p class="text-center main-color"><strong>Email Address</strong></p>
                        <p class="text-center"id="modal-email"></p>
                        <p class="text-center main-color"><strong> Date Registered</strong></p>
						<p  class="mb-5 text-center"> <span id="modal-acc-created"></span> </p>
                        <p class="text-left red"><i> <span id="modal-status-updated" ></span> </i></p>
				</div>
			
				</div>
               
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

            <div class="modal-body justify-content-center d-flex flex-column align-items-center py-4">
    <img src="images/resc/warning.png">
    <p class="text-center my-3 fs-5 red fw-bold">Confirm Delete?</p>
    <p class="m-0 fs-7 fw-medium">A heads up: this action <b> cannot be undone! </b></p>
    <p class="m-0 fs-7 fw-medium mb-3">Type <b>'Confirm Delete' </b> to proceed:</p>
    <div class="col-12 col-md-9 justify-content-center mb-4">
        <input type="text" id="confirmDeleteInput" class="form-control text-center" placeholder="Type here...">
        <span class="validation-message text-center fs-8 red">Please type the words exactly as shown to proceed.</span>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button type="button" class="btn btn-secondary btn-cancel mx-1" data-bs-dismiss="modal">Cancel</button>
        <div id="confirmDeleteButton-container">
            <button type="button" id="confirmDeleteButton" class="btn btn-danger btn-delete mx-1" disabled>Delete</button>
        </div>
</div>

                
        
                </div>
            </div>
        </div>
    </div>

<!-- Modal for warning message -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <div class="modal-header p-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                <img src="images/resc/yellow-warning.png" class="img-icons-2 img-fluid mb-4">
                <p class=" fs-5 text-center yellow mb-3">Action Required</p>
                <p class="fs-7 text-center fw-medium m-0">Please complete the current action before</p>
                <p class="fs-7 text-center fw-medium">performing another operation.</p>
            </div>
         
           
        </div>
    </div>
</div>
<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreConfirmationModal" tabindex="-1" aria-labelledby="restoreConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
       
            <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                <img src="images/resc/blue-info.png" class="img-icons-3 img-fluid mb-4">
                <p class=" fs-5 text-center blue mb-3">Restore Accounts?</p>
                <p class="fs-7 text-center fw-medium m-0"> Are you sure you want to restore the selected</p>
                <p class="fs-7 text-center fw-medium">account(s)? This action cannot be undone.</p>
                <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-secondary btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
        <div>
        <button type="button" id="confirmRestoreBtn" class="btn btn-info">Yes, restore accounts</button>
        </div>
</div>
            </div>
        
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSuccessModal" tabindex="-1" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content p-3">
        <div class="modal-header p-1">
             
                <button type="button" class="btn-close" id="refreshPageBtn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                <img src="images/resc/check-animation.gif" class="img-icons img-fluid">
                <p class=" fs-5 text-center green m-0">Items Successfully deleted</p>
                <p class="fs-7 text-center fw-medium">Permanent deletion complete.</p>
            </div>
        
        </div>
    </div>
</div>
<div class="modal fade" id="restoreSuccessModal" tabindex="-1" aria-labelledby="restoreSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
<div class="modal-dialog">
        <div class="modal-content p-3">
        <div class="modal-header p-1">
             
                <button type="button" class="btn-close" id="refreshPageBtn2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                <img src="images/resc/check-animation.gif" class="img-icons img-fluid">
                <p class=" fs-5 text-center green mb-3">Restored Successfully!</p>
                <p class="fs-7 text-center fw-medium m-0">Accounts have been restored! You can now</p>
                <p class="fs-7 text-center fw-medium">Access them in the  <a href="manage-voters.php" class="underlined-link">Voters</a></ul> table.</p>
            </div>
     
        </div>
    </div>
</div>
</div>



				<?php include_once __DIR__ . '/includes/components/footer.php'; ?>

				<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
				<script src="scripts/script.js"></script>
				<script src="scripts/feather.js"></script>
				<script src="scripts/manage-voters.js"></script>
                <script src="scripts/recycle-bin.js"></script>
				
			
	</body>


	</html>

	<?php
} else {
	header("Location: landing-page.php");
}
?>