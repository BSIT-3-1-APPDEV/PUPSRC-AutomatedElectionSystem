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
    
    // Check if a record was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['title' => $row['title'], 'photo_url' => $row['photo_url'],'register_date' => $row['register_date'], 'first_name' => $row['first_name'], 'last_name' => $row['last_name'], 'middle_name' => $row['middle_name'], 'suffix' => $row['suffix'],  'section' => $row['section'], 'year_level' => $row['year_level'], 'status_updated' => $row['status_updated']  ]);
    } else {
        echo json_encode(['error' => 'No record found']);
    }
    
    // Close statement and connection

} else {
    echo json_encode(['error' => 'Invalid request']);
}

