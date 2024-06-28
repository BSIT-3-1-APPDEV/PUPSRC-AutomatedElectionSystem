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
    <title>Update Email</title>
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
    <link rel="stylesheet" href="styles/feedback-suggestions.css" />
    <link rel="stylesheet" href="styles/setting-email.css" />
    <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_acronym . '.css'; ?>">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  </head>

  <body>

    <?php
    include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
    include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/topnavbar.php');
    ?>
    <div class="main" id="change-password">
      <div class="container-email">
        <div class="row justify-content-center">
          <!-- FOR VERIFICATION TABLE -->
          <div class="col-md-10 card-box">
            <div class="table-wrapper" id="profile">
              <form class="needs-validation" id="reset-password-form" novalidate enctype="multipart/form-data">
                <input type="hidden" id="token" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="img-container">
                  <img src="images/Emails/<?php echo strtolower($org_name); ?>-email.png" alt="Email Icon" class="forgot-password-icon">
                </div>

                <div class="form-group">
                  <h4 class="reset-password-title text-center main-color <?php echo strtoupper($org_name); ?>-text-color" id="">Update your email address</h4>
                  <p class="reset-password-subtitle text-center"><b>Please enter your new email address below to update your <br> account information.</>
                  </p>

                  <!-- Displays error message -->
                  <?php if (isset($error_message)) : ?>
                    <div class="d-flex align-items-center justify-content-center mb-0">
                      <div class="fw-medium border border-danger text-danger alert alert-danger alert-dismissible fade show  custom-alert" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle flex-shrink-0 me-2" viewBox="0 0 16 16">
                          <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                          <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                        </svg>
                        <div class="d-flex align-items-center">
                          <span class="pe-1"><?php echo $error_message; ?></span>
                          <button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <div class="row mt-5 mb-3 reset-pass">
                    <div class="col-md-8 mb-2 position-relative">
                      <div class="input-group mb-3" id="reset-password">
                        <input type="email" class="form-control reset-password-password" name="email" onkeypress="return avoidSpace(event)" placeholder="Enter a new email address" id="password" required>
                        <label for="email" class="new-password translate-middle-y main-color <?php echo strtoupper($org_name); ?>-text-color" id="">NEW EMAIL ADDRESS</label>
                        <button class="btn btn-secondary reset-password-password" type="button" id="reset-password-toggle-1">

                        </button>
                      </div>
                    </div>
                    <div class="col-md-8 mb-0 mt-0 position-relative">
                      <p class="reset-password-subtitlee">For security purposes, please confirm the change of your email address by entering your password.</p>
                      <div class="input-group" id="reset-password">
                        <input type="password" class="form-control reset-password-password" onkeypress="return avoidSpace(event)" id="password_confirmation" name="password_confirmation" placeholder="Enter your password" required>
                        <label for="password_confirmation" class="new-password translate-middle-y main-color <?php echo strtoupper($org_name); ?>-text-color" id="">PASSWORD</label>
                        <button class="btn btn-secondary reset-password-password" type="button" id="reset-password-toggle-2">
                          <i class="fas fa-eye-slash"></i>
                        </button>
                      </div>
                    </div>
                    <div id="password-mismatch-error" class="text-danger" style="display: none;">Incorrect password. Please try again.</div>

                  </div>
                  <div class="col-md-12 reset-pass">
                    <!-- <button class="btn login-sign-in-button mt-4" id="<?php echo strtoupper($org_name); ?>-login-button" type="submit" name="reset-password-submit" id="reset-password-submit">Set Password</button> -->
                    <button class="btn login-sign-in-button mt-3 mb-3" id="<?php echo strtoupper($org_name); ?>-login-button" type="submit" name="new-password-submit">Update Email</button>

                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <!-- Need Js to function -->
    <div class="modal" id="successEmailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="success-modal">
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray" onclick="closeModal()"></i>
                    </div>
                    <div class="text-center">
                        <div class="col-md-12">
                            <img src="images/resc/check-animation.gif" class="check-perc" alt="Checked Logo">
                        </div>
                        <div class="row">
                            <div class="col-md-12 pb-3">
                                <p class="fw-bold fs-3 text-success spacing-4">Successfully updated!</p>
                                <p class="fw-medium spacing-5">Your email address has been updated.
                                </p>
                                <button class="button-check main-bg-color text-white py-2 px-4" id="Home">
                                  <a class="custom-link" href="../src/setting-email-update"><b>Back to Home</b></a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
      <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
    </div>

    <script src="../src/scripts/feather.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="scripts/reset-password.js"></script>


    <!-- Updated script for password toggle -->
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const togglePassword1 = document.querySelector("#password-toggle-1");
        const passwordInput1 = document.querySelector("#change-password");
        const eyeIcon1 = togglePassword1.querySelector("i");

        togglePassword1.addEventListener("click", function() {
          const type =
            passwordInput1.getAttribute("type") === "password" ?
            "text" :
            "password";
          passwordInput1.setAttribute("type", type);

          // Toggle eye icon classes
          eyeIcon1.classList.toggle("fa-eye-slash");
          eyeIcon1.classList.toggle("fa-eye");
        });

        // Show or hide eye toggle based on input value
        passwordInput1.addEventListener("input", function() {
          if (passwordInput1.value === "") {
            togglePassword1.style.display = "none";
          } else {
            togglePassword1.style.display = "block";
          }
        });
      });
    </script>
  </body>

  </html>

<?php
} else {
  header("Location: landing-page");
}
?>