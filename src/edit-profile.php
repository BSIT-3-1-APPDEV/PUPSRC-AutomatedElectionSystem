<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/classes/admin-dashboard-queries.php');

// Create an instance of DatabaseConnection
$dbConnection = new DatabaseConnection();

// Create an instance of Application
$app = new Application($dbConnection);

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {
    $org_name = $_SESSION['organization'] ?? '';

    include FileUtils::normalizeFilePath('includes/organization-list.php');
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    $org_full_name = $org_full_names[$org_name];

    // Fetch positions and year level counts using Application methods
    $positions = $app->getPositions();
    $firstPosition = $app->getFirstPosition();
    $yearLevelCounts = $app->getYearLevelCounts();
    $voterCounts = $app->getVoterCounts();
    $totalVotersCount = $voterCounts['totalVotersCount'];
    $votedVotersCount = $voterCounts['votedVotersCount'];
    $abstainedVotersCount = $voterCounts['abstainedVotersCount'];
    $totalPercentage = number_format($voterCounts['totalPercentage'], 2);
    $votedPercentage = number_format($voterCounts['votedPercentage'], 2);
    $candidateCount = $app->getCandidateCount();
    $voter_id = $_SESSION['voter_id'];
    $first_name = $app->getFirstName($voter_id);



?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
        <title>Edit Profile</title>

        <!-- Montserrat Font -->
        <link href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="styles/profile.css">
        <link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/core.css">
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/5.1.0/screenfull.min.js"></script>


        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
        <style>
            <?php


            // Output the CSS with the organization color variable for background-color
            echo ".main-bg-color { background-color: var(--$org_name) !important; }";

            echo ".main-border-color { border: var(--$org_name)  2px solid !important;}";
            echo ".main-border-color:hover { background-color: var(--$org_name) !important; }";

            // Output the CSS for the line with the dynamic color
            echo ".line { border-bottom: 2px solid var(--$org_name); width: 100%; }";
            ?>
        </style>
    </head>

    <body>

        <?php include FileUtils::normalizeFilePath('includes/components/sidebar.php'); ?>

        <main class="main">
            <div class="container form-container profile-container">
                <h2 class="form-title profile-title main-color mb-3">Edit Profile Information</h2>
                <form>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="lastName" class="form-label">Last Name <span>*</span></label>
                            <input type="text" class="form-control" id="lastName" value="<?php echo isset($last_name) ? $last_name : ''; ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="firstName" class="form-label">First Name <span>*</span></label>
                            <input type="text" class="form-control" id="firstName" value="<?php echo isset($first_name) ? $first_name : ''; ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="middleName" class="form-label">Middle Name <span>*</span></label>
                            <input type="text" class="form-control" id="middleName" value="Marcelino" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="suffix" class="form-label">Suffix <span>*</span></label>
                            <input type="text" class="form-control" id="suffix" value="Jr" required>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label for="email" class="form-label">Email <span>*</span></label>
                            <input type="email" class="form-control" id="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                        </div>
                        <!-- <div class="btn-container">
                            <button type="button" class="btn btn-primary main-bg-color" id="changePasswordBtn">Change Password</button>
                            <button type="button" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary main-bg-color">Save Changes</button>
                        </div> -->
                        <div class="btn-container">
                            <div>
                                <button type="button" class="btn btn-primary main-bg-color" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#successResetPasswordLinkModal"><img src="images/resc/icons/change-pass.png" class="change-pass-icon">Change Password</button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary cancel-button">Cancel</button>
                                <button type="submit" class="btn btn-primary main-bg-color save-button">Save Changes</button>
                            </div>
                        </div>
                </form>
            </div>
        </main>

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
                            <div class="col-md-12 mt-4 mb-4">
                                <button type="button" class="btn btn-secondary cancel-button" data-bs-dismiss="modal">Cancel</button>
                                <a href="change-password.php" class="btn btn-primary delete-button" id="delete-button">Submit</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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




        <!-- <div class="modal fade" id="successResetPasswordLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                        <!-- Add 'd-flex align-items-center' class to input container 
                        <div class="form-group d-flex align-items-center">
                            <input type="password" class="form-control w-75 mx-auto mt-4 align-self-center" id="change-password" name="change-password" onkeypress="return avoidSpace(event)" placeholder="Type here...">
                        </div>
                        <div class="invalid-feedback text-start" id="email-error"></div>
                    </div>
                    <div class="col-md-12 mt-4 mb-4">
                        <button type="button" class="btn btn-secondary cancel-button" data-bs-dismiss="modal">Cancel</button>
                        <!-- <a href="edit-password.php" class="btn btn-primary main-bg-color delete-button disabled" id="delete-button">Delete</a> 
                        <a href="edit-password.php" class="btn btn-primary main-bg-color delete-button" id="delete-button">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->


        <!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById("change-password");
        const deleteButton = document.getElementById("delete-button");

        passwordInput.addEventListener("input", function() {
            if (validateEmail(passwordInput.value.trim())) {
                deleteButton.classList.remove("disabled");
            } else {
                deleteButton.classList.add("disabled");
            }
        });
    });
</script> -->



        <?php include FileUtils::normalizeFilePath('includes/components/footer.php'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/admin_dashboard.js"></script>
        <script src="scripts/feather.js"></script>
        <script src="scripts/edit-profile.js"></script>


    </body>

    </html>
<?php
} else {
    header("Location: landing-page.php");
}
?>