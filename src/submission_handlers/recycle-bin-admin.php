<?php

$conn = DatabaseConnection::connect();

$queryExecutor = new QueryExecutor($conn);
// Pagination Parameters


// Calculate OFFSET for SQL query
$offset = ($page - 1) * $limit;

// SQL Query for 'verified' table with LIMIT and OFFSET
$query_verified = "SELECT * FROM voter WHERE account_status = 'invalid' AND role IN ('admin', 'head_admin')";

// Execute the SQL query for fetching paginated data
$verified_tbl = $queryExecutor->executeQuery($query_verified);


// You can then use these total pages to display pagination links in your HTML
?>

