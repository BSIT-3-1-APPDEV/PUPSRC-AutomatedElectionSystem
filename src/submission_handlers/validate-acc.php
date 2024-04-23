<?php
require_once '../includes/classes/db-connector.php';
require_once '../includes/session-handler.php';
require_once '../includes/classes/session-manager.php';
require_once '../includes/classes/insertion.php';

if(isset($_POST['voter_id'])) {

    $voterManager = new VoterManager();
    $voter_id = $_POST['voter_id'];

    $approve_query = "UPDATE voter SET status = 'Active' WHERE voter_id = $voter_id";
    $voterManager->approveVoter($voter_id, $approve_query);
}
?>