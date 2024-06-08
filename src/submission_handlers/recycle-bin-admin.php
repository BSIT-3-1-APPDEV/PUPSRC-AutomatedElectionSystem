<?php

$conn = DatabaseConnection::connect();

$queryExecutor = new QueryExecutor($conn);

// Set the role and account_status properties
$queryExecutor->role = "('admin', 'head_admin')";
$queryExecutor->account_status = 'invalid';

// Query with placeholders
$query_verified = "SELECT * FROM voter WHERE account_status = '{$queryExecutor->account_status}' AND role IN {$queryExecutor->role}";

// Execute the SQL query for fetching paginated data
$verified_tbl = $queryExecutor->executeQuery($query_verified);

// You can then use these total pages to display pagination links in your HTML
?>
