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
    // Check connection
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
            <div class="popup-container">
                <h1>ELECTION RESULTS</h1>

                <?php
                if (isset($_GET['election_year'])) {
                    $election_year = htmlspecialchars($_GET['election_year']);
                    // Query to fetch positions
                    $query = "SELECT * FROM position WHERE sequence > 0";
                    $result = $connection->query($query);

                    if ($result->num_rows > 0) {
                        // Loop through each position and display a table for each
                        while ($row = $result->fetch_assoc()) {
                            $position_id = $row['position_id'];
                            $title = $row['title'];

                            // Query to fetch data related to each position (candidates)
                            $data_query = "SELECT c.last_name, c.first_name, COUNT(v.vote_id) as num_votes
                           FROM `candidate` c
                           LEFT JOIN `vote` v ON c.candidate_id = v.candidate_id
                           WHERE c.position_id = $position_id AND c.election_year = ?
                           GROUP BY c.candidate_id";
                            $stmtData = $connection->prepare($data_query);
                            $stmtData->bind_param("i", $election_year);
                            $stmtData->execute();
                            $data_result = $stmtData->get_result();

                            // Query to fetch total votes for the position
                            $vote_query = "SELECT COUNT(*) as total_votes FROM `vote` v
                           JOIN `candidate` c ON v.candidate_id = c.candidate_id
                           WHERE c.position_id = $position_id AND c.election_year = ?";
                            $stmtVote = $connection->prepare($vote_query);
                            $stmtVote->bind_param("i", $election_year);
                            $stmtVote->execute();
                            $vote_result = $stmtVote->get_result();
                            $vote_row = $vote_result->fetch_assoc();
                            $total_votes = isset($vote_row['total_votes']) ? $vote_row['total_votes'] : 0;

                            // Query to fetch count of null votes (abstentions)
                            $abstain_query = "SELECT COUNT(*) as abstain_count FROM `vote` v
                              JOIN `candidate` c ON v.candidate_id = c.candidate_id
                              WHERE c.position_id = $position_id AND c.election_year = ? AND v.candidate_id IS NULL";
                            $stmtAbstain = $connection->prepare($abstain_query);
                            $stmtAbstain->bind_param("i", $election_year);
                            $stmtAbstain->execute();
                            $abstain_result = $stmtAbstain->get_result();
                            $abstain_row = $abstain_result->fetch_assoc();
                            $abstain_count = isset($abstain_row['abstain_count']) ? $abstain_row['abstain_count'] : 0;

                            // Display table with headers dynamically set to position title and total votes
                            echo "<table border='1'>";
                            echo "<br>";
                            echo "<br>";
                            echo "<th>{$title} Candidates</th>";
                            echo "<th colspan='2'>Total of {$total_votes} Votes</th>"; // Column header with total votes and percentage
                            echo "<tbody>";

                            // Output candidate data rows with percentage
                            while ($data_row = $data_result->fetch_assoc()) {
                                $candidate_votes = $data_row['num_votes'];
                                $percentage = round(($candidate_votes / $total_votes) * 100); // Calculate percentage without decimals

                                echo "<tr>";
                                echo "<td>{$data_row['last_name']}, {$data_row['first_name']}</td>"; // Last Name
                                echo "<td>{$candidate_votes} ({$percentage}%)</td>"; // Votes and percentage
                                echo "</tr>";
                            }

                            // Display abstain row with percentage
                            $abstain_percentage = round(($abstain_count / $total_votes) * 100); // Calculate abstain percentage
                            echo "<tr>";
                            echo "<td>Abstain</td>";
                            echo "<td>{$abstain_count} ({$abstain_percentage}%)</td>"; // Abstain count and percentage
                            echo "</tr>";

                            echo "</tbody>";
                            echo "</table>";

                            // Close result sets
                            $stmtData->close();
                            $stmtVote->close();
                            $stmtAbstain->close();
                        }
                    } else {
                        echo "No positions found";
                    }

                    // Close result set for positions query
                    $result->close();
                } else {
                    echo "<tr><td colspan='4'>Election year not specified</td></tr>";
                }
                ?>

            </div>
            <div class="popup-container">
                <h1>VOTERS TURNOUT</h1>

                <?php
                // Path to the JSON file
                $jsonFile = 'data/voters-turnout.json';

                // Check if the file exists
                if (file_exists($jsonFile)) {
                    // Read the JSON file
                    $jsonData = file_get_contents($jsonFile);
                    // Decode the JSON data
                    $data = json_decode($jsonData, true);

                    if ($data !== null) {
                        $candidateCount = $data['candidate_count'];
                        $abstained_vote_count = $data['abstained_vote_count'];
                        $voterCounts = $data['voter_counts'];

                        $totalVotersCount = $voterCounts['totalVotersCount'];
                        $votedVotersCount = $voterCounts['votedVotersCount'];
                        $abstainedVotersCount = $voterCounts['abstainedVotersCount'];
                        $totalPercentage = $voterCounts['totalPercentage'];
                        $votedPercentage = $voterCounts['votedPercentage'];


                        // Generate the HTML table
                        echo "<table border='1'>";
                        echo "<thead>";
                        echo "<th colspan='2'>Total count of registered voters</th>";
                        echo "<th>Total count of registered voters who didn't vote</th>";
                        echo "<th>Total count of abstained votes</th>";
                        echo "<th>Total count of voters accounts</th>";
                        echo "</thead>";
                        echo "<tbody>";
                        echo "<td colspan='2'>{$votedVotersCount}</td>";
                        echo "<td>{$abstainedVotersCount}</td>";
                        echo "<td>{$abstained_vote_count}</td>";
                        echo "<td>{$totalVotersCount}</td>";
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p>Error decoding JSON data</p>";
                    }
                } else {
                    echo "<p>JSON file not found</p>";
                }
                ?>
            </div>

        </body>

        </html>

<?php
    } else {
        echo "<div class='popup-container'><p>Organization not set</p></div>";
    }

    // Close MySQLi connection
    $connection->close();
} else {
    header("Location: landing-page.php");
}
?>