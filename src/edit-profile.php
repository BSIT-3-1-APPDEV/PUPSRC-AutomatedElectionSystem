<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/csrf-token.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');
include_once FileUtils::normalizeFilePath('includes/default-time-zone.php');

$_SESSION['referringPage'] = $_SERVER['PHP_SELF'];

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

    include_once FileUtils::normalizeFilePath('includes/session-exchange.php');

    $connection = DatabaseConnection::connect();

    $voter_id = $_SESSION['voter_id'];
    $sql = "SELECT last_name, first_name, middle_name, suffix, email, password FROM voter WHERE voter_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()) {
        $last_name = $row['last_name'] ?? '';
        $first_name = $row['first_name'] ?? '';
        $middle_name = $row['middle_name'] ?? '';
        $suffix = $row['suffix'] ?? '';
        $email = $row['email'] ?? '';
    }

    $stmt->close();
    $connection->close();
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
        <link rel="stylesheet" href="styles/loader.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    </head>

    <body>

        <?php 
        include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
        include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
        ?>

        <!-- For success update of profile -->
        <div aria-live="polite" aria-atomic="true" class="position-relative" style="z-index: 1050;"> 
            <div class="position-fixed toast-container top-0 end-0 p-3">           
                <div id="profileUpdatedToast"  class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="images/resc/ivote-icon.png" height="30px" width="30px" class="rounded me-2" alt="ivote-icon">
                        <strong class="me-auto">Success</strong>
                        <small class="text-body-secondary"> <!-- Seconds --> </small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body" id="toastBody">
                        <!-- Display message here -->
                    </div>
                </div>
            </div>
        </div>


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
                                    <form id="editProfile" method="post" class="needs-validation" novalidate>
                                        
                                        <!-- CSRF Token hidden field -->
                                        <?php $csrf_token = CsrfToken::generateCSRFToken(); ?>
                                        <input type="hidden" id="csrfToken" value="<?php echo $csrf_token; ?>">

                                        <div class="row">
                                            <!-- Last Name -->
                                            <div class="col-md-4 mb-3">
                                                <label for="lastName" class="form-label">Last Name <span class="asterisk">*</span></label>
                                                <input type="text" class="form-control" name="last-name" id="lastName" value="<?php echo htmlspecialchars($last_name); ?>" autocomplete="family-name" required>
                                                <div class="ps-1 fw-medium valid-feedback text-start" id="validLastName">
                                                    <!-- Display valid message -->

                                                </div>
                                                <div class="ps-1 fw-medium text-start invalid-feedback" id="invalidLastName">
                                                    <!-- Display error messages here -->
                                                </div>
                                            </div>

                                            <!-- First Name -->
                                            <div class="col-md-3 mb-3">
                                                <label for="firstName" class="form-label">First Name <span class="asterisk">*</span></label>
                                                <input type="text" class="form-control" name="first-name" id="firstName" value="<?php echo htmlspecialchars($first_name); ?>" autocomplete="given-name" required>
                                                <div class="ps-1 fw-medium valid-feedback text-start" id="validFirstName">
                                                    <!-- Display default valid message -->
                                                </div>
                                                <div class="ps-1 fw-medium text-start invalid-feedback" id="invalidFirstName">
                                                    <!-- Display error messages here -->
                                                </div>
                                            </div>

                                            <!-- Middle Name -->
                                            <div class="col-md-3 mb-3">
                                                <label for="middleName" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" name="middle-name" id="middleName" value="<?php echo htmlspecialchars($middle_name); ?>" autocomplete="additional-name">
                                                <div class="ps-1 fw-medium valid-feedback text-start" id="validMiddleName">
                                                    <!-- Display default valid message -->
                                                </div>
                                                <div class="ps-1 fw-medium text-start invalid-feedback" id="invalidMiddleName">
                                                    <!-- Display error messages here -->
                                                </div>
                                            </div>

                                            <!-- Suffix -->
                                            <div class="col-md-2 mb-">
                                                <label for="suffix" class="form-label">Suffix</label>
                                                <input type="text" class="form-control" name="suffix" id="suffix" value="<?php echo htmlspecialchars($suffix); ?>" autocomplete="honorific-suffix">
                                                <div class="ps-1 fw-medium valid-feedback text-start" id="validSuffix">
                                                    <!-- Display valid message -->
                                                </div>
                                                <div class="ps-1 fw-medium text-start invalid-feedback" id="invalidSuffix">
                                                    <!-- Display error messages here -->
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-md-6 mb-5 mt-4">
                                                <label for="email" class="form-label">Email <span class="asterisk">*</span></label>
                                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" autocomplete="email" required>
                                                <div class="ps-1 fw-medium valid-feedback text-start" id="validEmail">
                                                    <!-- Display valid message -->
                                                </div>
                                                <div class="ps-1 fw-medium text-start invalid-feedback" id="invalidEmail">
                                                    <!-- Display error messages here -->
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="btn-container">
                                            <div>
                                                <button type="button" class="btn btn-primary main-bg-color" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><img src="images/resc/icons/change-pass.png" class="change-pass-icon">Change Password</button>
                                            </div>
                                            <div>
                                                <button type="submit" id="saveChanges" name="save-changes" class="btn btn-primary main-bg-color save-button rounded-3">Save Changes</button>
                                                <button type="button" id="cancelChanges" class="btn btn-secondary cancel-button rounded-3">Cancel</button>
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

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content d-flex align-items-center justify-content-center" id="success-modal">
                    <div class="modal-body text-center w-100">
                        <div class="col-md-12">
                            <img src="images/resc/icons/shield.png" class="change-passs-modal-icon" alt="shield-icon">
                        </div>
                        <p class="fw-bold fs-4 change-password-title spacing-4 mt-3">Change Password</p>
                        <p class="change-password-sub">For security purposes, please type your current password to proceed.</p>
                        <form id="currentPasswordForm" name="current-password-form" enctype="multipart/form-data">
                            <div class="col-12 col-md-12 mb-5">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="change-password" name="change-password" autocomplete="current-password" placeholder="Type here...">
                                    <button class="btn btn-secondary eye-toggle" type="button" id="password-toggle-1">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                    <div id="error-message" class="fs-7 mt-2 fw-medium invalid-feedback me-5">
                                        <!-- Display error messages here -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm fw-bold btn-secondary rounded-2 px-4 me-1" data-bs-dismiss="modal" id="cancel-modal">Cancel</button>
                                <button class="btn btn-sm delete-button rounded-2 py-1" type="submit" id="delete-button">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Max Limit Modal -->
        <div class="modal" id="maxLimitReachedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="max-modal">
                    <div class="modal-body">
                        <div class="d-flex justify-content-end">
                            <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray" role="button" data-bs-dismiss="modal" id="closeMaxLimitReachedModal"></i>
                        </div>
                        <div class="text-center">
                            <div class="col-md-12 mt-3 mb-3">
                                <img src="images/resc/warning.png" class="warning-icon" alt="Warning Icon">
                            </div>
                            <div class="row">
                                <div class="col-md-12 pb-3">
                                    <p class="fw-bold fs-3 spacing-4 limit" >Max Limit Reached</p>
                                    <p class="fw-medium max-text">Sorry, you've reached the maximum number of attempts to change your password. For security reasons, your session will be terminated. Please wait for <strong>30 minutes</strong> before trying again.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/footer.php'); ?>

        <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="scripts/loader.js"></script>
        <script src="scripts/feather.js"></script>
        <script src="scripts/edit-profile.js"></script>
        <script src="scripts/verify-password.js"></script>

    </body>
</html>

<?php
} else {
    header("Location: landing-page");
}
?>