<?php

$conn = DatabaseConnection::connect();

$queryExecutor = new QueryExecutor($conn);
$query = "SELECT * FROM voter WHERE status = 'For Verification'";
$to_verify_tbl = $queryExecutor->executeQuery($query);

$voter_query = "SELECT * FROM voter WHERE status != 'For Verification'";
$verified_tbl = $conn->query($voter_query);