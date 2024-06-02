<?php
session_start();
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');

// Initialize candidates array
$candidates = [];

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // ------ END OF SESSION EXCHANGE

    // Database connection
    $connection = DatabaseConnection::connect();

    if ($connection) {
        $voter_id = $_SESSION['voter_id'];

        if (isset($_SESSION['organization']) && isset($_GET['position_id']) && isset($_GET['election_year'])) {
            // Retrieve the organization name
            $organization = $_SESSION['organization'];

            // Fetch election years
            $yearsQuery = "SELECT DISTINCT election_year FROM candidate ORDER BY election_year DESC";
            $result_years = $connection->query($yearsQuery);
        
            $position_id = $_GET['position_id'];
            $election_year = $_GET['election_year'];

            // Prepare and execute query
            $query = $connection->prepare("SELECT c.first_name, c.last_name, COUNT(v.vote_id) as vote_count
                                        FROM candidate c
                                        JOIN vote v ON c.candidate_id = v.candidate_id
                                        WHERE c.position_id = ? AND c.election_year = ?
                                        GROUP BY c.candidate_id
                                        ORDER BY vote_count DESC");
            if ($query) {
                $query->bind_param("ii", $position_id, $election_year);
                $query->execute();
                $result = $query->get_result();

                // Fetch candidates and vote counts
                while ($row = $result->fetch_assoc()) {
                    $candidates[] = [
                        'name' => $row['first_name'] . ' ' . $row['last_name'],
                        'vote_count' => $row['vote_count']
                    ];
                }
            } else {
                // Handle query preparation error
                echo json_encode(['error' => 'Query preparation error']);
            }
        } else {
            // Handle missing session or GET parameters
            echo json_encode(['error' => 'Missing session or GET parameters']);
        }
    } else {
        // Handle database connection error
        echo json_encode(['error' => 'Database connection error']);
    }
}

// Return JSON response
echo json_encode(['candidates' => $candidates]);
?>
