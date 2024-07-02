<?php


include_once str_replace('/', DIRECTORY_SEPARATOR, 'classes/file-utils.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('session-exchange.php');
    // ------ END OF SESSION EXCHANGE
    
    // Fetch organization name
    $org_name = $_SESSION['organization'] ?? '';

    $connection = DatabaseConnection::connect();

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    $yearsQuery = "SELECT DISTINCT election_year FROM candidate ORDER BY election_year DESC";
    $result_years = $connection->query($yearsQuery);

    if ($result_years && $result_years->num_rows > 0) {
        $selected_year = isset($_GET['election_year']) ? $_GET['election_year'] : null;
    }

    $items_per_page = 5;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $items_per_page;

    if (isset($_GET['election_year'])) {
        $election_year = htmlspecialchars($_GET['election_year']);
        $query = "SELECT c.*, p.title as position_title, COUNT(v.vote_id) as vote_count 
                  FROM candidate c 
                  JOIN vote v ON c.candidate_id = v.candidate_id 
                  JOIN position p ON c.position_id = p.position_id 
                  WHERE c.election_year = ? 
                  GROUP BY c.candidate_id 
                  ORDER BY c.position_id, vote_count DESC 
                  LIMIT ? OFFSET ?";

        $stmt = $connection->prepare($query);

        if ($stmt) {
            $stmt->bind_param("iii", $election_year, $items_per_page, $offset);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $highest_voted_candidates = [];

                while ($candidate_data = $result->fetch_assoc()) {
                    $position_id = $candidate_data['position_id'];
                    if (!isset($highest_voted_candidates[$position_id])) {
                        $highest_voted_candidates[$position_id] = $candidate_data;
                    }
                }

                // Separate the display of the first candidate
                $first_candidate_data = reset($highest_voted_candidates);
                displayCandidate($first_candidate_data, true, $org_name);

                // Remove the first element from the array
                array_shift($highest_voted_candidates);

                // Display the remaining candidates
                foreach ($highest_voted_candidates as $candidate_data) {
                    displayCandidate($candidate_data, false, $org_name);
                }
            } else {
                displayNoCandidatesFoundMessage();
            }
        } else {
            echo "Query preparation failed: " . $connection->error;
        }
    } else {
        echo "Election year not specified.";
    }


function displayCandidate($candidate_data, $isFirst = false, $org_name)
{
    $last_name = htmlspecialchars($candidate_data['last_name']);
    $first_name = htmlspecialchars($candidate_data['first_name']);
    $candidate_name = $last_name . ', ' . $first_name;
    $candidate_section = htmlspecialchars($candidate_data['program']) . ' ' . htmlspecialchars($candidate_data['year_level']) . '- ' . htmlspecialchars($candidate_data['section']);
    $vote_count = htmlspecialchars($candidate_data['vote_count']);
    $position_title = htmlspecialchars($candidate_data['position_title']);
    $card_class = $isFirst ? 'card-candidate' : 'card-runnerup';
?>
    <div class="row">
        <div class="col-11 col-md-10 col-lg-11 mx-auto">
            <div class="card <?php echo $card_class; ?> mb-4">
                <div class="card-body d-flex justify-content-between">
                    <img src="user_data/<?php echo $org_name ?>/candidate_imgs/<?php echo htmlspecialchars($candidate_data["photo_url"]); ?>" class="org-logo" alt="Candidate Image">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?php echo $candidate_name ?></h3>
                        <h5 class="card-title main-color"><?php echo $candidate_section ?></h5>
                        <h2 class="card-position2"><?php echo $position_title; ?></h2>
                    </div>
                    <p class="main-color"><strong><?php echo $vote_count; ?></strong> Votes</p>
                </div>
            </div>
        </div>
    </div>
<?php
}

function displayNoCandidatesFoundMessage()
{
?>
    <div class="row">
        <div class="col-11 col-md-10 col-lg-11 mx-auto">
            <div class="card card-runnerup mb-4">
                <div class="card-body d-flex justify-content-between">
                    <div class="card-body text-center">
                        <h2 class="no-avail-candidate">No candidates found.</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
