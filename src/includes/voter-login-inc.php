<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('classes/voter-login-controller.php');
require_once FileUtils::normalizeFilePath('classes/voter-login-class.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

if(isset($_SESSION['voter_id']) || $_SESSION['organization'] == NULL) {
    $_SESSION['error_message'] = "Due to unusual activities we've detected from you. Your organization session was terminated.";
    unset($_SESSION['voter_id']);
    header("Location: ../landing-page.php");
    exit();
}

// Check if CSRF token isn't both set and submitted thru POST method
if(!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    displayUnsetToken();
}

// Validate CSRF token if it matches what is stored in session variable
if($_POST['csrf_token'] == $_SESSION['csrf_token']) {

    // Check if CSRF token isn't expired yet
    if(time() >= $_SESSION['csrf_expiry']) {
        displayUnsetToken();
    }

    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_expiry']);
}

// Catch invalid CSRF token
else {
    displayUnsetToken();
}

// Check the login form submission
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['sign-in'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Instantiates LoginController class
    $login = new LoginController($email, $password);

    // Run error handlers and redirects to intended page
    $login->loginUser();
}

function displayUnsetToken() {
    $_SESSION['error_message'] = 'Something went wrong. Please reload the page.';
    header("Location: ../voter-login.php");
    exit();
}
?>
