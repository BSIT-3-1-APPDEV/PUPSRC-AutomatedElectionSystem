<?php

// Establish a database connection
$conn = DatabaseConnection::connect();

// Construct the SQL query
$query = "SELECT c.*, p.title FROM candidate c LEFT JOIN position p ON c.position_id = p.position_id WHERE c.candidacy_status = ?";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind parameter
$stmt->bind_param('s', $candidacy_status);

// Set the candidacy_status property
$candidacy_status = 'removed';

// Execute the statement
$stmt->execute();

// Get the result
$verified_tbl = $stmt->get_result();

// Check if there are rows returned
if ($verified_tbl->num_rows > 0) {
    // Fetch data and display it
} else {
    // Display message for empty result set
    echo "No removed candidates";
}

// Close the statement
$stmt->close();

// Close the connection
$conn->close();

?>
