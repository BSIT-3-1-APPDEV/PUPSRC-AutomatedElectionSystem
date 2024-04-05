<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sidebar Menu for Admin Dashboard | CodingNepal</title>
  
  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- Bootstrap 5 code -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


  <link rel="stylesheet" href="../../src/styles/style.css" /> 
  <!-- REPLACE WITH: <link rel="stylesheet" href="src/styles/style.css" /> -->
</head>

<body>
  <nav class="sidebar">

    <img src="../../src/images/logos/jpia.png" alt="" class="org-logo">
    <!-- REPLACE WITH: <img src="src/images/logos/jpia.png" alt="" class="org-logo"> -->
    
    <h6>JUNIOR PHILIPPINE INSTITUTE OF ACCOUNTANTS</h6>

    <div class="menu-content">
      <ul class="menu-items">
        <div class="menu-title">Your menu title</div>

        <li class="item">
          <a href="#">Your first link</a>
        </li>

        <li class="item">
          <div class="submenu-item" data-bs-toggle="collapse" href="#firstSubmenu">
            <span>First submenu</span>
            <i class="fas fa-chevron-right"></i>
          </div>

          <ul class="menu-items submenu collapse" id="firstSubmenu">
            <div class="menu-title">
              <i class="fas fa-chevron-left"></i>
              Your submenu title
            </div>
            <li class="item">
              <a href="#">First sublink</a>
            </li>
            <!-- Add more sublinks as needed -->
          </ul>
        </li>

        <li class="item">
          <a href="#">Your second link</a>
        </li>

        <li class="item">
          <a href="#">Your third link</a>
        </li>
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
    <h1>Admin Dashboard Content</h1>
  </main>


  <footer class="navbar navbar-expand-lg navbar-light bg-light fixed-bottom">
    <div class="container-fluid">
      <span class="navbar-text mx-auto fw-bold">© BSIT 3-1 | All Rights Reserved</span>
    </div>
  </footer>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="../../src/scripts/script.js"></script>
  <!-- REPLACE WITH: <script src="src/scripts/script.js"></script> -->
</body>
</html>