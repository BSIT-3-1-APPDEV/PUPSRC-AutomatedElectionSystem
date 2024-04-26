<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';


if(isset($_SESSION['voter_id'])) {

    // ------ SESSION EXCHANGE
    include 'includes/session-exchange.php';
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
  <!-- <link rel="stylesheet" href="styles/style.css" /> -->
  <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_acronym . '.css'; ?>">
  <!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  

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
          <b>Hello, Iskolar</b> <i class='fas fa-user-circle main-color ps-3' style='font-size:23px;'></i>
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

<main>
<div class="m-4">
  <div class="row">
    <div class="col-lg-12">
      <div class="p-4 title main-color text-center fw-bolder spacing">
        <h3><b>BALLOT FORM</b></h3>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Introductory Greetings -->
<div class="modal fade adjust-modal" id="greetModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-2">
      <div class="modal-body pt-3">
      <div class="d-flex justify-content-end"> 
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <b><div class="greetings-blue">Hello </div><div class="greetings-red">Isko't Iska!</div></b>
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

<!-- Modal for Voting Guidelines -->
<div class="modal fade" id="votingModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="reminder">
          <div class="title-2 main-bg-color">
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


<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="text-center pb-4">
          <div class="main-color pt-4">
            <h4><b>CANDIDATE PREVIEW</b></h4>
        </div>
             Kindly review and confirm selections.
        </div>
        <div id="selectedCandidate"></div> <!-- Display selected candidate here -->
      </div>
        <div class="text-center pb-4">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Cancel</button>
          <button type="submit" class="btn btn-success" id="submitModalButton">
            Submit Vote</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal for Vote Submitted -->
<div class="modal fade adjust-submit-modal" id="voteSubmittedModal" tabindex="-1" aria-labelledby="voteSubmittedModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pb-4">
      <div class="modal-body text-center pb-4">
        <img src="../src/images/resc/check-animation.gif" width="50%">
        <h4 class="pb-2"><b>Vote Submitted!</b></h4>
        <button class="button-check main-bg-color text-white p-2"><a class="text-white" href="feedback-suggestions.php" ><b>Give Feedback</b></a></button>
      </div>
    </div>
  </div>
</div>


<div class="m-4">
  <div class="row">
  <div class="col-lg-3 col-md-2 d-none d-md-block">
    <div class="reminder">
        <div class="title-2 main-bg-color">
            <b>Voting Guidelines</b>
        </div>

        <div class="p-xl-4">
            <div class="pb-2">
                Select only one (1) candidate each position.
                <hr>
            </div>
            <div class="pb-2">
                Do not leave an empty selection.
                <hr>
            </div>
            <div class="pb-2">
                Vote buying and intimidation are prohibited.
                <hr>
            </div>
            <div class="pb-2">
                Displaying your ballot or discussing your vote to another person's votes is prohibited.
                <hr>
            </div>
            <div class="pb-2">
                Only registered voters are permitted to vote.
                <hr>
            </div>
            <div class="pb-2">
                After selecting one (1) candidate each position, click the Submit Vote button to successfully cast your vote.
                <hr>
            </div>
        </div>
    </div>
</div>


     <!-- Voting Section -->

    <div class="col-lg-9 col-md-10">

       <!-------------------------- Student Details ----------------------->

<!-- <div class ="reminder">
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
      </div> -->

  <form id="voteForm" method="post" action="../src/includes/insert-vote.php">
    <?php if ($result_positions->num_rows == 0 || $result_candidates->num_rows == 0): ?>
        <div class="reminder">
            <div class="text-position main-color pb-4">
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
            <div class="reminder mb-4" data-position-title="<?php echo htmlspecialchars($row['title']); ?>">
                <div class="text-position main-color">
                    <b><?php echo strtoupper($row['title']) ?></b>
                </div>
                <div class="subtitle">
                    <div class="hover-color">
                        <a href="#<?php echo $modal_id ?>" data-toggle="modal">Duties and Responsibilities</a>
                    </div>
                </div>
                
                <!-- Modal for Duties and Responsibilities -->
                <div class="modal fade adjust-modal" id="<?php echo $modal_id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header main-bg-color text-white d-flex justify-content-between align-items-center">
                                <h4 class="modal-title mb-0"><b><?php echo strtoupper($row['title']) ?></b></h4>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="main-color pt-2 pb-2"><b>DUTIES AND RESPONSIBILITIES</b></div>
                                <ul>
                                    <li class="pb-2">The executive power shall be vested on the <?php echo strtoupper($org_acronym) ?> president alone.</li>
                                    <li class="pb-2">He shall be the official representative of the studentry of PUP-SRC to any event or organization.</li>
                                    <li class="pb-2">He shall be the official representative alone of the University Board of Discipline.</li>
                                    <li class="pb-2">He shall have the responsibility of promoting the vision and mission and the philosophy of the institution to the whole studentry.</li>
                                    <li class="pb-2">He shall promote worthy projects for the interest of the students.</li>
                                    <li class="pb-2">He shall promote honesty and integrity in every dealing in and out his jurisdiction.</li>
                                    <li class="pb-2">He shall be the official officer speaker in any assembly concerning his leadership.</li>
                                    <li class="pb-2">He shall have the right to approve and appoint officers as committee heads and members needed in case of events.</li>
                                    <li class="pb-2">He shall have the power to nominate or to appoint any student to any branch of the <?php echo strtoupper($org_acronym) ?> Officers.</li>
                                    <li class="pb-2">He shall not be eligible for re-election.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fetch candidates matching the position_id -->
                <?php $result_candidates->data_seek(0);  ?>
                <?php $candidate_count = 0; ?>
                <div class="row">
                    <?php while ($row_candidates = $result_candidates->fetch_assoc()): ?>
                        <?php if ($row_candidates['position_id'] == $row['position_id']): ?>
                            <?php
                            $full_name = $row_candidates['last_name'] . ", " . $row_candidates['first_name'];
                            ?>
                            <div class="col-lg-6 col-md-6 col-sm-12 p-xl-4">
                                <label>
                                    <div class="candidate-info ps-4">
                                        <img src="images/candidate-profile/placeholder.png" alt="Candidate Image" width="100px" height="100px">
                                        <div>
                                            <input type="hidden" name="position_id[<?php echo $row['position_id'] ?>][]" value="<?php echo $row['position_id'] ?>" >
                                            <input type="hidden" name="candidate_id[<?php echo $row_candidates['candidate_id'] ?>][]" value="<?php echo $row_candidates['candidate_id'] ?>" >
                                            <input type="radio" name="position[<?php echo $row['position_id'] ?>]" value="<?php echo $row_candidates['candidate_id'] ?>">
                                            <?php echo $full_name ?><br>
                                            <div class="undisplay main-color subtitle-2"><b><?php echo $row_candidates['section'] ?></b></div>
                                        </div>
                                    </div>
                                </label>
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
                        <label class="text-muted">
                            <input type="radio" name="position[<?php echo $row['position_id'] ?>]" value="">
                            <b>ABSTAIN</b><br>
                        </label>
                    </div>
                </div>
            </div><!-- Close reminder -->
        <?php endwhile; ?>
    <?php endif; ?>
    <div class="text-center pb-4 mt-3">
        <button type="button" class="button-reset" onclick="resetForm()"><u>Reset Form</u></button>
        <button type="submit" class="button-submit main-bg-color" id="submitVoteBtn" onclick="validateForm()">
            Submit Vote
        </button>
    </div>
  </div>
</form>


</main>
</body>

<?php include_once __DIR__ . '/includes/components/footer.php'; ?>


  <script src="../src/scripts/feather.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 

  <script>
    function validateForm(event) {
        var voteForm = document.getElementById('voteForm');
        var reminders = voteForm.querySelectorAll('.reminder');
        var isValid = true;
        var scrollToReminder = null;
        var selectedCandidateHTML = '';

        var pairCounter = 0;
        reminders.forEach(function(reminder) {
            var radioButtons = reminder.querySelectorAll('input[type="radio"]');
            var radioButtonChecked = false;

            radioButtons.forEach(function(radioButton) {
                if (radioButton.checked) {
                    radioButtonChecked = true;
                    // get the selected position and candidates
                    var positionTitle = reminder.getAttribute('data-position-title');
                    var candidateName = radioButton.parentNode.querySelector('div').textContent.trim();
                    var candidateSection = radioButton.parentNode.querySelector('.undisplay').textContent.trim(); // Get candidate section
                    var candidateHTML = radioButton.parentNode.innerHTML.replace(radioButton.outerHTML, '').replace(candidateName, '').trim();
                    var imageSrc = reminder.parentNode.querySelector('img').getAttribute('src'); // Get candidate image source

            // Wrap each pair in a row
            if (pairCounter % 2 === 0) {
                selectedCandidateHTML += '<div class="row ms-4">';
            }

          // Add the image and pair to the row
          selectedCandidateHTML += '<div class="col-lg-6 pb-sm-3"><img src="' + imageSrc + '"width="80px" height="80px" style="display: inline-block; vertical-align: middle;border-radius: 10px; border: 2px solid #ccc;">' +
                         '<div class="ps-4" style="display: inline-block; vertical-align: middle; "><b><div class="main-color"' + candidateHTML + '</div><div style="font-size:12px">' + 
                         positionTitle.toUpperCase() + '</b></div></div>' + '</div>';

            pairCounter++;

            // Close the row after every second pair
            if (pairCounter % 2 === 0) {
                selectedCandidateHTML += '</div><br>'; // Close the row
            }
        }
    });

    var reminderError = reminder.querySelector('.text-danger');


            if (!radioButtonChecked) {
                if (!reminderError) {
                    var requiredText = document.createElement('div');
                    requiredText.classList.add('text-danger', 'mt-4', 'ps-4');
                    requiredText.innerHTML = "<i>This field is required. Please select one (1) candidate or click ABSTAIN.</i>";
                    reminder.insertBefore(requiredText, reminder.firstChild);
                }

                reminder.classList.add('border', 'border-danger');
                isValid = false;
                if (!scrollToReminder) {
                    scrollToReminder = reminder;
                }
            } else {
                if (reminderError) {
                    reminder.removeChild(reminderError);
                }
                reminder.classList.remove('border', 'border-danger');
            }
        });

        if (!isValid && scrollToReminder) {
            event.preventDefault(); // Prevent form submission
            scrollToReminder.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            // If all fields are valid, display the modal with form preview
            event.preventDefault(); // Prevent form submission

            document.getElementById('confirmationModal').classList.add('show');
            document.getElementById('confirmationModal').style.display = 'block';
            document.body.classList.add('modal-open');
            document.getElementById('confirmationModal').setAttribute('aria-modal', true);
            document.getElementById('confirmationModal').setAttribute('aria-hidden', false);
            document.getElementById('confirmationModal').setAttribute('role', 'dialog');

            document.getElementById('selectedCandidate').innerHTML = selectedCandidateHTML;
        }
    }

    document.getElementById('voteForm').addEventListener('submit', validateForm);

    // Dynamically remove the error message if a radio button is once selected
    var radioButtons = document.querySelectorAll('input[type="radio"]');
    radioButtons.forEach(function(radioButton) {
        radioButton.addEventListener('change', function() {
            var reminder = this.closest('.reminder');
            var reminderError = reminder.querySelector('.text-danger');
            if (reminderError) {
                reminder.removeChild(reminderError);
                reminder.classList.remove('border', 'border-danger');
            }
        });
    });

    // Submit the form when the "Submit Vote" button is clicked
    document.getElementById('submitModalButton').addEventListener('click', function() {
        document.getElementById('voteForm').submit();

    });
</script>


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
  // Function to show the vote submitted modal
function showVoteSubmittedModal() {
    $('#voteSubmittedModal').modal('show');
}

// Close modals when clicking on the close button
var closeButtons = document.getElementsByClassName('btn-close');
for (var i = 0; i < closeButtons.length; i++) {
    closeButtons[i].addEventListener('click', function() {
        var modal = this.closest('.modal');
        $(modal).modal('hide');
    });
}

// Show the vote submitted modal when the page loads (if redirected with success parameter)
window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showVoteSubmittedModal();
    } else {
        $(document).ready(() => {
            $('#greetModal').modal('show');
        }); 
    }
};
</script>





</html>
<?php
} else {
  header("Location: voter-login.php");
}
?>