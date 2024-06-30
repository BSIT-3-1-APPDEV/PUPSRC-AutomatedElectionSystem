<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'classes/file-utils.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');

header('Content-Type: application/json');

// Initialize candidates array
$candidates = [];

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath($baseDir . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'session-exchange.php');
    // ------ END OF SESSION EXCHANGE

    // Database connection
    $connection = DatabaseConnection::connect();

    if ($connection) {
        $voter_id = $_SESSION['voter_id'];

        if (isset($_SESSION['organization']) && isset($_GET['position_id']) && isset($_GET['election_year'])) {
            // Retrieve the organization name
            $organization = $_SESSION['organization'];

            $position_id = $_GET['position_id'];
            $election_year = $_GET['election_year'];

            // Prepare and execute query to get candidate votes
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

                // Prepare and execute query to get abstain votes
                $abstainQuery = $connection->prepare("SELECT COUNT(vote_id) as abstain_count
                                                      FROM vote
                                                      WHERE position_id = ? AND candidate_id IS NULL");
                if ($abstainQuery) {
                    $abstainQuery->bind_param("i", $position_id);
                    $abstainQuery->execute();
                    $abstainResult = $abstainQuery->get_result();

                    // Fetch abstain vote count
                    $abstainRow = $abstainResult->fetch_assoc();
                    $abstainCount = $abstainRow['abstain_count'];

                    // Add abstain votes to the candidates array
                    $candidates[] = [
                        'name' => 'Abstained',
                        'vote_count' => $abstainCount
                    ];
                } else {
                    // Handle query execution error
                    echo json_encode(['error' => 'Query execution error']);
                    exit;
                }

                echo json_encode(['candidates' => $candidates]);
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
} else {
    echo json_encode(['error' => 'Unauthorized access']);
}
