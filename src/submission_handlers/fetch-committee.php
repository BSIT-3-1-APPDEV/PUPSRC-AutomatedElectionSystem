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

    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'acc_created';
    $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';

    // Validate sort_by parameter to prevent SQL injection
    $valid_sort_columns = ['acc_created', 'first_name'];
    if (!in_array($sort_by, $valid_sort_columns)) {
        $sort_by = 'acc_created';
    }

    // Validate sort_order parameter to prevent SQL injection
    $sort_order = strtoupper($sort_order);
    if ($sort_order !== 'ASC' && $sort_order !== 'DESC') {
        $sort_order = 'DESC';
    }

    $query = "SELECT voter_id, first_name, middle_name, last_name, suffix, role, acc_created FROM voter WHERE role IN ('admin', 'head_admin') ORDER BY $sort_by $sort_order LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $voters = [];
    while ($row = $result->fetch_assoc()) {
        $voters[] = $row;
    }

    $stmt->close();

    $countQuery = "SELECT COUNT(*) as total FROM voter WHERE role IN ('admin', 'head_admin')";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];

    $countStmt->close();

    echo json_encode([
        'voters' => $voters,
        'totalRows' => $totalRows
    ]);
?>
