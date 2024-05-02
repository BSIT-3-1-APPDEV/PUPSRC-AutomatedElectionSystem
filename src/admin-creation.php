<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');

if (isset($_SESSION['voter_id'])) {

    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    $allowedRoles = array('Committee Member', 'Admin Member');
    if (in_array($_SESSION['role'], $allowedRoles)) {
        include FileUtils::normalizeFilePath('submission_handlers/add-member.php');
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
            <link rel="stylesheet" href="styles/admin-creation.css" />
            <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


        </head>

        <body>


            <?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

            <div class="main">
                <div class="container mb-5 pl-5">
                    <div class="row justify-content-center">
                        <div class="col-md-11">
                            <div class="breadcrumbs d-flex">
                                <button type="button" class="btn btn-lvl-white d-flex align-items-center spacing-8 fs-8">
                                    <i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
                                </button>
                                <button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">ADD
                                    COMMITTEE</button>
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
                                        <div class="col-md-12">
                                            <h2 class="form-title">Create Admin Account</h2>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <form action="" method="post" id="admin-form">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-4 mx-auto">
                                                    <div class="form-group local-forms">
                                                        <label for="last_name" class="login-danger">Last Name <span
                                                                class="required"> * </span> </label>
                                                        <input type="text" id="last_name" name="last_name"
                                                            placeholder="Enter Last Name" required pattern="^[a-zA-Z]+$"
                                                            maxlength="20"
                                                            title="Last name can only contain alphabetic characters and should not exceed 20 characters">
                                                        <span class="error-message" id="last_name_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-4 mx-auto">
                                                    <div class="form-group local-forms">
                                                        <label for="first_name" class="login-danger">First Name<span
                                                                class="required"> * </span> </label>
                                                        <input type="text" id="first_name" name="first_name"
                                                            placeholder="Enter First Name" required pattern="^[a-zA-Z]+$"
                                                            maxlength="20"
                                                            title="First name can only contain alphabetic characters and should not exceed 20 characters">
                                                        <span class="error-message" id="first_name_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-3 mx-auto">
                                                    <div class="form-group local-forms">
                                                        <label for="middle_name" class="login-danger">Middle Name<span
                                                                class="required"> * </span> </label>
                                                        <input type="text" id="middle_name" name="middle_name"
                                                            placeholder="Enter Middle Name" required pattern="^[a-zA-Z]+$"
                                                            maxlength="20"
                                                            title="Middle name can only contain alphabetic characters and should not exceed 20 characters">
                                                        <span class="error-message" id="middle_name_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group local-forms">
                                                        <label for="email" class="login-danger">Email<span class="required"> *
                                                            </span> </label>
                                                        <input type="email" id="email" name="email" placeholder="Email" required
                                                            pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                                            title="Please enter a valid email address">
                                                        <span class="error-message" id="email_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group local-forms shorter-form-group">
                                                        <label for="role" class="login-danger">Role<span class="required"> *
                                                            </span> </label>
                                                        <select id="role" name="role" required>
                                                            <option value="Admin Member">Admin Member</option>
                                                            <option value="Committee Member">Committee Member</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <div class="d-flex flex-row">
                                                    <button type="reset" class="reset-button">Reset Form</button>
                                                    <button type="submit" value="Submit" class="button-create">Create
                                                        Account</button>
                                                </div>
                                            </div>
                                        </form>
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
            <script src="scripts/member-form-validation.js"></script>

            <!-- Created Modal -->
            <div class="modal" id="createdModal" tabindex="-1" role="dialog" <?php if (isset($_SESSION['account_created']) && $_SESSION['account_created'])
                echo 'data-show="true"'; ?>>
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="text-center">
                                <div class="col-md-12">
                                    <img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
                                </div>
                                <div class="row">
                                    <div class="col-md-12 pb-3">
                                        <p class="fw-bold fs-3 success-color spacing-4">Successfully Created!</p>
                                        <p class="fw-medium spacing-5">An email containing the generated password will be sent
                                            to their inbox shortly.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>


        </html>

        <script>
            $(document).ready(function () {
                var createdModal = new bootstrap.Modal(document.getElementById('createdModal'), {});

                <?php if (isset($_SESSION['account_created']) && $_SESSION['account_created']) { ?>
                    // Show the created modal
                    createdModal.show();

                    // Reload the page after a short delay
                    setTimeout(function () {
                        location.reload();
                    }, 3000); // 3 seconds

                    // Reset the session variable
                    <?php unset($_SESSION['account_created']); ?>
                <?php } ?>
            });
        </script>
        <?php
    } else {
        // User is not authorized to access this page
        header("Location: landing-page.php");
    }
} else {
    header("Location: landing-page.php");
}
?>