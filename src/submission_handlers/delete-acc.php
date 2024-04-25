<?php
require_once '../includes/classes/db-connector.php';
require_once '../includes/session-handler.php';
require_once '../includes/classes/session-manager.php';
require_once '../includes/classes/query-handler.php';

if (isset($_POST['voter_id'])) {
    $voterManager = new VoterManager();
    $voter_id = $_POST['voter_id'];


    $delete_query = "DELETE FROM voter WHERE voter_id = $voter_id";
    $voterManager->validateVoter($voter_id, $delete_query);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Voter ID not provided']);
}