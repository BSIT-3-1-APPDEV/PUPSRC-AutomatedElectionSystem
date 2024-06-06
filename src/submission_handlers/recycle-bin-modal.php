<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, '../includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');


	include FileUtils::normalizeFilePath('includes/session-exchange.php');

	
if (isset($_POST['voter_id'])) {
    $voter_id = $_POST['voter_id'];
    
    // Establish database connection
    $conn = DatabaseConnection::connect();
    
    // Initialize the query executor
    $queryExecutor = new QueryExecutor($conn);
    
    // Query to fetch the cor based on voter_id
    $query = "SELECT cor, acc_created, email, status_updated FROM voter WHERE voter_id = ?";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if a record was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['cor' => $row['cor'], 'status_updated' => $row['status_updated'], 'acc_created' => $row['acc_created'], 'email' => $row['email']]);
    } else {
        echo json_encode(['error' => 'No record found']);
    }
    
    // Close statement and connection

} else {
    echo json_encode(['error' => 'Invalid request']);
}

