<?php

$conn = DatabaseConnection::connect();

$queryExecutor = new QueryExecutor($conn);

// SQL Query for 'verified' table with LEFT JOIN to get the position title
$query_verified = "  SELECT c.*, p.title  FROM candidate c  LEFT JOIN position p ON c.position_id = p.position_id WHERE c.candidacy_status = 'removed'
";

// Execute the SQL query for fetching data
$verified_tbl = $queryExecutor->executeQuery($query_verified);

// You can then use these total pages to display pagination links in your HTML
?>
