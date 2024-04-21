<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';
require_once 'includes/classes/session-manager.php';


if(isset($_SESSION['voter_id'])) {

  $connection = DatabaseConnection::connect();
  // Assume $connection is your database connection
  $voter_id = $_SESSION['voter_id'];
  
  // Prepare and execute a query to fetch the first name of the user
  $stmt = $connection->prepare("SELECT first_name FROM voter WHERE voter_id = ?");
  $stmt->bind_param('i', $voter_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $row = $result->fetch_assoc();
      
  // Retrieve the first name from the fetched row
  $first_name = $row['first_name'];

  $stmt->close();

  
  $org_name = $_SESSION['organization'];
  

?>

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
  <link rel="stylesheet" href="../src/styles/ballot-forms.css">
  <link rel="stylesheet" href="styles/core.css">
  <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_name . '.css'; ?>">
  <!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

  <style>
       <?php echo ".bg-color { background-color: var(--$org_name); }"; ?>
  </style>

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
          <a class="nav-link dropdown-toggle main-color" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             Hello,<?php echo ' ' . $first_name; ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
             <a class="dropdown-item" href="voter-logout.php">Logout</a>
          </div>
        </li>
        <li class="nav-item d-lg-none">
           <a class="nav-link" href="voter-logout.php">Logout</a>
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
      <div class="p-4 title main-color text-center fw-bolder spacing">
        <h3><b>BALLOT FORM</b></h3>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Voting Guidelines -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="reminder">
          <div class="title-2 bg-color">
            <b><center>Voting Guidelines</center></b>  
             <!-- Close button -->
             <!-- <div class="text-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            -->
          </div>
          <br>
            <div class="container">
              Select only one (1) candidate each position.
              <hr>
            </div>
            <div class="container">
              Do not leave an empty selection.
              <hr>
            </div>
            <div class="container">
              Vote buying and intimidation are prohibited.
              <hr>
            </div>
            <div class="container">
              Displaying your ballot or discussing your vote to another person's votes is prohibited.
              <hr>
            </div>
            <div class="container">
              Only registered voters are permitted to vote.
              <hr>
            </div>
            <div class="container">
              After selecting one (1) candidate each position, click the Submit Vote button to successfully cast your vote.
              <hr>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container mt-4">
  <div class="row">
  <div class="col-lg-3 col-md-2 d-none d-md-block">
    <div class="reminder">
        <div class="title-2 bg-color">
            <b>Voting Guidelines</b>
        </div>

        <div class="container p-xl-4">
            <div class="container">
                Select only one (1) candidate each position.
                <hr>
            </div>
            <div class="container">
                Do not leave an empty selection.
                <hr>
            </div>
            <div class="container">
                Vote buying and intimidation are prohibited.
                <hr>
            </div>
            <div class="container">
                Displaying your ballot or discussing your vote to another person's votes is prohibited.
                <hr>
            </div>
            <div class="container">
                Only registered voters are permitted to vote.
                <hr>
            </div>
            <div class="container">
                After selecting one (1) candidate each position, click the Submit Vote button to successfully cast your vote.
                <hr>
            </div>
        </div>
    </div>
</div>
     <!-- Voting Section -->

    <div class="col-lg-9 col-md-10">
      <div class ="reminder">
        <div class="main-color ps-4 pt-4 spacing">
          <b>STUDENT INFORMATION</b>
        </div>
        <div class="row">
          <div class="col-lg-6 col-sm-10">
            <div class=" main-color pt-4 ps-5">
              Full Name
            </div>
            <div class="pt-2"></div>
            <div class="ps-5 pb-5">
              <input type="text" class="form-control" placeholder="Dela Cruz, Juan">
            </div>
          </div>
          <div class="col-lg-5 col-sm-10">
            <div class=" main-color pt-lg-4 ps-4 ps-sm-5">
              Student Number
            </div>
            <div class="pt-2"></div>
            <div class="ps-4 ps-sm-5 pb-5">
             <input type="text" class="form-control" placeholder="2000-00123-SR-0">
            </div>
          </div>
        </div>
      </div>

      <!-- Candidate Section -->
      <div class="mb-4"></div>
      <div class="reminder">
        <div class="text-position main-color">
          <b>President</b>
        </div>
        <div class="subtitle">Select Candidate</div>
        <div class="row">
          <div class="col-lg-6 col-md-12 col-sm-12 p-xl-4">
            <label>
              <div class="candidate-info ps-4">
                <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
              <div>
                  <input type="radio" name="president-candidate" >
                  Candidate Name<br>
                  <div class="main-color subtitle-2"><b>Section</b> </div>
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
                  <div class="main-color subtitle-2"><b>Section</b> </div>
                </div>
              </div>
              <div class="pb-sm-4"></div>
            </label>
          </div>
        </div>
      </div>

      <div class="mb-4"></div>
      <div class="reminder">
        <div class="text-position main-color">
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
                  <div class="main-color subtitle-2"><b>Section</b> </div>
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
                  <div class="main-color subtitle-2"><b>Section</b> </div>
                </div>
              </div>
              <div class="pb-sm-4"></div>
            </label>
          </div>
        </div>
      </div>

      <div class="mb-4"></div>
      <div class="reminder">
        <div class="text-position main-color">
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
                  <div class="main-color subtitle-2"><b>Section</b> </div>
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
                  <div class="main-color subtitle-2"><b>Section</b> </div>
                </div>
              </div>
              <div class="pb-sm-4"></div>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-3">
  <button type="button" class="button-reset" onclick="resetForm()"><u>Reset Form</u></button>
  <button type="button" class="button-submit bg-color">
    <!-- Temporary only -->
    <a href="../src/feedback-suggestions.php"> Submit Vote </a>
  </button>
</div>
</div>
</body>

  
<footer class="navbar navbar-expand-lg navbar-light bg-light mt-5">
    <div class="container-fluid">
      <span class="navbar-text mx-auto fw-bold spacing main-color">BSIT 3-1 | ALL RIGHTS RESERVED 2024</span>
    </div>
  </footer>

  <script src="../src/scripts/feather.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../src/scripts/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
  function resetForm() {
    document.querySelectorAll('input[type="radio"]').forEach((radio) => {
      radio.checked = false;
    });
    document.querySelectorAll('input[type="text"]').forEach((textInput) => {
      textInput.value = '';
    });
  }
</script>

<script>
  document.getElementById('toggleBtn').addEventListener('click', function() {
    var myModal = new bootstrap.Modal(document.getElementById('infoModal'));
    myModal.show();
  });
</script>
  
</html>
<?php
} else {
  header("Location: voter-login.php");
}
?>