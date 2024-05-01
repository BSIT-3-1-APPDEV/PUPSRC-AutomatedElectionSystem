<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/session-manager.php');
include FileUtils::normalizeFilePath(__DIR__ . '/includes/session-exchange.php');

SessionManager::checkUserRoleAndRedirect();

/* Generates hexadecimal token that expires in 30 minutes
   to avoid Cross-Site Request Forgery */
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$_SESSION['csrf_expiry'] = time() + (60 * 30);

if(isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    // Unset the error message from the session once displayed
    unset($_SESSION['error_message']); 
}

if(isset($_SESSION['info_message'])) {
    $info_message = $_SESSION['info_message'];
    // Unset the info message from the session once displayed
    unset($_SESSION['info_message']); 
}

?>

<!DOCTYPE html>
<html lang="en">

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
    <link rel="icon" href="images/logos/<?php echo $org_name; ?>.png" type="image/x-icon">
    <title>Login</title>
</head>

<body class="login-body" id="<?php echo strtoupper($org_name); ?>-body">
    <nav class="navbar navbar-expand-lg fixed-top" id="login-navbar">
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <a href="landing-page.php"><img src="images/resc/iVOTE-Landing2.png" id="ivote-logo-landing-header" alt="ivote-logo"></a>
        </div>
    </nav>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 login-left-section">
                <div class="organization-names">
                    <img src="images/logos/<?php echo $org_name; ?>.png" class="img-fluid login-logo" alt="<?php echo strtoupper($org_name) . ' '; ?>Logo">
                    <p><?php echo strtoupper($org_full_name); ?></p>
                    <h1 class="login-AES">AUTOMATED ELECTION SYSTEM</h1>

                    <div class="login-wave-footer" id="<?php echo strtoupper($org_name); ?>-wave">
                        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 login-right-section">

                <div>
                    <form action="voter-login-inc.php" method="post" class="login-form needs-validation" novalidate>                
                        <h1 class="login-account">Login Account</h1>
                        <p>Sign in to your account</p>

                        <!--Displays error message-->
                        <?php if (isset($error_message)) : ?>
                        <div class="fw-medium border border-danger bg-transparent text-danger alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle flex-shrink-0 me-2" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                            </svg>
                            <div>
                                <span class="pe-1"><?php echo $error_message; ?></span>
                                <button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!--Displays info message-->
                        <?php if (isset($info_message)) : ?>
                        <div class="fw-medium border border-primary bg-transparent text-primary alert alert-primary alert-dismissible fade show d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle flex-shrink-0 me-2" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                            </svg>                            
                            <div>
                                <span class="pe-1"><?php echo $info_message; ?></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-12 mt-4 mb-3">
                            <input type="email" class="form-control" id="Email" name="email" placeholder="Email Address" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$">
                        </div>

                        <div class="col-md-12 mb-2">
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" id="Password" required>
                                <button class="btn btn-secondary" type="button" id="password-toggle">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <a href="forgot-password.php" class="text-align-start" >Forgot Password</a>

                        <div class="d-grid gap-2 mt-5 mb-4">
                            <!-- <button class="btn btn-primary" name="sign_in" type="submit">Sign In</button> -->
                            <button class="btn login-sign-in-button" id="<?php echo strtoupper($org_name); ?>-login-button"  name="sign-in" type="submit">Sign In</button>
                        </div>
                        <p>Don't have an account? <a href="#" id="<?php echo strtolower($org_name); ?>SignUP">Sign Up</a></p>
                        <input type="hidden" name="csrf_token" value="<?=$_SESSION['csrf_token'] ;?>">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Updated script for password toggle -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const togglePassword = document.querySelector("#password-toggle");
            const passwordInput = document.querySelector("#Password");
            const eyeIcon = togglePassword.querySelector("i");

            togglePassword.addEventListener("click", function() {
                const type =
                    passwordInput.getAttribute("type") === "password" ?
                    "text" :
                    "password";
                passwordInput.setAttribute("type", type);

                // Toggle eye icon classes
                eyeIcon.classList.toggle("fa-eye-slash");
                eyeIcon.classList.toggle("fa-eye");
            });
        });
    </script>

</body>

</html>