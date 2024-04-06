<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap 5 -->
    <link type="text/css" href="vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="src/styles/dist/landing.css">
    <title>PUP Automated Election System</title>
  </head>
  <body id="index-body">
<!-- Parallax section -->
<section class="parallax">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <div class="container-fluid text-center">
          <img src="src/images/logos/pup-src.png" class="img-fluid pup-logo" alt="PUPSRC Logo">
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
  <div class="container-fluid">
    <h2 class="landing-organization-title">Organization</h2>
    <p class="landing-organization-subtitle">- Select your organization - </p>

    <div class="container-fluid mt-4">
      <div class="row justify-content-center text-center">
        <div class="col-md-3">
          <div class="landing-page-org-card" id="SCO-landing-logo">
            <img src="src/images/logos/sco.png" alt="SCO Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">School Council Organization</h3>
          </div>
        </div> 
      </div>
    </div>

    <div class="container-fluid mt-4">
      <div class="row justify-content-center text-center">
        <div class="col-md-3" id="index-ACAP">
          <div class="landing-page-org-card" id="ACAP-landing-logo">
            <img src="src/images/logos/acap.png" alt="ACAP Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">ACAP</h3>
          </div>
        </div>
        <div class="col-md-3" id="index-AECES">
          <div class="landing-page-org-card" id="AECES-landing-logo">
            <img src="src/images/logos/aeces.png" alt="AECES Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">AECES</h3>
          </div>
        </div>

        <div class="col-md-3" id="index-ELITE">
          <a href="eliteLoginPage.html">
            <div class="landing-page-org-card" id="ELITE-landing-logo">
              <img src="src/images/logos/elite.png" alt="ELITE Logo" class="landing-page-logo-size">
              <h3 class="fw-bold pt-2">ELITE</h3>
            </div>
          </a>
        </div>  

      </div>
    </div>

    <div class="container-fluid mt-4">
      <div class="row justify-content-center text-center">
        <div class="col-md-3" id="index-ACAP">
          <div class="landing-page-org-card" id="GIVE-landing-logo">
            <img src="src/images/logos/give.png" alt="GIVE Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">GIVE</h3>
          </div>
        </div>
        <div class="col-md-3" id="index-JEHRA">
          <div class="landing-page-org-card" id="JEHRA-landing-logo">
            <img src="src/images/logos/jehra.png" alt="JEHRA Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">JEHRA</h3>
          </div>
        </div>

        <div class="col-md-3" id="index-JMAP">
          <div class="landing-page-org-card" id="JMAP-landing-logo">
            <img src="src/images/logos/jmap.png" alt="JMAP Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">JMAP</h3>
          </div>
        </div>  

      </div>
    </div>

    <div class="container-fluid mt-4">
      <div class="row justify-content-center text-center">
        <div class="col-md-3" id="index-JPIA">
          <div class="landing-page-org-card" id="JPIA-landing-logo">
            <img src="src/images/logos/jpia.png" alt="JPIA Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">JPIA</h3>
          </div>
        </div>
        <div class="col-md-3" id="index-PIIE">
          <div class="landing-page-org-card" id="PIIE-landing-logo">
            <img src="src/images/logos/piie.png" alt="PIIE Logo" class="landing-page-logo-size">
            <h3 class="fw-bold pt-2">PIIE</h3>
          </div>
        </div>
        </div>  
      </div>

    </div>
</section>

<footer class="bg-dark text-light text-center py-lg-2">
  <div class="container">
    <p class="triwan">&copy; BSIT 3-1 | ALL RIGHTS RESERVE 2024</p>
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
    </script>
</body>
</html>
