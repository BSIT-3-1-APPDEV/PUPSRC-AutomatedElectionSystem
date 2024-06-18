<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

$response = ['success' => false];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION['voter_id']);
    unset($_SESSION['maxLimit']);
    unset($_SESSION['correctPassword']);
    unset($_SESSION['role']);
    $response['success'] = true;
    echo json_encode($response);
    exit();
}
