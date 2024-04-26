<?php 
session_start();
require_once '../includes/classes/db-config.php';
require_once '../includes/classes/db-connector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $conn = DatabaseConnection::connect();

    // Check if position and candidate arrays are set
    if (isset($_POST['position_id']) && isset($_POST['candidate_id'])) {
        // Iterate through each position
        foreach ($_POST['position_id'] as $position_id => $position_value) {
            // Check if a candidate is selected for the position
            if (isset($_POST['position'][$position_id]) && !empty($_POST['position'][$position_id])) {
                // Get the selected candidate ID
                $candidate_id = $_POST['position'][$position_id];
            } else {
                // Set candidate_id to NULL by default for abstain votes
                $candidate_id = null;
            }
            // Prepare and execute the SQL query to insert the vote into the database
            $stmt = $conn->prepare("INSERT INTO vote (position_id, candidate_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $position_id, $candidate_id);
            $stmt->execute();
            $stmt->close();

        }
        // Redirect back to ballot forms to display the modal
        header("Location: ../../src/ballot-forms.php?success=1");
         exit();
    } 
} else {
    // Back to ballot forms if not submitted
    header("Location: ../../src/ballot-forms.php");
    exit();
}
?>

<!-- Modal HTML structure for vote submission confirmation -->
<div id="voteSubmittedModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Vote Submitted!</p>
  </div>
</div>

<script>
  // Function to show the vote submitted modal
  function showVoteSubmittedModal() {
    console.log("Showing modal");
    var modal = document.getElementById('voteSubmittedModal');
    modal.style.display = 'block';
  }

  // Close modals when clicking on the close button
  var closeButtons = document.getElementsByClassName('close');
  for (var i = 0; i < closeButtons.length; i++) {
    closeButtons[i].addEventListener('click', function() {
      var modal = this.parentElement.parentElement;
      modal.style.display = 'none';
    });
  }

  // Show the vote submitted modal when the page loads (if redirected with success parameter)
  window.onload = function() {
    console.log("Page loaded");
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
      console.log("Success parameter found");
      showVoteSubmittedModal();
    }
  };
</script>