<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
include_once FileUtils::normalizeFilePath('session-exchange.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('classes/manage-ip-address.php');
require_once FileUtils::normalizeFilePath('classes/csrf-token.php');
include_once FileUtils::normalizeFilePath('default-time-zone.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');

const ATTEMPTS_LIMIT = 5;
const BLOCK_TIME = 1800; // 30 mins
$time = time() - BLOCK_TIME;

$response = ['success' => false, 'maxLimit' => false, 'message' => 'An error occurred'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = $_POST;
    if (isset($input['password'])) {
        $current_password = $input['password'];
        $voter_id = $_SESSION['voter_id'];

        $connection = DatabaseConnection::connect();
        
        $ip_manager = new IpAddress();
        $ip_address = IpAddress::getIpAddress();

        isIpAddressBlocked($ip_manager, $ip_address, $time);

        $sql = "SELECT password FROM voter WHERE voter_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $voter_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verify the password against the hashed password
        if (password_verify($current_password, $hashed_password)) {
            $ip_manager->deleteIpAddress($ip_address);
            $_SESSION['correctPassword'] = true;
            encodeJSONResponse(['success' => true]);
        } 
        else {
            $ip_manager->storeIpAddress($ip_address, time());

            isIpAddressBlocked($ip_manager, $ip_address, $time);
            $count_attempts = $ip_manager->countIpAddressAttempt($ip_address, $time);

            $remaining_attempts = ATTEMPTS_LIMIT - $count_attempts;
            encodeJSONResponse(['message' => 'Incorrect password. Attempts left: ' . $remaining_attempts]);
        }
    } 
    else {
        encodeJSONResponse(['message' => 'Password not provided']);
    }
} 
else {
    encodeJSONResponse(['message' => 'Invalid request. Please try again.']);
}

// Check if a user of a certain ip address exceeds attempt limit within 30 minutes
function isIpAddressBlocked($ip_manager, $ip_address, $time) {
    $count_attempts = $ip_manager->countIpAddressAttempt($ip_address, $time);

    if($count_attempts >= ATTEMPTS_LIMIT) {
        $_SESSION['time'] = $time;
        $_SESSION['isBlocked'] = true;
        encodeJSONResponse(['maxLimit' => true]);
    }
}

function encodeJSONResponse($response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}