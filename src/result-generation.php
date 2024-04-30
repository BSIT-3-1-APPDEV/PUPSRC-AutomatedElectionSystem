<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');


if (isset($_SESSION['voter_id'])  && ($_SESSION['role'] == 'Committee Member')) {

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // ------ END OF SESSION EXCHANGE
    $connection = DatabaseConnection::connect();
    // Assume $connection is your database connection
    $voter_id = $_SESSION['voter_id'];

    if (isset($_SESSION['organization'])) {
        // Retrieve the organization name
        $organization = $_SESSION['organization'];

        // Fetch candidates
        $candidatesQuery = "SELECT * FROM candidate";
        $result = $connection->query($candidatesQuery);

        // Fetch positions
        $positionsQuery = "SELECT position_id, title FROM position";
        $result_position = $connection->query($positionsQuery);
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" type="image/x-icon" href="../../src/images/resc/ivote-favicon.png">
        <title>Result Generation</title>

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

        <!-- UPON USE, REMOVE/CHANGE THE '../../' -->
        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
        <link rel="stylesheet" href="../src/styles/style.css">
        <link rel="stylesheet" href="styles/core.css" />
        <link rel="stylesheet" href="../src/styles/result.css">
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css">

        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $organization . '.css'; ?>" id="org-style">
        <style>
            <?php

            // Output the CSS with the organization color variable for background-color
            echo ".main-bg-color { background-color: var(--$organization);}";
            echo ".main-color { color: var(--$organization);}";
            echo ".card-candidate { border: 2px solid var(--$organization);}";
            echo ".hover-color:hover { color: var(--$organization);}";

            ?>.btn-with-margin {
                margin-top: 30px;
                width: 160px;
                height: 33px;
                margin-right: 20px;
                border-radius: 25px;
            }
        </style>


    </head>

    <body>

        <!---------- SIDEBAR + HEADER START ------------>
        <?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>
        <!---------- SIDEBAR + HEADER END ------------>


        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-10 col-lg-11 mx-auto">
                        <div class="card-report main-bg-color mb-4">
                            <div class="card-body main-bg-color d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">ELECTION RESULTS</h5>
                                    <p class="card-text" id="selectedPosition">
                                        <?php
                                        if ($result_position && $result_position->num_rows > 0) {
                                            $selected_position_id = isset($_GET['position_id']) ? $_GET['position_id'] : null;

                                            // Loop through all rows to find the selected position
                                            while ($row = $result_position->fetch_assoc()) {
                                                if ($selected_position_id == $row['position_id']) {
                                                    echo "For <strong>" . $row['title'] . "</strong>";
                                                    break; // Exit the loop once the selected position is found
                                                }
                                            }

                                            // If the selected position is not found, display a default message
                                            if (!$selected_position_id || $row['position_id'] != $selected_position_id) {
                                                echo "Select <strong> POSITION</strong> to show";
                                            }
                                        } else {
                                            echo "For <strong> POSITION</strong>";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                                // Reset the pointer of $result_position back to the beginning
                                $result_position->data_seek(0);

                                if ($result_position && $result_position->num_rows > 0) {
                                    $selected_position_id = isset($_GET['position_id']) ? $_GET['position_id'] : null;
                                    $selected_position_title = "Position";

                                    // Loop through all rows to find the selected position
                                    while ($row = $result_position->fetch_assoc()) {
                                        if ($selected_position_id == $row['position_id']) {
                                            $selected_position_title = $row['title'];
                                            break; // Exit the loop once the selected position is found
                                        }
                                    }
                                ?>
                                    <div class="dropdown">
                                        <button class="btn-election main-color hover-color dropdown-button btn-with-margin" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?php echo $selected_position_title; ?>
                                            <span class="ms-auto">
                                                <i class="fas fa-chevron-down" id="dropdownIcon"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <?php
                                            // Reset the pointer of $result_position back to the beginning
                                            $result_position->data_seek(0);
                                            // Loop through all positions and display dropdown items
                                            while ($row = $result_position->fetch_assoc()) {
                                            ?>
                                                <li><a class="dropdown-item" href="#" onclick="selectPosition('<?php echo $row['title']; ?>', <?php echo $row['position_id']; ?>)"><?php echo $row['title']; ?></a></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>

                                <?php
                                } else {
                                    echo '<span class="empty-text">No positions available</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


                <?php
                // Check if the position parameter is set in the URL
                if (isset($_GET['position_id'])) {
                    // Sanitize and store the position from the URL
                    $position = htmlspecialchars($_GET['position_id']);

                    // Fetch candidates for the specified position from the database
                    $query = "SELECT * FROM candidate WHERE position_id = ?";
                    $stmt = $connection->prepare($query);

                    if ($stmt) {
                        $stmt->bind_param("s", $position);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result) {
                            if ($result->num_rows > 0) {
                                // Create an array to store candidates and their respective vote counts
                                $candidates = array();

                                // Fetch and store vote counts for each candidate
                                while ($candidate_data = $result->fetch_assoc()) {
                                    $candidate_id = $candidate_data['candidate_id'];
                                    $vote_count_query = $connection->prepare("SELECT COUNT(*) as vote_count FROM vote WHERE candidate_id = ?");
                                    $vote_count_query->bind_param("i", $candidate_id);
                                    $vote_count_query->execute();
                                    $vote_count_query->bind_result($vote_count);
                                    $vote_count_query->fetch();
                                    $vote_count_query->close();

                                    // Store candidate information and vote count in the array
                                    $candidates[] = array(
                                        'candidate_data' => $candidate_data,
                                        'vote_count' => $vote_count,
                                    );
                                }

                                // Sort candidates based on their vote count (descending order)
                                usort($candidates, function ($a, $b) {
                                    return $b['vote_count'] - $a['vote_count'];
                                });

                                // Separate the candidate with the highest vote count
                                $highest_vote_candidate = array_shift($candidates);

                                // Display candidate with the highest vote count separately
                                $candidate_data = $highest_vote_candidate['candidate_data'];
                                $vote_count = $highest_vote_candidate['vote_count'];
                                $last_name = $candidate_data['last_name'];
                                $first_name = $candidate_data['first_name'];
                                $candidate_name = $last_name . ', ' . $first_name;
                ?>
                                <!-- Display candidate with the highest vote count -->
                                <div class="row">
                                    <div class="col-12 col-md-10 col-lg-11 mx-auto">
                                        <div class="card card-candidate card-candidate mb-4">
                                            <div class="card-body d-flex justify-content-between">

                                                <img src="images/candidate-profile/<?php echo $candidate_data["photo_url"]; ?>" class="org-logo" alt="Candidate Image">
                                                <div class="card-body text-center">
                                                    <h3 class="card-title"><?php echo $candidate_name ?></h3>
                                                    <h5 class="card-title main-color">BSIT 3-1</h5>
                                                </div>
                                                <p class="main-color"><strong><?php echo $vote_count; ?></strong> Votes</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Display the rest of the candidates -->
                                <?php foreach ($candidates as $candidate_info) {
                                    $candidate_data = $candidate_info['candidate_data'];
                                    $vote_count = $candidate_info['vote_count'];
                                    $last_name = $candidate_data['last_name'];
                                    $first_name = $candidate_data['first_name'];
                                    $candidate_name = $last_name . ', ' . $first_name;
                                ?>
                                    <div class="row">
                                        <div class="col-12 col-md-10 col-lg-11 mx-auto">
                                            <div class="card card-runnerup mb-4">
                                                <div class="card-body d-flex justify-content-between">
                                                    <img src="images/candidate-profile/<?php echo $candidate_data["photo_url"]; ?>" class="org-logo" alt="Candidate Image">
                                                    <div class="card-body text-center">
                                                        <h3 class="card-title"><?php echo $candidate_name ?></h3>
                                                        <h5 class="card-title main-color">BSIT 3-1</h5>
                                                    </div>
                                                    <p class="main-color"><strong><?php echo $vote_count; ?></strong> Votes</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                // Display message for no candidates
                                ?>
                                <div class="row">
                                    <div class="col-12 col-md-10 col-lg-11 mx-auto">
                                        <div class="card card-runnerup mb-4">
                                            <div class="card-body d-flex justify-content-between">
                                                <div class="card-body text-center">
                                                    <h3 class="main-color">No Candidate Available for this Position</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <?php
                                }
                            } else {
                                // Handle query execution error
                                echo "Error executing the query: " . $connection->error;
                            }
                        } else {
                            // Handle prepared statement creation error
                            echo "Error preparing the statement: " . $connection->error;
                        }
                    } else {
                        // Display message for no candidates
                    ?>
                    <div class="row">
                        <div class="col-12 col-md-10 col-lg-11 mx-auto">
                            <div class="card card-runnerup mb-4">
                                <div class="card-body d-flex justify-content-between">
                                    <div class="card-body text-center">
                                        <h3 class="main-color">No Candidates Available for this Position</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <script>
            function selectPosition(title, positionId) {
                document.getElementById("selectedPosition").innerHTML = title;
                window.location.href = "?position_id=" + positionId;
            }
        </script>

        <?php include_once __DIR__ . '/includes/components/footer.php'; ?>

        <!-- UPON USE, REMOVE/CHANGE THE '../../' -->
        <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/feather.js"></script>

    </body>

    </html>
<?php
} else {
    header("Location: landing-page.php");
}
?>