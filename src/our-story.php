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
  <link rel="stylesheet" href="styles/dist/all-footer.css">
  <link rel="stylesheet" href="styles/our-story.css">
  <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
  <title>iVote</title>
  <title>iVote</title>

  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            <a class="nav-link" href="landing-page">Home</a>
          </li>
          <li class="nav-item fw-medium">
            <a class="nav-link" href="about-us">About Us</a>
          </li>
          <li class=" nav-item">
            <a class="nav-link active" href="register">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Parallax section -->
  <section class="parallax">
    <div class="container">
      <div class="row">
        <div class="col text-center text-white justify-content-center">
          <img src="images/resc/iVOTE4.png" class="img-fluid ivote-main-logo" alt="iVote Logo">
          <h5 id="index-PUPSRC" class="text-truncate mt-3">Polytechnic University of the Philippines -
            Santa Rosa Campus</h5>
          <h1 class="" id="index-AES">AUTOMATED ELECTION SYSTEM</h1>
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
  <section class="about-us-section" id="normal-section">
    <div class="container-fluid ">
      <div class="row">
        <div class="col col-md-6 our-story-left">
          <h2 class="landing-organization-title"><span class="hello-text">Our</span> Story</h2>
          <p class="our-story-subtitle">How it all came to be.</p>
          <div id="carouselMain" class="carousel slide carousel-shadow" data-bs-ride="carousel" data-bs-wrap="true">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="2" aria-label="Slide 3"></button>
              <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="images/our-story/PM Department/PM main pic.jpg" class="d-block w-100 " alt="PM Department">
              </div>
              <div class="carousel-item">
                <img src="images/our-story/DEV Department/DEV main pic.jpg" class="d-block w-100 " alt="DEV Department">
              </div>
              <div class="carousel-item">
                <img src="images/our-story/QA Department/QA main pic.jpg" class="d-block w-100 " alt="QA Department">
              </div>
              <div class="carousel-item">
                <img src="images/our-story/BA Department/BA main pic.jpg" class="d-block w-100 " alt="BA Department">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselMain" data-bs-slide="prev">
              <i class="bi bi-arrow-left" aria-hidden="true"></i>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselMain" data-bs-slide="next">
              <i class="bi bi-arrow-right" aria-hidden="true"></i>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
        <div class="col-md-6 our-story-right">
          <div class="container-fluid">
            <div class="row">
              <div class="col-4 col-sm-4 col-md-4 col-lg-4 image-container">
                <div id="carouselSmallPic" class="image-container slide" data-bs-ride="carousel" data-bs-wrap="true">
                  <div class="carousel-inner">
                    <div class="carousel-item active">
                      <img src="images/our-story/PM Department/PM small pic.jpg" class="d-block w-100 carousel-image" alt="PM Department">
                    </div>
                    <div class="carousel-item">
                      <img src="images/our-story/DEV Department/DEV small pic.jpg" class="d-block w-100 carousel-image" alt="DEV Department">
                    </div>
                    <div class="carousel-item">
                      <img src="images/our-story/QA Department/QA small pic.jpg" class="d-block w-100 carousel-image" alt="QA Department">
                    </div>
                    <div class="carousel-item">
                      <img src="images/our-story/BA Department/BA small pic.jpg" class="d-block w-100 carousel-image" alt="BA Department">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-8 col-sm-8 col-md-8 col-lg-8 ">
                <div id="carouselBigPic" class="image-container slide" data-bs-ride="carousel" data-bs-wrap="true">
                  <div class="carousel-inner">
                    <div class="carousel-item active">
                      <img src="images/our-story/PM Department/PM big pic.jpg" class="d-block w-100 carousel-image" alt="PM Department">
                    </div>
                    <div class="carousel-item">
                      <img src="images/our-story/DEV Department/DEV big pic.jpg" class="d-block w-100 carousel-image" alt="DEV Department">
                    </div>
                    <div class="carousel-item">
                      <img src="images/our-story/QA Department/QA big pic.jpg" class="d-block w-100 carousel-image" alt="QA Department">
                    </div>
                    <div class="carousel-item">
                      <img src="images/our-story/BA Department/BA big pic.jpg" class="d-block w-100 carousel-image" alt="BA Department">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="team-department mt-3">
            <div class="department-box mb-3" id="departmentBox">
              <div id="carouselDepartment" class="image-container slide" data-bs-ride="carousel" data-bs-wrap="true">
                <div class="carousel-inner">
                  <div class="carousel-item active" id="pm">
                    <h4 class="department-name">Project Manager Team</h4>
                    <p class="department-definition mb-0 mt-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque finibus urna et nulla mattis pharetra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean aliquet turpis congue, malesuada metus non, condimentum mi. Proin leo nulla, tincidunt vel est sit amet, pharetra dictum arcu.</p>
                  </div>
                  <div class="carousel-item" id="development">
                    <h4 class="department-name">Development Team</h4>
                    <p class="department-definition mb-0 mt-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque finibus urna et nulla mattis pharetra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean aliquet turpis congue, malesuada metus non, condimentum mi. Proin leo nulla, tincidunt vel est sit amet, pharetra dictum arcu.</p>
                  </div>
                  <div class="carousel-item" id="qa">
                    <h4 class="department-name">Quality Assurance Team</h4>
                    <p class="department-definition mb-0 mt-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque finibus urna et nulla mattis pharetra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean aliquet turpis congue, malesuada metus non, condimentum mi. Proin leo nulla, tincidunt vel est sit amet, pharetra dictum arcu.</p>
                  </div>
                  <div class="carousel-item" id="ba">
                    <h4 class="department-name">Business Analysts Team</h4>
                    <p class="department-definition mb-0 mt-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque finibus urna et nulla mattis pharetra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean aliquet turpis congue, malesuada metus non, condimentum mi. Proin leo nulla, tincidunt vel est sit amet, pharetra dictum arcu.</p>
                  </div>
                </div>
              </div>
              <div class="department-icon mt-0">
                <img src="images/resc/ivote-favicon.png" class="img-fluid ivote-our-story" alt="iVote Logo">
              </div>
            </div>

            <div class="container-fluid testimony">
              <div class="row">
                <div class="col-4 col-sm-4 col-md-4 col-lg-4 quotePic">
                  <div id="carouselPic" class="image-container slide" data-bs-ride="carousel" data-bs-wrap="true">
                    <div class="carousel-inner">
                      <div class="carousel-item active" id="pm">
                        <img src="images/our-story/DEV Department/DEV.jpg" class="img-fluid department-quote" alt="iVote Logo">
                      </div>
                      <div class="carousel-item" id="development">
                        <img src="images/our-story/DEV Department/DEV.jpg" class="img-fluid department-quote" alt="iVote Logo">
                      </div>
                      <div class="carousel-item" id="qa">
                        <img src="images/our-story/BA Department/BA.png" class="img-fluid department-quote" alt="iVote Logo">
                      </div>
                      <div class="carousel-item" id="ba">
                        <img src="images/our-story/BA Department/BA.png" class="img-fluid department-quote" alt="iVote Logo">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-8 col-sm-8 col-md-8 col-lg-8 quoteBox mb-0" id="quoteBox">
                  <div class="row">
                    <div class="col-2 col-sm-2 col-md-2 col-lg-2 quoteText mb-3">
                      <span class="quote-mark">“</span>
                    </div>
                    <div class="col-10 col-sm-10 col-md-10 col-lg-10">
                      <div id="carouselQuoteText" class="image-container" data-bs-ride="carousel" data-bs-wrap="true">
                        <div class="carousel-inner">
                          <div class="carousel-item active" id="pm">
                            <p class="quote">Beyond grateful for the team's collaborative effort in transforming data into actionable insights, which enable us to get things done and achieve our goals.</p>
                          </div>
                          <div class="carousel-item" id="development">
                            <p class="quote">What an amazing experience it is, to be surrounded by an environment composed of great-minded individuals, crafting a solution for enhancing the campus’ election system.</p>
                          </div>
                          <div class="carousel-item" id="qa">
                            <p class="quote">Beyond grateful for the team's collaborative effort in transforming data into actionable insights, which enable us to get things done and achieve our goals.</p>
                          </div>
                          <div class="carousel-item" id="ba">
                            <p class="quote">Beyond grateful for the team's collaborative effort in transforming data into actionable insights, which enable us to get things done and achieve our goals.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="carouselQuoteName" class="" data-bs-ride="carousel" data-bs-wrap="true">
                    <div class="carousel-inner">
                      <div class="carousel-item active" id="pm">
                        <p class="quote-name">Name, Project Manager</p>
                      </div>
                      <div class="carousel-item" id="development">
                        <p class="quote-name">Marie Jeremie Legrama, Developer</p>
                      </div>
                      <div class="carousel-item" id="qa">
                        <p class="quote-name">Name, Quality Assurance</p>
                      </div>
                      <div class="carousel-item" id="ba">
                        <p class="quote-name">Abegail Vicuña, Business Analyst</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>



      </div>
    </div>
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


  <!-- for the department -->
  <script>
    // Get the carousel instance
    let carousel = document.getElementById('carouselDepartment');

    // Listen for slide.bs.carousel event
    carousel.addEventListener('slide.bs.carousel', function(event) {
      // Get the current active item
      let activeItem = event.relatedTarget;

      // Remove any previously set background colors with a transition effect
      let departmentBox = document.getElementById('departmentBox');
      departmentBox.style.transition = 'background-color 0.5s ease-in-out';
      departmentBox.style.backgroundColor = '';

      // Set background color based on active item's id after a short delay
      setTimeout(() => {
        switch (activeItem.id) {
          case 'pm':
            departmentBox.style.backgroundColor = '#D9E0F0';
            break;
          case 'development':
            departmentBox.style.backgroundColor = '#ff87ab';
            break;
          case 'qa':
            departmentBox.style.backgroundColor = '#EECFCE';
            break;
          case 'ba':
            departmentBox.style.backgroundColor = '#c1ff9b';
            break;
          default:
            break;
        }
      }, 100); // Adjust delay as needed for smoother transition
    });
  </script>

  <!-- for the background color of the testimony -->
  <script>
    // Get the carousel instance
    let carouselQuoteText = document.getElementById('carouselQuoteText');

    // Listen for slide.bs.carousel event
    carouselQuoteText.addEventListener('slide.bs.carousel', function(event) {
      // Get the current active item
      let activeItem = event.relatedTarget;

      // Remove any previously set background colors with a transition effect
      let quoteBox = document.getElementById('quoteBox');
      quoteBox.style.transition = 'background-color 0.5s ease-in-out';
      quoteBox.style.backgroundColor = '';

      // Set background color based on active item's id after a short delay
      setTimeout(() => {
        switch (activeItem.id) {
          case 'pm':
            quoteBox.style.backgroundColor = '#D9E0F0';
            break;
          case 'development':
            quoteBox.style.backgroundColor = '#ff87ab';
            break;
          case 'qa':
            quoteBox.style.backgroundColor = '#EECFCE';
            break;
          case 'ba':
            quoteBox.style.backgroundColor = '#c1ff9b';
            break;
          default:
            break;
        }
      }, 100); // Adjust delay as needed for smoother transition
    });
  </script>

  <!-- for the quote pic -->
  <script>
    // Get the carousel instance
    let carouselPic = document.getElementById('carouselPic');

    // Listen for slide.bs.carousel event
    carouselPic.addEventListener('slide.bs.carousel', function(event) {
      // Get the current active item
      let activeItem = event.relatedTarget;

      // Remove any previously set border colors with a transition effect
      let departmentQuote = carouselPic.querySelectorAll('.department-quote');
      departmentQuote.forEach(item => {
        item.style.transition = 'border-color 0.5s ease-in-out';
        item.style.borderColor = ''; // Reset border color
      });

      // Set border color based on active item's id after a short delay
      setTimeout(() => {
        switch (activeItem.id) {
          case 'pm':
            departmentQuote[0].style.borderColor = '#D9E0F0'; // Index 0 for first item
            break;
          case 'development':
            departmentQuote[1].style.borderColor = '#ff87ab'; // Index 1 for second item
            break;
          case 'qa':
            departmentQuote[2].style.borderColor = '#EECFCE'; // Index 2 for third item
            break;
          case 'ba':
            departmentQuote[3].style.borderColor = '#c1ff9b'; // Index 3 for fourth item
            break;
          default:
            break;
        }
      }, 100); // Adjust delay as needed for smoother transition
    });
  </script>



  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let mainCarousel = document.getElementById('carouselMain');
      let carousels = ['carouselSmallPic', 'carouselBigPic', 'carouselDepartment', 'carouselPic', 'carouselQuoteText', 'carouselQuoteName'];

      mainCarousel.addEventListener('slide.bs.carousel', function(event) {
        let index = event.to;

        carousels.forEach(function(id) {
          let carousel = document.getElementById(id);
          let bsCarousel = bootstrap.Carousel.getInstance(carousel);
          bsCarousel.to(index);
        });
      });
    });
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