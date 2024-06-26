<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');

if (isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'student_voter')) {
  // ------ SESSION EXCHANGE
  include FileUtils::normalizeFilePath('includes/session-exchange.php');
  // ------ END OF SESSION EXCHANGE

  $connection = DatabaseConnection::connect();
  $voter_id = $_SESSION['voter_id']; // Get voter id to update the vote status
  $vote_status = $_SESSION['vote_status']; // Get voter id to update the vote status
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Information</title>
    <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">

    <!-- Montserrat Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <!-- Bootstrap 5 code -->
    <link type="text/css" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/loader.css" />
    <link rel="stylesheet" href="styles/user-setting-information.css" />
    <link rel="stylesheet" href="styles/profile.css" />
    <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_acronym . '.css'; ?>">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
      .nav-link:hover,
      .nav-link:focus {
        color: var(--main-color);
      }

      .navbar-nav .nav-item.dropdown.show .nav-link.main-color {
        color: var(--main-color);
      }

      .navbar-nav .nav-item.dropdown .nav-link.main-color,
      .navbar-nav .nav-item.dropdown .nav-link.main-color:hover,
      .navbar-nav .nav-item.dropdown .nav-link.main-color:focus {
        color: var(--main-color);
      }
    </style>

  </head>

  <body>

    <?php
    include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
    include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/topnavbar.php');
    ?>

    <main>
      <div class="container" style="margin-top: 5%; margin-bottom:5%;">
        <div class="row">
          <!-- left side -->
          <div class="col-lg-3 mb-4 pe-lg-3">
            <div class="row pb-3">
              <div class="px-4 pt-4 pb-3 title main-color text-center spacing">
                <h5><b>Settings & Privacy</b></h5>
              </div>
            </div>
            <div class="row">
              <div class="p-4 title" style="font-size: 12.8px;">
                <div class="px-2">
                  <div class="main-color d-flex align-items-center pt-2 pb-4">
                    <div class="pe-4">
                      <i data-feather="user" class="white" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                      <div class="mb-0" style="font-size: 18px; font-weight:600">
                        <a href="../src/user-setting-information.php" class="custom-link"> Information </a>
                      </div>
                      <div class="mb-0 des">See your account information like your email address and certificate of registration.</div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center pb-4">
                    <div class="pe-4">
                      <i data-feather="lock" class="white" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                      <div class="mb-0" style="font-size: 18px; font-weight:600">
                        <a href="../src/user-setting-password.php" class="custom-link"> Change Password </a>
                      </div>
                      <div class="mb-0 des">Ensure your account's security by updating your password whenever you need.</div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center pb-4">
                    <i class="fas fa-exchange-alt me-4" style="font-size: 1.1rem;"></i>
                    <div>
                      <div class="mb-0" style="font-size: 18px;">
                        <b><a href="../src/user-setting-transfer.php" class="custom-link">Transfer Org</a></b>
                      </div>
                      <div class="mb-0 des">Move your account to a different organization upon transfer.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- right side -->
          <div class="col-lg-9 ps-lg-4">
            <div class="row">
              <div class="p-4 title" style="font-size:15px;">
                <div class="py-3 px-2 px-lg-4 px-sm-1">
                  <h5 class="main-color pb-2">
                    <b>
                      <i data-feather="user" class="fas fa-exchange-alt me-4" style="font-size: 1rem;"></i>Information
                    </b>
                  </h5>
                  <div id="section-1" style="align-items: center; justify-content: center;">
                    <div class="rounded-icon main-bg-color">
                      <i data-feather="mail" class="white im-cust feather-4l"></i>
                    </div>
                    <div class="container-data">
                      <h4 class="email-add">Email Address</h4>
                      <p class="user-email-1">kimMingyu@gmail.com</p>
                      <hr class="email-border">
                      <button type="button" class="main-color transparent-btn" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#successResetPasswordLinkModal">Change Email Address</button>
                      </br>
                    </div>
                  </div>
                  <div class="col" style="margin-top: -200px; margin-left: 400px;">
                    <iframe id="pdfViewer" src="<?php echo "user_data/$org_name/cor/" . $row['cor']; ?>" class="right-aligned-iframe" frameborder="0"></iframe>
                  </div>
                  <a href="<?php echo "user_data/$org_name/cor/" . $row['cor']; ?>" download class="d-inline-flex align-items-center">
                  </a>
                  <p class="filename"><i data-feather="paperclip" class="white im-cust feather-2xs"></i>RegistrationCertificate.pdf</p>

                  <div class="tools">
                    <span class="fs-7 ps-sm-2 spacing-3 fw-medium download">
                      <i class="fas fa-download fa-sm"></i> Download
                    </span>
                    <i class="fa-solid fa-expand fa-sm fullscreen-icon"></i>
                  </div>

                  <br>
                  <br>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>

      <!-- Modals -->
      <div class="modal fade" id="successResetPasswordLinkModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content d-flex align-items-center justify-content-center" id="success-modal">
            <div class="modal-body text-center w-100">
              <div class="col-md-12">
                <img src="images/resc/icons/shield.png" class="change-passs-modal-icon" alt="iVote Logo">
              </div>
              <p class="fw-bold fs-4 change-password-title spacing-4 mt-3">Enter Password</p>
              <p class="change-password-sub">To change your email address, please provide your current password.</p>
              <form class="needs-validation" id="forgot-password-form" name="forgot-password-form" novalidate enctype="multipart/form-data">
                <div class="col-12 col-md-12">
                  <div class="input-group">
                    <input type="password" class="form-control mx-auto align-self-center" id="change-password" name="change-password" onkeypress="return avoidSpace(event)" placeholder="Type current password here...">
                    <button class="btn btn-secondary eye-toggle" type="button" id="password-toggle-1" style="display: none;">
                      <i class="fas fa-eye-slash"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-12 mt-5">
                  <button type="button" class="btn btn-secondary cancel-button" data-bs-dismiss="modal" id="cancel-modal">Cancel</button>
                  <button type="button" class="btn btn-primary delete-button" id="submit-button">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Modal -->
      <div class="modal" id="approvalModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="d-flex justify-content-end">
                <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray custom-margin" onclick="closeModalAndRedirect()"></i>
              </div>
              <div class="text-center">
                <div class="col-md-12">
                  <img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
                </div>
                <div class="row">
                  <div class="col-md-12 pb-3">
                    <p class="fw-bold fs-3 success-color spacing-4">Email Verification Sent</p>
                    <p class="fw-medium spacing-5">We sent a message to your current email.<br> Please follow the instructions to change <br> your email address.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--Max Attempts Reach Modal -->
      <div class="modal" id="errorModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="d-flex justify-content-end">
                <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray custom-margin" id="onlyErrorClose">
                </i>
              </div>
              <div class="text-center">
                <div class="col-md-12">
                  <img src="images/resc/warning.png" alt="Warning Icon">
                </div>

                <div class="row">
                  <div class="col-md-12 pb-3 pt-4">
                    <p class="fw-bold fs-3 danger spacing-4 px-2" style="color: #EE1C24;">Max Attempts Reached</p>
                    <p class="fw-medium spacing-5 pt-2 px-5 ">Sorry, you've reached the maximum number <br>
                      of attempts to change your password. For <br> security reasons, please wait for <b>30 minutes</b> <br> before trying again.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- JavaScript to show approval modal after submission -->
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('submit-button').addEventListener('click', function() {
            // Assuming the submission is successful, show the approval modal
            var approvalModal = new bootstrap.Modal(document.getElementById('approvalModal'));
            approvalModal.show();
          });
        });

        // JS for the submit approval modal when the user click submit the modal is closed
        document.getElementById('submit-button').addEventListener('click', function() {
          // Perform any form validation or submission logic here

          // If the form is valid, close the modal
          var myModalEl = document.getElementById('successResetPasswordLinkModal');
          var modal = bootstrap.Modal.getInstance(myModalEl); // Returns a Bootstrap modal instance
          modal.hide();
        });

        function closeModalAndRedirect() {
          var modal = document.getElementById('approvalModal');
          modal.style.display = 'none';
          // Replace with the actual URL you want to redirect to
          window.location.href = 'user-setting-information.php?voter_id=<?php echo htmlspecialchars($row["voter_id"]); ?>';
        }
        document.addEventListener('DOMContentLoaded', (event) => {
    const passwordInput = document.getElementById('change-password');
    const toggleButton = document.getElementById('password-toggle-1');

    // Show the toggle button when the password input is focused
    passwordInput.addEventListener('focus', () => {
      toggleButton.style.display = 'block';
    });

    // Prevent the toggle button from hiding if it is clicked
    toggleButton.addEventListener('mousedown', (event) => {
      event.preventDefault();
    });

    // Toggle the password visibility
    toggleButton.addEventListener('click', () => {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
      } else {
        passwordInput.type = 'password';
        toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
      }
    });
  });
      </script>
    </main>
    <div class="footer">
      <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
    </div>

    <script src="../src/scripts/feather.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGz5D6306zI1M1rEM0bzW2UN4u5d1a2KX9KRALhWV4aKN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cu5eC5sE/PZz57f5mlP34fIuFj0m9koW2j4X0eY9Fzj5sy9F2YfGOFlUNcr4fnfM" crossorigin="anonymous"></script>
    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/loader.js"></script>
    <script src="scripts/reset-password.js"></script>
  </body>

  </html>

<?php
} else {
  header("Location: landing-page.php");
}
?>