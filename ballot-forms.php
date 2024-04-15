<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ballot Form</title>
  
  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- Bootstrap 5 code -->
  <link type="text/css" href="vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="src/styles/orgs/give.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark main-color">
  <div class="container edit_navbar">
    <a class="navbar-brand spacing" href="#">
      <img src="src/images/logos/give.png" alt="Logo" width="35" height="35">
      <b>GIVE</b> Election Portal
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar dropdown-toggle"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Hello User
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Profile</a>
            <a class="dropdown-item" href="#">Settings</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="row">
    <div class="col">
      <div class="p-4 title text-center fw-bolder">
        <h3><b>BALLOT FORM</b></h3>
  
      </div>
    </div>
  </div>
</div>

<div class="container mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-2">
      <div class="reminder">
        <div class="title-2">
        <b>Voting Guidelines</b>
        </div>
      </div>
    </div>
  </div>
</div>



</body>
  
  
</html>