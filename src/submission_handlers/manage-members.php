<?php
$conn = DatabaseConnection::connect();

// -----------------FILTERING-----------------//
// Get the selected filters from the URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : [];
// Ensure $filter is an array
$filter = is_array($filter) ? $filter : [];

// -----------------SORTING-----------------//
// Get the sort parameter from the URL
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'acc_created';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Initialize the SQL query
$query = "SELECT * FROM voter WHERE account_status != ? AND role IN ('admin', 'head_admin')";
$params = ["For Verification"];

// Add role filter conditions based on selected filters
if (!empty($filter)) {
    $query .= " AND (";
    $roleFilters = [];
    if (in_array('admin', $filter)) {
        $roleFilters[] = "role = 'admin'";
    }
    if (in_array('head_admin', $filter)) {
        $roleFilters[] = "role = 'head_admin'";
    }
    if (!empty($roleFilters)) {
        $query .= implode(" OR ", $roleFilters);
    }
    $query .= ")";
}

// Add sorting order
$query .= " ORDER BY $sort $order";

// Prepare the statement for counting total records
$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get the total number of records
$total_records = $result->num_rows;
$stmt->close();

// ----------PAGINATION--------------//
// Set the maximum number of rows per page
$records_per_page = 5;

// Calculate the total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Get the current page number from the URL parameter (if it exists)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

// Modify the SQL query to fetch records for the current page
$query .= " LIMIT $offset, $records_per_page";

// Prepare the statement for fetching records
$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
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
?>