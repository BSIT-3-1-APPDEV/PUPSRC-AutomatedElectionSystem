<?php
require_once '../includes/classes/db-connector.php';
require_once '../includes/session-handler.php';

// Check if the user is logged in and has an organization session
if (!isset($_SESSION['organization'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

$org_name = $_SESSION['organization'];
include '../includes/organization-list.php';
$org_full_name = $org_full_names[$org_name];

// Connect to the database
$conn = DatabaseConnection::connect();

// Prepare and execute a query to fetch all candidates
$candidatesQuery = "SELECT * FROM candidate";
$result = $conn->query($candidatesQuery);

$candidatesData = []; // Initialize an empty array to store candidate data

// Process the fetched rows into an array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $candidate = new stdClass(); // Create a new stdClass object for each candidate
        $candidate->id = $row['candidate_id'];
        $candidate->firstName = $row['first_name'];
        $candidate->lastName = $row['last_name'];
        $candidate->photoUrl = $row['photo_url'];
        $candidate->positionId = $row['position_id'];

        // Fetch the position title based on the position ID
        $positionQuery = "SELECT title FROM position WHERE position_id = ?";
        $stmtPos = $conn->prepare($positionQuery);
        $stmtPos->bind_param('i', $candidate->positionId);
        $stmtPos->execute();
        $positionResult = $stmtPos->get_result();

        if ($positionResult->num_rows > 0) {
            $positionData = $positionResult->fetch_assoc();
            $candidate->positionTitle = $positionData['title'];
        } else {
            $candidate->positionTitle = 'Unknown Position'; // Default title if position not found
        }

        $stmtPos->close();

        // Add the candidate object to the candidates array
        $candidatesData[] = $candidate;
    }
} else {
    // No candidates found
    http_response_code(404);
    echo json_encode(['error' => 'No candidates found']);
    exit();
}

// Fetch votes count for each candidate
foreach ($candidatesData as $candidate) {
    $candidateVotesQuery = "SELECT COUNT(*) AS votes_count FROM vote WHERE candidate_id = ?";
    $stmtVotes = $conn->prepare($candidateVotesQuery);
    $stmtVotes->bind_param('i', $candidate->id);
    $stmtVotes->execute();
    $votesResult = $stmtVotes->get_result();

    if ($votesResult->num_rows > 0) {
        $votesData = $votesResult->fetch_assoc();
        $candidate->votesCount = $votesData['votes_count'];
    } else {
        $candidate->votesCount = 0; // Default votes count if no votes found
    }

    $stmtVotes->close();
}

// Close the database connection
$conn->close();

// Send the JSON-encoded candidatesData back to the client
header('Content-Type: application/json');
echo json_encode($candidatesData);
