<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/classes/csrf-token.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');
require_once FileUtils::normalizeFilePath('includes/classes/manage-ip-address.php');


if(isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'student_voter') ) {

     // ------ SESSION EXCHANGE
     include FileUtils::normalizeFilePath('includes/session-exchange.php');
     // ------ END OF SESSION EXCHANGE

$connection = DatabaseConnection::connect();
$csrf_token = CsrfToken::generateCSRFToken();

$current_org = $org_acronym; 

$voter_id = $_SESSION['voter_id'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Transfer Organization</title>
  <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
  
  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- Fontawesome CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <!-- Bootstrap 5 code -->
  <link type="text/css" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/user-settings.css">
  <link rel="stylesheet" href="styles/loader.css">
  <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_acronym . '.css'; ?>">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <style> .nav-link:hover, .nav-link:focus {color: var(--main-color);}
  .navbar-nav .nav-item.dropdown.show .nav-link.main-color {color: var(--main-color);}
  .navbar-nav .nav-item.dropdown .nav-link.main-color,.navbar-nav .nav-item.dropdown .nav-link.main-color:hover,
  .navbar-nav .nav-item.dropdown .nav-link.main-color:focus {color: var(--main-color);}</style>
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
                <div class="d-flex align-items-center pt-2 pb-4">
                  <div class="pe-4">
                  <i data-feather="user" class="white" style="width: 20px; height: 20px;"></i>
                  </div>
                  <div>
                    <div class="side-nav mb-0">
                      <a href="user-setting-information.php" class="custom-link"> Information </a>
                  </div>
                    <div class="mb-0 des">See your account information like your email address and certificate of registration.</div>
                  </div>
                </div>
                <div class="d-flex align-items-center pb-4">
                <div class="pe-4">
                  <i data-feather="lock" class="white" style="width: 20px; height: 20px;"></i>
                  </div>
                  <div>
                    <div class="side-nav mb-0">
                    <a href="user-setting-password.php" class="custom-link"> Change Password </a>
                    </div>
                    <div class="mb-0 des">Ensure your account's security by updating your password whenever you need.</div>
                  </div>
                </div>
                <div class="main-color d-flex align-items-center pb-4">
                  <i class="fas fa-exchange-alt me-4" style="font-size: 1.1rem;"></i>
                  <div>
                    <div class="side-nav mb-0">
                      <a href="user-setting-transfer.php" class="custom-link">Transfer Org</a>
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
                    <i class="fas fa-exchange-alt me-4" style="font-size: 1rem;"></i>Transfer Organization
                  </b>
                </h5>
                <div id="section-1" style="align-items: center; justify-content: center;">
                  <div class="pb-3"><div class="des" style="justify-self: auto;">Transferring your account to another Automated Election System (AES) of a student organization is applicable only if you have officially shifted to a different program. 
                    This process is almost similar to applying as a first-time voter in your new student organization. Adhering to these steps is crucial to transfer 
                    your account:</div></div>
                  <ul>
                    <li><b>Select Your New Student Organization:</b><div class="des"> The system will prompt you to select the student organization you wish to transfer to.</div></li>
                    <li><b>Upload Your Updated Certificate of Registration:</b><div class="des"> You will need to upload your updated Certificate of Registration.</div></li>
                    <li><b>Verification:</b><div class="des"> Your uploaded Certificate of Registration will be validated by the election committee of your selected student organization.</div></li>
                    <li><b>Confirmation Email:</b><div class="des"> Wait for an email from the student organization confirming your transfer request.</div></li>
                  </ul>
                  <div class="des">Please proceed with caution when transferring your account. Follow these steps carefully, and when you’re ready, click the “Proceed” button to continue with the transfer organization process.</div>
                  <div class="d-flex justify-content-end">
                    <div class="main-color pt-2"><a href="user-setting-transfer.php?page=2" class="custom-link"> <b>Proceed</b> &nbsp;
                    <i data-feather="arrow-right-circle" class="white"></i></a></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<div id="section-2" style="display: none;">
  <form method="post" id="changeOrgForm" enctype="multipart/form-data">
  <!-- CSRF Token hidden field -->
  <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    <div class="row py-4">
      <div class="col-lg-7" style="padding-top: 3%; padding-bottom:1%">
        <label for="organization" class="fs-7 spacing-3">Organization
          <span class="asterisk fw-medium"> *</span>
        </label>
        <?php
        echo '<select class="form-select form-control bg-primary text-black" id="organization" onchange="displaySelectedOption()" required>';
        echo '<option hidden value="">Choose Organization</option>';
        foreach ($org_acronyms as $value => $label) {
            if ($value !== $current_org && $value !== 'sco') {
                echo "<option value='$value'>" . strtoupper($label) . "</option>";
            }
        }
        echo '</select>';
        ?>
         <input type="hidden" name="voter_id" value="<?php echo $voter_id ?>">
        <input type="hidden" id="org" name="org" readonly value="">
        <div class="form-group pt-4">
          <label for="cor" class="fs-7 spacing-3">Certificate of Registration
            <span class="asterisk fw-medium"> *</span>
          </label>
          <input class="form-control form-control-sm pl-2" style="background-color:#EDEDED" type="file" name="cor" id="cor" accept=".pdf" max="25MB" onchange="displayFileName(this)">
          <small class="form-text text-muted">Only PDF files up to 25MB are allowed.</small>
        </div>
        <input type="hidden" id="corFileName">
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-4" style="padding-top: 2%; padding-bottom:2%">
        <h5 class="main-color pt-lg-0 pt-sm-3"><b>Note:</b></h5>
        <div class="des">The credentials linked to your account, such as your email address and password, will remain unchanged during the transfer process.</div>
      </div>
    </div>
    <div class="row pt-2">
      <div class="text-center mt-3" style="padding-bottom: 4%;">
      <button type="submit" class="button-submit main-bg-color" id="transferBtn" disabled>Submit</button>
      </div>
    </div>
  </form>
</div>

      <!-- Transfer Org Modal -->
        <div class="modal fade adjust-modal" id="transferOrgModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content d-flex align-items-center justify-content-center" id="success-modal">
                    <div class="modal-body text-center w-100 mt-4 mb-2">
                        <div class="col-md-12">
                            <img src="images/resc/blue-info.png" style="width: 25%; height:25%" alt="Info Circle Logo">
                        </div>
                        <p class="fw-bold fs-4 information-title spacing-4 mt-3">Transfer organization?</p>
                        <p class="info-sub">Are you sure you want to proceed with the transfer? <br>This action cannot be reversed.</p>
                        <button type="button" class="btn btn-gray" id="cancelModalButton" data-bs-dismiss="modal" aria-label="Close"><b>Cancel</b></button>
                        <button type="button" class="btn button-proceed" id="proceedBtn">Yes, proceed</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Password Modal -->
        <div class="modal fade adjust-modal" id="confirmPassModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content d-flex align-items-center justify-content-center" id="success-modal">
                    <div class="modal-body text-center w-100 mt-4 mb-2">
                        <div class="col-md-12">
                            <img src="images/resc/icons/shield.png" style="width: 25%; height:25%" alt="Shield Logo">
                        </div>
                        <p class="fw-bold fs-4 information-title spacing-4 mt-3">Confirm Password</p>
                        <p class="info-sub">Please re-enter your password to complete the transfer <br> process.</p>
                        <form id="confirmPasswordForm" method="post">
                           <!-- CSRF Token hidden field -->
                           <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="password-input-container">
                                <input type="password" maxlength="50" class="password-input" name="password" id="password" autocomplete="off" placeholder="Type password here..." oninput="handleInput()">
                                <span class="toggle-password" onclick="togglePasswordVisibility()">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                            <div class="pt-2" id="errorMessage" style="color: red; display: none; font-size:12px"></div>
                            <div class="pt-4">
                                <input type="hidden" name="voter_id" value="<?php echo $voter_id ?>">
                                <button type="button" class="btn btn-gray" id="cancelModalButton" data-bs-dismiss="modal" aria-label="Close"><b>Cancel</b></button>
                                <button type="button" class="btn button-proceed" id="realSubmitBtn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer Success Modal -->
        <div class="modal fade adjust-modal" id="transferSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content d-flex align-items-center justify-content-center" id="success-modal">
                    <div class="modal-body text-center w-100 mb-2">
                        <div class="col-md-12">
                            <img src="images/resc/check-animation.gif" style="width: 50%; height:50%" alt="Check Gif">
                        </div>
                        <p class="fw-bold fs-4 information-title spacing-4 text-success">Transfer Success!</p>
                        <p class="info-sub">We’ll send you an email once your account <br>is verified. You will now be redirected to the <br>homepage.</p>
                    </div>
                </div>
            </div>
        </div>

       <!-- Maximum Attempt Modal -->
      <div class="modal fade adjust-modal" id="maximumAttemptsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-body text-center">
                      <div class="d-flex justify-content-end w-100 border-0 me-4 mt-4">
                          <button type="button" class="btn-close custom-close-btn" id="closeMaximumAttemptsModal" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="col-md-12">
                          <img src="images/resc/warning.png" class="py-2" style="width: 30%; height:30%" alt="Warning Logo">
                      </div>
                      <p class="fw-bold fs-4 information-title spacing-4 text-danger">Max Attempts Reached</p>
                      <p class="info-sub">Sorry you've reached the maximum number<br> of attempts to confirm your password. For <br>
                          security reasons, please wait for 30 minutes <br> before trying again.</p>
                  </div>
              </div>
          </div>
      </div>

  </main>
</body>




<?php include_once __DIR__ . '/includes/components/footer.php'; ?>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="scripts/transfer-org.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="scripts/loader.js"></script>


</html>
<?php

} else {
  header("Location: landing-page.php");
}
?>