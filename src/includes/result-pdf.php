<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'classes/file-utils.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {
    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('session-exchange.php');
    // ------ END OF SESSION EXCHANGE
    $connection = DatabaseConnection::connect();

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $voter_id = $_SESSION['voter_id'];

    if (isset($_SESSION['organization'])) {
        // Retrieve the organization name
        $organization = $_SESSION['organization'];
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <link rel="stylesheet" href="styles/result.css" />
        </head>

        <body>
            <div class="popup-container">
                <h1>ELECTION WINNERS</h1>
                <p>Upon tabulation of the casted votes, the following candidates for the elective positions of <br> the PUP-SRC <?php echo mb_strtoupper($organization, 'UTF-8'); ?> garnered the total votes opposite to their names.</p>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th>POSITION</th>
                            <th>NAME</th>
                            <th>COURSE</th>
                            <th>VOTES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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

                                    // Display the highest voted candidates with their vote count and percentage
                                    foreach ($highestVotedCandidates as $candidate) {
                                        $position_id = $candidate['position_id'];
                                        $total_votes = $totalVotes[$position_id];
                                        $vote_count = $candidate['vote_count'];
                                        $percentage_vote = number_format(($vote_count / $total_votes) * 100, 0);

                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($candidate['position_title']) . "</td>";
                                        echo "<td>" . htmlspecialchars($candidate['last_name'] . ', ' . $candidate['first_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($candidate['program']) . "</td>";
                                        echo "<td>{$vote_count} / {$total_votes} ({$percentage_vote}%)</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>Error preparing total votes statement</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>Error preparing highest voted candidates statement</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Election year not specified</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </body>

        </html>

<?php
    } else {
        echo "<div class='popup-container'><p>Organization not set</p></div>";
    }
} else {
    header("Location: landing-page.php");
}
?>