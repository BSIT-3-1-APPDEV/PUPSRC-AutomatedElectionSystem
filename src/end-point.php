<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');

if(isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'student_voter'))  {

  if((isset($_SESSION['vote_status'])) && ($_SESSION['vote_status'] == 'voted')){

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
  <title>Thank You!</title>
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

  <!-- Loader -->
  <div class="loader-wrapper">
      <div class="loader"></div>
  </div>

<?php include_once __DIR__ . '/includes/components/topnavbar.php'; ?>

<div class="container mb-5">
    <div class="row justify-content-md-center align-items-center">
        <div class="col-lg-6 col-sm-12">
            <div class="end-point text-center">
                <?php echo '<img src="../src/images/resc/end-point/'. $org_acronym .'-endpoint.png" alt="Endpoint Image" class="img-fluid">';?>
            </div> 
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="reminder px-5 py-4">
                <div class="header main-color text-center py-2">
                    <b>Your ballot is securely cast!</b>
                </div>
                <div class="header-sub text-center pb-2">
                    Stay tuned for the upcoming announcement of the newly appointed committee members on 
                    <?php echo strtoupper($org_acronym); ?>'s
                      <?php if ($org_acronym == 'acap'){
                        echo '<a href="https://www.facebook.com/ACAPpage">';
                      } else if ($org_acronym == 'aeces'){
                        echo '<a href="https://www.facebook.com/OfficialAECES">';
                      } else if ($org_acronym == 'elite'){
                        echo '<a href="https://www.facebook.com/ELITE.PUPSRC">';
                      } else if ($org_acronym == 'give'){
                        echo '<a href="https://www.facebook.com/educgive">';
                      } else if ($org_acronym == 'jehra'){
                        echo '<a href="https://www.facebook.com/PUPSRCJEHRA">';
                      } else if ($org_acronym == 'jpia'){
                        echo '<a href="https://www.facebook.com/JPIA.PUPSRC">';
                      } else if ($org_acronym == 'piie'){
                        echo '<a href="https://www.facebook.com/piiepup">';
                      } else if ($org_acronym == 'jmap'){
                        echo '<a href="https://www.facebook.com/JMAPPUPSRCOfficial">';
                      }else if ($org_acronym == 'sco'){
                        echo '<a href="https://www.facebook.com/thepupsrcstudentcouncil">';
                      }
                      ?>
                      Facebook</a> page. We sincerely appreciate your participation,&nbsp;<?php echo $org_personality ?>!
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
  <script src="../src/scripts/feedback-suggestions.js"></script>
  <script src="scripts/loader.js"></script>

  <?php
  } else{
    header("Location: ballot-forms.php");
  }
} else {
  header("Location: landing-page.php");
}
?>