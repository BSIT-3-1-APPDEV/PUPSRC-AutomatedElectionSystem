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
    $voter_query = "SELECT * FROM voter WHERE voter_id = ?";
    $stmt = $conn->prepare($voter_query);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['account_status'] == 'verified') {

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
            <title>Voter Details</title>

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
            <link rel="stylesheet" href="styles/voter-details.css" />
            <link rel="stylesheet" href="styles/validate-voter.css" />
            <link rel="stylesheet" href="styles/loader.css" />
            <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

        </head>

        <body>

            <?php 
            include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
            include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
            ?>

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
                                <button type="button" class="btn btn-current rounded-pill spacing-8 fs-8">VOTER PROFILE</button>
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
                                        <div class="row">
                                            <!-- FIRST COLUMN -->
                                            <div class="col-md-7 p-sm-5">
                                                <!-- Header of Left Column -->
                                                <div class="row pdf-dtls">
                                                    <!-- COR Name -->
                                                    <div class="col-sm-6 col-12 d-flex flex-row">
                                                        <p class="fw-bold fs-7">
                                                            <i class="fas fa-paperclip fa-sm"></i>
                                                            <span class="ps-sm-1 spacing-5"><?php echo $row["cor"] ?></span>
                                                        </p>
                                                    </div>
                                                    <!-- Download + Full Screen Name -->
                                                    <div class="col-sm-6 col-12 d-flex flex-row-reverse">
                                                        <div class="row funcs">
                                                            <div class="col-12 col-sm-12"> <!-- Adjusted column size -->
                                                                <!-- Download -->
                                                                <a href="<?php echo "user_data/$org_name/cor/" . $row['cor']; ?>"
                                                                    download class="d-inline-flex align-items-center">
                                                                    <i class="fas fa-download fa-sm"></i>
                                                                    <span
                                                                        class="fs-7 ps-sm-2 spacing-5 fw-medium">Download</span>
                                                                </a>
                                                                <i class="fa-solid fa-expand fa-sm fullscreen-icon"></i>
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
                                                    <div class="row information">
                                                        <div class="col-md-12 text-center">
                                                            <!-- Title -->
                                                            <p class="fw-bold fs-3 main-color spacing-4">Voter Details
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

                                                    <div class="row information">
                                                        <div class="col-md-12 pt-sm-4">
                                                            <!-- Description -->
                                                            <p class="fw-medium fs-7 spacing-6">The following are the voter's
                                                                provided information.</p>
                                                        </div>
                                                    </div>
                                                </section>

                                                <!-- Student Information -->
                                                <section>
                                                    <div class="row pt-sm-4 information">
                                                        <div class="col-md-12">
                                                            <!-- Email -->
                                                            <p class="fw-bold fs-6 main-color spacing-4">Email Address</p>
                                                            <p class="fw-medium fs-6 pt-sm-2 text-truncate">
                                                                <?php echo $row["email"] ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-sm-4 information">
                                                        <div class="col-md-12">
                                                            <!-- Status -->
                                                            <p class="fw-bold fs-6 main-color spacing-4">Date Registered</p>
                                                            <p class="fw-medium fs-6 pt-sm-2">
                                                                <?php
                                                                $date = new DateTime($row["acc_created"]);
                                                                echo $date->format('F j, Y');
                                                                ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-sm-4 information">
                                                        <div class="col-md-12">
                                                            <!-- Date -->
                                                            <p class="fw-bold fs-6 main-color spacing-4">Date Verified</p>
                                                            <p class="fw-medium fs-6 pt-sm-2">
                                                                <?php
                                                                $date = new DateTime($row["status_updated"]);
                                                                echo $date->format('F j, Y');
                                                                ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-sm-4">
                                                        <div class="col-12 acc-status">
                                                            <!-- Status -->
                                                            <p class="fw-bold fs-6 main-color spacing-4">Account Status</p>
                                                            <div class="row pt-sm-4 status-acc">
                                                                <div class="col-sm-5 col-12">

                                                                    <?php
                                                                    $status = $row["account_status"];
                                                                    $statusClass = '';

                                                                    switch ($status) {
                                                                        case 'verified':
                                                                            $statusClass = 'active-status';
                                                                            break;
                                                                        case 'invalid':
                                                                            $statusClass = 'inactive-status';
                                                                            break;
                                                                        default:
                                                                            $statusClass = '';
                                                                            break;
                                                                    }
                                                                    ?>
                                                                    <span class="status-background <?php echo $statusClass; ?>">
                                                                        <?php echo ucfirst($status); ?></span>
                                                                </div>
                                                                <div class="col-sm-5 col-12 status-update">
                                                                    <!-- Status -->
                                                                    <p class="fw-bold fs-8 spacing-4 no-padding">Last update on:
                                                                    </p>
                                                                    <p class="fw-medium fs-8 no-padding">
                                                                        <?php
                                                                        $date = new DateTime($row["status_updated"]);
                                                                        echo $date->format('F j, Y');
                                                                        ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </section>
                                                <!-- Buttons -->
                                                <section>
                                                    <div class="row pt-sm-5 del-btn">
                                                        <div class="col-md-12 text-end">
                                                            <button
                                                                class="del-no-border px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
                                                                id="reject-btn" data-toggle="modal"
                                                                data-target="#rejectModal">Delete Account</button>
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


            <!-- Move To Trashbin Modal -->
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
                                                <p class="pt-2 fs-7 fw-medium spacing-5">The account will be deleted and moved
                                                    to <span class="fw-bold">Recycle Bin</span>.</p>
                                                <p class="fw-medium spacing-5 pt-1 fs-7">Are you sure you want to delete?</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 pt-1 text-center">
                                    <div class="d-inline-block">
                                        <button class="btn btn-light px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
                                            onClick="closeModal()" aria-label="Close">Cancel</button>
                                    </div>
                                    <div class="d-inline-block">
                                        <form class="d-inline-block">
                                            <input type="hidden" id="voter_id" name="voter_id" value="<?php echo $voter_id; ?>">
                                            <button class="btn btn-danger px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6"
                                                type="submit" id="confirm-move" value="delete">Delete</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Successfully Moved to Trashbin Modal -->
            <div class="modal" id="trashbinMoveDone" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body pb-5">
                            <div class="d-flex justify-content-end">
                                <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray"
                                    onclick="redirectToPage('manage-voters.php')">
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
                                        onClick="redirectToPage('trashbin.php')" aria-label="Close">Go To Recycle Bin</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- The following block of codes of modals,
            "TOTAL DELETION" can be used for the Trashbin Module. -->

                <!-- TOTAL DELETION: Confirm Delete Modal -->
                <div class="modal" id="totalDeleteModal" tabindex="-1" role="dialog">
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
                                                    <p class="pt-2 fw-medium spacing-5">A heads up: this action <span
                                                            class="fw-bold">cannot be undone!</span></p>
                                                    <p class="fw-medium spacing-5 pt-1">Type '<span class="fw-bold">Confirm
                                                            Delete</span>' to proceed.</p>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center"> <!-- Add justify-content-center class -->
                                                <div class="col-md-11 pb-3 pt-3 confirm-delete text-center mx-auto">
                                                    <!-- Add mx-auto class -->
                                                    <form action="#" method="post">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control pt-2 bg-primary text-black"
                                                                id="confirm-deletion" placeholder="Type here..."
                                                                oninput="validateConfirmation()">
                                                        </div>
                                                    </form>
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
                                                <input type="hidden" id="voter_id" name="voter_id"
                                                    value="<?php echo $voter_id; ?>">
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

                <!-- TOTAL DELETION SUCCESS: Deleted Successfully Modal -->
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


                <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
                <script src="scripts/script.js"></script>
                <script src="scripts/manage-voters.js"></script>
                <script src="scripts/feather.js"></script>
                <script src="scripts/loader.js"></script>

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