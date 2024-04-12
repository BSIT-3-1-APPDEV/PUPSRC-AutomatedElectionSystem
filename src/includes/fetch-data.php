<?php
session_start(); // Start the session if not already started
$_SESSION['organization'] = 'jehra';
// Log the incoming request data
$requestData = file_get_contents('php://input');
file_put_contents('request_log.txt', $requestData . PHP_EOL, FILE_APPEND);

require_once 'classes/db-config.php';
require_once 'classes/db-connector.php';

// Establish the database connection
$conn = DatabaseConnection::connect();

// Get the selected position from the request body
$data = json_decode($requestData, true);
$selectedPosition = $data['position'];

// Fetch data for the selected position from the database
$data = fetchDataForPosition($conn, $selectedPosition);



    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);

// Function to fetch data for a specific position from the database
function fetchDataForPosition($conn, $selectedPosition) {
    // Check if the connection is valid
    if ($conn->connect_error) {
        return false; // Return false if there's a connection error
    }

    // Fetch data from the database
    // Adjust the SQL query to fetch data based on the selected position
    $sql = "SELECT v.position_id, v.candidate_id, COUNT(*) AS vote_count, c.photo_url, c.last_name, c.first_name
            FROM vote v
            INNER JOIN candidate c ON v.candidate_id = c.candidate_id
            WHERE v.position_id = (
                SELECT position_id 
                FROM position 
                WHERE title = '$selectedPosition'
            ) 
            GROUP BY v.position_id, v.candidate_id";
    
    $result = $conn->query($sql);

    // Check if the query was successful
    if (!$result) {
        return false; // Return false if there's a query error
    }

    // Create an array to store the fetched data
    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Add each row to the data array
        $data[] = $row;
    }

    return $data;
}
?>
