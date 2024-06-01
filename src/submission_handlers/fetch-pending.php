<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, '../includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');

$conn = DatabaseConnection::connect();
$queryExecutor = new QueryExecutor($conn);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 5; 
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM voter WHERE account_status = 'for_verification' AND role = 'student_voter' LIMIT $limit OFFSET $offset";
$to_verify = $queryExecutor->executeQuery($query);

$voters = [];
while ($row = $to_verify->fetch_assoc()) {
    $voters[] = [
        'voter_id' => $row['voter_id'],
        'email' => $row['email'],
        'acc_created' => $row['acc_created']
    ];
}

$countQuery = "SELECT COUNT(*) as total FROM voter WHERE account_status = 'for_verification' AND role = 'student_voter'";
$countResult = $queryExecutor->executeQuery($countQuery);
$totalRows = $countResult->fetch_assoc()['total'];

echo json_encode([
    'voters' => $voters,
    'totalRows' => $totalRows
]);

?>
