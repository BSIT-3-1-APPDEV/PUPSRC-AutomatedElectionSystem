<?php
require_once 'includes/session-handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Bootstrap 5 -->
  <link type="text/css" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="styles/dist/landing.css">

  <link rel="icon" href="images/logos/pup-src.png" type="image/png">
  <title>PUP Automated Election System</title>
</head>

<body id="index-body">
  <!-- Parallax section -->
  <section class="parallax">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="container-fluid text-center">
            <img src="images/logos/pup-src.png" class="img-fluid pup-logo" alt="PUPSRC Logo">
            <h5 id="index-PUPSRC" class="text-truncate">Polytechnic University of the Philippines - Santa Rosa Campus</h5>
            <h1 id="index-AES">AUTOMATED ELECTION SYSTEM</h1>
            <a href="#organizations" type="button" class="btn btn-primary fw-bold index-button">Select your Organization</a>
          
            <div class="index-wave-footer">
              <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                  <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
              </svg>
          </div>
        </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Normal section -->
  <section id="organizations" class="organizations">
    <form action="includes/classes/landing-page-controller.php" method="post">
      <div class="container-fluid">
        <h2 class="landing-organization-title">Organization</h2>
        <p class="landing-organization-subtitle">- Select your organization - </p>

        <div class="container-fluid mt-4">
          <div class="row justify-content-center text-center">
            <div class="col-md-3">
              <a href="sco-login-page.php" name="submit_btn" value="sco" >
                <div class="landing-page-org-card" id="SCO-landing-logo">
                  <img src="images/logos/sco.png" alt="SCO Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">Student Council Organization</h3>
                </div>
              </a>
            </div> 
          </div>
        </div>

        <div class="container-fluid mt-4">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4" id="index-ACAP">
              <a href="acap-login-page.php" name="submit_btn" value="acap">
                <div class="landing-page-org-card" id="ACAP-landing-logo">
                  <img src="images/logos/acap.png" alt="ACAP Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">ACAP</h3>
                </div>
              </a>
            </div>
            <div class="col-md-3  mb-4" id="index-AECES">
              <a href="aeces-login-page.php" name="submit_btn" value="aeces">
                <div class="landing-page-org-card" id="AECES-landing-logo">
                  <img src="images/logos/aeces.png" alt="AECES Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">AECES</h3>
                </div>
              </a>
            </div>

            <div class="col-md-3" id="index-ELITE">
              <a href="elite-login-page.php" name="submit_btn" value="elite">
                <div class="landing-page-org-card" id="ELITE-landing-logo">
                  <img src="images/logos/elite.png" alt="ELITE Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">ELITE</h3>
                </div>
              </a>
            </div>  
          </div>
        </div>

        <div class="container-fluid mt-4">
          <div class="row justify-content-center text-center">
            <div class="col-md-3  mb-4" id="index-GIVE">
              <a href="give-login-page.php" name="submit_btn" value="give">
                <div class="landing-page-org-card" id="GIVE-landing-logo">
                  <img src="images/logos/give.png" alt="GIVE Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">GIVE</h3>
                </div>
              </a>
            </div>
            <div class="col-md-3  mb-4" id="index-JEHRA">
              <a href="jehra-login-page.php" name="submit_btn" value="jehra">
                <div class="landing-page-org-card" id="JEHRA-landing-logo">
                  <img src="images/logos/jehra.png" alt="JEHRA Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">JEHRA</h3>
                </div>
              </a>
            </div>

            <div class="col-md-3" id="index-JMAP">
              <a href="jmap-login-page.php" name="submit_btn" value="jmap">
                <div class="landing-page-org-card" id="JMAP-landing-logo">
                  <img src="images/logos/jmap.png" alt="JMAP Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">JMAP</h3>
                </div>
              </a>
            </div>  

          </div>
        </div>

        <div class="container-fluid mt-4">
          <div class="row justify-content-center text-center">
            <div class="col-md-3  mb-4" id="index-JPIA">
              <a href="jpia-login-page.php" name="submit_btn" value="jpia">
                <div class="landing-page-org-card" id="JPIA-landing-logo">
                  <img src="images/logos/jpia.png" alt="JPIA Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">JPIA</h3>
                </div>
              </a>
            </div>
            <div class="col-md-3  mb-4" id="index-PIIE">
              <a href="piie-login-page.php" name="submit_btn" value="piie">
                <div class="landing-page-org-card" id="PIIE-landing-logo">
                  <img src="images/logos/piie.png" alt="PIIE Logo" class="landing-page-logo-size">
                  <h3 class="fw-bold pt-2">PIIE</h3>
                </div>
              </a>
            </div>
            </div>  
          </div>


        </div>
      </div>  
    </form>
  </section>

  <footer class="bg-dark text-light text-center py-lg-2">
    <div class="container">
      <p class="copyright">&copy; BSIT 3-1 | ALL RIGHTS RESERVED 2024</p>
    </div>
  </footer>

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

    // Add event listeners to organization cards to submit the form when clicked
    document.querySelectorAll('.landing-page-org-card').forEach(card => {
      card.addEventListener('click', function() {
        // Get the organization name from the card ID
        const orgName = this.id.split('-')[0];
        // Set the value of the hidden input field in the form
        document.getElementById('selectedOrganization').value = orgName;
        // Submit the form
        document.getElementById('organizationForm').submit();
      });
    });
  </script>
</body>
</html>
