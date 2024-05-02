<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');

if(isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'Student Voter') && ($_SESSION['status'] == 'Active')) {

 // if((isset($_SESSION['vote_status'])) && ($_SESSION['vote_status'] == 'Voted')){

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
  <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_acronym . '.css'; ?>">
  <!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>


</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container">
    <a class="navbar-brand spacing" href="#">
      <img src="../src/images/resc/ivote-logo.png" alt="Logo" width="50px">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-none d-lg-block">
          <a class="nav-link main-color" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <b>Hello, Iskolar</b><i class="fas fa-user-circle main-color ps-3" style="font-size: 23px;"></i> <i class="fas fa-chevron-down text-muted ps-2"></i>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="includes/voter-logout.php">Logout</a>
          </div>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link" href="includes/voter-logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-md-center align-items-center">
        <div class="col-lg-6 col-sm-12">
            <div class="end-point text-center">
                <?php echo '<img src="../src/images/resc/end-point/'. $org_acronym .'-endpoint.png" alt="Endpoint Image" class="img-fluid">';?>
            </div> 
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="reminder p-4">
                <div class="header main-color text-center pb-2">
                    <b>Your ballot is securely cast!</b>
                </div>
                <div class="header-sub text-center" style="font-weight: 400;">
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
                      Facebook</a> page. We sincerely appreciate your participation, Isko't-Iska!
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
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


  <?php
  //} else{
   // header("Location: ballot-forms.php");
 // }
} else {
  header("Location: landing-page.php");
}
?>