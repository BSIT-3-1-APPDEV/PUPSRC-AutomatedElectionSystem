<?php
session_start(); // Start the session if not already started
$_SESSION['organization'] = 'jehra';
// Include the necessary files
require_once 'includes/classes/db-config.php';
require_once 'includes/classes/db-connector.php';

// Establish the database connection
$conn = DatabaseConnection::connect();

// Now you can use $connection to execute database queries
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
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <!-- Bootstrap 5 code -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/admin_dashboard.css">
  <link rel="stylesheet" href="styles/style.css">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <nav class="sidebar">
    <img src="images/logos/jpia.png" alt="" class="org-logo">
    <h6>JUNIOR PHILIPPINE INSTITUTE OF ACCOUNTANTS</h6>
    <div class="menu-content">
      <ul class="menu-items">
        <li class="menu-title">Your menu title</li>
        <li class="item"><a href="#">Your first link</a></li>
        <li class="item">
          <div class="submenu-item" data-bs-toggle="collapse" href="#firstSubmenu">
            <span>First submenu</span>
            <i class="fas fa-chevron-right"></i>
          </div>
          <ul class="menu-items submenu collapse" id="firstSubmenu">
            <li class="menu-title">
              <i class="fas fa-chevron-left"></i>
              Your submenu title
            </li>
            <li class="item"><a href="#">First sublink</a></li>
            <!-- Add more sublinks as needed -->
          </ul>
        </li>
        <li class="item"><a href="#">Your second link</a></li>
        <li class="item"><a href="#">Your third link</a></li>
      </ul>
    </div>
  </nav>
  <nav class="navbar">
    <div class="container-fluid">
      <i class="fas fa-bars" id="sidebar-close"></i>
      <span class="navbar-text mx-auto fw-bold">ELECTION COMMITTEE PORTAL</span>
    </div>
  </nav>
  <main class="main">
    
    <div class="container mt-5">
      <div class="row justify-content-center mt-5">
  
        <div class="col-lg-4 ml-5 p-4"> 
          <a href="#" class="card admin-card admin-link">
            <div class="card-body d-flex align-items-center justify-content-center">
            <div class="icon-container">
            <i data-feather="bar-chart-2" class ="margin-add"></i>
</div>

              REPORTS
            </div>
          </a>
        </div>
        <div class="col-lg-4 p-4"> 
          <a href="#" class="card admin-card admin-link">
            <div class="card-body d-flex align-items-center justify-content-center">
            <div class="icon-container">
            <i data-feather="users" class ="margin-add"></i>
</div>
              MANAGE VOTERS
            </div>
          </a>
        </div>
        <div class="col-lg-4 p-4"> 
          <a href="#" class="card admin-card admin-link">
            <div class="card-body d-flex align-items-center justify-content-center">
              <div class="icon-container">
            <i data-feather="archive" class ="margin-add"></i>
</div>
              ARCHIVE
            </div>
          </a>
        </div>
      </div>

      <div class="row justify-content-center ">
        <div class="col-md-12 position-container ">    
          <h4 class="main-text">LIVE RESULTS</h4>
          <select id="positions" class="positions-dropdown mb-4 mt-2">
    <!-- Dropdown options will be dynamically populated here -->
</select>

        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-12 ">
          <div class="card ">
            <canvas id="myChart"></canvas>
          </div>
        </div>
      </div>
      <div class="row justify-content-center mb-5">
      <h4 class="main-text mt-3">PER YEAR METRICS</h4>
  <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
    <div class="card p-3">
      <h1 class="pink fw-bold ">52</h1>
      <h6 class="font-size-change fw-bold gray">1st Year</h6>
    </div>
  </div>
  <div class="col-md-1  d-none align-self-center d-lg-block"> <!-- Add d-lg-block -->
  <div class="line"></div>
</div>

  <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
    <div class="card p-3">
      <h1 class="pink fw-bold ">52</h1>
      <h6 class="font-size-change fw-bold gray">2nd Year</h6>
    </div>
  </div>
  <div class="col-md-1 d-none align-self-center d-lg-block"> <!-- Add d-lg-block -->
  <div class="line"></div>
</div>

  <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
    <div class="card p-3">
      <h1 class="pink fw-bold ">52</h1>
      <h6 class="font-size-change fw-bold gray">3rd Year</h6>
    </div>
  </div>
  <div class="col-md-1  d-none align-self-center d-lg-block"> <!-- Add d-lg-block -->
  <div class="line"></div>
</div>

  <div class="col-lg-2 col-md-6 col-xs-6 col-s-6 text-center mt-2">
    <div class="card p-3">
      <h1 class="pink fw-bold ">52</h1>
      <h6 class="font-size-change fw-bold gray">4th Year</h6>
    </div>
  </div>
</div>

</div>
    </div>
    
  </main>
  <footer class="navbar navbar-expand-lg navbar-light bg-light fixed-bottom">
    <div class="container-fluid">
      <span class="navbar-text mx-auto fw-bold">© BSIT 3-1 | All Rights Reserved</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="scripts/script.js"></script>
  <script src="scripts/admin_dashboard.js"></script>
  <script>
      feather.replace();
    </script>
</body>
</html>
