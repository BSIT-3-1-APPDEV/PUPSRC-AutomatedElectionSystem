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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'acc_created';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';

// Validate sort_by and sort_order
$valid_columns = ['acc_created', 'first_name'];
$valid_orders = ['asc', 'desc'];

if (!in_array($sort_by, $valid_columns)) {
    $sort_by = 'acc_created';
}
if (!in_array(strtolower($sort_order), $valid_orders)) {
    $sort_order = 'DESC';
}

$searchCondition = $search ? "AND LOWER(CONCAT_WS(' ', COALESCE(TRIM(first_name), ''), COALESCE(TRIM(middle_name), ''), COALESCE(TRIM(last_name), ''), COALESCE(TRIM(suffix), ''), COALESCE(TRIM(role), ''), COALESCE(TRIM(acc_created), ''))) LIKE ?" : "";

// Prepare the main query with sorting
$query = "SELECT voter_id, first_name, middle_name, last_name, suffix, role, acc_created
          FROM voter
          WHERE account_status = 'verified' AND role IN ('admin', 'head_admin')
          $searchCondition
          ORDER BY $sort_by $sort_order
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

if ($search) {
    $searchParam = "%".strtolower($search)."%";
    $stmt->bind_param("sii", $searchParam, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

$voters = [];
while ($row = $result->fetch_assoc()) {
    $voters[] = $row;
}
$stmt->close();

// Count total rows
$countQuery = "SELECT COUNT(*) as total
               FROM voter
               WHERE account_status = 'verified' AND role IN ('admin', 'head_admin')
               $searchCondition";
$countStmt = $conn->prepare($countQuery);
if ($search) {
    $countStmt->bind_param("s", $searchParam);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$countStmt->close();

echo json_encode([
    'voters' => $voters,
    'totalRows' => $totalRows
]);
?>
