<?php

$conn = DatabaseConnection::connect();

$queryExecutor = new QueryExecutor($conn);
$query = "SELECT * FROM voter WHERE account_status = 'for_verification' AND role = 'student_voter'";
$to_verify_tbl = $queryExecutor->executeQuery($query);

$voter_query = "SELECT * FROM voter WHERE account_status != 'for_verification' AND role = 'student_voter'";
$verified_tbl = $queryExecutor->executeQuery($voter_query);