<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');

// Check if voter_id and role is set in session
SessionManager::checkUserRoleAndRedirect();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fontawesome Link for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/dist/landing.css">
  <link rel="stylesheet" href="styles/loader.css">
  <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
  <title>iVote</title>
  <title>iVote</title>

  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body id="index-body">

  <?php include_once FileUtils::normalizeFilePath('includes/components/loader.html'); ?>

  <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
      <!-- <a class="navbar-brand" href="#">Your Brand</a> -->
      <img src="images/resc/ivote-icon-2.png" id="ivote-logo-landing-header" alt="ivote-logo">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="true" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item fw-medium">
            <a class="nav-link" href="landing-page.php">Home</a>
          </li>
          <li class="nav-item fw-medium">
            <a class="nav-link" href="about-us.php">About Us</a>
          </li>
          <li class=" nav-item">
            <a class="nav-link active" href="register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Parallax section -->
  <section class="parallax">
    <div class="container">
      <div class="row">
        <div class="col text-center text-white">
          <img src="images/resc/iVOTE4.png" class="img-fluid ivote-logo" alt="iVote Logo">
          <h5 id="index-PUPSRC" class="text-truncate stroked-text">Polytechnic University of the Philippines -
            Santa Rosa Campus</h5>
          <h1 class="stroked-text" id="index-AES">AUTOMATED ELECTION SYSTEM</h1>
        </div>
      </div>
      <div class="index-wave-footer">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
        </svg>
      </div>
    </div>
  </section>

  <!-- Normal section -->
  <section class="about-us-section" id="normal-section">
    <div class="container-fluid text-center">
      <div class="row">
        <div class="col-12 col-md-6 our-story-right">
          <h2 class="landing-organization-title"><span class="hello-text">Our</span> Story</h2>
          <p class="our-story-subtitle">How it all came to be.</p>
          <div id="carouselExample" class="carousel slide">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2" aria-label="Slide 3"></button>
              <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner position-relative">
              <div class="carousel-item active">
                <img src="images/our-story/409092520_944075257280478_7136331831805441509_n.jpg" class="d-block w-100" alt="Our Story Pic">
              </div>
              <div class="carousel-item">
                <img src="images/our-story/420587533_1513456552556489_7524115223452657082_n.jpg" class="d-block w-100" alt="Our Story Pic">
              </div>
              <div class="carousel-item">
                <img src="images/our-story/420589854_1135641680778898_4546773681696695970_n.jpg" class="d-block w-100" alt="Our Story Pic">
              </div>
              <div class="carousel-item">
                <img src="images/our-story/429193899_929505311949923_8565451832022205432_n.jpg" class="d-block w-100" alt="Our Story Pic">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
              <i class="bi bi-arrow-left" aria-hidden="true"></i>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
              <i class="bi bi-arrow-right" aria-hidden="true"></i>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
        <div class="col-12 col-md-6 our-story-left">
          <div class="row team-image">
            <div class="col-12 col-md-5">
              <img src="images/our-story/429302032_984602726329224_430578961855462892_n.jpg" alt="Our Story Pic">
            </div>
            <div class="col-12 col-md-7">
              <img src="images/our-story/446217357_1770118973512294_2538348661980998587_n.jpg" alt="Our Story Pic">
            </div>
          </div>
          <div class="team-bubble">
            <h4 class="our-story-teams">Business Analysts Team</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Pellentesque finibus urna et nulla mattis pharetra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean aliquet turpis congue, malesuada metus non, condimentum mi. Proin leo nulla, tincidunt vel est sit amet, pharetra dictum arcu. </p>
            <div class="text-end">
            <img src="images/resc/ivote-favicon.png" class="team-ivote-icon" alt="iVOTE Icon">
            </div>
          </div>
          <div class="row team-comment">
            <div class="col-12 col-md-5 kaliwa">
            <img src="images/logos/elite.png" alt="Our Story Pic" class="team-comment-picture">
            </div>
            <div class="col-12 col-md-7 kanan">
            <blockquote class="blockquote">
          <p class="mb-0">Working with the team has never been greater. Working with the team has never been greater. Working with the team has been.</p>
          <footer class="blockquote-footer text-white">Choi Soobin, <cite title="Source Title">Business Analyst</cite></footer>
        </blockquote>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

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

  <script>
    // Wait for the document to finish loading
    document.addEventListener("DOMContentLoaded", function() {
      // Get the element to scroll to
      var normalSection = document.getElementById("normal-section");
      // Scroll to the element
      normalSection.scrollIntoView();
    });
  </script>

</body>

</html>