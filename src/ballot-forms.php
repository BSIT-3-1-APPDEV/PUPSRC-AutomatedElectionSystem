<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';

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



          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <a class="nav-link dropdown-toggle accent-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Hello, User
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">

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
<?php
} else {
  header("Location: voter-login.php");
}
?>
