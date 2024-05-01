<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/voter-login-controller.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/voter-login-class.php');

// Check if CSRF token isn't both set and submitted thru POST method
if(!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    $_SESSION['error_message'] = 'Login token is not set.';
    header("Location: voter-login.php");
    exit();
}

// Validate CSRF token if it matches what is stored in session variable
if($_POST['csrf_token'] == $_SESSION['csrf_token']) {

    // Check if CSRF token isn't expired yet
    if(time() >= $_SESSION['csrf_expiry']) {
        $_SESSION['error_message'] = 'Login token is expired. Reload the page';
        header("Location: voter-login.php");
        exit();      
    }

    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_expiry']);
}

// Catch invalid CSRF token
else {
    $_SESSION['error_message'] = 'Invalid login token. Reload the page.';
    header("Location: voter-login.php");
    exit();   
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
?>
