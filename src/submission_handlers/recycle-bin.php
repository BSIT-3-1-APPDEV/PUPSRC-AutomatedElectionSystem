<?php

$conn = DatabaseConnection::connect();

$queryExecutor = new QueryExecutor($conn);

// Define the status and role values
$queryExecutor->status = 'invalid';
$queryExecutor->role = 'student_voter';

// Query with placeholders
$query_verified = "SELECT * FROM voter WHERE account_status = '{$queryExecutor->status}' AND role = '{$queryExecutor->role}'";

// Execute the SQL query for fetching paginated data
$verified_tbl = $queryExecutor->executeQuery($query_verified);

?>
