<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, '../includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');

if (isset($_POST['voter_id'])) {
    $voterManager = new VoterManager();
    $voter_id = $_POST['voter_id'];

    $inactive_query = "UPDATE voter SET account_status = 'invalid' WHERE voter_id = ?";
    $stmt = $voterManager->prepare($inactive_query);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Voter ID not provided']);
}
?>