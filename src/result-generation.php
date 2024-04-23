<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';

if (isset($_SESSION['voter_id'])) {

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
    <link rel="stylesheet" href="../src/styles/style.css">
    <link rel="stylesheet" href="../src/styles/result.css">
    <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $organization . '.css'; ?>" id="org-style">
    <style>
        <?php
/*

    // Output the CSS with the organization color variable for background-color
    echo ".main-bg-color { background-color: var(--$organization); }";

    // Output the CSS for the line with the dynamic color
    echo ".line { border-bottom: 2px solid var(--$organization); width: 100%; }"; */
    ?>

    </style>


</head>

<body>

    <!---------- SIDEBAR + HEADER START ------------>
    <?php include_once __DIR__ . '/includes/components/sidebar.php';?>
    <!---------- SIDEBAR + HEADER END ------------>


    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-10 mx-auto">
                    <div class="card card-report mb-5">
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">ELECTION RESULTS</h5>
                                    <p class="card-text" id="selectedPosition">
                                        <?php
                                            if ($result_position && $result_position->num_rows > 0) {
                                                    $selected_position_id = isset($_GET['position_id']) ? $_GET['position_id'] : null;

                                                    // Loop through all rows to find the selected position
                                                    while ($row = $result_position->fetch_assoc()) {
                                                        if ($selected_position_id == $row['position_id']) {
                                                            echo "For ", $row['title'];
                                                            break; // Exit the loop once the selected position is found
                                                        }
                                                    }

                                                    // If the selected position is not found, display a default message
                                                    if (!$selected_position_id || $row['position_id'] != $selected_position_id) {
                                                        echo "Position";
                                                    }
                                                } else {
                                                    echo "No positions available";
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
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?php echo $selected_position_title; ?>
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
                                            echo "No positions available.";
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
            $stmt->bind_param("s", $position);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if candidates exist for the position
            if ($result->num_rows > 0) {
                // Loop through candidates
                while ($candidate_data = $result->fetch_assoc()) {
                    $candidate_id = $candidate_data['candidate_id'];
                    $vote_count_query = "SELECT COUNT(*) as vote_count FROM vote WHERE candidate_id = $candidate_id";
                    $vote_count_result = $connection->query($vote_count_query);

                    if ($vote_count_result) {
                        $vote_count_data = $vote_count_result->fetch_assoc();
                        $vote_count = $vote_count_data['vote_count'];

                        // Display candidate information
                        $last_name = $candidate_data['last_name'];
                        $first_name = $candidate_data['first_name'];
                        $candidate_name = $last_name . ', ' . $first_name;
                        ?>
                                <!-- Display candidate card -->
                                <div class="row">
                                    <div class="col-12 col-md-10 col-lg-10 mx-auto">
                                        <div class="card card-candidate mb-5">
                                            <div class="card-body d-flex justify-content-between">
                                                <img src="<?php echo $candidate_data["photo_url"]; ?>" class="card-img-top" alt="Candidate Image">
                                                <div class="card-body text-center">
                                                    <h3 class="card-title"><?php echo $candidate_name ?></h3>
                                                    <h5 class="card-title">BSIT 3-1</h5>
                                                </div>
                                                <p><?php echo $vote_count; ?> Votes</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
     <?php
                        } else {
                                // Handle vote count query error
                                echo "Error executing vote count query: " . $connection->error;
                                }
                }
                    } else {
                        // Display message for no candidates
                        echo "<div class='row'><div class='col-12 col-md-10 col-lg-10 mx-auto'><div class='card card-runnerup mb-5'><div class='card-body d-flex justify-content-between'><div class='card-body text-center'><h3 class='card-title'>No Candidates</h3></div></div></div></div></div>";
                    }
            } else {
                // Display message for no candidates
                echo "<div class='row'><div class='col-12 col-md-10 col-lg-10 mx-auto'><div class='card card-runnerup mb-5'><div class='card-body d-flex justify-content-between'><div class='card-body text-center'><h3 class='card-title'>Choose Position</h3></div></div></div></div></div>";
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
