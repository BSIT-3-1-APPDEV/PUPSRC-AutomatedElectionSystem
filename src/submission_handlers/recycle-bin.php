<?php

// Establish a database connection
$conn = DatabaseConnection::connect();

// Construct the SQL query
$query = "SELECT * FROM voter WHERE account_status = ? AND role = ?";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind parameters
$stmt->bind_param('ss', $account_status, $role);

// Set the status and role values
$account_status = 'invalid';
$role = 'student_voter';

// Execute the statement
$stmt->execute();

// Get the result
$verified_tbl = $stmt->get_result();

// Close the statement
$stmt->close();

// Close the connection
$conn->close();

?>
