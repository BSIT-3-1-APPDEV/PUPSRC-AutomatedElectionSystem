<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('includes/classes/admin-dashboard-queries.php');

// Create an instance of DatabaseConnection
$dbConnection = new DatabaseConnection();

// Create an instance of Application
$app = new Application($dbConnection);

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('includes/session-exchange.php');

    $voterCounts = $app->getVoterCounts();
    $totalVotersCount = $voterCounts['totalVotersCount'];
    $votedVotersCount = $voterCounts['votedVotersCount'];
    $abstainedVotersCount = $voterCounts['abstainedVotersCount'];
    $candidateCount = $app->getCandidateCount();
    $totalPercentage = number_format($voterCounts['totalPercentage'], 2);
    $votedPercentage = number_format($voterCounts['votedPercentage'], 2);

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

           // Fetch positions
           $positionsQuery = "SELECT position_id, title FROM position ORDER BY title";
           $result_positions = $connection->query($positionsQuery);
 
           if ($result_years && $result_years->num_rows > 0) {
               $selected_year = isset($_GET['election_year']) ? $_GET['election_year'] : null;
           }
    }
 ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
        <title>Election Reports</title>

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

        <!-- Styles -->
        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $organization . '.css'; ?>" id="org-style">
        <link rel="stylesheet" href="styles/style.css" />
        <link rel="stylesheet" href="styles/core.css" />
        <link rel="stylesheet" href="styles/result.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <link rel="stylesheet" href="styles/tables.css" />

        <!--JS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            <?php

          // Output the CSS with the organization color variable for background-color
          echo ".main-bg-color { background-color: var(--$organization);}";
          echo ".main-color { color: var(--$organization);}";
          echo ".card-candidate { border: 2px solid var(--$organization);}";
          echo ".hover-color:hover { color: var(--$organization);}";

          ?>.btn-with-margin {
              margin-top: 38px;
              width: 180px;
              height: 33px;
              padding-left: 10px;
              padding-right: -20px;
              margin-right: 50px;
              border-radius: 25px;
          }
        </style>

    </head>

    <body>

        <?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

        <div class="main">
            <div class="container">
            <div class="row">
                    <div class="col-11 col-md-10 col-lg-11 mx-auto">
                        <div class="card-report main-bg-color mb-5">
                            <div class="card-body main-bg-color d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title"><i data-feather="bar-chart-2" class="white mb-xl-1"></i>  Election Reports</h5>
                                    <p class="card-text" id="selectedYear">
                                        <?php
                                        if ($result_years && $result_years->num_rows > 0) {
                                            $selected_year = isset($_GET['election_year']) ? $_GET['election_year'] : null;

                                            // Loop through all rows to find the selected year
                                            while ($row = $result_years->fetch_assoc()) {
                                                if ($selected_year == $row['election_year']) {
                                                    echo "Current Academic Year: <strong>" . $row['election_year']. "</strong>";
                                                    break; // Exit the loop once the selected year is found
                                                }
                                            }

                                            // If the selected year is not found, display a default message
                                            if (!$selected_year || $row['election_year'] != $selected_year) {
                                                echo "Select <strong> ELECTION YEAR</strong> to show";
                                            }
                                        } else {
                                            echo "<strong> ELECTION YEAR</strong>";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                                // Reset the pointer of $result_years back to the beginning
                                $result_years->data_seek(0);

                                if ($result_years && $result_years->num_rows > 0) {
                                    $selected_year = isset($_GET['election_year']) ? $_GET['election_year'] : null;
                                    $selected_year_title = "Election Year";

                                    // Loop through all rows to find the selected year
                                    while ($row = $result_years->fetch_assoc()) {
                                        if ($selected_year == $row['election_year']) {
                                            $selected_year_title = $row['election_year'];
                                            break; // Exit the loop once the selected year is found
                                        }
                                    }
                                ?>
                                <button class="report-generator-btn main-bg-color" onclick="downloadPDF()"  type="button" aria-expanded="false">
                                    <i data-feather="download" class="white im-cust feather-1xs"></i> Download
                                </button>
                                    <div class="dropdown">
                                        <button class="btn-election main-color hover-color dropdown-button btn-with-margin " type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="ms-auto">
                                                <div class="text-truncate"><?php echo "A.Y. " . $selected_year_title; ?> <i data-feather="chevron-down" class="white im-cust feather-1xs"></i></div>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu " aria-labelledby="dropdownMenuButton">
                                            <?php
                                            // Reset the pointer of $result_years back to the beginning
                                            $result_years->data_seek(0);
                                            // Loop through all years and display dropdown items
                                            while ($row = $result_years->fetch_assoc()) {
                                            ?>
                                                <li><a class="dropdown-item " href="#" onclick="selectYear('<?php echo $row['election_year']; ?>')"><?php echo $row['election_year']; ?></a></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php
                                } else {
                                    echo '<span class="empty-text">No election years available</span>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card card-header main-color"><p><strong>ELECTION RESULTS</strong></p></div>
                    </div>
                </div>

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
                            GROUP BY c.candidate_id 
                            ORDER BY c.position_id, vote_count DESC";
                    $stmt = $connection->prepare($query);

                    if ($stmt) {
                        $stmt->bind_param("i", $election_year);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                            // Create an array to store the highest vote candidate for each position
                            $highest_vote_candidates = array();

                            // Fetch and group candidates by position_id
                            while ($candidate_data = $result->fetch_assoc()) {
                                $position_id = $candidate_data['position_id'];
                                if (!isset($highest_vote_candidates[$position_id])) {
                                    $highest_vote_candidates[$position_id] = $candidate_data;
                                }
                            }

                            // Function to display a candidate
                            function displayCandidate($candidate_data, $isFirst = false) {
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
                                                <img src="images/candidate-profile/<?php echo htmlspecialchars($candidate_data["photo_url"]); ?>" class="org-logo" alt="Candidate Image">
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

                            // Display the highest vote candidate for the first position separately
                            $first_candidate_data = reset($highest_vote_candidates);
                            displayCandidate($first_candidate_data, true);

                            // Remove the first element from the array
                            array_shift($highest_vote_candidates);

                            // Display the highest vote candidates for the remaining positions
                            foreach ($highest_vote_candidates as $candidate_data) {
                                displayCandidate($candidate_data);
                            }
                        } else {
                            ?>
                            <div class="row">
                                <div class="col-11 col-md-10 col-lg-11 mx-auto">
                                    <div class="card card-runnerup mb-4">
                                        <div class="card-body d-flex justify-content-between">
                                            <div class="card-body text-center">
                                                <h2 class="no-avail-candidate">No candidates found for the selected election year.</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p class='text-center mt-4'>Unable to fetch data for the selected election year.</p>";
                    }
                }
                ?>

                <div class="dropdown2">
                <button class="btn-convert dropdown-button"><i data-feather="download" class="main-color im-cust small-icon"></i> Download results as...</button>
                <div class="dropdown-content2">
                    <a href="#" onclick="downloadPDF()">PDF (.pdf)</a>
                    <a href="#option2">Excel (.xsl)</a>
                </div>
                </div>
                    <button class="btn-previous"><i class="fas fa-chevron-left" id="submenuIcon"></i> Previous</button>
                    <button class="btn-next">   Next   <i class="fas fa-chevron-right" id="submenuIcon"></i></button>
                    <br>
                    <br>
                    <div class="col-11 col-md-10 col-lg-11 mx-auto">
                        <div class="card-graph mb-5">
                            <canvas id="myChart" style="padding: 60px 60px 40px 40px; height: 50px; width: 200px;"></canvas>
                            <div class="form-group">
                            <select id="positionSelect" class="form-control2 main-bg-color text-truncate">
                                <?php
                                if ($result_positions && $result_positions->num_rows > 0) {
                                    while ($row = $result_positions->fetch_assoc()) {
                                        echo "<option value='"  . htmlspecialchars($row['position_id']) . "'>" . htmlspecialchars($row['title']) . "</option>";
                                    }
                                } else {
                                    echo '<option value="" disabled>No positions available</option>';
                                }
                                ?>
                            </select>
                        </div>
                        </div>
                
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: [], // Initially empty
                                datasets: [{
                                    label: '# of Votes',
                                    data: [], // Initially empty
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderWidth: 1,
                                    barThickness: 40
                                }]
                            },
                            options: {
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                        },
                                        x: {
                                            grid: {
                                                display: false
                                            }
                                        }
                                    }
                                }
                            });

                    function updateChart(labels, data) {
                        myChart.data.labels = labels;
                        myChart.data.datasets[0].data = data;
                        myChart.update();
                    }

                    document.getElementById('positionSelect').addEventListener('change', function () {
                        var selectedPosition = this.value;
                        var electionYear = '<?php echo $selected_year; ?>';

                        fetch(`fetch_candidates.php?position_id=${selectedPosition}&election_year=${electionYear}`)
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                var labels = data.candidates.map(candidate => candidate.name);
                                var votes = data.candidates.map(candidate => candidate.vote_count);
                                updateChart(labels, votes);
                            });
                    });
                </script>

                    
                    <div class="card card-header">
                        <p class="main-color"><strong>VOTERS TURNOUT</strong></p>
                    </div>

                    <div class="row m-0 p-0 justify-content-between">
                        <div class="col-md-7 m-0 ps-0 pe-md-4 pe-md-0 pe-sm-0 pe-0 ">
                            <div class="card p-4 " style="border-radius: 27px; height: 280px;">
                                <div class="card-body2">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12 col-lg-6 pe-lg-0 pe-xl-5">
                                            <canvas id="chartProgress" width="200" height="200"></canvas>
                                        </div>
                                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                        <script>// Create a doughnut chart
                                        var myChartCircle = new Chart('chartProgress', {
                                            type: 'doughnut',
                                            data: {
                                                datasets: [{
                                                    data: [<?php echo $totalPercentage; ?>, <?php echo $votedPercentage; ?>], // Static dummy data
                                                    backgroundColor: ['#4CAF50', '#E5E5E5'], // Static colors
                                                    borderWidth: 0
                                                }]
                                            },
                                            options: {
                                                maintainAspectRatio: false,
                                                cutout: 75,
                                                cutoutPercentage: 75, // Adjust the size of the circle
                                                rotation: Math.PI / 2,
                                                legend: {
                                                    display: false
                                                },
                                                tooltips: {
                                                    enabled: false
                                                }
                                            },
                                            plugins: [{
                                                beforeInit: (chart) => {
                                                    const dataset = chart.data.datasets[0];
                                                    dataset.data = [dataset.data[0], 100 - dataset.data[0]]; // Calculate remaining percentage
                                                }
                                            },
                                            {
                                                beforeDraw: (chart) => {
                                                    var width = chart.width,
                                                        height = chart.height,
                                                        ctx = chart.ctx;
                                                    ctx.restore();
                                                    var fontSize = (height / 150).toFixed(2);
                                                    ctx.font = "bold " + fontSize + "em Montserrat, sans-serif"; // Bold and Montserrat
                                                    ctx.fillStyle = "black";
                                                    ctx.textBaseline = "middle";
                                                    var text = chart.data.datasets[0].data[0] + "%",
                                                        textX = Math.round((width - ctx.measureText(text).width) / 2),
                                                        textY = height / 1.75;
                                                    ctx.fillText(text, textX, textY);

                                                    // Adding 'Completed' text
                                                    var completedFontSize = (height / 300).toFixed(2); // Smaller font size
                                                    ctx.font = "bold " + completedFontSize + "em Montserrat";
                                                    var completedText = "Completed",
                                                        completedTextX = Math.round((width - ctx.measureText(completedText).width) / 2),
                                                        completedTextY = textY - 30; // Position above the percentage text
                                                    ctx.fillText(completedText, completedTextX, completedTextY);
                                                    ctx.save();
                                                }
                                            }]
                                        });

                                        </script>

                                        <div class="col-md-12 col-lg-6 justify-content-center align-self-center border-left pb-4">
                                            <div class="col-md-12 metrics-header justify-content-center align-items-center d-flex d-sm-flex d-md-block mt-3">
                                                <small class="text-center ps-4 fw-700">Total count of voters</small>
                                            </div>
                                            <div class="col-md-12 metrics-content justify-content-center align-items-center main-color d-flex d-sm-flex d-md-block mb-3">
                                                <span class="text-center ps-4 fw-700 fs-20"><?php echo $votedVotersCount; ?> out of <?php echo $totalVotersCount; ?></span>
                                            </div>

                                            <div class="col-md-12 metrics-header justify-content-center align-items-center d-flex d-sm-flex d-md-block">
                                                <small class="text-center ps-4 fw-700">Abstained</small>
                                            </div>
                                            <div class="col-md-12 metrics-content justify-content-center align-items-center main-color d-flex d-sm-flex d-md-block">
                                                <span class="text-center ps-4 fw-700"><?php echo $abstainedVotersCount; ?> students</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-lg-5 px-0 d-flex flex-column">
                            <!-- Voters Account Card -->
                            <div class="card p-1 mt-1 mt-md-0 " style="border-radius: 27px;">
                                <div class="card-body2 d-flex align-items-center justify-content-between p-3">
                                    <div class="row w-100" style="padding-left: 27px;">
                                        <div class="col-9">
                                            <div class="col-12">
                                                <span class="secondary-metrics-header main-color">Total count of</span>
                                            </div>
                                            <div class="col-12">
                                                <span class="secondary-metrics-content">VOTER ACCOUNTS</span>
                                            </div>
                                        </div>
                                        <div class="col-3" >
                                            <div class="col-12">
                                                <div class="circle main-bg-color">
                                                    <span class="secondary-metrics-number"><?php echo $totalVotersCount; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <!-- Candidate Card -->
                            <div class="card p-1 mt-1 mt-md-0" style="border-radius: 27px;">
                                <div class="card-body2 d-flex align-items-center justify-content-between p-3">
                                    <div class="row w-100" style="padding-left: 27px;">
                                        <div class="col-9">
                                            <div class="col-12">
                                                <span class="secondary-metrics-header main-color">Total count of</span>
                                            </div>
                                            <div class="col-12">
                                                <span class="secondary-metrics-content">CANDIDATES</span>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="col-12">
                                                <div class="circle main-bg-color">
                                                    <span class="secondary-metrics-number"><?php echo $candidateCount; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                    <div class="card card-header">
                    <p class="main-color"><strong>FEEDBACK AND SUGGESTIONS</strong></p>
                    </div>
                    <div class="card-feedback mb-5">
                        <div class="col-sm-6">
							<p class="feedback-text fs-3 main-color fw-bold ls-10 spacing-6">Feedback Ratings</p>
						</div>
                        <div class="card-body d-flex flex-column justify-content-between">
                        <div class="row">
                        <div class="emoji">
                        <ul class="feedback pb-4">
                            <li class="angry" data-value="Very Unsatisfied" title="Very Unsatisfied">
                                <div>
                                    <svg class="eye left">
                                        <use xlink:href="#eye">
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye">
                                    </svg>
                                    <svg class="mouth">
                                        <use xlink:href="#mouth">
                                    </svg>
                                </div>
                            </li>
                            <li class="sad" data-value="Unsatisfied" title="Unsatisfied">
                                <div>
                                    <svg class="eye left">
                                        <use xlink:href="#eye">
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye">
                                    </svg>
                                    <svg class="mouth">
                                        <use xlink:href="#mouth">
                                    </svg>
                                </div>
                            </li>
                            <li class="ok" data-value="Neutral" title="Neutral">
                                <div></div>
                            </li>
                            <li class="good" data-value="Satisfied" title="Satisfied">
                                <div>
                                    <svg class="eye left">
                                        <use xlink:href="#eye">
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye">
                                    </svg>
                                    <svg class="mouth">
                                        <use xlink:href="#mouth">
                                    </svg>
                                </div>
                            </li>
                            <li class="happy" data-value="Very Satisfied" title="Very Satisfied">
                                <div>
                                    <svg class="eye left">
                                        <use xlink:href="#eye">
                                    </svg>
                                    <svg class="eye right">
                                        <use xlink:href="#eye">
                                    </svg>
                                </div>
                            </li>
                        </ul>
                        <div class="pb-2"></div>

                        <input type="hidden" id="rating" name="rating" value="">
                    
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 4" id="eye">
                                    <path d="M1,1 C1.83333333,2.16666667 2.66666667,2.75 3.5,2.75 C4.33333333,2.75 5.16666667,2.16666667 6,1"></path>
                                </symbol>
                                <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 7" id="mouth">
                                    <path d="M1,5.5 C3.66666667,2.5 6.33333333,1 9,1 C11.6666667,1 14.3333333,2.5 17,5.5"></path>
                                </symbol>
                            </svg>
                            <div class="horizontal-align">
                            <div>
                                <h1>50 %</h1>
                                <h2>Very Unsatisfied</h2>
                            </div>
                            <div>
                                <h1>40 %</h1>
                                <h2>Unsatisfied</h2>
                            </div>
                            <div>
                                <h1>20 %</h1>
                                <h2>Neutral</h2>
                            </div>
                            <div>
                                <h1>50 %</h1>
                                <h2>Satisfied</h2>
                            </div>
                            <div>
                                <h1>50 %</h1>
                                <h2>Very Satisfied</h2>
                            </div>
                        </div>
                        </div>
                    </div>       
                </div>
            </div>

                        <div class="card-graph mb-5">
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
            function selectPosition(title, positionId) {
                document.getElementById("selectedPosition").innerHTML = title;
                window.location.href = "?position_id=" + positionId;
            }

            //for dropdown download
            window.onclick = function(event) {
            if (!event.target.matches('.dropdown-button2')) {
                var dropdowns = document.getElementsByClassName("dropdown-content2");
                for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
                }
            }
            }

            // Attach event listeners to rating and feedback inputs to update button text
            ratingInput.addEventListener('input', updateButtonText);
            feedbackInput.addEventListener('input', updateButtonText);


            // Selecting emoji, no initial selected emoji
            document.querySelectorAll('.feedback li').forEach(entry => entry.addEventListener('click', e => {
            if (entry.classList.contains('active')) {
                entry.classList.remove('active');
            } else {
                document.querySelector('.feedback li.active')?.classList.remove('active');
                entry.classList.add('active');
            }
            e.preventDefault();
            }));

            // Add value of the rating, corresponding with the emoji selected
            document.addEventListener("DOMContentLoaded", function() {
            var feedbackOptions = document.querySelectorAll(".feedback li");

            feedbackOptions.forEach(function(option) {
                option.addEventListener("click", function() {
                    var value = this.getAttribute("data-value");
                    document.getElementById("rating").value = value;
                });
            });
            });

    </script>
    <script>
        function selectYear(election_year) {
            window.location.href = "?election_year=" + election_year;
        }

        feather.replace();
    </script>
    </body>
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
                                    GROUP BY c.position_id 
                                    ORDER BY c.position_id, vote_count DESC";
                            $stmt = $connection->prepare($query);

                            if ($stmt) {
                                $stmt->bind_param("i", $election_year);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $position_id = $row['position_id'];
                                        $candidate_name = $row['last_name'] . ', ' . $row['first_name'];
                                        $candidate_section = $row['program'];
                                        $vote_count = $row['vote_count'];
                                        $position_title = $row['position_title'];

                                        // Display only the candidate with the highest vote count for each position
                                        /*echo "<tr>";
                                        echo "<td>" . htmlspecialchars($position_title) . "</td>";
                                        echo "<td>" . htmlspecialchars($candidate_name) . "</td>";
                                        echo "<td>" . htmlspecialchars($candidate_section) . "</td>";
                                        echo "<td>" . htmlspecialchars($vote_count) . "</td>";
                                        echo "</tr>"; */
                                        
                                    }
                                    $stmt->close();
                                } else {
                                    echo "Error fetching candidates";
                                }
                            } else {
                                echo "Error preparing statement";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
            <script>
            function downloadPDF() {
                fetch('result-pdf.php?election_year=<?php echo $election_year; ?>')
                .then(response => response.text())
                .then(phpString => {
                const options = {
                    margin: 10,
                    filename: 'generated_pdf.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                };

                html2pdf().from(phpString).set(options).save();
                })
                .catch(error => {
                console.error('Error fetching PHP content:', error);
                });
            }

            function alldownloadPDF() {
                fetch('allresult-pdf.php?election_year=<?php echo $election_year; ?>')
                .then(response => response.text())
                .then(phpString => {
                const options = {
                    margin: 10,
                    filename: 'generated_pdf.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                };

                html2pdf().from(phpString).set(options).save();
                })
                .catch(error => {
                console.error('Error fetching PHP content:', error);
                });
            }
            </script>

            <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="scripts/script.js"></script>
            <script src="scripts/feather.js"></script>
    </html>

<?php
} else {
    header("Location: landing-page.php");
}
?>