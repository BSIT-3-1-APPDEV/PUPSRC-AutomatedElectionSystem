<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/db-connector.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/default-time-zone.php');

SessionManager::checkUserRoleAndRedirect();

$token = $_GET['token'];
$token_hash = hash('sha256', $token);

// Get the org name from the URL
$url_org_name = $_GET['orgName'];
$org_name = $url_org_name;
$_SESSION['organization'] = $org_name;

include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-exchange.php');

$connection = DatabaseConnection::connect();

$sql = "SELECT reset_token_hash, reset_token_expires_at FROM voter WHERE reset_token_hash = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    $_SESSION['error_message'] = 'Reset link was not found.';
    header("Location: voter-login.php");
    exit();
}

$expiry_time = strtotime($row["reset_token_expires_at"]);
$current_time = time();

if ($expiry_time <= $current_time) {
    $_SESSION['error_message'] = 'Reset link has expired.';
    header("Location: voter-login.php");
    exit();
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Unset the error message from the session once displayed
}

?>

<!--Modify the html and css of this. This page is for resetting the password-->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fontawesome Link for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Online Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Akronim&family=Anton&family=Aoboshi+One&family=Audiowide&family=Black+Han+Sans&family=Braah+One&family=Bungee+Outline&family=Hammersmith+One&family=Krona+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="styles/dist/landing.css">
    <link rel="stylesheet" href="styles/loader.css">
    <link rel="stylesheet" href="styles/orgs/<?php echo $org_name; ?>.css">
    <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
    <title>Reset Password</title>
</head>

<body class="login-body reset-password-body">

    <?php
    include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
    ?>

    <nav class="navbar navbar-expand-lg fixed-top" id="login-navbar">
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <a href="landing-page.php"><img src="images/resc/iVOTE-Landing2.png" id="ivote-logo-landing-header" alt="ivote-logo"></a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-md-6 reset-password-form">
                <form class="needs-validation" id="reset-password-form" novalidate enctype="multipart/form-data">
                    <input type="hidden" id="token" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="img-container">
                        <img src="images/resc/icons/forgot-pass.png" alt="Forgot Password Icon" class="forgot-password-icon">
                    </div>

                    <div class="form-group">
                        <h4 class="reset-password-title" id="scoSignUP">Set your new password</h4>
                        <p class="reset-password-subtitle">Let's keep your account safe! Please choose a strong password for added security.</p>
                        
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
                                    <input type="password" class="form-control reset-password-password" name="password" onkeypress="return avoidSpace(event)" placeholder="Enter a strong password" id="password" required>
                                    <label for="password" class="new-password translate-middle-y" id="scoSignUP">NEW PASSWORD</label>
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
                                    <input type="password" class="form-control reset-password-password" onkeypress="return avoidSpace(event)" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                                    <label for="password_confirmation" class="new-password translate-middle-y" id="scoSignUP">CONFIRM PASSWORD</label>
                                    <button class="btn btn-secondary reset-password-password" type="button" id="reset-password-toggle-2">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="password-mismatch-error" class="text-danger" style="display: none;">Passwords do not match.</div>

                        </div>
                        <div class="col-md-12 reset-pass">
                            <!-- <button class="btn login-sign-in-button mt-4" id="<?php echo strtoupper($org_name); ?>-login-button" type="submit" name="reset-password-submit" id="reset-password-submit">Set Password</button> -->
                            <button class="btn login-sign-in-button mt-3 mb-3" id="SCO-login-button" type="submit" name="new-password-submit">Set Password</button>
                            
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Success Modal -->
    <div class="modal" id="successPasswordResetModal" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="col-md-12">
                            <img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
                        </div>

                        <div class="row">
                            <div class="col-md-12 pb-3">
                                <p class="fw-bold fs-3 text-success spacing-4">Password Updated!</p>
                                <p class="fw-medium spacing-5">Your password has been changed successfully. Use your new password to log in.
                                </p>
                                <!-- Button to redirect to login here -->
                                <!-- Sample only -->
                                <a href="voter-login.php">Go to Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="scripts/reset-password.js"></script>
    <script src="scripts/loader.js"></script>

</body>

</html>