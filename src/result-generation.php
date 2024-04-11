<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Results</title>
  
  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- Bootstrap 5 code -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


  <link rel="stylesheet" href="../../src/styles/style.css" /> 
  <link rel="stylesheet" href="../../src/styles/result.css">
</head>

<body>
  <nav class="sidebar">

    <img src="../../src/images/logos/jpia.png" alt="" class="org-logo">
    <!-- REPLACE WITH: <img src="src/images/logos/jpia.png" alt="" class="org-logo"> -->
    
    <h6>JUNIOR PHILIPPINE INSTITUTE OF ACCOUNTANTS</h6>

    <div class="menu-content">
      <ul class="menu-items">
        <div class="menu-title">Your menu title</div>

        <li class="item">
          <a href="#">Your first link</a>
        </li>

        <li class="item">
          <div class="submenu-item" data-bs-toggle="collapse" href="#firstSubmenu">
            <span>First submenu</span>
            <i class="fas fa-chevron-right"></i>
          </div>

          <ul class="menu-items submenu collapse" id="firstSubmenu">
            <div class="menu-title">
              <i class="fas fa-chevron-left"></i>
              Your submenu title
            </div>
            <li class="item">
              <a href="#">First sublink</a>
            </li>
            <!-- Add more sublinks as needed -->
          </ul>
        </li>

        <li class="item">
          <a href="#">Your second link</a>
        </li>

        <li class="item">
          <a href="#">Your third link</a>
        </li>
      </ul>
    </div>
  </nav>

  <nav class="navbar">
    <div class="container-fluid">
      <i class="fas fa-bars" id="sidebar-close"></i>
      <span class="navbar-text mx-auto fw-bold">ELECTION COMMITTEE PORTAL</span>
    </div>
  </nav>
  
  
  <main class="main">
    
  <div class="d-flex flex-column flex-wrap">
    <div class="card d-flex flex-column">
        <p class="title">RESULT GENERATION</p>
        <p class="title-position">FOR PRESIDENT</p>
        <div class="dropdown">
            <button class="dropbtn" id="dropdownBtn" >Position</button>
            <div class="dropdown-content" id="dropdownContent">
                <a href="#">President</a>
                <a href="#">Vice-President</a>
                <a href="#">Secretary</a>
            </div>
        </div>
    </div>

      <div id="results-container" class="d-flex flex-column">
      <div class="results-report">
          <div class="candidate">
              <img src="../../src/images/resc/mingkyu.jpg" alt="Candidate Image">
              <h4 class="candidate-name">EDAN, IVAN ANGELO P.</h4>
              <h5><span class="info">BSIT 3-1</span></h5>
              <h5>283 </h5>
              <h5>Votes</h5>
          </div>
      </div>
      <div class="results-report-runnerup">
          <div class="candidate">
              <img src="../../src/images/resc/mingkyu.jpg" alt="Candidate Image">
              <h4 class="candidate-name">EDAN, IVAN ANGELO P.</h4>
              <p class="info">BSIT 3-1</p>
              <h5>283 </h5>
              <h5>Votes</h5>
          </div>
      </div>
  </div>
    
    
  <script>
    //JAVASCRIPT FOR RESULT CONTAINER AUTOMATIC ADD NEW CONTAINER WHEN THERE IS NEW RESULT
  function addCandidate(name, imgSrc, altText, votes) {
  // Create a new div element with the class of 'candidate'
  const candidateDiv = document.createElement('div');
  candidateDiv.classList.add('candidate');

  // Create a new img element and set its src and alt attributes
  const img = document.createElement('img');
  img.src = imgSrc;
  img.alt = altText;
  candidateDiv.appendChild(img);

  // Create a new p element and set its text content
  const p = document.createElement('p');
  p.textContent = name;
  candidateDiv.appendChild(p);

  // Create a new span element and set its text content
  const span = document.createElement('span');
  span.textContent = votes;
  candidateDiv.appendChild(span);

  // Get the results-container div and append the new candidate div to it
  const resultsContainerDiv = document.querySelector('#results-container');
  resultsContainerDiv.appendChild(candidateDiv);
  }

  function addCandidate(name, imgSrc, altText, votes) {
  // Create a new div element with the class of 'card'
  const cardDiv = document.createElement('div');
  cardDiv.classList.add('results-report-runnerup');

  // Create a new div element with the class of 'candidate'
  const candidateDiv = document.createElement('div');
  candidateDiv.classList.add('candidate');

  // Create a new img element and set its src and alt attributes
  const img = document.createElement('img');
  img.src = imgSrc;
  img.alt = altText;
  candidateDiv.appendChild(img);

 // Create a new p element and set its text content
  const p = document.createElement('p');
  p.innerHTML = name + '<br>BSIT 3-1';
  candidateDiv.appendChild(p);

  // Create a new span element and set its text content
  const span = document.createElement('span');
  span.textContent = votes;
  candidateDiv.appendChild(span);

  // Append the candidate div to the card div
  cardDiv.appendChild(candidateDiv);

  // Get the results-container div and append the new candidate div to it
  const resultsContainerDiv = document.querySelector('#results-container');
  resultsContainerDiv.appendChild(cardDiv);
 }

    // Call the addCandidate function every time there is a new name
    addCandidate('KIM, MINGKYU', '../../src/images/resc/mingkyu.jpg', 'Candidate Image', '283 Votes');
   
    //JAVASCRIPT FOR DROPDOWN OPEN & CLOSE ONCLICK
        // dropdown content will show when button is clicked
        document.getElementById("dropdownBtn").addEventListener("click", function() {
            var dropdownContent = document.getElementById("dropdownContent");
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });

        // Close the dropdown content if the user clicks again 
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }

    </script>
  </main>

  <footer class="navbar navbar-expand-lg navbar-light bg-light fixed-bottom">
    <div class="container-fluid">
      <span class="navbar-text mx-auto fw-bold">© BSIT 3-1 | All Rights Reserved</span>
    </div>
  </footer>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="../../src/scripts/script.js"></script>
  <script src="vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>