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
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Determine sorting column and order
$orderBy = "acc_created";
$orderDir = "";


switch ($sortOrder) {
    case 'asc':
        $orderBy = "email"; // Sorting alphabetically by email
        $orderDir = 'ASC';
        break;
    case 'desc':
        $orderBy = "email"; // Sorting alphabetically by email
        $orderDir = 'DESC';
        break;
    case 'newest':
        $orderBy = "acc_created"; // Sorting by creation date
        $orderDir = 'DESC';
        break;
    case 'oldest':
        $orderBy = "acc_created"; // Sorting by creation date
        $orderDir = 'ASC';
        break;
    default:
        $orderBy = "acc_created"; // Default to sorting by creation date
        $orderDir = 'DESC'; // Default sorting order
        break;
}

$query = "SELECT voter_id, email, account_status, status_updated FROM voter WHERE account_status = ? AND role = ? ORDER BY $orderBy $orderDir LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$account_status = 'verified';
$role = 'student_voter';
$stmt->bind_param('ssii', $account_status, $role, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$voters = [];
while ($row = $result->fetch_assoc()) {
    $voters[] = [
        'voter_id' => $row['voter_id'],
        'email' => $row['email'],
        'account_status' => $row['account_status'],
        'status_updated' => $row['status_updated']
    ];
}

$stmt->close();

$countQuery = "SELECT COUNT(*) as total FROM voter WHERE account_status = ? AND role = ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param('ss', $account_status, $role);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];

$countStmt->close();

echo json_encode([
    'voters' => $voters,
    'totalRows' => $totalRows
]);

?>
