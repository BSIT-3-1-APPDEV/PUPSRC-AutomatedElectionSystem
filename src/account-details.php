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
    include FileUtils::normalizeFilePath('submission_handlers/manage-details.php');
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
        <link rel="stylesheet" href="styles/account-details.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="scripts/loader.js" defer></script>

    </head>

    <body>

        <?php
        include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
        include_once __DIR__ . '/includes/components/sidebar.php';
        ?>

        <div class="main">

            <div class="container mb-5 ml-10">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="breadcrumbs d-flex">
                            <button type="button" class=" btn-white d-flex align-items-center spacing-8 fs-8">
                                <i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
                            </button>
                            <button type="button" class="btn-back spacing-8 fs-8"
                                onclick="window.location.href='manage-committee.php'">COMMITTEE MEMBERS</button>
                            <button type="button" class="btn btn-current rounded-pill spacing-8 fs-8">ACCOUNT
                                DETAILS</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 card-box mt-md-10">
                        <div class="container-fluid">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="row">
                                            <p class="fs-3 main-color fw-bold ls-10 spacing-6">Account Details</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-md-4 mx-auto">
                                        <div class="row">
                                            <div class="col-md-12 text-left mx-auto ms-3">
                                                <p class="fw-bold fs-5 pt-sm-2">
                                                    <?php echo strtoupper($voter['first_name'] . ' ' . $voter['middle_name'] . ' ' . $voter['last_name'] . ' ' . $voter['suffix']); ?>
                                                </p>
                                                <p class="fw-bold fs-7 main-color spacing-4" style="margin-top: -20px">
                                                    <?php echo strtoupper($org_acronym) ?> Committee Member
                                                </p>

                                                <p class="fw-bold fs-5 pt-sm-2"><?php echo $voter['email']; ?></p>
                                                <p class="fw-bold fs-7 main-color spacing-4" style="margin-top: -20px">
                                                    Email Address</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-center d-none d-md-flex">
                                        <div class="divider"></div>
                                    </div>

                                    <div class="col-md-5 mt-md-4">
                                        <div class="row">
                                            <div class="col-md-12 mx-auto text-center">
                                                <p class="fw-bold fs-6 main-color spacing-4">iVOTE Committee Role</p>
                                                <p style="margin-top: -10px" >
                                                <form id="role-form"
                                                    action="manage-details.php?voter_id=<?php echo $voter_id; ?>"
                                                    method="post">
                                                    <div class="dropdown">
                                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                                                        style="
                                                        <?php
                                                            if ($voter['role'] == 'admin') {
                                                                echo 'background-color: #03C04A; color: white;';
                                                            } elseif ($voter['role'] == 'head_admin') {
                                                                echo 'background-color: blue; color: white;';
                                                            } else {
                                                                echo 'background-color: #6c757d; color: white;'; // Default color
                                                            }
                                                        ?>
                                                        ">
                                                        
                                                        <?php
                                                            if ($voter["role"] == 'admin') {
                                                                echo 'Admin';
                                                            } elseif ($voter["role"] == 'head_admin') {
                                                                echo 'Head Admin';
                                                            } else {
                                                                echo 'Select a role';
                                                            }
                                                        ?>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                            <li><a class="dropdown-item" href="#" onclick="changeButtonText(this, 'admin')">Admin</a></l>
                                                            <li><a class="dropdown-item" href="#" onclick="changeButtonText(this, 'head_admin')">Head Admin</a></li>
                                                        </ul>
                                                    </div>
                                                </form>
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Block Section -->
                                        <div class="row">
                                            <div class="col-md-12 mx-auto text-center me-5">
                                                <p class="fw-medium fs-6 pt-sm-2">
                                                    <?php echo $voter['formatted_account_created']; ?>
                                                </p>
                                                <p class="fw-bold fs-7 main-color spacing-4" style="margin-top: -20px">
                                                    Account Created</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-end d-none d-md-block" style="margin-top: 30px">
                                        <button class="btn btn-link mb-3 fw-bold"
                                            style="color: red; text-decoration: underline;">Delete Account</button>
                                    </div>
                                    <div class="col-md-12 delete-btn-mobile d-block d-md-none">
                                        <button class="btn btn-link mb-3 fw-bold"
                                            style="color: red; text-decoration: underline;">Delete Account</button>
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
        <script src="scripts/account-details.js"></script>



        <!-- Confirm Reject Modal -->
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
                                            <p class="pt-2 fw-medium spacing-5">The account(s) will be deleted and
                                                moved to
                                                Recycle Bin.
                                                Are you sure you want to delete?
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 pt-3 text-center">
                                <div class="d-inline-block">
                                    <button class="btn btn-light px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
                                        onClick="closeModal()" aria-label="Close">Cancel</button>
                                </div>
                                <div class="d-inline-block">
                                    <form class="d-inline-block">
                                        <input type="hidden" id="voter_id" name="voter_id" value="<?php echo $voter_id; ?>">
                                        <button class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
                                            type="submit" id="confirm-delete" value="delete" disabled>Delete</button>
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
                            <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray"
                                onclick="redirectToPage('manage-voters.php')">
                            </i>
                        </div>
                        <div class="text-center p-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="fw-bold fs-3 danger spacing-4">Account Deleted</p>
                                    <p class="fw-medium spacing-5">The account has been successfully deleted.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>


    </html>

    <?php
} else {
    header("Location: landing-page.php");
}
?>