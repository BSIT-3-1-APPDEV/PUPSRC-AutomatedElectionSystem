<?php
session_start(); // Start the session if not already started
$_SESSION['organization'] = 'jehra';

// Include the necessary files
require_once 'classes/db-config.php';
require_once 'classes/db-connector.php';

$logMessage = "Attempting to establish database connection...";
error_log($logMessage);
// Establish the database connection
$conn = DatabaseConnection::connect();
if ($conn->connect_error) {
    // Log connection failure
    $logMessage = "Database connection failed: " . $conn->connect_error;
    error_log($logMessage);
    die($logMessage);
} else {
    // Log successful connection
    $logMessage = "Database connection established successfully.";
    error_log($logMessage);
}
// Fetch positions available from the database
$positions = fetchPositionsFromDatabase($conn);

// Return the positions as JSON
echo json_encode($positions);

// Function to fetch positions available from the database
function fetchPositionsFromDatabase($conn) {
    // Check if the connection is valid
    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }

    // Fetch positions from the "position" table
    $sql = 'SELECT title FROM position'; // Adjust the query based on your database structure

    $result = $conn->query($sql);

    // Check for query execution error
    if (!$result) {
        die('Error executing query: ' . $conn->error);
    }

    // Create an array to store the fetched positions
    $positions = [];
    while ($row = $result->fetch_assoc()) {
        // Add each position to the positions array
        $positions[] = $row['title'];
    }

    return $positions;
}
?>
