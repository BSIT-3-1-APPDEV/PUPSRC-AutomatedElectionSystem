<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';

if(isset($_SESSION['voter_id'])) {  

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu for Admin Dashboard | CodingNepal</title>

    <!-- Montserrat Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/admin_dashboard.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/core.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    
    <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $organization . '.css'; ?>" id="org-style">
    <style>
        <?php
    

        // Output the CSS with the organization color variable for background-color
        echo ".main-bg-color { background-color: var(--$organization); }";

        // Output the CSS for the line with the dynamic color
        echo ".line { border-bottom: 2px solid var(--$organization); width: 100%; }";
        ?>
 

    </style>
</head>

<body>
<?php  include_once __DIR__ . '/includes/components/sidebar.php'; ?>
    <main class="main">

        <div class="container ">
            <div class="row justify-content-center">

                <div class="col-lg-4 ml-5 p-4">
                    <a href="#" class="card admin-card main-color admin-link">
                        <div class="card-body d-flex align-items-center justify-content-center ">
                            <div class="icon-container">
                                <i data-feather="bar-chart-2" class="margin-add main-color"></i>
                            </div>

                            REPORTS
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 p-4">
                    <a href="#" class="card admin-card main-color admin-link">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="icon-container">
                                <i data-feather="users" class="margin-add main-color"></i>
                            </div>
                            MANAGE VOTERS
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 p-4">
                    <a href="#" class="card admin-card admin-link main-color">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="icon-container">
                                <i data-feather="archive" class="margin-add main-color"></i>
                            </div>
                            ARCHIVE
                        </div>
                    </a>
                </div>
            </div>

            <div class="row justify-content-center ">
                <div class="col-md-12 position-container ">
                    <h4 class="main-color main-text">LIVE RESULTS</h4>
                    <select id="positions" class="positions-dropdown main-bg-color mb-4 mt-2" onchange="fetchCandidates(this.value)">
                        <?php
                        // Loop through positions array to generate options
                        foreach ($positions as $position) {
                            echo "<option value=\"$position\">$position</option>";
                        }
                        ?>
                    </select>


                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 ">
                    <div class="card chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mb-5">
                <h4 class="main-text main-color mt-3">PER YEAR METRICS</h4>
                <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
                    <div class="card p-3">
                        <h2 class="main-color fw-bold ">52</h2>
                        <h6 class="font-size-change fw-bold gray">1st Year</h6>
                    </div>
                </div>
                <div class="col-md-1  d-none align-self-center d-lg-block"> <!-- Add d-lg-block -->
                    <div class="line"></div>
                </div>

                <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
                    <div class="card p-3">
                        <h2 class="main-color fw-bold ">52</h2>
                        <h6 class="font-size-change fw-bold gray">2nd Year</h6>
                    </div>
                </div>
                <div class="col-md-1 d-none align-self-center d-lg-block"> <!-- Add d-lg-block -->
                    <div class="line"></div>
                </div>

                <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
                    <div class="card p-3">
                        <h2 class="main-color fw-bold ">52</h2>
                        <h6 class="font-size-change fw-bold gray">3rd Year</h6>
                    </div>
                </div>
                <div class="col-md-1  d-none align-self-center d-lg-block"> <!-- Add d-lg-block -->
                    <div class="line"></div>
                </div>

                <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
                    <div class="card p-3">
                        <h2 class="main-color fw-bold ">52</h2>
                        <h6 class="font-size-change fw-bold gray">4th Year</h6>
                    </div>
                </div>
            </div>

        </div>

    </main>
  

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/admin_dashboard.js"></script>
    <script>
        feather.replace();
    </script>




</body>

</html>
<?php
} else {
  header("Location: voter-login.php");
}
?>