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



?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
        <title>Profile</title>

        <!-- Montserrat Font -->
        <link href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="styles/profile.css">
        <link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/core.css">
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/5.1.0/screenfull.min.js"></script>


        <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
        <style>
            <?php


            // Output the CSS with the organization color variable for background-color
            echo ".main-bg-color { background-color: var(--$org_name) !important; }";

            echo ".main-border-color { border: var(--$org_name)  2px solid !important;}";
            echo ".main-border-color:hover { background-color: var(--$org_name) !important; }";

            // Output the CSS for the line with the dynamic color
            echo ".line { border-bottom: 2px solid var(--$org_name); width: 100%; }";
            ?>.full-screen {
                background-color: #f5f5f5;
            }

            .full-screen-content.centered {
                margin-top: 20vh !important;
                margin-left: 100px !important;
                margin-right: 100px !important;
            }
        </style>
    </head>

    <body>

        <?php include FileUtils::normalizeFilePath('includes/components/sidebar.php'); ?>

        <main class="main">

        <!-- WAG LANG MUNA TO ALISIN, REFERENCE KO TO -->
            <!-- <div class="container-fluid hehe">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3 class="profile-title" id="<?php echo strtolower($org_name); ?>SignUP">Profile Information</h3>
                        <button class="btn btn-outline-primary btn-sm">Edit Profile</button>
                    </div>
                    <div class="col-6 position-relative">

                        <div class="member-card">
                            <div class="member-card-header">
                                iVOTE Committee Role
                            </div>
                            <div class="member-card-body">
                                MEMBER
                            </div>
                        </div>
                        <div class="vertical-line"></div>
                    </div>
                    <div class="col-6 right-profile d-flex flex-column justify-content-end">
                        <h6 class=" profile mb-0" id="<?php echo strtolower($org_name); ?>SignUP">Full Name</h6>
                        <h5 class="member-name mt-0">Dator, Rhey Yuri Marcelino</h5>
                        <h6 class=" profile mt-4 mb-0" id="<?php echo strtolower($org_name); ?>SignUP">Email Address</h6>
                        <h5 class="member-name mt-0">rhey.yuridator@gmail.com</h5>
                    </div>
                </div>
            </div> -->

            <div class="container-fluid profile-container">
                <div class="">
                    <div class="d-flex justify-content-between">
                        <h3 class="profile-title main-color">Profile Information</h3>
                        <a href="edit-profile.php" type="button" class="btn btn-outline-primary main-color main-border-color edit-button">Edit Profile</a>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 text-center position-relative card-container">
                            <div class="member-card main-border-color">
                                <div class="member-card-header main-bg-color">
                                    iVOTE Committee Role
                                </div>
                                <div class="member-card-body">
                                    MEMBER
                                </div>
                            </div>
                            <div class="vertical-line"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info">
                                <div>
                                    <span class="label main-color">Full Name</span>
                                    <p class="user-name">Dator, Rhey Yuri Marcelino</p>
                                </div>
                                <div>
                                    <span class="label main-color">Email Address</span>
                                    <p class="user-email">rhey.yuridator@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </main>

        <?php include FileUtils::normalizeFilePath('includes/components/footer.php'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="scripts/script.js"></script>
        <script src="scripts/admin_dashboard.js"></script>
        <script src="scripts/feather.js"></script>


    </body>

    </html>
<?php
} else {
    header("Location: landing-page.php");
}
?>