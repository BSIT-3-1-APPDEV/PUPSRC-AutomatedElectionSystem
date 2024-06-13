<?php

$conn = DatabaseConnection::connect();

// Initialize the SQL query
$query = "SELECT * FROM voter WHERE account_status != ? AND role IN ('admin', 'head_admin')";
$params = ["For Verification"];

// Prepare the statement
$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$verified_tbl = $stmt->get_result();

$stmt->close();
$conn->close();
?>