<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, '../includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('../includes/mailer.php');
require_once FileUtils::normalizeFilePath('../includes/classes/email-sender.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {
    $voterManager = new VoterManager();
    $emailSender = new EmailSender($mail);
    $voter = new Voter();

    $voter_id = $_POST['voter_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        // Updates status in database
        $approve_query = "UPDATE voter SET account_status = 'verified' WHERE voter_id = $voter_id";
        $voterManager->validateVoter($voter_id, $approve_query);

        // Sending of email
        $recipientEmail = $voter->getEmailById($voter_id);
        $emailSender->sendApprovalEmail($recipientEmail);

    } elseif ($action == 'reject') {

        // Updates status in database
        $reject_query = "UPDATE voter SET account_status = 'invalid' WHERE voter_id = $voter_id";
        $voterManager->validateVoter($voter_id, $reject_query);

        // Sending of email
        $recipientEmail = $voter->getEmailById($voter_id);
        $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
        $otherReason = isset($_POST['otherReason']) ? $_POST['otherReason'] : '';
        $emailSender->sendRejectionEmail($recipientEmail, $reason, $otherReason);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Voter ID not provided']);
}
?>