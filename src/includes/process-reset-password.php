<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/session-manager.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/session-exchange.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/db-connector.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/error-reporting.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/default-time-zone.php');

if($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $token = $_POST['token'];
    $password = trim($_POST['password']);
    $password_confirmation = trim($_POST['password_confirmation']);
    $token_hash = hash("sha256", $token);

    $error = newPasswordValidation($password);

    if($error) {
        $_SESSION['error_message'] = $error;
        header("Location: ../reset-password?token=" . urlencode($token) . "&orgName=" . urlencode($org_name));;
        exit();
    }

    if ($password !== $password_confirmation) {
        $_SESSION['error_message'] = 'Your passwords do not match.';
        header("Location: ../reset-password?token=" . urlencode($token) . "&orgName=" . urlencode($org_name));
        exit();
    }

    $connection = DatabaseConnection::connect();

    $sql = "SELECT * FROM voter WHERE reset_token_hash = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        $_SESSION['error_message'] = 'Your password reset link was not found.';
        header("Location: ../voter-login");
        exit();
    }

    $expiry_time = strtotime($row["reset_token_expires_at"]);
    $current_time = time();

    if ($expiry_time <= $current_time) {
        $_SESSION['error_message'] = 'Your password reset link has expired.';
        header("Location: ../voter-login");
        exit();
    }

    $new_password = password_hash($password_confirmation, PASSWORD_DEFAULT);

    // Updating password values in database
    $sql = "UPDATE voter SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE BINARY email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('si', $new_password, $row['email']);
    $success = $stmt->execute();

    if($success) {
        echo json_encode(['success' => true]);
        exit(); 
    }
    else {
        $_SESSION['error_message'] = "Failed to reset your password. Please try again.";
        header("Location: ../reset-password?token=" . urlencode($token) . "&orgName=" . urlencode($org_name));
    }
    exit();    
}

// Validate new password 
function newPasswordValidation($password) {
    if (strlen($password) < 8 || strlen($password) > 20) {
        return "Your password must be between 8 and 20 characters long.";
    }
    if (!preg_match("/\d/", $password)) {
        return "Your password must contain at least 1 number.";
    }
    if (!preg_match("/[A-Z]/", $password)) {
        return "Your password must contain at least 1 uppercase letter.";
    }
    if (!preg_match("/[a-z]/", $password)) {
        return "Your password must contain at least 1 lowercase letter.";
    }
    if (!preg_match("/[\W_]/", $password)) {
        return "Your password must contain at least 1 special character.";
    }
    if (preg_match("/\s/", $password)) {
        return "Your password must not contain any spaces.";
    }
    return "";
}