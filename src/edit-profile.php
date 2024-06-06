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
        <link rel="stylesheet" href="styles/profile.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    </head>

    <body>

        <?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

        <div class="main">


            <div class="container">
                <div class="row justify-content-center">
                    <!-- FOR VERIFICATION TABLE -->
                    <div class="col-md-10 card-box">
                        <div class="table-wrapper" id="profile">
                            <div class="table-title">
                                <div class="row">
                                    <!-- Table Header -->
                                    <div class="col-sm-6">
                                        <p class="fs-3 main-color fw-bold ls-10 spacing-6">Edit Profile Information</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="lastName" class="form-label">Last Name <span class="asterisk">*</span></label>
                                                <input type="text" class="form-control" id="lastName" value="<?php echo isset($last_name) ? $last_name : ''; ?>" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="firstName" class="form-label">First Name <span class="asterisk">*</span></label>
                                                <input type="text" class="form-control" id="firstName" value="Rhey Yuri" required>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="middleName" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" id="middleName" value="Marcelino" required>
                                            </div>
                                            <div class="col-md-2 mb-">
                                                <label for="suffix" class="form-label">Suffix <span class="asterisk">*</span></label>
                                                <input type="text" class="form-control" id="suffix" value="Jr" required>
                                            </div>
                                            <div class="col-md-6 mb-5 mt-4">
                                                <label for="email" class="form-label">Email <span class="asterisk">*</span></label>
                                                <input type="email" class="form-control" id="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="btn-container">
                                            <div>
                                                <button type="button" class="btn btn-primary main-bg-color" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#successResetPasswordLinkModal"><img src="images/resc/icons/change-pass.png" class="change-pass-icon">Change Password</button>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary main-bg-color save-button">Save Changes</button>
                                                <button type="button" class="btn btn-secondary cancel-button">Cancel</button>
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

        <!-- Modals -->
        <div class="modal fade" id="successResetPasswordLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content d-flex align-items-center justify-content-center" id="success-modal">
                    <div class="modal-body text-center w-100">
                        <div class="col-md-12">
                            <img src="images/resc/icons/shield.png" class="change-passs-modal-icon" alt="iVote Logo">
                        </div>
                        <p class="fw-bold fs-4 change-password-title spacing-4 mt-3">Change Password</p>
                        <p class="change-password-sub">For security purposes, please type your current password to proceed.</p>
                        <form class="needs-validation" id="forgot-password-form" name="forgot-password-form" novalidate enctype="multipart/form-data">
                            <div class="col-12 col-md-12">
                                <div class="input-group">
                                    <input type="password" class="form-control mx-auto align-self-center" id="change-password" name="change-password" onkeypress="return avoidSpace(event)" placeholder="Type here...">
                                    <button class="btn btn-secondary eye-toggle" type="button" id="password-toggle-1" style="display: none;">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <button type="button" class="btn btn-secondary cancel-button" data-bs-dismiss="modal" id="cancel-modal">Cancel</button>
                                <a href="change-password.php" class="btn btn-primary delete-button" id="delete-button">Submit</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <?php include_once __DIR__ . '/includes/components/footer.php'; ?>

        <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/feather.js"></script>
        <script src="scripts/table-funcs.js"></script>


        <!-- Updated script for password toggle -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const togglePassword1 = document.querySelector("#password-toggle-1");
                const passwordInput1 = document.querySelector("#change-password");
                const eyeIcon1 = togglePassword1.querySelector("i");

                togglePassword1.addEventListener("click", function() {
                    const type =
                        passwordInput1.getAttribute("type") === "password" ?
                        "text" :
                        "password";
                    passwordInput1.setAttribute("type", type);

                    // Toggle eye icon classes
                    eyeIcon1.classList.toggle("fa-eye-slash");
                    eyeIcon1.classList.toggle("fa-eye");
                });

                // Show or hide eye toggle based on input value
                passwordInput1.addEventListener("input", function() {
                    if (passwordInput1.value === "") {
                        togglePassword1.style.display = "none";
                    } else {
                        togglePassword1.style.display = "block";
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