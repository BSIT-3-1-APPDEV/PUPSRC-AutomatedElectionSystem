<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');


if (isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'student_voter')) 
{
   if(($_SESSION['vote_status'] != 'voted' )){

   

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // ------ END OF SESSION EXCHANGE

  $connection = DatabaseConnection::connect();
  // Assume $connection is your database connection

  // Query for selecting all columns in position
  $stmt_positions = $connection->prepare("SELECT * FROM position");
  $stmt_positions->execute();
  $result_positions = $stmt_positions->get_result();

  // Query for selecting all columns in candidate
  $stmt_candidates = $connection->prepare("SELECT * FROM candidate");
  $stmt_candidates->execute();
  $result_candidates = $stmt_candidates->get_result();

  $voter_id = $_SESSION['voter_id']; // Get voter id to update the vote status
  
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
  <!-- <link rel="stylesheet" href="styles/style.css" /> -->
  <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_acronym . '.css'; ?>">
  <!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  
  <style>.hover-color a:hover {color: var(--<?php echo "main-color"; ?>); } 
  input[type="radio"]:checked::before {background-color: var(--main-color);}
  input[type="radio"]:checked { border-color: var(--main-color); }
  .clicked {background-color: var(--main-color);color: white;}</style>

</head>

<body>

<?php 
      $personality = '';
      if ($org_acronym == 'acap'){
          $personality = "Psychmates";
      } else if ($org_acronym == 'aeces'){
          $personality = "AECES";
      } else if ($org_acronym == 'elite'){
          $personality = "Enthusiasts";       
      } else if ($org_acronym == 'give'){
          $personality = "Educators";      
      } else if ($org_acronym == 'jehra'){
          $personality = "JEHRA";    
      } else if ($org_acronym == 'jpia'){
          $personality = "JPIANs";   
      } else if ($org_acronym == 'piie'){
          $personality = "IEhinyero";  
      } else if ($org_acronym == 'jmap'){
          $personality = "Marketista"; 
      } else if ($org_acronym == 'sco'){
          $personality = "Iskolar"; 
      }
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container">
    <div class="navbar-brand spacing">
      <img src="../src/images/resc/ivote-logo.png" alt="Logo" width="50px">
   </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-none d-lg-block">
          <a class="nav-link main-color" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <b>Hello, <?php echo $personality ?></b><i class="fas fa-user-circle main-color ps-3" style="font-size: 25px;"></i> <i class="fas fa-chevron-down text-muted ps-2"></i>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="includes/voter-logout.php">Logout</a>
          </div>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link" href="includes/voter-logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main>

<div class="m-4">
  <div class="row">
    <div class="col-lg-12">
      <div class="p-4 title main-color text-center spacing" id="title">
        <!-- Toggle button for small screens -->
        <div class="m-0">
        <button id="toggleButton" type="button" class="title main-color spacing border-0 d-md-none d-lg-none" data-toggle="modal" data-target="#guidelinesModal" style="white-space: nowrap;">
          <span class="d-md-inline d-lg-inline">BALLOT FORM</span>
      </button>
        </div>
       <!-- Text for medium and large screens -->
      <span class="d-none d-md-inline d-lg-inline">BALLOT FORM</span>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="guidelinesModal" tabindex="-1" role="dialog" aria-labelledby="guidelinesModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content" style="margin: 0;">
            <div class="modal-body" style="padding: 0;">
                <div class="title-2 main-bg-color">
                    Voting Guidelines
                </div>
                <div class="pt-4"></div>
                <div class="ps-4 pe-4 pb-2">
                    Select only one (1) candidate each position.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Do not leave an empty selection.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Vote buying and intimidation are prohibited.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Displaying your ballot or discussing your vote to another person's votes is prohibited.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Only registered voters are permitted to vote.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    After selecting one (1) candidate each position, click the Submit Vote button to successfully cast your vote.
                </div>
                <br>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal -->
    </div>
  </div>
</div>


<!-- Modal for Introductory Greetings -->
<div class="modal fade adjust-modal" id="greetModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pt-2 pb-2 ps-3 pe-3">
      <div class="modal-body pt-3">
        <div class="d-flex justify-content-end"> 
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <b><div class="greetings-blue">Hello </div><div class="greetings-red"><?php echo $personality ?>!</div></b>
        <p class="pt-3">Voting just got even better at the Polytechnic University of the Philippines – Santa Rosa Campus, all thanks to technology! 
        With iVOTE Automated Election System (AES), you can now cast your vote electronically. 
        Make sure to carefully review the voting guidelines for an enhanced experience. 
        Take a moment to consider the duties and responsibilities of each electoral position when selecting your new leaders. 
        Rest assured, we've taken extensive measures to ensure the security and confidentiality of your votes. 
        We’re eager to hear your thoughts about the system as well. Thanks for showing up, and remember, 
        the election outcome is determined by your vote!</p>
      </div>
    </div>
  </div>
</div>


<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="text-center pb-4">
          <div class="main-color" style="padding-top: 6%;">
            <h4><b>CANDIDATE PREVIEW</b></h4>
          </div>
          Kindly review and confirm selections.
        </div>
        <div id="selectedCandidate"></div> <!-- Display selected candidate here -->
      </div>
      <div class="text-center" style="padding-bottom: 6%;">
        <button type="button" class="btn btn-gray pt-2 pb-2 px-4" id="cancelModalButton" style="margin-right: 12px;"  data-bs-dismiss="modal" aria-label="Close"><b>Cancel</b></button>
        <button type="submit" class="btn btn-success pt-2 pb-2 px-4" id="submitModalButton"><b>Submit Vote</b></button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Vote Submitted -->
<div class="modal fade adjust-submit-modal" id="voteSubmittedModal" tabindex="-1" aria-labelledby="voteSubmittedModalLabel" aria-hidden="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pb-4">
      <div class="modal-body text-center pb-2">
        <img src="../src/images/resc/check-animation.gif" width="300px">
        <h4 class="pb-4"><b>Vote Submitted!</b></h4>
        <button class="button-check main-bg-color text-white py-2 px-4" onclick="window.location.href='../src/feedback-suggestions.php';">
          <b>Give Feedback</b>
        </button>
      </div>
    </div>
  </div>
</div>


<div class="m-4">
  <div class="row">
    <div class="col-lg-3 col-md-2 d-none d-md-block">
       <div class="reminder">
          <div class="title-2 main-bg-color">
              Voting Guidelines
          </div>
        <div>
          <div class="font-weight1">
            <div class="pt-4"></div>
                <div class="ps-4 pe-4 pb-2">
                    Select only one (1) candidate each position.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Do not leave an empty selection.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Vote buying and intimidation are prohibited.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Displaying your ballot or discussing your vote to another person's votes is prohibited.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    Only registered voters are permitted to vote.
                </div>
                <hr>
                <div class="ps-4 pe-4 pb-2">
                    After selecting one (1) candidate each position, click the Submit Vote button to successfully cast your vote.
                </div>
                <br>
           </div>
        </div>
    </div>
</div>


     <!-- Voting Section -->

    <div class="col-lg-9 col-md-10">

       <!-------------------------- Student Details ----------------------->

     <!--<div class ="reminder">
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
              <input type="text" name="voter_name" id="voter_name" class="form-control" placeholder="Dela Cruz, Juan">
            </div>
          </div>
          <div class="col-lg-5 col-sm-10">
            <div class=" main-color pt-lg-4 ps-4 ps-sm-5">
              Student Number
            </div>
            <div class="pt-2"></div>
            <div class="ps-4 ps-sm-5 pb-5">
             <input type="text" name="student_num" id="student_num" class="form-control" placeholder="2000-00123-SR-0">
            </div>
          </div>
        </div>
      </div> <div class="pb-4"></div> -->

  <form id="voteForm" method="post" action="../src/includes/insert-vote.php">
    <?php if ($result_positions->num_rows == 0 || $result_candidates->num_rows == 0): ?>
        <div class="reminder">
            <div class="main-color py-4 px-4">
                <b>No entered positions and candidates</b>
            </div>
        </div>
    <?php else: ?>
        <?php $modal_counter = 0; ?>
        <?php while ($row = $result_positions->fetch_assoc()): ?>
            <?php
            $modal_id = 'duties-modal-' . $modal_counter;
            $modal_counter++;
            ?>
            <?php
            // Fetch candidates matching the position_id
            $result_candidates->data_seek(0);
            $candidate_count = 0;
            $hasCandidates = false;
            ?>
            <?php while ($row_candidates = $result_candidates->fetch_assoc()): ?>
                <?php if ($row_candidates['position_id'] == $row['position_id']): ?>
                    <?php
                    $hasCandidates = true;
                    $candidate_count++;
                    ?>
                    <div class="reminder mb-4" data-position-title="<?php echo htmlspecialchars($row['title']); ?>">
                        <div class="text-position main-color ps-5">
                            <b><?php echo strtoupper($row['title']) ?></b>
                        </div>
                        <div class="hover-color ps-5 pb-4">
                            <a href="#<?php echo $modal_id ?>" data-toggle="modal">Duties and Responsibilities</a>
                        </div>
               <!-- Modal for Duties and Responsibilities -->
               <div class="modal fade adjust-modal" id="<?php echo $modal_id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header main-bg-color text-white d-flex justify-content-between align-items-center" style="border-top-right-radius: 18px; border-top-left-radius:18px">
                                <h4 class="modal-title mb-0"><b><?php echo strtoupper($row['title']) ?></b></h4>
                                <button type="button" class="btn-close me-2"  data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <?php $lines = explode("\n", $row['description']);
                                  $count = count($lines); ?>
                            <div class="modal-body">
                              <div class="main-color pt-4 pb-3"><b>DUTIES AND RESPONSIBILITIES</b></div>
                                <ul>
                                <?php foreach ($lines as $key => $line): ?>
                                    <?php if ($key === $count - 1): ?>
                                        <li><?php echo htmlspecialchars($line); ?></li>
                                    <?php else: ?>
                                        <li class="pb-2"><?php echo htmlspecialchars($line); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                               </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fetch candidates matching the position_id -->
                <?php $result_candidates->data_seek(0); ?>
                <?php $candidate_count = 0; ?>
                <div class="row">
                    <?php while ($row_candidates = $result_candidates->fetch_assoc()): ?>
                        <?php if ($row_candidates['position_id'] == $row['position_id']): ?>
                            <?php
                            $full_name = $row_candidates['last_name'] . ", " . $row_candidates['first_name'];
                            ?>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="px-5">
                                    <div class="candidate-info pb-4">
                                    <label for="<?php echo $row_candidates['candidate_id'] ?>">
                                        <img src="images/candidate-profile/<?php echo $row_candidates['photo_url'] ?>" alt="Candidate Image" width="100px" height="100px">
                                    </label>
                                        <div>
                                            <input type="hidden" name="position_id[<?php echo $row['position_id'] ?>][]" value="<?php echo $row['position_id'] ?>">
                                            <input type="hidden" name="candidate_id[<?php echo $row_candidates['candidate_id'] ?>][]" value="<?php echo $row_candidates['candidate_id'] ?>">
                                            <div style="display: flex; align-items: center;" class="ps-3">
                                                <input type="radio" id="<?php echo $row_candidates['candidate_id'] ?>" name="position[<?php echo $row['position_id'] ?>]" value="<?php echo $row_candidates['candidate_id'] ?>">
                                                <label for="<?php echo $row_candidates['candidate_id'] ?>" style="display: flex; flex-direction: column; align-items: left; font-size: 15px">
                                                    <div class="ps-4">
                                                        <div class="font-weight2"> <?php echo $full_name ?> </div>
                                                        <div class="font-weight3 undisplay main-color"><?php echo $row_candidates['program'] ?> <?php echo $row_candidates['year_level'] ?>-<?php echo $row_candidates['section']?></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $candidate_count++; ?>
                            <?php if ($candidate_count % 2 == 0): ?>
                                </div><!-- Close current row -->
                                <div class="row"><!-- Start new row -->
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div><!-- Close row -->
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <hr>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center pt-2 pb-4">
                        <div class="text-muted">
                            <input type="radio" id="abstain_<?php echo $row['position_id'] ?>" name="position[<?php echo $row['position_id'] ?>]" value="" style="vertical-align: middle;">
                            <label for="abstain_<?php echo $row['position_id'] ?>" style="vertical-align: middle;"><b>&nbsp;&nbsp;ABSTAIN</b></label><br>
                        </div>
                    </div>
                </div>
            </div><!-- Close reminder -->
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endwhile; ?>
    <?php endif; ?>
    <!-- Voter ID Input -->
    <input type="hidden" name="voter_id" value="<?php echo $voter_id ?>">
    <!-- Submit and Reset Buttons -->
    <?php if ($result_positions->num_rows > 0 && $result_candidates->num_rows > 0): ?>
        <div class="text-center pb-4 mt-3">
            <button type="button" class="button-reset" onclick="resetForm()"><u>Reset Form</u></button>
            <button type="submit" class="button-submit main-bg-color" id="submitVoteBtn" onclick="validateForm()">
                Submit Vote
            </button>
        </div>
    <?php endif; ?>
</form>


</main>
</body>

<?php include_once __DIR__ . '/includes/components/footer.php'; ?>


<?php
// PHP code to fetch $row_candidates['photo_url']
$imageSrc = 'images/candidate-profile/' . $row_candidates['photo_url'];
?>

  <script src="../src/scripts/feather.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src ="../src/scripts/ballot-forms.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</html>
<?php
  } else {
    header("Location: end-point.php");
  }
} else {
  header("Location: landing-page.php");
}
?>