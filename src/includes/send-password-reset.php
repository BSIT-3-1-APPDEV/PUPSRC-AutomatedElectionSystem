<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/mailer.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/classes/email-sender.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/error-reporting.php');
include_once FileUtils::normalizeFilePath(__DIR__. '/session-exchange.php');
include_once FileUtils::normalizeFilePath(__DIR__. '/default-time-zone.php');

// Set initial value of success key to false
$response = ['success' => false, 'message' => 'An error occurred'];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? NULL;    

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please provide a valid email address';
        echo json_encode(['success' => false, 'message' => $response['message']]);
        exit();
    }

    $connection = DatabaseConnection::connect();

    // Check if email exists
    $sql = "SELECT email, account_status FROM voter WHERE BINARY email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0) {
        $response['message'] = 'User with this email does not exist.';
        echo json_encode(['success' => false, 'message' => $response['message']]);
        exit();
    }

    $row = $result->fetch_assoc();

    if($row['account_status'] == 'invalid') {
        $response['message'] = 'This account has been rejected.';
        echo json_encode(['success' => false, 'message' => $response['message']]);
        exit();
    }

    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);

    // Password reset link available only in 30 mins
    $duration = time() + (60 * 30);
    $expiry = date("Y-m-d H:i:s", $duration); 

    $sql = "UPDATE voter SET reset_token_hash = ?, reset_token_expires_at = ? WHERE BINARY email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('sss', $token_hash, $expiry, $email);
    $stmt->execute();

    if($stmt->affected_rows) {     
        // Create an instance of EmailSender
        $send_email = new EmailSender($mail);
        $send_email->sendPasswordResetEmail($email, $token, $org_name);      
        // Set value of success key to true
        $response['success'] = true;
        echo json_encode($response);
        exit();   
    } else {
        $response['message'] = 'Failed to generate password reset link. Please try again.';
        echo json_encode(['success' => false, 'message' => $response['message']]);
        exit();
    } 
}