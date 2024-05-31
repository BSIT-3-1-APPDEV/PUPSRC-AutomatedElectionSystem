<?php
$conn = DatabaseConnection::connect();
$queryExecutor = new QueryExecutor($conn);

$voter_id = $_POST['voter_id']; // Assuming $voter_id is being passed via POST for security

$voter_query = "SELECT email FROM voter WHERE voter_id = ?";
$voter_email_result = $queryExecutor->executeQuery($voter_query, [$voter_id], 'i');

if ($voter_email_result->num_rows > 0) {
    $voter_email = $voter_email_result->fetch_assoc()['email'];

} else {
   
}
?>