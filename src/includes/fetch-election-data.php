<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, 'classes/file-utils.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');


$connection = DatabaseConnection::connect();

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
// Fetch election years
          $yearsQuery = "SELECT DISTINCT election_year FROM candidate ORDER BY election_year DESC";
          $result_years = $connection->query($yearsQuery);

          if ($result_years && $result_years->num_rows > 0) {
            $selected_year = isset($_GET['election_year']) ? $_GET['election_year'] : null;
        }
        
        
if (isset($_GET['election_year'])) {
    $election_year = htmlspecialchars($_GET['election_year']);
    $query = "SELECT c.*, p.title as position_title, COUNT(v.vote_id) as vote_count 
              FROM candidate c 
              JOIN vote v ON c.candidate_id = v.candidate_id 
              JOIN position p ON c.position_id = p.position_id 
              WHERE c.election_year = ? 
              GROUP BY c.position_id 
              ORDER BY c.position_id, vote_count DESC";
    $stmt = $connection->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $election_year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($candidate_data = $result->fetch_assoc()) {
                $position_id = $candidate_data['position_id'];
                $last_name = htmlspecialchars($candidate_data['last_name']);
                $first_name = htmlspecialchars($candidate_data['first_name']);
                $candidate_name = $last_name . ', ' . $first_name;
                $candidate_section = htmlspecialchars($candidate_data['program']) . ' ' . htmlspecialchars($candidate_data['year_level']) . '- ' . htmlspecialchars($candidate_data['section']);
                $vote_count = htmlspecialchars($candidate_data['vote_count']);
                $position_title = htmlspecialchars($candidate_data['position_title']);
                echo "<div class='row'>
                        <div class='col-11 col-md-10 col-lg-11 mx-auto'>
                            <div class='card card-candidate mb-4'>
                                <div class='card-body d-flex justify-content-between'>
                                    <img src='images/candidate-profile/".htmlspecialchars($candidate_data["photo_url"])."' class='org-logo' alt='Candidate Image'>
                                    <div class='card-body text-center'>
                                        <h3 class='card-title'>$candidate_name</h3>
                                        <h5 class='card-title main-color'>$candidate_section</h5>
                                        <h2 class='card-position2'>$position_title</h2>
                                    </div>
                                    <p class='main-color'><strong>$vote_count</strong> Votes</p>
                                </div>
                            </div>
                        </div>
                    </div>";
            }
        } else {
            echo "No candidates found for the selected election year.";
        }
    } else {
        echo "Query preparation failed: " . $connection->error;
    }
} else {
    echo "Election year not specified.";
}
