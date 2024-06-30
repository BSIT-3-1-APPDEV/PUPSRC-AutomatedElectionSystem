<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/classes/csrf-token.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
include_once FileUtils::normalizeFilePath('includes/default-time-zone.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');


if (isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'student_voter')) 
{
   if(($_SESSION['vote_status'] == NULL)){

    // ------ SESSION EXCHANGE
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // ------ END OF SESSION EXCHANGE

  $connection = DatabaseConnection::connect();
  // Assume $connection is your database connection

  $csrf_token = CsrfToken::generateCSRFToken();

  // Query for selecting all columns in position
  $stmt_positions = $connection->prepare("SELECT * FROM position ORDER BY sequence ASC");
  $stmt_positions->execute();
  $result_positions = $stmt_positions->get_result();

  // Query for selecting all columns in candidate
  $stmt_candidates = $connection->prepare("SELECT * FROM candidate WHERE candidacy_status = 'verified'");
  $stmt_candidates->execute();
  $result_candidates = $stmt_candidates->get_result();

  $voter_id = $_SESSION['voter_id']; // Get voter id to update the vote status
  $vote_status = $_SESSION['vote_status']; // Get voter id to update the vote status
  
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
  <link rel="stylesheet" href="styles/ballot-forms.css">
  <link rel="stylesheet" href="styles/loader.css" />
  <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_acronym . '.css'; ?>">
  <!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  
  <style>.hover-color a:hover {color: var(--<?php echo "main-color"; ?>); } 
  input[type="radio"]:checked::before {background-color: var(--main-color);}
  input[type="radio"]:checked { border-color: var(--main-color); }
  input[type="checkbox"]:checked::before {background-color: var(--main-color);}
  input[type="checkbox"]:checked { border-color: var(--main-color); }
  .clicked {background-color: var(--main-color);color: white;}
  .nav-link:hover, .nav-link:focus {color: var(--<?php echo "main-color"; ?>); }
  .navbar-nav .nav-item.dropdown.show .nav-link.main-color {color: var(--main-color);}
  .navbar-nav .nav-item.dropdown .nav-link.main-color,.navbar-nav .nav-item.dropdown .nav-link.main-color:hover,
  .navbar-nav .nav-item.dropdown .nav-link.main-color:focus {color: var(--main-color);}
  input[type="text"]:focus {border-color: var(--main-color);}
  </style>

</head>

<body>

<?php
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html'); 
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/topnavbar.php'); 
?>

<main>

<div class="my-4">
  <div class="row">
    <div class="col-lg-12">
      <div class="p-3 p-lg-3 p-md-3 px-sm-4 py-sm-3 title main-color text-center spacing" id="title">
        <!-- Toggle button for small screens -->
        <div class="m-0">
          <button id="toggleButton" type="button" class="title main-color spacing border-0 d-md-none d-lg-none" data-toggle="modal" data-target="#guidelinesModal" style="white-space: nowrap;">
            <span class="d-md-inline d-lg-inline">BALLOT FORM</span>
          </button>
        </div>
       <!-- Text for medium and large screens -->
      <span class="d-none d-md-inline d-lg-inline">BALLOT FORM</span>
    </div>
    </div>
  </div>
</div>


    
<!-- Modal -->
<div class="modal fade" id="guidelinesModal" tabindex="-1" role="dialog" aria-labelledby="guidelinesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="margin: 0; font-size: 14px">
            <div class="modal-body" style="padding: 0;">
                <div class="title-2 main-bg-color">
                    Voting Guidelines
                </div>
                <div class="pt-4"></div>
                <div class="ps-4 pe-4 pb-2">
                    Select your preferred candidate(s) for each position.
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
                    After selecting candidate(s) each position, click the <span class="main-color"><b>Submit Vote</b></span> button to successfully cast your vote.
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->



<!-- Modal for Introductory Greetings -->
<div class="modal fade adjust-modal" id="greetModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pt-2 pb-2 ps-3 pe-3" style="border-radius: 20px;">
      <div class="modal-body pt-3">
        <div class="d-flex justify-content-end"> 
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <b><div class="greetings-blue">Hello </div><div class="greetings-red"><?php echo $org_personality ?>!</div></b>
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
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true" 
      data-backdrop="static" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-body">
        <div class="text-center pb-4">
          <div class="main-color pt-lg-5 pt-md-3 pt-3">
            <h4><b>BALLOT PREVIEW</b></h4>
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
<div class="modal fade adjust-submit-modal" id="voteSubmittedModal" tabindex="-1" aria-labelledby="voteSubmittedModalLabel" 
    aria-hidden="false" data-backdrop="static" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pb-4" style="border-radius: 20px;">
      <div class="modal-body text-center pb-2">
        <img src="../src/images/resc/check-animation.gif" width="300px">
        <h4 class="pb-4"><b>Vote Submitted!</b></h4>
        <button class="button-check main-bg-color text-white py-2 px-4" id="giveFeedbackbtn">
          <a class="custom-link" href="../src/feedback-suggestions"><b>Give Feedback</b></a>
        </button>
      </div>
    </div>
  </div>
</div>

    <div class="row">
        <div class="col-lg-3 col-0 pb-sm-4 pb-1 d-none d-md-block">
            <div class="reminder">
                <div class="title-2 main-bg-color">
                    Voting Guidelines
                </div>
                <div>
                    <div class="font-weight1" style="font-size: 15px;">
                        <div class="pt-4"></div>
                        <div class="ps-4 pe-4 pb-2">
                            Select your preferred candidate(s) for each position.
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
                            After selecting candidate(s) each position, click the 
                            <span class="main-color"><b>Submit Vote</b></span> 
                            button to successfully cast your vote.
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    
     <!--------------------- Voting Section ------------------------->

  <div class="col-lg-9 col-12">
    <form id="voteForm" method="post">
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
                        <div class="text-position main-color pt-md-3 pt-lg-4 pt-sm-2 pt-4 ps-3 ps-lg-5 ps-sm-3 d-flex align-items-center justify-content-between">
                          <b><?php echo strtoupper($row['title']) ?></b>
                          <?php if ($row['max_votes'] > 1): ?>
                          <!-- Display on large screens -->
                              <div class="ps-3 ps-lg-5 ps-sm-3 me-5 d-none d-lg-block" style="font-size: 12px;">
                                  <i data-feather="info" style="width: 12px; height: 12px"></i>&nbsp;
                                  Select up to&nbsp;<b><?php echo $row['max_votes'] ?></b>&nbsp;candidates
                              </div>
                          <?php endif; ?>
                      </div>
                      <div class="hover-color ps-3 ps-lg-5 ps-sm-3 pb-4" style="font-size: 12px;">
                          <a href="#<?php echo $modal_id ?>" data-toggle="modal">Duties and Responsibilities</a>
                          <!-- Display below on medium and small screens -->
                          <?php if ($row['max_votes'] > 1): ?>
                              <div class="main-color d-block d-md-none d-lg-none pt-3">
                                  <i data-feather="info" style="width: 12px; height: 12px"></i>&nbsp;
                                  Select up to&nbsp;<b><?php echo $row['max_votes'] ?></b>&nbsp;candidates
                              </div>
                          <?php endif; ?>
                      </div>
                          <!-- Modal for Duties and Responsibilities -->
                          <div class="modal fade adjust-modal" id="<?php echo $modal_id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                      <div class="modal-header main-bg-color text-white d-flex justify-content-between align-items-center">
                                          <h4 class="modal-title mb-0"><b><?php echo strtoupper($row['title']) ?></b></h4>
                                          <button type="button" class="btn-close me-2" data-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          <div class="main-color pt-4 pb-3"><b>DUTIES AND RESPONSIBILITIES</b></div>
                                             <div id="description-output-<?php echo $modal_id ?>"></div>
                                           <script>
                                                // Retrieve Delta format JSON from PHP variable
                                                var deltaJson = <?php echo $row['description']; ?>;
                                                // Initialize Quill editor without any DOM element (for conversion only)
                                                var quill = new Quill(document.createElement('div'));
                                                // Set Delta content to Quill editor
                                                quill.setContents(deltaJson.ops);
                                                // Get HTML content from Quill editor
                                                var htmlContent = quill.root.innerHTML;
                                                document.getElementById('description-output-<?php echo $modal_id ?>').innerHTML = htmlContent;
                                         </script>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Fetch candidates matching the position_id -->
                          <?php
                          // Reset candidates result set pointer
                          $result_candidates->data_seek(0);
                          $candidate_loop_count = 0;
                          ?>
                          <div class="row px-2">
                              <?php while ($row_candidates = $result_candidates->fetch_assoc()): ?>
                                  <?php if ($row_candidates['position_id'] == $row['position_id']): ?>
                                      <?php
                                      $full_name = $row_candidates['last_name'] . ", " . $row_candidates['first_name'];
                                      ?>
                                      <div class="col-lg-6 col-md-12 col-sm-12">
                                          <div class="px-lg-5 px-3 px-sm-3 px-md-3">
                                              <div class="candidate-info pb-4">
                                                  <label for="<?php echo $row_candidates['candidate_id'] ?>">
                                                      <img src="user_data/<?php echo $org_acronym ?>/candidate_imgs/<?php echo $row_candidates['photo_url'] ?>" alt="Candidate Image" width="100px" height="100px">
                                                  </label>
                                                  <div>
                                                      <input type="hidden" name="position_id[<?php echo $row['position_id'] ?>][]" value="<?php echo $row['position_id'] ?>">
                                                      <input type="hidden" name="candidate_id[<?php echo $row_candidates['candidate_id'] ?>][][]" value="<?php echo $row_candidates['candidate_id'] ?>">
                                                      <div style="display: flex; align-items: center;" class="ps-3">
                                                          <input type="checkbox" id="<?php echo $row_candidates['candidate_id'] ?>" name="position[<?php echo $row['position_id'] ?>][]" value="<?php echo $row_candidates['candidate_id'] ?>" class="candidate-checkbox" 
                                                              data-img-src="user_data/<?php echo $org_acronym ?>/candidate_imgs/<?php echo $row_candidates['photo_url'] ?>" data-max-votes="<?php echo $row['max_votes'] ?>">
                                                          <label for="<?php echo $row_candidates['candidate_id'] ?>" style="display: flex; flex-direction: column; align-items: left; font-size: 15px">
                                                              <div class="ps-4">
                                                                  <div class="font-weight2" style="font-size:15px"><?php echo $full_name ?></div>
                                                                  <div class="font-weight3 undisplay main-color" style="font-size:12px"><?php echo $row_candidates['program'] ?> <?php echo $row_candidates['year_level'] ?>-<?php echo $row_candidates['section'] ?></div>
                                                              </div>
                                                          </label>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <?php $candidate_loop_count++; ?>
                                      <?php if ($candidate_loop_count % 2 == 0): ?>
                          </div><!-- Close current row -->
                          <div class="row px-2"><!-- Start new row -->
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
                                  <input type="radio" class="abstain-checkbox" id="abstain_<?php echo $row['position_id'] ?>" name="position[<?php echo $row['position_id'] ?>][]" data-position-id="<?php echo $row['position_id'] ?>" value="" style="vertical-align: middle;">
                                  <label for="abstain_<?php echo $row['position_id'] ?>" style="vertical-align: middle; font-size:15px"><b>&nbsp;&nbsp;ABSTAIN</b></label><br>
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
      <!-- CSRF Token hidden field -->
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
      <!-- Submit and Reset Buttons -->
      <?php if ($result_positions->num_rows > 0 && $result_candidates->num_rows > 0): ?>
          <div class="text-center pb-4 mt-3">
              <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center">
                  <button type="submit" class="button-submit main-bg-color mb-2 mb-sm-0 mr-sm-2 order-sm-2" id="submitVoteBtn" onclick="validateForm()">
                      Submit Vote
                  </button>
                  <button type="button" class="button-reset order-sm-1" onclick="resetForm()">
                      <u>Reset Form</u>
                  </button>
              </div>
          </div>
      <?php endif; ?>
</form>

</main>
</body>

<?php include_once __DIR__ . '/includes/components/footer.php'; ?>


<script>
  window.onload = function() {
      $(document).ready(() => {
          $('#greetModal').modal('show');
      }); 
  };
</script>

  <script src="scripts/feather.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src ="scripts/ballot-forms.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="scripts/loader.js"></script>

</html>
<?php
  } else {
    header("Location: end-point");
  }
} else {
  header("Location: landing-page");
}
?>