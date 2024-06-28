<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');

if(isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'student_voter'))  {


    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // ------ END OF SESSION EXCHANGE

  $connection = DatabaseConnection::connect();
  // Assume $connection is your database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Voting Closed</title>
  <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
  
  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- Bootstrap 5 code -->
  <link type="text/css" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../src/styles/feedback-suggestions.css">
  <link rel="stylesheet" href="styles/loader.css" />
  <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_acronym . '.css'; ?>">
  <!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <style> .nav-link:hover, .nav-link:focus {color: var(--<?php echo "main-color"; ?>); }
  .navbar-nav .nav-item.dropdown.show .nav-link.main-color {color: var(--main-color);}
  .navbar-nav .nav-item.dropdown .nav-link.main-color,.navbar-nav .nav-item.dropdown .nav-link.main-color:hover,
  .navbar-nav .nav-item.dropdown .nav-link.main-color:focus {color: var(--main-color);}</style>

</head>

<body>

<?php 
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/topnavbar.php'); 
?>

<div class="container mb-5">
    <div class="row justify-content-md-center align-items-center">
        <div class="col-lg-6 col-sm-12 order-sm-2">
            <div class="voting-closed text-center">
                <?php echo '<img src="../src/images/resc/closed-election-year/'. $org_acronym .'-closed-elec.png" alt="Closed Election Image" class="img-fluid">';?>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 order-sm-1">
            <div class="reminder px-2 px-sm-2 px-lg-5 py-4">
                <div class="header main-color text-center py-2">
                    The voting period is <br>currently closed!
                </div>
                <div class="header-sub text-center px-2 px-sm-2 pb-sm-4 pb-lg-2 pb-4">
                    Stay tuned for the continuation of the voting process on
                    <?php echo strtoupper($org_acronym); ?>'s
                    <?php 
                    switch ($org_acronym) {
                        case 'acap':
                            echo '<a href="https://www.facebook.com/ACAPpage">';
                            break;
                        case 'aeces':
                            echo '<a href="https://www.facebook.com/OfficialAECES">';
                            break;
                        case 'elite':
                            echo '<a href="https://www.facebook.com/ELITE.PUPSRC">';
                            break;
                        case 'give':
                            echo '<a href="https://www.facebook.com/educgive">';
                            break;
                        case 'jehra':
                            echo '<a href="https://www.facebook.com/PUPSRCJEHRA">';
                            break;
                        case 'jpia':
                            echo '<a href="https://www.facebook.com/JPIA.PUPSRC">';
                            break;
                        case 'piie':
                            echo '<a href="https://www.facebook.com/piiepup">';
                            break;
                        case 'jmap':
                            echo '<a href="https://www.facebook.com/JMAPPUPSRCOfficial">';
                            break;
                        case 'sco':
                            echo '<a href="https://www.facebook.com/thepupsrcstudentcouncil">';
                            break;
                        default:
                            break;
                    }
                    ?>
                    Facebook</a> page. We appreciate your patience, &nbsp;<?php echo $org_personality ?>! Your understanding is greatly valued.
                </div>
            </div>
        </div>
    </div>
</div>


</body>

<div id="footer-wrapper" style="position: fixed; bottom: 0; width: 100%;">
    <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
</div>

<script>
  // Stick the footer at the bottom

    window.addEventListener('DOMContentLoaded', function() {
        const footerWrapper = document.getElementById('footer-wrapper');

        function checkZoomLevel() {
            const zoomLevel = window.devicePixelRatio * 100;
            if (zoomLevel >= 110) {
                footerWrapper.style.position = 'static'; // Remove position fixed
            } else {
                footerWrapper.style.position = 'fixed';
                footerWrapper.style.bottom = '0';
                footerWrapper.style.width = '100%';
            }
        }

        // Call the function on page load
        checkZoomLevel();

        // Listen for window resize events
        window.addEventListener('resize', checkZoomLevel);
    });
</script>

  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="scripts/loader.js"></script>

  <?php

} else {
  header("Location: landing-page");
}
?>