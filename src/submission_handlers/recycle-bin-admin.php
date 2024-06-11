<?php

// Establish a database connection
$conn = DatabaseConnection::connect();

// Construct the SQL query
$query = "SELECT * FROM voter WHERE account_status = ? AND role IN (?, ?)";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind parameters
$stmt->bind_param('sss', $account_status, $role1, $role2);

// Set the role and account_status properties
$account_status = 'invalid';
$role1 = 'admin';
$role2 = 'head_admin';

// Execute the statement
$stmt->execute();

// Get the result
$verified_tbl = $stmt->get_result();

// Check if there are rows returned
if ($verified_tbl->num_rows > 0) {
    // Fetch data and display it
} else {

}

// Close the statement
$stmt->close();

// Close the connection
$conn->close();

?>
