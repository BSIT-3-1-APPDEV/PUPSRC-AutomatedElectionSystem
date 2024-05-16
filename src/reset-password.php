<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/db-connector.php');

// SessionManager::checkUserRoleAndRedirect();

$token = $_GET['token'];
$token_hash = hash('sha256', $token);

// Get the org name from the URL
$url_org_name = $_GET['orgName'];
$org_name = $url_org_name;
$_SESSION['organization'] = $org_name;

include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-exchange.php');

$token = $_GET['token'];
$token_hash = hash('sha256', $token);

$connection = DatabaseConnection::connect();

$sql = "SELECT reset_token_hash, reset_token_expires_at FROM voter WHERE reset_token_hash = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['reset_token_hash'] == NULL) {
    $_SESSION['error_message'] = 'Reset link was not found.';
    header("Location: voter-login.php");
    exit();
}

if (strtotime($row["reset_token_expires_at"]) <= time()) {
    $_SESSION['error_message'] = 'Reset link has expired.';
    header("Location: voter-login.php");
    exit();
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
    <link rel="stylesheet" href="styles/orgs/<?php echo $org_name; ?>.css">
    <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
    <title>Reset Password</title>
</head>

<body class="login-body reset-password-body">

    <nav class="navbar navbar-expand-lg fixed-top" id="login-navbar">
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <a href="landing-page.php"><img src="images/resc/iVOTE-Landing2.png" id="ivote-logo-landing-header" alt="ivote-logo"></a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-md-6 reset-password-form">
                <form method="post" action="includes/process-reset-password.php" class="needs-validation" id="reset-password-form" novalidate action="BABAGUHIN ITU.php" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="img-container">
                        <img src="images/resc/icons/forgot-pass.png" alt="Forgot Password Icon" class="forgot-password-icon">
                    </div>

                    <div class="form-group">
                        <h4 class="reset-password-title" id="scoSignUP">Set your new password</h4>
                        <p class="reset-password-subtitle">Let's keep your account safe! Please choose a strong password for added security.</p>

                        <div class="row mt-5 mb-3 reset-pass">
                            <div class="col-md-8 mb-2 position-relative">
                                <div class="input-group mb-3" id="reset-password">
                                    <input type="password" class="form-control reset-password-password" name="password" placeholder="Enter a strong password" id="password" required>
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
                                    <input type="password" class="form-control reset-password-password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
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
                            <button class="btn login-sign-in-button mt-3 mb-3" id="SCO-login-button" type="submit" data-bs-toggle="modal" data-bs-target="#forgot-password-modal">Set Password</button>
                            
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Modals -->
    <div class="modal fade" id="forgot-password-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="m justify-content-center">
                    <h1 class="modal-title fs-5 fw-bold mb-2" id="<?php echo strtolower($org_name); ?>SignUP">Forgot Password
                        <!-- </h1><hr> -->
                </div>
                <div class="modal-body">
                    <form action="includes/send-password-reset.php" method="post" class="needs-validation" id="forgot-password-form" name="forgot-password-form" novalidate enctype="multipart/form-data">
                        <div class="col-12 col-md-12">
                            <div class="d-flex align-items-start mb-0 pb-0">
                                <!-- <p for="email" class="form-label text-start ps-1">We will send a password reset link to your registered email address.</p> -->
                                <p>Email Address</p>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" onkeypress="return avoidSpace(event)" placeholder="Email Address" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$">
                            <div class="invalid-feedback text-start">
                                Please provide a valid email.
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="row reset-pass">
                                <div class="col-4">
                                    <button type="button" id="sendPasswordResetLink" class="btn cancel-button w-100 mt-4" data-bs-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col-4">
                                    <button class="btn login-sign-in-button w-100 mt-4" id="<?php echo strtoupper($org_name); ?>-login-button" type="submit" name="send-email-btn">Send</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>



    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/reset-password.js"></script>

</body>

</html>