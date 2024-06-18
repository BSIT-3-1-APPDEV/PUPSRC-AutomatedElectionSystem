<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/csrf-token.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/session-handler.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/session-exchange.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/error-reporting.php');

// CsrfToken::validateCSRFToken();

$response = ['success' => false, 'message' => 'An error occurred'];
$voter_id = $_SESSION['voter_id'];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    // Code here
    $password = trim($_POST['password']);
    $password_confirmation = trim($_POST['password_confirmation']);

    $error = newPasswordValidation($password);

    if($error) {
        encodeJSONResponse(['message' => $error]);
    }

    if ($password !== $password_confirmation) {
        encodeJSONResponse(['message' => 'Your passwords do not match.']);
    }

    $connection = DatabaseConnection::connect();

    $sql = "SELECT password FROM voter WHERe voter_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();
    $stmt->bind_result($current_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the password against the hashed(current) password
    if (password_verify($password, $current_password)) {
        encodeJSONResponse(['message' => 'New password cannot be the same with the current password.']);
    }

    $new_password = password_hash($password, PASSWORD_DEFAULT);

    // Updating password values in database
    $sql = "UPDATE voter SET password = ? WHERE voter_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $new_password, $voter_id);
    $success = $stmt->execute();
    $stmt->close();
    $connection->close();   

    if(!$success) {
        encodeJSONResponse(['message' => 'Something went wrong. Please try again.']);   
    }
    else {
        encodeJSONResponse(['success' => 'true']);
    }
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

function encodeJSONResponse($response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}