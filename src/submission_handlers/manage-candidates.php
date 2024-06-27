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
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'candidate_creation';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Validate and sanitize the sort parameter
$allowedSortColumns = array('candidate_id', 'last_name', 'first_name', 'middle_name', 'suffix', 'party_list', 'position_id', 'photo_url', 'section', 'candidate_creation');
$sort = in_array($sort, $allowedSortColumns) ? $sort : 'candidate_id';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

// Initialize the SQL query
$query = "SELECT c.candidate_id, c.last_name, c.first_name, c.middle_name, c.suffix, c.party_list, c.position_id, p.title as position, c.photo_url, c.section, c.`candidate_creation` FROM candidate c
JOIN position p ON c.position_id = p.position_id";

// Add position filter conditions based on selected filters
if (!empty($filter)) {
    $query .= " WHERE " . implode(" OR ", array_fill(0, count($filter), "p.title = ?"));
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
    // No filters, count all records in candidate table
    $countQuery = "SELECT COUNT(*) as total FROM candidate";
    $countStmt = $conn->prepare($countQuery);
    if ($countStmt) {
        $countStmt->execute();
        $result = $countStmt->get_result();
        $total_records = $result->fetch_assoc()['total'];
        $countStmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
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
$query .= " ORDER BY $sort $order LIMIT $offset, $records_per_page";

// Prepare the statement for fetching records
$stmt = $conn->prepare($query);
if (!empty($filter)) {
    $bindTypes = str_repeat('s', count($filter)); // Assuming all titles are strings
    $stmt->bind_param($bindTypes, ...$filter);
}
$stmt->execute();
$verified_tbl = $stmt->get_result();

// Generate the pagination links
function createPagination($totalPages, $currentPage, $sort, $order, $filter)
{
    $pagination = '';

    // Always show the first page
    $pagination .= createPageLink(1, $currentPage, $sort, $order, $filter);

    if ($totalPages > 1) {
        // Show ellipsis if current page is greater than 4
        if ($currentPage > 4) {
            $pagination .= '<span>...</span>';
        }

        // Define the range of page numbers to display
        $start = max(2, $currentPage - 2);
        $end = min($totalPages - 1, $currentPage + 2);

        // Adjust the range if we are close to the start or end
        if ($currentPage <= 4) {
            $end = min(5, $totalPages - 1);
        } elseif ($currentPage >= $totalPages - 3) {
            $start = max($totalPages - 4, 2);
        }

        // Generate the page links within the defined range
        for ($i = $start; $i <= $end; $i++) {
            $pagination .= createPageLink($i, $currentPage, $sort, $order, $filter);
        }

        // Show ellipsis if current page is less than totalPages - 3
        if ($currentPage < $totalPages - 3) {
            $pagination .= '<span>...</span>';
        }

        // Always show the last page
        if ($totalPages > 1) {
            $pagination .= createPageLink($totalPages, $currentPage, $sort, $order, $filter);
        }
    }

    return $pagination;
}

function createPageLink($page, $currentPage, $sort, $order, $filter)
{
    $page_url = "manage-candidate.php?page=$page&sort=$sort&order=$order";
    if (!empty($filter)) {
        $page_url .= '&filter[]=' . implode('&filter[]=', array_map('urlencode', $filter));
    }

    $activeClass = $page == $currentPage ? ' class="active"' : '';
    return "<a href=\"$page_url\"$activeClass>$page</a> ";
}

// Generate the pagination links
$pagination_links = createPagination($total_pages, $current_page, $sort, $order, $filter);

$stmt->close();
$conn->close();
