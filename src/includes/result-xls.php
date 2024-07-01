<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'classes/file-utils.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');

header('Content-Type: application/json');

// Establishing the connection
$connection = DatabaseConnection::connect();

if (!$connection) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

if (isset($_GET['election_year'])) {
    $election_year = htmlspecialchars($_GET['election_year']);

    // Query to find the highest voted candidate for each position
    $highestVotedQuery = "SELECT 
                            c.candidate_id,
                            c.position_id,
                            p.title AS position_title,
                            c.last_name,
                            c.first_name,
                            c.program,
                            COUNT(v.vote_id) AS vote_count
                          FROM candidate c
                          JOIN vote v ON c.candidate_id = v.candidate_id
                          JOIN position p ON c.position_id = p.position_id
                          WHERE c.election_year = ?
                          GROUP BY c.candidate_id, c.position_id
                          ORDER BY c.position_id, vote_count DESC";

    $stmt = $connection->prepare($highestVotedQuery);

    if ($stmt) {
        $stmt->bind_param("i", $election_year);
        $stmt->execute();
        $result = $stmt->get_result();

        // Store the highest voted candidates in an array
        $highestVotedCandidates = [];
        $positions = [];

        while ($row = $result->fetch_assoc()) {
            if (!isset($positions[$row['position_id']])) {
                $positions[$row['position_id']] = true;
                $highestVotedCandidates[] = $row;
            }
        }

        // Query to get the total votes for each position
        $totalVotesQuery = "SELECT 
                            c.position_id,
                            COUNT(v.vote_id) AS total_votes
                           FROM candidate c
                           JOIN vote v ON c.candidate_id = v.candidate_id
                           WHERE c.election_year = ?
                           GROUP BY c.position_id";

        $stmtTotalVotes = $connection->prepare($totalVotesQuery);

        if ($stmtTotalVotes) {
            $stmtTotalVotes->bind_param("i", $election_year);
            $stmtTotalVotes->execute();
            $totalVotesResult = $stmtTotalVotes->get_result();

            // Store the total votes for each position in an array
            $totalVotes = [];
            while ($row = $totalVotesResult->fetch_assoc()) {
                $totalVotes[$row['position_id']] = $row['total_votes'];
            }

            // Prepare the final data array with vote count and percentage
            $data = [];
            foreach ($highestVotedCandidates as $candidate) {
                $position_id = $candidate['position_id'];
                $total_votes = $totalVotes[$position_id];
                $vote_count = $candidate['vote_count'];
                $percentage_vote = number_format(($vote_count / $total_votes) * 100, 0);

                $data[] = [
                    'POSITION' => htmlspecialchars($candidate['position_title']),
                    'NAME' => htmlspecialchars($candidate['last_name'] . ', ' . $candidate['first_name']),
                    'COURSE' => htmlspecialchars($candidate['program']),
                    'VOTES' => "{$vote_count} / {$total_votes} ({$percentage_vote}%)"
                ];
            }

            echo json_encode($data);
            exit();
        } else {
            echo json_encode(['error' => 'Error preparing total votes statement']);
            exit();
        }
    } else {
        echo json_encode(['error' => 'Error preparing highest voted candidates statement']);
        exit();
    }
} else {
    echo json_encode(['error' => 'No election year specified']);
    exit();
}
