<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/mailer.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/email-sender.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/error-reporting.php');

if(!isset($_POST['send-email-btn'])) {
    $_SESSION['error_message'] = 'Something went wrong.';
    header("Location: ../voter-login.php");
    exit();    
}

$email = $_POST['email'];
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = 'Please provide a valid email';
    header("Location: ../voter-login.php");
    exit();
}

$connection = DatabaseConnection::connect();

// Check if email exists
$sql = "SELECT email FROM voter WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$row = $stmt->get_result();

if($row->num_rows === 0) {
    $_SESSION['notice_message'] = 'User with this email does not exist.';
    header("Location: ../voter-login.php");
    exit();
}

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30); // Password reset link available only in 30 mins

$sql = "UPDATE voter SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('sss', $token_hash, $expiry, $email);
$stmt->execute();

if($connection->affected_rows) {
    $send_email = new EmailSender($mail);
    $send_email->sendPasswordResetEmail($email, $token); 
}

header("Location: ../voter-login.php");
exit();