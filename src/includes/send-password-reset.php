<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/session-manager.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/session-exchange.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/db-connector.php');

SessionManager::checkUserRoleAndRedirect();

if(!isset($_POST['send-email-btn'])) {
    $_SESSION['error_message'] = 'Something went wrong.';
    header("Location: ../voter-login.php");
    exit();    
}

$email = $_POST['email'];
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
    $mail = require FileUtils::normalizeFilePath(__DIR__ . '/mailer.php');

    // For now we set the url to localhost. This will be change
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";  // To change
    $mail->Body = <<<END

    Click <a href="http://localhost/PUPSRC-AutomatedElectionSystem/src/reset-password.php?token=$token">here</a> 
    to reset your password.

    END;

    try {
        $mail->send();
        header("Location: ../voter-login.php");
        exit();
    }
    catch(Exception $e) {
        echo "Mailer error: {$mail->ErrorInfo}";
    }
}

header("Location: ../voter-login.php");
echo "Reset password URL is sent to your email. Please check.";
exit();