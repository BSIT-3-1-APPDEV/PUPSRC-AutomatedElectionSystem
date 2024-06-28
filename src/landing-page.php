<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
include_once FileUtils::normalizeFilePath('includes/organization-list.php');

SessionManager::checkUserRoleAndRedirect();
session_destroy();

if (isset($_SESSION['error_message'])) {
  $error_message = $_SESSION['error_message'];
  unset($_SESSION['error_message']); // Unset the error message from the session once displayed
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fontawesome Link for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/dist/landing.css">
  <link rel="stylesheet" href="styles/dist/all-footer.css">
  <link rel="stylesheet" href="styles/loader.css" />
  <link rel="preload" href="images/resc/ivote-icon.png" as="image">
  <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
  <title>iVote</title>

  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
</head>

<body id="index-body">

  <?php include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html'); ?>

  <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
      <!-- <a class="navbar-brand" href="#">Your Brand</a> -->
      <img src="images/resc/ivote-icon-2.png" id="ivote-logo-landing-header" alt="ivote-logo">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item fw-medium">
            <a class="nav-link" href="landing-page">Home</a>
          </li>
          <li class="nav-item fw-medium">
            <a class="nav-link" href="about-us">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="register">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Parallax section -->
  <section class="parallax">
    <div class="container">
    <!-- Displays error message -->
    <?php if (isset($error_message)) : ?>
        <div class="text-danger alert alert-warning alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle flex-shrink-0 me-2" viewBox="0 0 16 16">
                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
            </svg>
            <div class="d-flex align-items-center">
                <span class="pe-1"><?php echo $error_message; ?></span>
                <button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
      <div class="row">
        <div class="col text-center text-white justify-content-center">
          <img src="images/resc/iVOTE4.png" class="img-fluid ivote-main-logo" alt="iVote Logo">
          <h5 id="index-PUPSRC" class="text-truncate mt-3">Polytechnic University of the Philippines -
            Santa Rosa Campus</h5>
          <h1 class="" id="index-AES">AUTOMATED ELECTION SYSTEM</h1>
          <a href="#organizations" type="button" class="btn btn-primary fw-bold index-button" id="">Select Organization</a>
        </div>
      </div>
      <div class="index-wave-footer" id="organizations">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
        </svg>
      </div>
    </div>
  </section>

  <!-- Normal section -->
  <section class="organizations">
    <form action="includes/classes/landing-page-controller.php" method="post">
      <div class="container-fluid">
        <h2 class="landing-organization-title"><span class="hello-text">Hello,</span> Isko’t Iska!</h2>
        <p class="landing-organization-subtitle">- Select your Organization - </p>

        <div class="container-fluid">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['sco']; ?>" class="landing-page-org-card" id="SCO-landing-logo">
                <img src="images/logos/sco.png" alt="SCO Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-capitalize"><?php echo $org_full_names['sco']; ?></h5>
              </button>
            </div>
          </div>
        </div>

        <div class="container-fluid">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4" id="index-ACAP">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['acap']; ?>" class="landing-page-org-card" id="ACAP-landing-logo">
                <img src="images/logos/acap.png" alt="ACAP Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['acap']; ?></h5>
              </button>
            </div>

            <div class="col-md-3 mb-4" id="index-AECES">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['aeces']; ?>" class="landing-page-org-card" id="AECES-landing-logo">
                <img src="images/logos/aeces.png" alt="AECES Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['aeces']; ?></h5>
              </button>
            </div>

            <div class="col-md-3 mb-4" id="index-AECES">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['elite']; ?>" class="landing-page-org-card" id="ELITE-landing-logo">
                <img src="images/logos/elite.png" alt="ELITE Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['elite']; ?></h5>
              </button>
            </div>
          </div>
        </div>

        <div class="container-fluid">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4" id="index-ACAP">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['give']; ?>" class="landing-page-org-card" id="GIVE-landing-logo">
                <img src="images/logos/give.png" alt="GIVE Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['give']; ?></h5>
              </button>
            </div>
            <div class="col-md-3 mb-4" id="index-JEHRA">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['jehra']; ?>" class="landing-page-org-card" id="JEHRA-landing-logo">
                <img src="images/logos/jehra.png" alt="JEHRA Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['jehra']; ?></h5>
              </button>
            </div>

            <div class="col-md-3 mb-4" id="index-JMAP">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['jmap']; ?>" class="landing-page-org-card" id="JMAP-landing-logo">
                <img src="images/logos/jmap.png" alt="JMAP Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['jmap']; ?></h5>
              </button>
            </div>

          </div>
        </div>

        <div class="container-fluid ">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4" id="index-JPIA">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['jpia']; ?>" class="landing-page-org-card" id="JPIA-landing-logo">
                <img src="images/logos/jpia.png" alt="JPIA Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['jpia']; ?></h5>
              </button>
            </div>
            <div class="col-md-3 mb-4" id="index-PIIE">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['piie']; ?>" class="landing-page-org-card" id="PIIE-landing-logo">
                <img src="images/logos/piie.png" alt="PIIE Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['piie']; ?></h5>
              </button>
            </div>
          </div>
        </div>
      </div>
      </div>
    </form>
  </section>

  <?php include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/all-footer.php'); ?>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="scripts/loader.js"></script>

  <!-- JavaScript for dynamic text change PUPSRC -->
  <script>
    const indexPUPSRC = document.getElementById('index-PUPSRC');

    function updateText() {
      if (window.innerWidth <= 768) {
        indexPUPSRC.textContent = 'PUP SANTA ROSA CAMPUS';
      } else {
        indexPUPSRC.textContent = 'Polytechnic University of the Philippines - Santa Rosa Campus';
      }
    }

    window.addEventListener('load', updateText);
    window.addEventListener('resize', updateText);
  </script>
</body>

</html>