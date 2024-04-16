<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ballot Form</title>
  <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
  
  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- Bootstrap 5 code -->
  <link type="text/css" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../src/styles/orgs/give.css">
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
      <span class="navbar dropdown-toggle"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-none d-lg-block">
          <a class="nav-link dropdown-toggle accent-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Hello, User
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Logout</a>
          </div>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link" href="#">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="row">
    <div class="col text-end d-md-none">
      <div class="toggle-btn">
        <button type="button" id="toggleBtn"><span data-feather="info" class="accent-3 me-xl-3 mb-xl-1"></span></button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="p-4 title text-center fw-bolder spacing">
        <h3><b>BALLOT FORM</b></h3>
      </div>
    </div>
  </div>
</div>

<div class="container mt-4">
  <div class="row">
  <div class="col-lg-3 col-md-2 d-none d-md-block">
    <div class="reminder">
        <div class="title-2">
            <b>Voting Guidelines</b>
        </div>

        <div class="container p-xl-4">
            <div class="container p-xl-2">
                Select only one (1) candidate each position.
                <hr>
            </div>
            <div class="container p-xl-2">
                Do not leave an empty selection.
                <hr>
            </div>
            <div class="container p-xl-2">
                Vote buying and intimidation are prohibited.
                <hr>
            </div>
            <div class="container p-xl-2">
                Displaying your ballot or discussing your vote to another person's votes is prohibited.
                <hr>
            </div>
            <div class="container p-xl-2">
                Only registered voters are permitted to vote.
                <hr>
            </div>
            <div class="container p-xl-2">
                After selecting one (1) candidate each position, click the Submit Vote button to successfully cast your vote.
                <hr>
            </div>
        </div>
    </div>
</div>
     <!-- Candidate Section -->

    <div class="col-lg-9 col-md-10">
      <div class="reminder">
        <div class="text-position">
          <b>President</b>
        </div>
        <div class="subtitle">Select Candidate</div>
        <div class="row">
          <div class="col-lg-6 col-md-12 col-sm-12 p-xl-4">
            <label>
              <div class="candidate-info ps-4">
                <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
              <div>
                  <input type="radio" name="president-candidate">
                  Candidate Name<br>
                  Section
                </div>
              </div>
            </label>
          </div>
          <div class="col-lg-6 col-md-12 col-sm-12 p-xl-4">
            <label>
              <div class="candidate-info ps-4">
                <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
                <div>
                  <input type="radio" name="president-candidate">
                  Candidate Name<br>
                  Section
                </div>
              </div>
            </label>
          </div>
        </div>
      </div>

      <div class="mb-4"></div>
      <div class="reminder">
        <div class="text-position">
          <b>Vice President</b>
        </div>
        <div class="subtitle">Select Candidate</div>
        <div class="row">
          <div class="col-lg-6 col-md-12 col-sm-12 p-xl-4">
            <label>
              <div class="candidate-info ps-4">
                <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
                <div>
                  <input type="radio" name="vice-president-candidate">
                  Candidate Name<br>
                  Section
                </div>
              </div>
            </label>
          </div>
          <div class="col-lg-6 col-md-12 col-sm-12 p-xl-4">
            <label>
              <div class="candidate-info ps-4">
                <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
                <div>
                  <input type="radio" name="vice-president-candidate">
                  Candidate Name<br>
                  Section
                </div>
              </div>
            </label>
          </div>
        </div>
      </div>

      <div class="mb-4"></div>
      <div class="reminder">
        <div class="text-position">
          <b>Secretary</b>
        </div>
        <div class="subtitle">Select Candidate</div>
        <div class="row">
          <div class="col-lg-6 col-md-12 col-sm-12 p-xl-4">
            <label>
              <div class="candidate-info ps-4">
                <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
                <div>
                  <input type="radio" name="secretary-candidate">
                  Candidate Name<br>
                  Section
                </div>
              </div>
            </label>
          </div>
          <div class="col-lg-6 col-md-12 col-sm-12 p-xl-4">
            <label>
              <div class="candidate-info ps-4">
                <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
                <div>
                  <input type="radio" name="secretary-candidate">
                  Candidate Name<br>
                  Section
                </div>
              </div>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-3">
  <button type="button" class="btn button-reset"><u>Reset Form</u></button>
  <button type="button" class="btn button-submit">Submit Vote</button>
</div>
</div>
</body>
  
<footer class="navbar navbar-expand-lg navbar-light bg-light mt-5">
    <div class="container-fluid">
      <span class="navbar-text mx-auto fw-bold spacing accent-3">BSIT 3-1 | ALL RIGHTS RESERVED 2024</span>
    </div>
  </footer>

  <script src="../src/scripts/feather.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../src/scripts/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
</html>