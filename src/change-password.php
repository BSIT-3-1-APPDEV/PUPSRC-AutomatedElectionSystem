<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/csrf-token.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

    include FileUtils::normalizeFilePath('includes/session-exchange.php');

    if(!isset($_SESSION['correctPassword'])) {
        header("Location: " . $_SESSION['referringPage']);
        exit(); 
    }
    else {
        if(!$_SESSION['correctPassword'] === true) {
            header("Location: " . $_SESSION['referringPage']);
            exit(); 
        }
    }

    $_SESSION['referringPage'] = $_SERVER['PHP_SELF'];

    if(isset($_SESSION['isBlocked']) && $_SESSION['isBlocked'] === true) {
        session_destroy();
    }

    // $csrf_token = CsrfToken::generateCSRFToken();

    if (isset($_SESSION['error_message'])) {
        $error_message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    }
    
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
        <link rel="stylesheet" href="styles/dist/landing.css" />
        <link rel="stylesheet" href="styles/loader.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="scripts/loader.js" defer></script>
    </head>

    <body>

        <?php 
        include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
        include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
        ?>

        <div class="main" id="change-password">
            <div class="container">
                <div class="row justify-content-center">
                    <!-- FOR VERIFICATION TABLE -->
                    <div class="col-md-10 card-box">
                        <div class="table-wrapper" id="profile">
                            <form class="needs-validation" id="reset-password-form" novalidate enctype="multipart/form-data">
                                
                                <!-- CSRF Token hidden field -->
                                <!-- <input type="hidden" name="csrf_token" value="<?php // echo $csrf_token; ?>">               -->
                                
                                <div class="img-container">
                                    <img src="images/resc/Change-Pass/<?php echo strtolower($org_name); ?>-change-pass.png" alt="Forgot Password Icon" class="forgot-password-icon">
                                </div>

                                <div class="form-group">
                                    <h4 class="reset-password-title text-center <?php echo strtoupper($org_name); ?>-text-color" id="">Set your new password</h4>
                                    <p class="reset-password-subtitle text-center">Let's keep your account safe! Please choose a strong <br>password for added security.</p>

                                    <!-- Displays error message -->
                                    <?php if (isset($error_message)) : ?>
                                        <div class="d-flex align-items-center justify-content-center mb-0">
                                            <div class="fw-medium border border-danger text-danger alert alert-danger alert-dismissible fade show  custom-alert" role="alert">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle flex-shrink-0 me-2" viewBox="0 0 16 16">
                                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                                </svg>
                                                <div class="d-flex align-items-center">
                                                    <span class="pe-1"><?php echo $error_message; ?></span>
                                                    <button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="row mt-5 mb-3 reset-pass">
                                        <div class="col-md-8 mb-2 position-relative">
                                            <div class="input-group mb-3" id="reset-password">
                                                <input type="password" class="form-control reset-password-password" name="password" placeholder="Enter a strong password" id="password" required>
                                                <label for="password" class="new-password translate-middle-y <?php echo strtoupper($org_name); ?>-text-color">NEW PASSWORD</label>
                                                <button class="btn btn-secondary reset-password-password" type="button" id="reset-password-toggle-1">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                            <div class="password-requirements">
                                                <ul id="password-requirements-list">
                                                    <h6 class="reset-pass-title">YOUR PASSWORD MUST CONTAIN:</h6>
                                                    <li id="length" class="requirement unmet"><span class="requirement-circle"></span> Between 8 and 20 characters</li>
                                                    <li id="uppercase" class="requirement unmet"><span class="requirement-circle"></span> At least 1 uppercase letter</li>
                                                    <li id="lowercase" class="requirement unmet"><span class="requirement-circle"></span> At least 1 lowercase letter</li>
                                                    <li id="number" class="requirement unmet"><span class="requirement-circle"></span> At least 1 number</li>
                                                    <li id="special" class="requirement unmet"><span class="requirement-circle"></span> At least 1 special character</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-8 mb-0 mt-0 position-relative">
                                            <div class="input-group" id="reset-password">
                                                <input type="password" class="form-control reset-password-password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                                                <label for="password_confirmation" class="new-password translate-middle-y <?php echo strtoupper($org_name); ?>-text-color">CONFIRM PASSWORD</label>
                                                <button class="btn btn-secondary reset-password-password" type="button" id="reset-password-toggle-2">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                            <div id="password-mismatch-error" class="text-center ps-1 text-danger fw-semibold fs-7 mt-2" style="display: none;">PASSWORDS DO NOT MATCH.</div>
                                            <div id="error-message" class="text-center ps-1 text-danger fw-semibold fs-7 mt-2 text-uppercase" >
                                                <!-- Display error message -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 reset-pass">
                                        <button class="btn login-sign-in-button fw-semibold my-3 px-4 rounded-2" id="<?php echo strtoupper($org_name); ?>-login-button" type="submit" name="change-password-submit">Set Password</button>
                                        <script>
                                            const ORG_NAME = "<?php echo strtoupper($org_name) . '-login-button'; ?>";
                                        </script>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Change Password Modal -->
        <div class="modal fade" id="successChangePasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content d-flex align-items-center justify-content-center" id="success-modal">
                    <div class="modal-body text-center w-100">
                        <div class="col-md-12">
                            <img src="images/resc/check-animation.gif" class="success-change-pass-modal-icon" alt="iVote Logo">
                        </div>
                        <p class="fw-bold fs-4 change-password-title spacing-4 mt-2" id="password-updated">Password Updated</p>
                        <p class="change-password-sub mb-4">Your password has been successfully updated! Please log in again.</p>
                        <a href="voter-login.php" class="btn btn-primary px-5 success-change-pass-button fw-semibold" id="success-change-pass-button">Go to Landing Page</a>
                        <p class="timer mt-2 mb-0 pb-0">Redirecting to landing page <strong>10</strong> seconds...</p>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once __DIR__ . '/includes/components/footer.php'; ?>

        <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/feather.js"></script>
        <script src="scripts/change-password.js"></script>

    </body>
</html>

<?php
} else {
    header("Location: landing-page");
}
?>