<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');

if (isset($_SESSION['voter_id'])) {

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
        <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
        <title>Archive</title>

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

        <!-- Styles -->
        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $organization . '.css'; ?>" id="org-style">
        <link rel="stylesheet" href="styles/style.css" />
        <link rel="stylesheet" href="styles/core.css" />
        <link rel="stylesheet" href="styles/archive.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

        <!--JS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            <?php

            // Output the CSS with the organization color variable for background-color
            echo ".main-bg-color { background-color: var(--$organization);}";
            echo ".main-color { color: var(--$organization);}";
            echo ".card-candidate { border: 2px solid var(--$organization);}";

            ?>
        </style>

    </head>

    <body>

        <?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>


        <div class="main">

            <div class="container">
                <div class="main-color ">
                    <h5>ARCHIVE PER SCHOOL YEAR</h5>
                </div>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 mx-auto">
                        <div class="card mb-5">
                            <div class="card-body d-flex flex-column justify-content-between align-items-end">
                                <div class="dropdown" id="selectedPosition">
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
                                        <div class="dropdown mt-auto">
                                            <button class="position-btn main-bg-color" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?php echo $selected_position_title; ?>
                                                <i class="fas fa-chevron-down" id="dropdownIcon1"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
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
                                <div class="dropdown mt-auto">
                                    <button class="year-btn main-bg-color" type="button" id="yeardropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        School Year
                                        <i class="fas fa-chevron-down" id="dropdownIcon2"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="year-dropdown">
                                        <li><a class="dropdown-item" href="#">2022-2024</a></li>
                                        <li><a class="dropdown-item" href="#">2021-2022</a></li>
                                        <li><a class="dropdown-item" href="#">2020-2021</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-11">
                                    <!-- Chart Section -->
                                    <?php
                                    // Include the necessary library for generating charts (e.g., Chart.js)
                                    // Ensure that the necessary JavaScript files are included in your HTML document

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
                                                    // Initialize arrays to store candidate names and vote counts
                                                    $candidateNames = array();
                                                    $voteCounts = array();

                                                    // Fetch candidates and their vote counts
                                                    while ($candidate_data = $result->fetch_assoc()) {
                                                        $candidate_id = $candidate_data['candidate_id'];
                                                        $vote_count_query = $connection->prepare("SELECT COUNT(*) as vote_count FROM vote WHERE candidate_id = ?");
                                                        $vote_count_query->bind_param("i", $candidate_id);
                                                        $vote_count_query->execute();
                                                        $vote_count_query->bind_result($vote_count);
                                                        $vote_count_query->fetch();
                                                        $vote_count_query->close();

                                                        // Store candidate information
                                                        $last_name = $candidate_data['last_name'];
                                                        $first_name = $candidate_data['first_name'];
                                                        $candidate_name = $last_name . ', ' . $first_name;

                                                        // Store candidate names and vote counts in arrays
                                                        $candidateNames[] = $candidate_name;
                                                        $voteCounts[] = $vote_count;
                                                    }
                                    ?>
                                                    <!-- Display chart for the current position -->
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <canvas id="position_<?php echo $position; ?>_chart" width="400" height="200"></canvas>
                                                        </div>
                                                    </div>
                                                    <script>
                                                       // Initialize Chart.js
                                                    var ctx_<?php echo $position;?> = document.getElementById('position_<?php echo $position;?>_chart').getContext('2d');
                                                    var chart_<?php echo $position;?> = new Chart(ctx_<?php echo $position;?>, {
                                                        type: 'bar',
                                                        data: {
                                                            labels: <?php echo json_encode($candidateNames);?>,
                                                            datasets: [{
                                                                label: 'Vote Counts',
                                                                data: <?php echo json_encode($voteCounts);?>,
                                                                backgroundColor: 'main-bg-color',
                                                                borderColor: 'main-bg-color',
                                                                borderWidth: 0
                                                            }]
                                                        },
                                                        options: {
                                                            legend: {
                                                                display: false
                                                            },
                                                            scales: {
                                                                y: {
                                                                    beginAtZero: true
                                                                }
                                                            }

                                                        }
                                                    });
                                                    </script>
                                    <?php
                                                } else {
                                                    // Display message for no candidates
                                                    echo "<div style='text-align:center;'><img src='images/resc/folder-empty.png' class='empty'></div>"; 
                                                    echo "<div class='no-avail row'><div class='col-12'><p>No candidates available for this position.</p></div></div>";
                                                }
                                            } else {
                                                // Handle query execution error
                                                echo "<div class='row'><div class='col-12'><p>Error executing the query: " . $connection->error . "</p></div></div>";
                                            }
                                        } else {
                                            // Handle prepared statement creation error
                                            echo "<div class='row'><div class='col-12'><p>Error preparing the statement: " . $connection->error . "</p></div></div>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <h3>Name and Number of Votes for this School Year</h3>

                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="dropdown">
                                <button class="report-generator-btn main-bg-color" type="button" id="generate-report-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Generate Report <i data-feather="download" class="white im-cust feather-1xs"></i>
                                </button>
                                <ul class="dropdown-menu " aria-labelledby="generate-report-dropdown">
                                    <li><a class="dropdown-item" href="#" id="generate-pdf">Export as PDF</a></li>
                                    <li><a class="dropdown-item" href="#" id="generate-docx">Export as Word File</a></li>
                                    <li><a class="dropdown-item" href="#" id="generate-excel">Export as Excel File</a></li>
                                </ul>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup">
            <div class="row">
                <div class="col-9 col-md-9 col-lg-9 mx-auto">
                    <div class="popup-content">
                        <h2>Archive Preview</h2>
                        <div class="popup-container">
                            <img class="preview-logo" src="images/logos/jpia.png" alt="Organization Logo">
                            <h3>JUNIOR PHILIPPINE INSTITUTE OF ACCOUNTANTS</h3>
                            <h2>PRESIDENT</h2>
                            <h1>2021-2022</h1>
                            <table>
                                <tr>
                                    <th>Candidate Name</th>
                                    <th>Course, Year & Section</th>
                                    <th>Number of Votes</th>
                                </tr>
                                <tr>
                                    <td>Kim Mingyu</td>
                                    <td>BSIT 3-1</td>
                                    <td>349</td>
                                </tr>
                                <tr>
                                    <td>Kim Jennie</td>
                                    <td>BSIT 3-1</td>
                                    <td>287</td>
                                </tr>
                                <tr>
                                    <td>Park Jihoon</td>
                                    <td>BSIT 3-1</td>
                                    <td>108</td>
                                </tr>
                            </table>
                        </div>
                        <div class="button-container">
                            <button class="btn btn-primary" id="download-pdf">Download</button>
                            <button class="btn btn-secondary" id="cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup2">
            <div class="row">
                <div class="col-9 col-md-9 col-lg-9 mx-auto">
                    <div class="popup-content">
                        <h2>Archive Preview</h2>
                        <div class="popup-container">
                            
                        </div>
                        <div class="button-container">
                            <button class="btn btn-primary" id="download-excel">Download</button>
                            <button class="btn btn-secondary2" id="cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script>
            // Script for pdf preview pop-up
            document.getElementById("generate-pdf").addEventListener("click", function() {
                document.querySelector(".popup").style.display = "flex";
            })
            document.querySelector(".btn-secondary").addEventListener("click", function() {
                document.querySelector(".popup").style.display = "none";
            })
            // Script for pdf preview pop-up
            document.getElementById("generate-excel").addEventListener("click", function() {
                document.querySelector(".popup2").style.display = "flex";
            })
            document.querySelector(".btn-secondary2").addEventListener("click", function() {
                document.querySelector(".popup2").style.display = "none";
            })
        </script>



        <script>
            // Changing of toggle icon of submenus for position
            document.addEventListener('DOMContentLoaded', function() {
                var dropdownMenuButton1 = document.getElementById('dropdownMenuButton1');
                var dropdownIcon = document.getElementById('dropdownIcon');

                dropdownMenuButton1.addEventListener('click', function() {
                    if (dropdownIcon.classList.contains('fa-chevron-down')) {
                        dropdownIcon.classList.remove('fa-chevron-down');
                        dropdownIcon.classList.add('fa-chevron-up');
                    } else {
                        dropdownIcon.classList.remove('fa-chevron-up');
                        dropdownIcon.classList.add('fa-chevron-down');
                    }
                    dropdownIcon.style.transition = 'transform 0.5s ease';
                });
            });
            // Changing of toggle icon of submenus for year
            document.addEventListener('DOMContentLoaded', function() {
                var yeardropdown = document.getElementById('yeardropdown');
                var dropdownIcon2 = document.getElementById('dropdownIcon2');

                yeardropdown.addEventListener('click', function() {
                    if (dropdownIcon2.classList.contains('fa-chevron-down')) {
                        dropdownIcon2.classList.remove('fa-chevron-down');
                        dropdownIcon2.classList.add('fa-chevron-up');
                    } else {
                        dropdownIcon2.classList.remove('fa-chevron-up');
                        dropdownIcon2.classList.add('fa-chevron-down');
                    }
                    dropdownIcon2.style.transition = 'transform 0.5s ease';
                });
            });
           
            
        </script>

        <script>
            function selectPosition(title, positionId) {
                document.getElementById("selectedPosition").innerHTML = title;
                window.location.href = "?position_id=" + positionId;
            }
        </script>

        <?php include_once __DIR__ . '/includes/components/footer.php'; ?>


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