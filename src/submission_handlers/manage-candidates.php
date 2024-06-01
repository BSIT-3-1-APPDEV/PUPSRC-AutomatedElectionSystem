<?php


$conn = DatabaseConnection::connect();

// -----------------FETCHING POSITION TITLES-----------------//
$positionQuery = "SELECT DISTINCT title FROM position";
$positionStmt = $conn->prepare($positionQuery);
$positionStmt->execute();
$positions = $positionStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$positionStmt->close();

// -----------------FILTERING-----------------//
// Get the selected filters from the URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : [];
// Ensure $filter is an array
$filter = is_array($filter) ? $filter : [];

// -----------------SORTING-----------------//
// Get the sort parameter from the URL
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'candidate-creation';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Validate and sanitize the sort parameter
$allowedSortColumns = array('candidate_id', 'last_name', 'first_name', 'middle_name', 'suffix', 'party_list', 'position_id', 'photo_url', 'section', 'candidate-creation');
$sort = in_array($sort, $allowedSortColumns) ? $sort : 'candidate_id';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

// Initialize the SQL query
$query = "SELECT c.candidate_id, c.last_name, c.first_name, c.middle_name, c.suffix, c.party_list, c.position_id, p.title as position, c.photo_url, c.section, c.`candidate-creation` FROM candidate c
JOIN position p ON c.position_id = p.position_id";

// Add position filter conditions based on selected filters
if (!empty($filter)) {
    $query .= " WHERE (";
    $query .= implode(" OR ", array_fill(0, count($filter), "p.title = ?"));
    $query .= ")";
}

// Prepare the statement for counting total records
$countQuery = "SELECT COUNT(*) as total FROM candidate c
JOIN position p ON c.position_id = p.position_id";
if (!empty($filter)) {
    $countQuery .= " WHERE (";
    $countQuery .= implode(" OR ", array_fill(0, count($filter), "p.title = ?"));
    $countQuery .= ")";
    $countStmt = $conn->prepare($countQuery);
    if ($countStmt) {
        $bindTypes = str_repeat('s', count($filter)); // Assuming all titles are strings
        $countStmt->bind_param($bindTypes, ...$filter);
        $countStmt->execute();
        $result = $countStmt->get_result();
        $total_records = $result->fetch_assoc()['total'];
        $countStmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    // No filters, set total_records to 0
    $total_records = 0;
}

// ----------PAGINATION--------------//
// Set the maximum number of rows per page
$records_per_page = 5;

// Calculate the total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Get the current page number from the URL parameter (if it exists)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

// Add sorting order and limit for fetching records for the current page
$query .= " ORDER BY '$sort' $order LIMIT $offset, $records_per_page";

// Prepare the statement for fetching records
$stmt = $conn->prepare($query);
$stmt->execute();
$verified_tbl = $stmt->get_result();

// Generate the pagination links
for ($i = 1; $i <= $total_pages; $i++) {
    // Generate the filter parameters for the link
    $filterParams = '';
    if (!empty($filter)) {
        foreach ($filter as $f) {
            $filterParams .= '&filter[]=' . urlencode($f);
        }
    }

    // Generate the sorting parameters for the link
    $sortParams = '&sort=' . urlencode($sort) . '&order=' . urlencode($order);
}

$stmt->close();
$conn->close();
