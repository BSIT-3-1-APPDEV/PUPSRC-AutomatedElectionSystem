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
	<link rel="stylesheet" href="../src/styles/style.css" />
  <link rel="stylesheet" href="../../src/styles/result.css">
	<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

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
            <h5 class="card-title">RESULTS REPORT</h5>
            <p class="card-text">For President</p>
          </div>
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              Position
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <li><a class="dropdown-item" href="#">President</a></li>
              <li><a class="dropdown-item" href="#">Vice President</a></li>
              <li><a class="dropdown-item" href="#">Secretary</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
    <div class="row">
      <div class="col-12 col-md-10 col-lg-10 mx-auto">
          <div class="card card-candidate mb-5">
              <div class="card-body d-flex justify-content-between">
              <img src="../../src/images/resc/mingkyu.jpg" class="card-img-top" alt="Candidate Image">
                  <div class="card-body text-center">
                      <h3 class="card-title">EDAN, IVAN ANGELO P.</h3>
                      <h5 class="card-title">BSIT 3-1</h5>
                  </div>
                  <p class="card-text">283 Votes</p>
              </div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-12 col-md-10 col-lg-10 mx-auto">
          <div class="card card-runnerup mb-5">
              <div class="card-body d-flex justify-content-between">
              <img src="../../src/images/resc/mingkyu.jpg" class="card-img-top" alt="Candidate Image">
                  <div class="card-body text-center">
                      <h3 class="card-title">KIM, MINGYU</h3>
                      <h5 class="card-title">BSIT 3-1</h5>
                  </div>
                  <p class="card-text">100 Votes</p>
              </div>
          </div>
      </div>
  </div>
</div>
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

      </script>
  </div>
                


	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="scripts/script.js"></script>
	<script src="scripts/feather.js"></script>

</body>
</html>