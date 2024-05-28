<?php
require_once '../includes/classes/db-connector.php';
require_once '../includes/session-handler.php';
require_once '../includes/classes/session-manager.php';
require_once '../includes/classes/query-handler.php';

if(isset($_POST['voter_id'])) {
    $voterManager = new VoterManager();
    $voter_id = $_POST['voter_id'];
    $status = $_POST['status'];

    if ($status == 'Active') {
        $active_query = "UPDATE voter SET account_status = 'verified' WHERE voter_id = $voter_id";
        $voterManager->validateVoter($voter_id, $active_query);
    } elseif ($status == 'Disabled') {
        $inactive_query = "UPDATE voter SET account_status = 'Inactive' WHERE voter_id = $voter_id";
        $voterManager->validateVoter($voter_id, $inactive_query);
    } elseif ($status == 'Reject') {
        $for_verif_query = "UPDATE voter SET account_status = 'Rejected' WHERE voter_id = $voter_id";
        $voterManager->validateVoter($voter_id, $for_verif_query);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Voter ID not provided']);
}