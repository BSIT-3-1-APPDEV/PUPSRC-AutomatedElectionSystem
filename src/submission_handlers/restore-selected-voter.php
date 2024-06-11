<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, '../includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'ids' parameter is set in the POST request
    if (isset($_POST['ids'])) {
        $ids = $_POST['ids'];
        
        // Establish database connection
        $conn = DatabaseConnection::connect();
        
        // Initialize the query executor
        $queryExecutor = new QueryExecutor($conn);
        
        // Prepare the SQL statement to update selected items
        $query = "UPDATE voter SET account_status = 'verified' WHERE voter_id IN (";
        $params = array();
        foreach ($ids as $id) {
            $query .= "?, ";
            $params[] = $id;
        }
        // Replace the last comma and space with a closing parenthesis
        $query = rtrim($query, ", ") . ")";
        
        // Execute the update query
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat("i", count($ids)), ...$params); // Bind parameters dynamically
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Selected items updated successfully']);
        } else {
            echo json_encode(['error' => 'No items updated']);
        }
        
        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Return an error response if 'ids' parameter is not set
        echo json_encode(['error' => 'No IDs provided for update']);
    }
} else {
    // Return an error response if the request method is not POST
    echo json_encode(['error' => 'Invalid request method']);
}
?>
