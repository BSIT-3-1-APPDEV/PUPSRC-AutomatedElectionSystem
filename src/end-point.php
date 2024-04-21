<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';
require_once 'includes/classes/session-manager.php';

//SessionManager::checkUserRoleAndRedirect();

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
  <title>Thank You!</title>
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

<div class="container">
    <div class="row justify-content-md-center align-items-center">
        <div class="col-lg-6 col-sm-12">
            <div class="end-point text-center">
                <?php echo '<img src="../src/images/resc/end-point/'. $org_name .'-endpoint.png" alt="Endpoint Image" class="img-fluid">';?>
            </div> 
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="reminder p-4">
                <div class="header main-color text-center pb-2">
                    <b>Your ballot is securely cast!</b>
                </div>
                <div class="header-sub text-center">
                    Stay tuned for the upcoming announcement of the newly appointed committee members on 
                    <?php echo strtoupper($org_name); ?>'s
                      <?php if ($org_name == 'acap'){
                        echo '<a href="https://www.facebook.com/ACAPpage">';
                      } else if ($org_name == 'aeces'){
                        echo '<a href="https://www.facebook.com/OfficialAECES">';
                      } else if ($org_name == 'elite'){
                        echo '<a href="https://www.facebook.com/ELITE.PUPSRC">';
                      } else if ($org_name == 'give'){
                        echo '<a href="https://www.facebook.com/educgive">';
                      } else if ($org_name == 'jehra'){
                        echo '<a href="https://www.facebook.com/PUPSRCJEHRA">';
                      } else if ($org_name == 'jpia'){
                        echo '<a href="https://www.facebook.com/JPIA.PUPSRC">';
                      } else if ($org_name == 'piie'){
                        echo '<a href="https://www.facebook.com/piiepup">';
                      } else if ($org_name == 'jmap'){
                        echo '<a href="https://www.facebook.com/JMAPPUPSRCOfficial">';
                      }else if ($org_name == 'sco'){
                        echo '<a href="https://www.facebook.com/thepupsrcstudentcouncil">';
                      }
                      ?>
                      Facebook</a> page. We sincerely appreciate
                    your participation, Isko't-Iska!
                </div>
            </div>
        </div> 
    </div>
</div>

</body>

  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../src/scripts/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<?php
} else {
  header("Location: voter-login.php");
}
?>