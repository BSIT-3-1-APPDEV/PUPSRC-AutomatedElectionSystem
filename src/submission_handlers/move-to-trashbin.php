<?php
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');

if (isset($_POST['voter_id'])) {
    $voterManager = new VoterManager();
    $voter_id = $_POST['voter_id'];


    $inactive_query = "UPDATE voter SET account_status = 'invalid' WHERE voter_id = $voter_id";
    $voterManager->validateVoter($voter_id, $inactive_query);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Voter ID not provided']);
}