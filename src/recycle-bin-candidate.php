<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');


require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {


    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    include FileUtils::normalizeFilePath('submission_handlers/recycle-bin-candidate.php');

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
        <link rel="stylesheet" href="styles/loader.css" />
        <link rel="stylesheet" href="styles/tables.css" />
        <link rel="stylesheet" href="styles/recycle-bin.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="scripts/loader.js" defer></script>

    </head>

    <body>

        <?php include_once __DIR__ . '/includes/components/sidebar.php'; 
        include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
        ?>

        <div class="main">
            <div class="row justify-content-center">
                <div class="col-md-10 card-box">
                    <div class="container-fluid">
                        <div class="card-box p-0 py-3 p-sm-4">
                            <div class="d-flex align-items-center">
                                <i data-feather="trash-2" class="feather-xs im-cust-2" style="font-size: 30px; margin-right: 15px; color:red;"></i>
                                <h3  class="recently-deleted"><b>Recently Deleted</b></h3>
                            </div>
                            <span style="display: block; margin-top: 10px;" class="recently-deleted-text">
                                Recently deleted items will be permanently deleted after the days shown. After that, you won’t be able to restore them.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-md-10">
                    <div class="row justify-content-between mb-1">
                    <div class="col-33 card justify-content-center d-flex align-items-center">
                            <a href="recycle-bin.php" class="recycle-navigations">
                                <div class="recycle-navigation text-center p-2">Voters' Accounts</div>
                            </a>
                        </div>

                        <div class="col-33 card justify-content-center d-flex align-items-center">
                            <a href="recycle-bin-admin.php" class="recycle-navigations">
                                <div class="recycle-navigation text-center p-2">Admin Accounts</div>
                            </a>

                        </div>
                        <div class="col-33 card justify-content-center d-flex align-items-center">
                            <a href="recycle-bin-candidate.php" class="recycle-navigations">
                                <div class="recycle-navigation-active text-center p-2">Candidates</div>
                            </a>
                        </div>
                    </div>
                    <!-- Add a separate row for the line -->
                    <div class="row mb-3 justify-content-end">

                        <div class="col-33  justify-content-center d-flex">
                            <hr class="line custom-hr main-color " style="width: 50%; border-width:2px; margin:0px;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- VERIFIED TABLE -->
            <div class="row justify-content-center">
            <div class="col-md-10 card-box mt-md-5 p-0 py-4 px-4 p-sm-5">
                    <div class="container-fluid">
                        <div class="card-box">
                            <div class="row">
                                <div class="content">
                                    <?php if ($verified_tbl->num_rows > 0) { ?>
                                        <div class="table-title p-3">
                                            <div class="row">
                                                <!-- Table Header -->
                                                <div class="col-sm-6">
                                                    <p class="fs-3 main-color fw-bold ls-10 spacing-6 recently-deleted ms-0 ps-0">Candidate Details</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row d-flex justify-content-end align-items-center">
                                                        <!-- Delete -->
                                                        <div class="col-4 col-sm-3 p-0 border-right justify-content-center align-items-center d-flex" style="height: 20px;">
                                                            <button class="delete-btn pe-2 fs-7 spacing-6 fw-medium" type="button" id="deleteBtn">
                                                                <i class="fa-solid fa-trash-can fa-sm"></i> Delete
                                                            </button>
                                                        </div>
                                                        <!-- Restore -->
                                                        <div class="col-4 col-sm-3 p-0 justify-content-center align-items-center d-flex"  style="height: 20px;">
                                                            <button class="restore-btn fs-7 spacing-6 fw-medium ms-2" type="button" id="restoreBtn">
                                                                <i class="fa-solid fa-clock-rotate-left fa-sm"></i> Restore
                                                            </button>
                                                        </div>
                                                        <!-- Sort By -->
                                                        <div class="col-4 col-sm-3 p-0 justify-content-center align-items-center d-flex"  style="height: 20px;">
                                                            <div class="dropdown sort-by">
                                                                <button class="sortby-tbn fs-7 spacing-6 fw-medium ms-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

                        <!-- Table Contents -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="tl-header">
                                    <tr>
                                        <th class="col-md-3 tl-left text-center fs-7 fw-bold spacing-5"><input type="checkbox" id="selectAllCheckbox"> </th>
                                        <th class="col-md-3 text-center fs-7 fw-bold spacing-5">
                                            <i data-feather="mail" class="feather-xs im-cust"></i> Full Name
                                        </th>
                                        <th class="col-md-3 text-center fs-7 fw-bold spacing-5">
                                            <i data-feather="star" class="feather-xs im-cust"></i> Candidacy Position
                                        </th>
                                        <th class="col-md-3 tl-right text-center fs-7 fw-bold spacing-5">
                                            <i data-feather="calendar" class="feather-xs im-cust"></i> Remaining Days
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $verified_tbl->fetch_assoc()) { ?>
                                        <tr>
                                            <td class="text-center"><input type='checkbox' class='select-checkbox' value=<?php echo $row['candidate_id']; ?>></td>
                                            <td class="col-md-6 text-center text-truncate">
                                                <a href="#" class="email-link" data-voter-id="<?php echo $row['candidate_id']; ?>">
                                                    <?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']; ?>
                                                </a>
                                            </td>
                                            <td class="col-md-6 text-center text-truncate">
                                                <?php echo $row['title']; ?>
                                            </td>
                                            <td class="col-md-6 text-center">
                                                <span class="">
                                                    <?php
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
                                                </span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-6 justify-content-start d-flex my-2">
                                <button class="btn btn-danger btn-sm px-3 me-2" id="deleteSelectedbtn"> Delete Selected</button>
                                <button class="btn btn-secondary btn-sm cancelDelete px-3" id="cancelDelete"> Cancel</button>
                                <button class="btn btn-info btn-sm px-3 me-2" id="restoreSelectedbtn"> Restore Selected</button>
                                <button class="btn btn-secondary btn-sm cancelDelete px-3" id="cancelRestore"> Cancel</button>
                            </div>
                            <div class="col-12 col-sm-6">
                                <!-- Pagination Links -->
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
                                    <p class="fs-3 main-color fw-bold ls-10 spacing-6">Candidate Details</p>
                                </div>
                            </div>
                            <div class="col-md-12 no-registration text-center">
                                <img src="images/resc/folder-empty.png" class="illus">
                                <p class="fw-bold spacing-6 black">No accounts deleted</p>
                                <p class="spacing-3 pt-1 black fw-medium">Check again once you've deleted an account!</p>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
     </div>
     </div>
</div>

        <!-- Confirm Delete Modal -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
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

        <!-- Candidate Details Modal -->
        <div class="modal fade" id="candidateDetailsModal" tabindex="-1" aria-labelledby="voterDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-size">
                <div class="modal-content px-4 py-4">
                    <div class="modal-header">
                        <p class="fs-4 main-color fw-bold ls-10 spacing-6 text-center">Candidate Details</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7 pe-5 justify-content-center d-flex flex-direct align-items-center border-right-modal">
                                <img id="modal-photo-url" src="" alt="Candidate Photo" class="img-fluid candidate-pictures mb-2" />
                                <b>
                                    <p id="modal-name" class="spacing-6 m-0 ps-0 pb-3 border-bottom-modal"></p>
                                </b>
                            </div>
                            <div class="col-md-5">
                                <p class="text-center" id="modal-email"></p>
                                <p class="text-center m-0">
                                    <span id="modal-acc-created"></span>
                                </p>
                                <p class="text-left main-color fs-8 mb-0 ms-5"><strong>Candidacy Position</strong></p>
                                <p class="text-left fs-7 fw-bold mb-3 ms-5"><span id="modal-title"></span></p>
                                <p class="text-left main-color fs-8 mb-0 ms-5"><strong>Block Section</strong></p>
                                <p class="text-left fs-7 fw-bold mb-3 ms-5"><span id="modal-year-level"></span>-<span id="modal-section"></span></p>
                                <p class="text-left main-color fs-8 mb-0 ms-5"><strong>Date Registered</strong></p>
                                <p class="text-left fs-7 fw-bold mb-5 ms-5"><span id="modal-register-date"></span></p>
                            </div>
                            <div class="col-md-7"></div>
                            <div class="col-md-5 mt-3">
                                <p class="text-center red fs-8 fw-medium"><i><span id="modal-status-updated"></span></i></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Required Modal -->
        <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header p-1">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                        <img src="images/resc/yellow-warning.png" class="img-icons-2 img-fluid mb-4">
                        <p class="fs-5 text-center yellow mb-3">Action Required</p>
                        <p class="fs-7 text-center fw-medium m-0">Please complete the current action before</p>
                        <p class="fs-7 text-center fw-medium">performing another operation.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Restore Confirmation Modal -->
        <div class="modal fade" id="restoreConfirmationModal" tabindex="-1" aria-labelledby="restoreConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                        <img src="images/resc/blue-info.png" class="img-icons-3 img-fluid mb-4">
                        <p class="fs-5 text-center blue mb-3">Restore Accounts?</p>
                        <p class="fs-7 text-center fw-medium m-0">Are you sure you want to restore the selected</p>
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

        <!-- Successfully Deleted Modal --->
        <div class="modal fade" id="deleteSuccessModal" tabindex="-1" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header p-1">
                        <button type="button" class="btn-close" id="refreshPageBtn" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                        <img src="images/resc/check-animation.gif" class="img-icons img-fluid">
                        <p class="fs-5 text-center green m-0">Items Successfully deleted</p>
                        <p class="fs-7 text-center fw-medium">Permanent deletion complete.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Successfully Restored Modal --->
        <div class="modal fade" id="restoreSuccessModal" tabindex="-1" aria-labelledby="restoreSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header p-1">
                        <button type="button" class="btn-close" id="refreshPageBtn2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center d-flex flex-direct align-items-center">
                        <img src="images/resc/check-animation.gif" class="img-icons img-fluid">
                        <p class="fs-5 text-center green mb-3">Restored Successfully!</p>
                        <p class="fs-7 text-center fw-medium m-0">Accounts have been restored! You can now</p>
                        <p class="fs-7 text-center fw-medium">Access them in the <a href="manage-candidate.php" class="underlined-link">Candidate</a> table.</p>
                    </div>
                </div>
            </div>
        </div>


        <?php include_once __DIR__ . '/includes/components/footer.php'; ?>

        <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/feather.js"></script>
        <script src="scripts/manage-voters.js"></script>
        <script src="scripts/recycle-bin-candidates.js"></script>
        <script>
            $(document).ready(function() {
                $('.email-link').on('click', function(event) {
                    event.preventDefault();

                    var voterId = $(this).data('voter-id');

                    $.ajax({
                        type: 'POST',
                        url: 'submission_handlers/recycle-bin-candidate-modal.php',
                        data: {
                            voter_id: voterId
                        }, // Changed from voter_id to candidate_id
                        dataType: 'json',
                        success: function(response) {
                            if (response.title) {
                                var orgName = '<?php echo $org_name; ?>';
                                var formattedPhotoUrl = 'user_data/' + orgName + '/candidate_imgs/' + response.photo_url;

                                $('#modal-title').text(response.title);
                                $('#modal-photo-url').attr('src', formattedPhotoUrl);
                                $('#modal-register-date').text(response.register_date);
                                var statusUpdatedDate = new Date(response.status_updated);
                                var formattedStatusUpdated = 'Deleted at: ' + statusUpdatedDate.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) + ' | ' + statusUpdatedDate.toLocaleDateString([], {
                                    month: 'short',
                                    day: '2-digit',
                                    year: 'numeric'
                                });
                                $('#modal-status-updated').text(formattedStatusUpdated);

                                var name = (response.last_name + ', ' + response.first_name + ' ' + response.middle_name + ' ' + response.suffix).toUpperCase();
                                $('#modal-name').text(name);

                                $('#modal-section').text(response.section);
                                $('#modal-year-level').text(response.year_level);

                                $('#candidateDetailsModal').modal('show');
                            } else {
                                alert(response.error || 'An error occurred');
                            }
                        },
                        error: function() {
                            alert('An error occurred while fetching the data');
                        }
                    });

                });
            });
            
$(document).ready(function() {
  // Function to handle delete button click
  $('#confirmDeleteButton').on('click', function () {
      var selectedIds = [];
      $('.select-checkbox:checked').each(function () {
          selectedIds.push($(this).val());
      });
      if (selectedIds.length > 0) {
          // Send AJAX request to delete selected items
          $.ajax({
              type: 'POST',
              url: 'submission_handlers/delete-selected-candidates.php',
              data: { ids: selectedIds },
              dataType: 'json',
              success: function (response) {
                  // Handle success response
                  console.log('Selected items deleted successfully');
                  $('#deleteSuccessModal').modal('show');
                  $.each(selectedIds, function(index, id) {
                    $('.select-checkbox[value="' + id + '"]').closest('tr').remove();
                });

},
              error: function (jqXHR, textStatus, errorThrown) {
                  // Handle error response
                  console.error('An error occurred while deleting selected items:', textStatus, errorThrown);
              }
          });
      } else {
          console.warn('No items selected for deletion');
      }
  });

  // Function to handle restore button click
  $('#confirmRestoreBtn').on('click', function () {
      var selectedIds = [];
      $('.select-checkbox:checked').each(function () {
          selectedIds.push($(this).val());
      });
      if (selectedIds.length > 0) {
          // Send AJAX request to restore selected items
          $.ajax({
              type: 'POST',
              url: 'submission_handlers/restore-selected-candidate.php',
              data: { ids: selectedIds },
              dataType: 'json',
              success: function (response) {
                  // Handle success response
                  console.log('Selected items restored successfully');
                  $('#restoreSuccessModal').modal('show');
                  $.each(selectedIds, function(index, id) {
                    $('.select-checkbox[value="' + id + '"]').closest('tr').remove();
                });

},
              error: function (jqXHR, textStatus, errorThrown) {
                  // Handle error response
                  console.error('An error occurred while restoring selected items:', textStatus, errorThrown);
              }
          });
      } else {
          console.warn('No items selected for restoration');
      }
  });
});

        </script>



    </body>


    </html>

<?php
} else {
    header("Location: landing-page.php");
}
?>