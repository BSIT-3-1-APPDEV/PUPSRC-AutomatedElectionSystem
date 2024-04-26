<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/voter-login-controller.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/voter-login-class.php');

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['sign_in'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Instantiates LoginController class
    $login = new LoginController($email, $password);

    // Run error handlers and redirects to intended page
    $login->loginUser();
}
?>
