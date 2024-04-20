<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';


if(isset($_SESSION['voter_id'])) {

  $connection = DatabaseConnection::connect();
  // Assume $connection is your database connection
  $voter_id = $_SESSION['voter_id'];
 
  
  

  if(isset($_SESSION['organization'])) {
    // Retrieve the organization name
    $organization = $_SESSION['organization'];
    $result = mysqli_query($connection, "SELECT * FROM candidate");
    
    $query = "SELECT position_id, title FROM position";
    $result_position = mysqli_query($connection, $query);
    
    
    
  } 

?>  
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" type="image/x-icon" href="../../src/images/resc/ivote-favicon.png">
	<title>Result Generation</title>

	<!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<link rel="stylesheet" href="../src/styles/style.css">
  <link rel="stylesheet" href="../src/styles/result.css">
	<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css">
  
  <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $organization . '.css'; ?>" id="org-style">
    <style>
        <?php
    /*

        // Output the CSS with the organization color variable for background-color
        echo ".main-bg-color { background-color: var(--$organization); }";

        // Output the CSS for the line with the dynamic color
        echo ".line { border-bottom: 2px solid var(--$organization); width: 100%; }"; */
        ?>
    
    </style>
    

</head>

<body>

	<!---------- SIDEBAR + HEADER START ------------>
	<?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>
	<!---------- SIDEBAR + HEADER END ------------>

  
	<div class="main">
  <div class="container">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-10 mx-auto">
            <div class="card card-report mb-5">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">ELECTION RESULTS</h5>
                        <p class="card-text" id="selectedPosition">
                            <?php 
                            if ($result_position && mysqli_num_rows($result_position) > 0) {
                                $selected_position_id = isset($_GET['position_id']) ? $_GET['position_id'] : null;
                            
                                // Loop through all rows to find the selected position
                                while ($row = mysqli_fetch_assoc($result_position)) {
                                    if ($selected_position_id == $row['position_id']) {
                                        echo "For ", $row['title'];
                                        break; // Exit the loop once the selected position is found
                                    }
                                }
                                
                                // If the selected position is not found, display a default message
                                if (!$selected_position_id || $row['position_id'] != $selected_position_id) {
                                    echo "Position";
                                }
                            } else {
                                echo "No positions available";
                            }
                            ?>
                        </p>
                    </div>
                    <?php
                    // Reset the pointer of $result_position back to the beginning
                    mysqli_data_seek($result_position, 0);

                    if ($result_position && mysqli_num_rows($result_position) > 0) {
                        $selected_position_id = isset($_GET['position_id']) ? $_GET['position_id'] : null;
                        $selected_position_title = "Position";

                        // Loop through all rows to find the selected position
                        while ($row = mysqli_fetch_assoc($result_position)) {
                            if ($selected_position_id == $row['position_id']) {
                                $selected_position_title = $row['title'];
                                break; // Exit the loop once the selected position is found
                            }
                        }
                        ?>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $selected_position_title; ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php
                                // Reset the pointer of $result_position back to the beginning
                                mysqli_data_seek($result_position, 0);

                                // Loop through all positions and display dropdown items
                                while ($row = mysqli_fetch_assoc($result_position)) {
                                    ?>
                                    <li><a class="dropdown-item" href="#" onclick="selectPosition('<?php echo $row['title']; ?>', <?php echo $row['position_id']; ?>)"><?php echo $row['title']; ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    <?php
                    } else {
                        echo "No positions available.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
                   
  <?php
    // Check if the position parameter is set in the URL
    if (isset($_GET['position_id'])) {
        // Sanitize and store the position from the URL
        $position = htmlspecialchars($_GET['position_id']);

        // Ensure the connection is valid
        if ($connection) {
            // Fetch candidates for the specified position from the database
            $query = "SELECT * FROM candidate WHERE position_id = ?";
            $stmt = mysqli_prepare($connection, $query);

            // Check if the prepared statement is valid
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $position);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // Check if the result set is valid
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {  
                        // Create an array to store candidates and their respective vote counts
                        $candidates = array();

                        // Fetch and store vote counts for each candidate
                        while ($candidate_data = mysqli_fetch_assoc($result)) {
                            $candidate_id = $candidate_data['candidate_id'];
                            $vote_count_query = mysqli_query($connection, "SELECT COUNT(*) as vote_count FROM vote WHERE candidate_id = $candidate_id");
                            $vote_result = mysqli_fetch_assoc($vote_count_query);
                            $vote_count = $vote_result['vote_count'];

                            // Store candidate information and vote count in the array
                            $candidates[] = array(
                                'candidate_data' => $candidate_data,
                                'vote_count' => $vote_count
                            );
                        }

                        // Sort candidates based on their vote count (descending order)
                        usort($candidates, function($a, $b) {
                            return $b['vote_count'] - $a['vote_count'];
                        });

                        // Separate the candidate with the highest vote count
                        $highest_vote_candidate = array_shift($candidates);

                        // Display candidate with the highest vote count separately
                        $candidate_data = $highest_vote_candidate['candidate_data'];
                        $vote_count = $highest_vote_candidate['vote_count'];
                        $last_name = $candidate_data['last_name'];
                        $first_name = $candidate_data['first_name'];
                        $candidate_name= $last_name . ', ' .$first_name;
                        ?>
                        <!-- Display candidate with the highest vote count -->
                        <div class="row">
                            <div class="col-12 col-md-10 col-lg-10 mx-auto">
                                <div class="card card-candidate mb-5">
                                    <div class="card-body d-flex justify-content-between">
                                        <img src="<?php echo $candidate_data["photo_url"]; ?>" class="card-img-top" alt="Candidate Image">
                                        <div class="card-body text-center">
                                            <h3 class="card-title"><?php echo $candidate_name ?></h3>
                                            <h5 class="card-title">BSIT 3-1</h5>
                                        </div>
                                        <p><?php echo $vote_count; ?> Votes</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display the rest of the candidates -->
                        <?php foreach ($candidates as $candidate_info) {
                            $candidate_data = $candidate_info['candidate_data'];
                            $vote_count = $candidate_info['vote_count'];
                            $last_name = $candidate_data['last_name'];
                            $first_name = $candidate_data['first_name'];
                            $candidate_name= $last_name . ', ' .$first_name;
                        ?>
                            <div class="row">
                                <div class="col-12 col-md-10 col-lg-10 mx-auto">
                                    <div class="card card-runnerup mb-5">
                                        <div class="card-body d-flex justify-content-between">
                                            <img src="<?php echo $candidate_data["photo_url"]; ?>" class="card-img-top" alt="Candidate Image">
                                            <div class="card-body text-center">
                                                <h3 class="card-title"><?php echo $candidate_name ?></h3>
                                                <h5 class="card-title">BSIT 3-1</h5>
                                            </div>
                                            <p><?php echo $vote_count; ?> Votes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php
                    } else {      
                        // Display message for no candidates
                        ?>
                        <div class="row">
                            <div class="col-12 col-md-10 col-lg-10 mx-auto">
                                <div class="card card-runnerup mb-5">
                                    <div class="card-body d-flex justify-content-between">
                                        <div class="card-body text-center">
                                            <h3 class="card-title">No Candidates</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    }
                } else {
                    // Handle query execution error
                    echo "Error executing the query: " . mysqli_error($connection);
                }
            } else {
                // Handle prepared statement creation error
                echo "Error preparing the statement: " . mysqli_error($connection);
            }
        } else {
            // Handle connection error
            echo "Connection to the database failed.";
        }
    } else {      
        // Display message for no candidates
        ?>
        <div class="row">
            <div class="col-12 col-md-10 col-lg-10 mx-auto">
                <div class="card card-runnerup mb-5">
                    <div class="card-body d-flex justify-content-between">
                        <div class="card-body text-center">
                            <h3 class="card-title">Choose Position</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php 
    }
?>

</div>
<!--
      <script>
      function addCandidateCard(name, imageSrc, position, votes) {
        // Create the card elements
        const col = document.createElement('div');
        col.classList.add('col-12', 'col-md-10', 'col-lg-10', 'mx-auto');

        const card = document.createElement('div');
        card.classList.add('card', 'card-runnerup', 'mb-5');

        const cardBody = document.createElement('div');
        cardBody.classList.add('card-body', 'd-flex', 'justify-content-between');

        const cardImg = document.createElement('img');
        cardImg.classList.add('card-img-top');
        cardImg.src = imageSrc;
        cardImg.alt = 'Candidate Image';

        const cardTextContainer = document.createElement('div');
        cardTextContainer.classList.add('card-body', 'text-center');

        const cardTitle = document.createElement('h3');
        cardTitle.classList.add('card-title');
        cardTitle.textContent = name;

        const cardSubtitle = document.createElement('h5');
        cardSubtitle.classList.add('card-title');
        cardSubtitle.textContent = position;

        const cardText = document.createElement('p');
        cardText.classList.add('card-text');
        cardText.textContent = votes;

        // Append the elements to the DOM
        cardTextContainer.appendChild(cardTitle);
        cardTextContainer.appendChild(cardSubtitle);
        cardBody.appendChild(cardImg);
        cardBody.appendChild(cardTextContainer);
        cardBody.appendChild(cardText);
        card.appendChild(cardBody);
        col.appendChild(card);

        // Add the new card to the main container
        const main = document.querySelector('.container');
        main.appendChild(col);
      }

      addCandidateCard('LAST NAME, FIRST NAME', '../../src/images/resc/mingkyu.jpg', 'BSIT 3-1', '100 Votes');
      addCandidateCard('LAST NAME, FIRST NAME', '../../src/images/resc/mingkyu.jpg', 'BSIT 3-1', '100 Votes');
      </script> -->
  </div>
              
  <script>
    function selectPosition(title, positionId) {
        document.getElementById("selectedPosition").innerHTML = title;
        window.location.href = "?position_id=" + positionId;
    }
  </script>


	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="scripts/script.js"></script>
	<script src="scripts/feather.js"></script>
    

</body>
</html>
<?php
} else {
  header("Location: landing-page.php");
}
?>
