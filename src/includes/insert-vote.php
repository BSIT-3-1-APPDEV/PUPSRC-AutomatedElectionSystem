<?php 
session_start();
require_once '../includes/classes/db-config.php';
require_once '../includes/classes/db-connector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $conn = DatabaseConnection::connect();

    // Check if position and candidate arrays are set
    if (isset($_POST['position_id'], $_POST['candidate_id'], $_POST['voter_id'])) {
        // Sanitize input
        $voter_id = intval($_POST['voter_id']);

        // Iterate through each position
        foreach ($_POST['position_id'] as $position_id => $position_value) {
            // Check if a candidate is selected for the position
            if (isset($_POST['position'][$position_id]) && !empty($_POST['position'][$position_id])) {
                // Get the selected candidate ID
                $candidate_id = intval($_POST['position'][$position_id]);
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

        // Prepare and execute the SQL query to update voter status
        $stmt_vote = $conn->prepare("UPDATE voter SET vote_status = 'Voted' WHERE voter_id = ?");
        if (!$stmt_vote) {
            die("Error in SQL: " . $conn->error);
        }
        $stmt_vote->bind_param("i", $voter_id);
        if (!$stmt_vote->execute()) {
            die("Error executing statement: " . $stmt_vote->error);
        }
        $stmt_vote->close();

        // Redirect back to ballot forms to display the modal
        header("Location: ../../src/ballot-forms.php?success=1");
        exit();
    } 
} else {
    header("Location: ../../src/feedback-suggestions.php");
    exit();
}
?>
