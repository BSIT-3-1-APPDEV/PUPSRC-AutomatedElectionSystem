<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, '../includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');


	include FileUtils::normalizeFilePath('includes/session-exchange.php');

	
if (isset($_POST['voter_id'])) {
    $voter_id = $_POST['voter_id'];
    
    // Establish database connection
    $conn = DatabaseConnection::connect();
    
    // Initialize the query executor
    $queryExecutor = new QueryExecutor($conn);
    

    $query = "SELECT c.*, p.title  FROM candidate c  LEFT JOIN position p ON c.position_id = p.position_id WHERE c.candidacy_status = 'removed' AND candidate_id = ?";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Check if $row is null
        if ($row === null) {
            // Convert null values to empty string
            echo json_encode(['title' => '', 'photo_url' => '', 'register_date' => '', 'first_name' => '', 'last_name' => '', 'middle_name' => '', 'suffix' => '', 'section' => '', 'year_level' => '', 'status_updated' => '']);
        } else {
            // Convert null values to empty string and return the fetched data
            echo json_encode([
                'title' => $row['title'] ?? '',
                'photo_url' => $row['photo_url'] ?? '',
                'register_date' => $row['register_date'] ?? '',
                'first_name' => $row['first_name'] ?? '',
                'last_name' => $row['last_name'] ?? '',
                'middle_name' => $row['middle_name'] ?? '',
                'suffix' => $row['suffix'] ?? '',
                'section' => $row['section'] ?? '',
                'year_level' => $row['year_level'] ?? '',
                'status_updated' => $row['status_updated'] ?? ''
            ]);
        }
    } else {
        // No record found
        echo json_encode(['error' => 'No record found']);
    }
    
} else {
    echo json_encode(['error' => 'Invalid request']);
}

