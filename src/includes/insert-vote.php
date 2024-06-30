<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Establish database connection
    $conn = DatabaseConnection::connect();

    if (isset($_POST['position'], $_POST['candidate_id'], $_POST['voter_id'])) {

        $voter_id = $_POST['voter_id'];
        $vote_status = 'voted'; // Default to voted

        // Check if all positions are abstained
        $allAbstained = true;

        foreach ($_POST['position'] as $position_id => $candidates) {
            foreach ($candidates as $candidate_id) {
                // Check if any candidate_id is not empty (not abstained)
                if ($candidate_id !== '') {
                    $allAbstained = false;
                    break 2; // Exit both loops
                }
            }
        }

        // Set vote status based on allAbstained
        if ($allAbstained) {
            $vote_status = 'abstained';
        }

        // Insert votes into the database
        foreach ($_POST['position'] as $position_id => $candidates) {
            foreach ($candidates as $candidate_id) {

                // Check if abstain option is selected (value will be empty string '')
                if ($candidate_id === '') {
                    // If abstain, set candidate_id to NULL
                    $candidate_id = null;
                }

                // Prepare and execute the SQL query to insert the vote into the database
                $stmt = $conn->prepare("INSERT INTO vote (position_id, candidate_id) VALUES (?, ?)");
                if (!$stmt) {
                    die("Error in SQL: " . $conn->error);
                }
                $stmt->bind_param("ii", $position_id, $candidate_id);
                if (!$stmt->execute()) {
                    die("Error executing statement: " . $stmt->error);
                }
                $stmt->close();
            }
        }
        
        // Determine the voter status based on whether all votes were abstained
        $vote_status = $all_abstained ? 'Abstained' : 'Voted';

        // Prepare and execute the SQL query to update voter status
        $stmt_vote = $conn->prepare("UPDATE voter SET vote_status = ? WHERE voter_id = ?");
        if (!$stmt_vote) {
            die("Error in SQL: " . $conn->error);
        }
        $stmt_vote->bind_param("si", $vote_status, $voter_id);
        if (!$stmt_vote->execute()) {
            die("Error executing statement: " . $stmt_vote->error);
        }
        $stmt_vote->close();

        // need to define directly
        if ($vote_status == 'voted'){
            $_SESSION['vote_status'] = 'voted';
        } else {
            $_SESSION['vote_status'] = 'abstained';
        }   
    }
}
?>
