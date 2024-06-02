<?php 
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // ------ END OF SESSION EXCHANGE
    $connection = DatabaseConnection::connect();
    // Assume $connection is your database connection
    $voter_id = $_SESSION['voter_id'];

    if (isset($_SESSION['organization'])) {
        // Retrieve the organization name
        $organization = $_SESSION['organization'];

        // Fetch election years
        $yearsQuery = "SELECT DISTINCT election_year FROM candidate ORDER BY election_year DESC";
        $result_years = $connection->query($yearsQuery);
    }
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
                <p>Upon tabulation of the casted votes, the following candidates for the elective positions of <br> the PUP-SRC <?php echo mb_strtoupper($organization, 'UTF-8');?> garnered the total votes opposite to their names.</p>
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
// Check if the election year parameter is set in the URL
if (isset($_GET['election_year'])) {
    // Sanitize and store the election year from the URL
    $election_year = htmlspecialchars($_GET['election_year']);

    // Fetch candidates with the highest votes for the specified election year
    $query = "SELECT c.*, p.title as position_title, COUNT(v.vote_id) as vote_count 
                FROM candidate c 
                JOIN vote v ON c.candidate_id = v.candidate_id 
                JOIN position p ON c.position_id = p.position_id 
                WHERE c.election_year = ? 
                GROUP BY c.position_id, c.candidate_id 
                ORDER BY c.position_id, vote_count DESC";
    $stmt = $connection->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $election_year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $displayed_positions = [];
            while ($row = $result->fetch_assoc()) {
                if (in_array($row['position_id'], $displayed_positions)) {
                    continue; // Skip if position already displayed
                }
                $displayed_positions[] = $row['position_id'];

                $position_title = htmlspecialchars($row['position_title']);
                $candidate_name = htmlspecialchars($row['last_name'] . ', ' . $row['first_name']);
                $candidate_section = htmlspecialchars($row['program']);
                $vote_count = htmlspecialchars($row['vote_count']);

                echo "<tr>";
                echo "<td>$position_title</td>";
                echo "<td>$candidate_name</td>";
                echo "<td>$candidate_section</td>";
                echo "<td>$vote_count</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No results found</td></tr>";
        }
    } else {
        echo "Error preparing statement";
    }
}
?>



                    </tbody>
                </table>
            </div>


</body>
</html> 
<?php
} else {
    header("Location: landing-page.php");
}
?>