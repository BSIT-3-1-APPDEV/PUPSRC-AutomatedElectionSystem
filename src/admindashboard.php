<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/classes/admin-dashboard-queries.php');

// Create an instance of DatabaseConnection
$dbConnection = new DatabaseConnection();

// Create an instance of Application
$app = new Application($dbConnection);

if (isset($_SESSION['voter_id']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'head_admin')) {
    $org_name = $_SESSION['organization'] ?? '';

    include FileUtils::normalizeFilePath('includes/organization-list.php');
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    $org_full_name = $org_full_names[$org_name];

    // Fetch positions and year level counts using Application methods
    $positions = $app->getPositions();
    $firstPosition = $app->getFirstPosition();
    $yearLevelCounts = $app->getYearLevelCounts();
    $voterCounts = $app->getVoterCounts();
    $totalVotersCount = $voterCounts['totalVotersCount'];
    $votedVotersCount = $voterCounts['votedVotersCount'];
    $abstainedVotersCount = $voterCounts['abstainedVotersCount'];
    $totalPercentage = number_format($voterCounts['totalPercentage'], 2);
    $votedPercentage = number_format($voterCounts['votedPercentage'], 2);
    $candidateCount = $app->getCandidateCount();
    $voter_id = $_SESSION['voter_id'];
    $first_name = $app->getFirstName($voter_id);
    $electionPeriodStatus = $app->checkElectionPeriod();

  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
    <title>Admin Dashboard</title>

    <!-- Montserrat Font -->
    <link href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/admin_dashboard.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/core.css">
    <link rel="stylesheet" href="styles/loader.css" />
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/5.1.0/screenfull.min.js"></script>
    <script src="scripts/loader.js" defer></script>
    
    <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
    <style>

    .full-screen{
        background-color: #f5f5f5;
    }
    .full-screen-content.centered{
     
        margin-top: 15vh !important;
        margin-left: 10px !important;
        margin-right: 10px !important;
   
   
    }

  
    </style>
    
    <script>
        // Assuming $org_name is a string or a valid data type for JSON encoding
        const orgName = <?php echo json_encode($org_name); ?>;
        console.log(orgName); // Log the actual variable
        const inElectionPeriod = <?php echo json_encode($electionPeriodStatus['inElectionPeriod']); ?>;
        console.log(inElectionPeriod);
    </script>
</head>

<body>
    
    <?php 
    include FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php'); 
    include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
    ?>

    <main class="main px-0">

        <div class="container px-md-3 px-lg-5 px-sm-2 px-0 p-4 justify-content-center d-flex ">
        <div class="col-md-12 my-3 p-0 mx-0">
            <div class="card p-4 mb-5 mt-3">
            <div class="card-body">

           
                    <h3 class="fw-700 ms-3">Hey there, <span class="main-color fw-700"> <?php echo isset($first_name) ? $first_name . "!" : "Admin!"; ?>
 </span> </h3>
                    <small class="ms-3 fw-600">Welcome to your dashboard.</small>
                    </div>
                    </div>  
                  
            <div id="button-container">
        <!-- Button will be appended here -->
    </div>

            <div class="full-screen justify-content-center align-items-center d-block">
            <button id="reset-button" class="d-none">Hide Candidates</button>
                <div class="full-screen-content">
            <div class="live-results-container">
         
                
            <div class="row">
                        <div class="col-12 col-sm-6 d-flex">
                    <h4 class="main-color main-text ms-2">LIVE RESULTS</h4>
                    </div>
               <div class="col-12 col-sm-6 justify-content-start justify-content-sm-end d-flex mb-3 my-sm-0">
                    <select id="positions" class="px-1  positions-dropdown main-bg-color<?php if (empty($positions)) echo ' no-positions'; ?>">
    <?php
    // Check if there are positions available
    if (empty($positions)) {
        echo "<option value='' disabled selected>No positions available</option>";
    } else {
        // Loop through positions array to generate options
        foreach ($positions as $position) {
            echo "<option value=\"$position\">$position</option>";
        }
    }
    ?>
</select>

                  
</div>

                  
                  
                    
                   
                    </div>
              
            </div>
         
         
                
            <?php if ($electionPeriodStatus['inElectionPeriod']) { ?>
                        <div class="card">
                            <div class="icon-container pt-2 pe-2 text-end">
                                <a id="fullscreen-button">
                                    <i data-feather="maximize" class="main-color"></i>
                                </a>
                            </div>
                            <div class="chart-container pb-3 px-5">
                                <canvas id="myChart"></canvas>
                            </div>
                  
                    <?php } else { ?>
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <img src="images/resc/Dashboard/admin-empty-state.jpeg" style="height:200px; width:auto;">
                                <h5 class="fs-6 gray">Election period has not yet started</h5>
                            </div>
                    
                    <?php } ?>
    
</div>
<div class="row">
<div class="justify-content-end d-flex mt-2" >
<span class="me-2 mt-2 anonymous-text d-none">Anonymous</span>

<label class="switch d-none" id="switch">
  <input type="checkbox">
  <span class="slider round"></span>
</label>
</div>
</div>
</div>

           
            </div>
            <div class="row mb-1 ">
                <h4 class="main-text main-color mt-4 ms-2 ">METRICS</h4>
                    </div>
                <div class="row m-0 p-0 justify-content-between">
               <div class= "col-lg-7 m-0  ps-0 pe-lg-4 pe-md-0 pe-sm-0 pe-0">
                <div class="card p-3 ">
                    <div class="card-body pr-5">
                <div class="row justify-content-center ">
                <div class="col-md-12 col-lg-6 pe-lg-0 pe-xl-5">
                <canvas id="chartProgress" width="300" height="200"></canvas>

                </div>
                
                
                <div class="col-md-12 col-lg-6 justify-content-center align-self-center border-left pb-4 ">
                    
                  
                 
                        <div class="col-md-12 metrics-header justify-content-center align-items-center d-flex d-sm-flex d-md-block mt-3 ">
                       <small class="text-center ps-4 fw-700"> Total count of voters </small>
                        </div>
                        <div class="col-md-12 metrics-content justify-content-center  align-items-center  main-color d-flex d-sm-flex d-md-block mb-3 ">
                        <span class="text-center ps-4 fw-700 fs-20"> <?php echo $votedVotersCount; ?> out of <?php echo $totalVotersCount; ?>
    </span></span>
                        </div>
                        
                       
                  
                    
               
                     
                        <div class="col-md-12 metrics-header justify-content-center  align-items-center  d-flex d-sm-flex d-md-block ">
                        <small class="text-center ps-4 fw-700"> Abstained</small>
                        </div>
                        <div class="col-md-12 metrics-content justify-content-center  align-items-center  main-color d-flex d-sm-flex d-md-block">
                        <span class="text-center ps-4 fw-700">   <?php echo $abstainedVotersCount; ?> students</span>
                        </div>
                        </div>
                   
                </div>
                </div>
                </div>
                </div>
                
                 <div class="col-lg-5 justify-content-between d-flex flex-direct  pe-lg-0 px-md-0  px-0">
                  
                 <div class="card p-3 mt-3 mt-md-3 mt-lg-0 p-0 p-md-3 py-lg-5 py-xl-3">
                     
                     <div class="card-body d-flex   align-items-center justify-content-between p-3">
            
                <div class="row w-100">
                    <div class="col-9">
                    <div class="col-12">
                         <span class="secondary-metrics-header main-color">   Total count of </span>
                        </div>
                        <div class="col-12">
                        <span class="secondary-metrics-content fs-6 ">   VOTER ACCOUNTS </span>
                        </div>
                    </div>
                    </div>
                    <div class="col-3">
                    <div class="col-12 ">
                    <div class="circle main-bg-color">
                    <span class="secondary-metrics-number"><?php echo $totalVotersCount; ?></span>
</div> 
                    </div>
                </div>     
            </div>
            </div>
            <div class="card p-3 mt-3  mt-md-3 mt-lg-0">
                <div class="card-body d-flex align-items-center justify-content-between p-3 p-md-3 py-lg-5 py-xl-3">

            
                <div class="row w-100">
                    <div class="col-9">
                    <div class="col-12">
                         <span class="secondary-metrics-header main-color">   Total count of </span>
                        </div>
                        <div class="col-12">
                        <span class="secondary-metrics-content ">   CANDIDATES </span>
                        </div>
                    </div>
                    </div>
                    <div class="col-3">
                    <div class="col-12 ">
                    <div class="circle main-bg-color">
                    <span class="secondary-metrics-number"><?php echo $candidateCount; ?></span>
 </div>
                    </div>
                </div>     
            </div>
            </div>
            </div>    
            </div>   
            <div class="row ">
                <h4 class="main-text main-color mt-3 ms-3 mb-0 pt-3">NAVIGATE</h4>
                    </div>
            <div class="row justify-content-start d-flex m-0 p-0">
    <div class="col-lg-4 ml-5 py-3 ps-0 pe-1 ">
        <div class="col-lg-11">
            <a href="result-generation" class="card admin-card admin-link px-5 pt-4 pb-5">
                <div class="card-body d-flex align-items-center justify-content-center p-2">
                    <div class="icon-container">
                        <img src="images/resc/Dashboard/Reports/<?php echo $org_name . '-reports.png'; ?>" alt="Reports Image" class="navigate-images">
                    </div>
                </div>
                <div class="row">
                    <span class="vw-1 m-0 fw-bold mt-1 text-center">Reports</span>
                </div>
                <div class="row p">
                    <span class="vw-1 m-0 mt-1 text-center navigate-text">Generate reports for year’s election results</span>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 ml-5 py-3 ps-0 pe-1 d-lg-flex d-md-block justify-content-center ">
        <div class="col-lg-11">
            <a href="manage-voters" class="card admin-card admin-link px-5 pt-4 pb-5">
                <div class="card-body d-flex align-items-center justify-content-center p-2">
                    <div class="icon-container">
                        <img src="images/resc/Dashboard/Manage Acc/<?php echo $org_name . '-manage-accs.png'; ?>" alt="Reports Image" class="navigate-images">
                    </div>
                </div>
                <div class="row">
                    <span class="vw-1 m-0 fw-bold mt-1 text-center text-nowrap">Manage Accounts</span>
                </div>
                <div class="row">
                    <span class="vw-1 m-0 mt-1 text-center navigate-text">Create, modify, or delete user accounts</span>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 ml-5 py-3 ps-0 pe-0 d-lg-flex d-md-block justify-content-end ">
        <div class="col-lg-11">
            <a href="configuration" class="card admin-card admin-link px-5 pt-4 pb-5">
                <div class="card-body d-flex align-items-center justify-content-center p-2">
                    <div class="icon-container">
                        <img src="images/resc/Dashboard/Configuration/<?php echo $org_name . '-config.png'; ?>" alt="Reports Image" class="navigate-images">
                    </div>
                </div>
                <div class="row">
                    <span class="vw-1 m-0 fw-bold mt-1 text-center">Configuration</span>
                </div>
                <div class="row">
                    <span class="vw-1 m-0 mt-1 text-center navigate-text">Set your organization’s voting preferences</span>
                </div>
            </a>
        </div>
    </div>
</div>


    </main>
  
    <?php     include FileUtils::normalizeFilePath('includes/components/footer.php');?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/admin_dashboard.js"></script>
    <script src="scripts/feather.js"></script>
    

<script>

   
    </script>

<script>// Function to create gradient
// Function to create gradient
const COMPUTED_STYLE = getComputedStyle(document.querySelector('.main-bg-color'));
const COMPUTED_COLORS = COMPUTED_STYLE.backgroundColor;

// Function to create gradient
function createGradient(ctx, chartArea, color) {
    const GRADIENT = ctx.createLinearGradient(0, 0, chartArea.width, 0);
    GRADIENT.addColorStop(0,'#FFFFFF');
    GRADIENT.addColorStop(1, color);
    return GRADIENT;
}

var myChartCircle = new Chart('chartProgress', {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [<?php echo $totalPercentage; ?>, <?php echo $votedPercentage; ?>],
            backgroundColor: function(context) {
                const CHART = context.chart;
                const { ctx, chartArea } = CHART;
                if (!chartArea) {
                    // This can happen if the chart is not yet initialized
                    return null;
                }
                return [
                    createGradient(ctx, chartArea, COMPUTED_COLORS),
                    '#E5E5E5' // Default color for remaining percentage
                ];
            },
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
        beforeInit: (CHART) => {
            const DATASET = CHART.data.datasets[0];
            DATASET.data = [DATASET.data[0], 100 - DATASET.data[0]]; // Calculate remaining percentage
        }
    },
    {
        beforeDraw: (CHART) => {
            var width = CHART.width,
                height = CHART.height,
                ctx = CHART.ctx;
            ctx.restore();
            var fontSize = (height / 150).toFixed(2);
            ctx.font = "bold " + fontSize + "em Montserrat, sans-serif"; // Bold and Montserrat
            ctx.fillStyle = "black";
            ctx.textBaseline = "middle";
            var text = CHART.data.datasets[0].data[0] + "%",
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

</body>

</html>
<?php
} else {
  header("Location: landing-page");
}
?>
