<?php
// Include necessary files and start the session if not already started
session_start();
require_once '../includes/classes/db-config.php';
require_once '../includes/classes/db-connector.php';

// Establish the database connection
$conn = DatabaseConnection::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['position'])) {
    // Get the selected position from the AJAX request
    $selectedPosition = $_POST['position'];

    // Prepare and execute a query to fetch candidates for the selected position
    $candidatesQuery = "SELECT * FROM candidate WHERE position_id IN (SELECT position_id FROM position WHERE title = ?)";
    $stmt = $conn->prepare($candidatesQuery);
    $stmt->bind_param('s', $selectedPosition);
    $stmt->execute();
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
            $stmt_pos = $conn->prepare($positionQuery);
            $stmt_pos->bind_param('i', $candidate->positionId);
            $stmt_pos->execute();
            $positionResult = $stmt_pos->get_result();

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
    
            $stmt_pos->close();
    
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
        $stmt_votes = $conn->prepare($candidateVotesQuery);
        $stmt_votes->bind_param('i', $candidate->id);
        $stmt_votes->execute();
        $votesResult = $stmt_votes->get_result();

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

        $stmt_votes->close();
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
