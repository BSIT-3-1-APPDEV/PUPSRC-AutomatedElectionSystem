<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

    $org_name = $_SESSION['organization'] ?? '';

    include '../includes/organization-list.php';
    
    $org_full_name = $org_full_names[$org_name];
$conn = DatabaseConnection::connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['position'])) {
    // Get the selected position from the AJAX request
    $selectedPosition = $_POST['position'];

// Get the current year
$currentYear = date("Y");
$nextYear = date("Y", strtotime("+1 year"));
$electionYear = $currentYear . '-' . $nextYear;

// Prepare and execute a query to fetch candidates for the selected position
$candidatesQuery = "SELECT c.*
                   FROM candidate c
                   INNER JOIN position p ON c.position_id = p.position_id
                   WHERE p.position_id IN (SELECT position_id FROM position WHERE title = ?)
                     AND c.election_year = ?";

$stmt = $conn->prepare($candidatesQuery);

// Bind the parameters: 's' for the string type of $selectedPosition and 'i' for the integer type of $currentYear
$stmt->bind_param('si', $selectedPosition, $electionYear);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

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

            // Check for errors in position query execution
            if (!$positionResult) {
                // Handle error - log or send appropriate response
                http_response_code(500);
                echo json_encode(['error' => 'Error executing position query']);
                exit(); // Exit the script
            }

            if ($positionResult->num_rows > 0) {
                $positionData = $positionResult->fetch_assoc();
                $candidate->positionTitle = $positionData['title'];
            } else {
                $candidate->positionTitle = 'Unknown Position'; // Default title if position not found
            }
    
            $stmtPos->close();
    
            // Add the candidate object to the candidates array
            $candidatesData[] = $candidate; // Use [] to append to the array
        }
    } else {
        // No candidates found for the selected position
        http_response_code(404);
        echo json_encode(['error' => 'No candidates found for the selected position']);
        exit(); // Exit the script
    }

    foreach ($candidatesData as $candidate) {
        $candidateVotesQuery = "SELECT COUNT(*) AS votes_count FROM vote WHERE candidate_id = ?";
        $stmtVotes = $conn->prepare($candidateVotesQuery);
        $stmtVotes->bind_param('i', $candidate->id);
        $stmtVotes->execute();
        $votesResult = $stmtVotes->get_result();

        // Check for errors in votes query execution
        if (!$votesResult) {
            // Handle error - log or send appropriate response
            http_response_code(500);
            echo json_encode(['error' => 'Error executing votes query']);
            exit(); // Exit the script
        }

        if ($votesResult->num_rows > 0) {
            $votesData = $votesResult->fetch_assoc();
            $candidate->votesCount = $votesData['votes_count'];
        } else {
            $candidate->votesCount = 0; // Default votes count if no votes found
        }

        $stmtVotes->close();
    }

    // Close the prepared statement
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Send the JSON-encoded candidatesData back to the client
    header('Content-Type: application/json');
    echo json_encode($candidatesData);
} else {
    // Invalid request, return an error response
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

// Exit the script to prevent any additional output
exit();
?>
