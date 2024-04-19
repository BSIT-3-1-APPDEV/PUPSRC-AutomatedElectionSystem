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

  if(isset($_SESSION['organization'])) {
    // Retrieve the organization name
    $org_name = $_SESSION['organization'];
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Feedback and Suggestions</title>
  <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
  
  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- Bootstrap 5 code -->
  <link type="text/css" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../src/styles/feedback-suggestions.css">
  <link rel="stylesheet" href="styles/core.css">
  <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_name . '.css'; ?>">

  <style>
       <?php echo ".bg-color { background-color: var(--$org_name); }"; ?>
  </style>
</head>

<body>
  
<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container">
    <div class="navbar-brand spacing" href="#">
      <img src="../src/images/resc/ivote-logo.png" alt="Logo" width="50px">
</div>
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
    <div class="col">
      <div class="p-4 title main-color text-center spacing">
        <h3><b>FEEDBACK & SUGGESTIONS</b></h3>
      </div>
    </div>
  </div>
</div>

<div class="container mt-4">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="reminder">
        <div class="text-position main-color text-center">
          <b>How was your experience?</b>
        </div>
        <div class="subtitle text-center pt-2">We would like your feedback to improve our website!</div> 
        <div class="row">
          <div class="text-position text-center emoji-size custom-radio pb-4">

            <input type="radio" id="emoji1" name="rating" value="Very Unsatisfied">
             <label for="emoji1" title="Very Unsatisfied">😠</label>
              
            <input type="radio" id="emoji2" name="rating" value="Unsatisfied">
             <label for="emoji2" title="Unsatisfied">😕</label>
            
            <input type="radio" id="emoji3" name="rating" value="Neutral">
             <label for="emoji3" title="Neutral">😐</label>

            <input type="radio" id="emoji4" name="rating" value="Satisfied">
             <label for="emoji4" title="Satisfied">😊</label>
            
            <input type="radio" id="emoji5" name="rating" value="Very Satisfied">
             <label for="emoji5" title="Very Satisfied">😀</label>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container mt-4">
  <div class="border-frame">
    <div class="row">
      <div class="col">
        <div class="text-position main-color">
          <b>Please leave more of your feedback below:</b>
        </div>
      </div>
    </div>
    <div class="row px-4 py-4">
      <div class="col">
        <textarea name="feedback" id="feedback" class="form-control " rows="10" placeholder="Type your feedback here..."></textarea>
      </div>
    </div>
  
    <div class="row pe-4 pb-4">
      <div class="col">
        <div class="text-center mt-3 text-lg-end">
          <button type="submit" onclick="submitForm()" class="button-submit bg-color">Submit Feedback</button>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="navbar navbar-expand-lg navbar-light bg-light mt-5">
    <div class="container-fluid">
      <span class="navbar-text mx-auto fw-bold spacing accent-3">BSIT 3-1 | ALL RIGHTS RESERVED 2024</span>
    </div>
  </footer>

</body>
  
<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../src/scripts/script.js"></script>


</html>
<?php
} else {
  header("Location: voter-login.php");
}
?>