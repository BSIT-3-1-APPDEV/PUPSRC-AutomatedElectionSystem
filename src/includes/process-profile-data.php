<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
include_once FileUtils::normalizeFilePath('session-exchange.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('classes/csrf-token.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');

// CsrfToken::validateCsrfToken();

// Set initial value of success key to false
$response = ['success' => false, 'message' => 'An error occured'];

if($_SERVER["REQUEST_METHOD"] === "POST") {

    $last_name = trim($_POST['lastName']) ?? '';
    $first_name = trim($_POST['firstName']) ?? '';
    $middle_name = trim($_POST['middleName']) ?? '';
    $suffix = trim($_POST['suffix']) ?? '';
    $email = trim($_POST['email']) ?? '';

    // Validate supplied email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please provide a valid email address';
        echo json_encode($response);
        exit();
    }

    // Check if all required fields are empty
    if(empty($last_name) || empty($first_name) || empty($email)) {
        $response['message'] = 'Please fill in all required fields.';
        echo json_encode($response);
        exit();
    }

    $connection = DatabaseConnection::connect();
    $sql = "UPDATE voter SET last_name = ?, first_name = ?, middle_name = ?, suffix = ?, email = ? WHERE voter_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssssi", $last_name, $first_name, $middle_name, $suffix, $email, $_SESSION['voter_id']);
    
    if(!$stmt->execute()) {
        $response['message'] = 'Something went wrong. Please try again.';
        echo json_encode($response);
        exit();
    }

    $stmt->close();
    $connection->close();

    $response['success'] = true;
    $response['message'] = 'Profile updated successfully.';
    $response['timestamp'] = time();
    echo json_encode($response);
    exit();
}
else {
    $_SESSION['error_message'] = 'Something went wrong. Please try again.';
    header("Location: ../edit-profile");
    exit();
}