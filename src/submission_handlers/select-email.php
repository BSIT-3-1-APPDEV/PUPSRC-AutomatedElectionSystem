<?php

$conn = DatabaseConnection::connect();
$queryExecutor = new QueryExecutor($conn);

$voter = "SELECT email FROM voter WHERE voter_id = $voter_id";
$voter_email = $queryExecutor->executeQuery($voter);