<?php

$conn = DatabaseConnection::connect();

// Initialize the SQL query
$query = "SELECT * FROM voter WHERE account_status = 'verified' AND role IN ('admin', 'head_admin')";

// Prepare the statement
$stmt = $conn->prepare($query);
$stmt->execute();
$verified_tbl = $stmt->get_result();

$stmt->close();
$conn->close();
?>
