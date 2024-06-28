<?php 
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $conn = DatabaseConnection::connect();

    // Check if position and candidate arrays are set
    if (isset($_POST['position_id'], $_POST['candidate_id'], $_POST['voter_id'])) {
        // Sanitize input
        $voter_id = intval($_POST['voter_id']);

        // Flag to check if all votes are abstained
        $all_abstained = true;

        // Iterate through each position
        foreach ($_POST['position_id'] as $position_id => $position_value) {
            // Check if a candidate is selected for the position
            if (isset($_POST['position'][$position_id]) && !empty($_POST['position'][$position_id])) {
                // Get the selected candidate ID
                $candidate_id = intval($_POST['position'][$position_id]);
                $all_abstained = false; // At least one vote is not abstained
            } else {
                // Set candidate_id to NULL by default for abstain votes
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
        exit();
    } 
} else {
    header("Location: ../../src/ballot-forms");
    exit();
}
?>