<?php
require_once '../includes/classes/db-connector.php';
require_once '../includes/session-handler.php';
require_once '../includes/classes/session-manager.php';
require_once '../includes/classes/query-handler.php';
require_once '../includes/mailer.php';
require_once '../includes/classes/email-sender.php';

if (isset($_POST['voter_id'])) {
if (isset($_POST['voter_id'])) {
    $voterManager = new VoterManager();
    $emailSender = new EmailSender($mail);
    $voter = new Voter();

    $emailSender = new EmailSender($mail);
    $voter = new Voter();

    $voter_id = $_POST['voter_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        // Updates status in database
        // Updates status in database
        $approve_query = "UPDATE voter SET status = 'Active' WHERE voter_id = $voter_id";
        $voterManager->validateVoter($voter_id, $approve_query);

        // Sending of email
        $recipientEmail = $voter->getEmailById($voter_id);
        $emailSender->sendApprovalEmail($recipientEmail);
        $conn->close();


        // Sending of email
        $recipientEmail = $voter->getEmailById($voter_id);
        $emailSender->sendApprovalEmail($recipientEmail);
        $conn->close();

    } elseif ($action == 'reject') {

        // Updates status in database

        // Updates status in database
        $reject_query = "UPDATE voter SET status = 'Rejected' WHERE voter_id = $voter_id";
        $voterManager->validateVoter($voter_id, $reject_query);

        // Sending of email
        $recipientEmail = $voter->getEmailById($voter_id);
        $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
        $otherReason = isset($_POST['otherReason']) ? $_POST['otherReason'] : '';
        $emailSender->sendRejectionEmail($recipientEmail, $reason, $otherReason);
        $conn->close();

        // Sending of email
        $recipientEmail = $voter->getEmailById($voter_id);
        $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
        $otherReason = isset($_POST['otherReason']) ? $_POST['otherReason'] : '';
        $emailSender->sendRejectionEmail($recipientEmail, $reason, $otherReason);
        $conn->close();
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Voter ID not provided']);
}
?>
?>