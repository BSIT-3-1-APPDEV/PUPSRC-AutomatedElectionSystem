<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('classes/registration-class.php');
require_once FileUtils::normalizeFilePath('classes/csrf-token.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sign-up"])) {

    if(!CsrfToken::validateCSRFToken()) {
        $_SESSION['error_message'] = 'Something went wrong. Please reload the page.';
        header("Location: ../register");
        exit();
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $retype_password = trim($_POST['retype-pass']);
    $organization = $_POST['org'];
    $cor = $_FILES['cor'];

    // Instantiate a new instance of Registration class
    $process_registration = new Registration($email, $password, $retype_password, $organization, $cor);
    $process_registration->processRegistrationCredentials();
}
