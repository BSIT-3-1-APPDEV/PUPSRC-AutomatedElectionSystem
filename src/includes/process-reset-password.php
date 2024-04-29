<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/session-manager.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/session-exchange.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/db-connector.php');

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

$connection = DatabaseConnection::connect();

$sql = "SELECT * FROM voter WHERE reset_token_hash = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row === NULL) {
    die("Token not found.");
}

if (strtotime($row["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}

// Put here Password field validation

// POST method for reset button

if ($_POST['password'] !== $_POST['password_confirmation']) {
    die("Passwords must match");
}

$new_password = $_POST['password_confirmation'];

// Updating password values in database
$sql = "UPDATE voter SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE voter_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('si', $new_password, $row['voter_id']);
$stmt->execute();
$stmt->close();


// $_SESSION['success_message'] = 'Successfully updated password. You can now login';
header("Location: ../voter-login.php");
exit();