<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-config.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/classes/csrf-token.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');

SessionManager::checkUserRoleAndRedirect();

$csrf_token = CsrfToken::generateCSRFToken();

// Set a new org value connecting to a diff db
$organization = 'sco';
$config = DatabaseConfig::getOrganizationDBConfig($organization);

// Creates database connection
$connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

$sql = "SELECT email FROM voter";
$result = $connection->query($sql);

$emails = array();

if ($result->num_rows > 0) {
    // Fetch and store all emails in the array
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['email'];
    }
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Check if registration success flag is set in the session
$registration_success = isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
    <link rel="preload" href="images/resc/ivote-icon.png" as="image">
    <link rel="stylesheet" href="styles/loader.css" />
    <link rel="stylesheet" href="styles/core.css" />
    <link rel="stylesheet" href="styles/dist/all-footer.css">
    <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    <link rel="stylesheet" href="styles/dist/register.css">
    <title>Register</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/register.js" defer></script>
    <script src="scripts/loader.js" defer></script>

    <script>
        // Will be used for validation of existing emails 
        const emails = <?php echo json_encode($emails); ?>
        // Will be used to display success modal 
        const registrationSuccess = <?php echo json_encode($registration_success); ?>
    </script>
</head>

<body>

    <?php include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html'); ?>

    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <!-- <a class="navbar-brand" href="#">Your Brand</a> -->
            <img src="images/resc/ivote-icon-2.png" id="ivote-logo-landing-header" alt="ivote-logo">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item fw-medium">
                        <a class="nav-link" href="landing-page.php">Home</a>
                    </li>
                    <li class="nav-item fw-medium">
                        <a class="nav-link" href="about-us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container-fluid mt-4 pt-3 main-reg-container" style="padding-top: 0.8rem">
        <div class="row mt-5 pl-5">
            <div class="col-md-6">
                <form id="register-form" action="includes/registration-inc.php" method="POST"
                    enctype="multipart/form-data">

                    <!-- CSRF Token hidden field -->
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div class="row">
                        <div class="col-12 header-register">
                            <p class="fs-2 fw-bold main-red spacing-6">Get Started</p>
                            <p class="fs-7 fw-semibold main-blue spacing-6">Sign up to start voting</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center mb-0 pb-0">
                        <!-- Displays error message from server-side -->
                        <?php if (isset($error_message)): ?>
                            <div class="fw-medium border border-danger text-danger alert alert-danger alert-dismissible fade show  custom-alert"
                                role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-exclamation-triangle flex-shrink-0 me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                                    <path
                                        d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                </svg>
                                <div class="d-flex align-items-center">
                                    <span class="pe-1"><?php echo $error_message; ?></span>
                                    <button type="button" class="btn-close text-danger" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="register-container">

                        <!-- Email Address -->
                        <div class="row pt-3">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="col-xl-7 col-md-7">
                                    <div class="form-group">
                                        <label for="email" class="fs-8 spacing-3">Email Address<span
                                                class="asterisk fw-medium">*</span></label>
                                        <input type="text" class="form-control pt-2 bg-white text-black" name="email"
                                            id="email" placeholder="Email Address" autocomplete="email" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Select Organization -->
                        <div class="row pt-2">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="col-xl-7 col-md-7">
                                    <label for="org" class="fs-8 spacing-3">Organization<span
                                            class="asterisk fw-medium">*</span></label>
                                    <select class="form-select form-control bg-white text-black"
                                        style="color: red; background-color: blue;" name="org" id="org" required>
                                        <option selected hidden value="">Select Organization</option>
                                        <option value="acap">ACAP</option>
                                        <option value="aeces">AECES</option>
                                        <option value="elite">ELITE</option>
                                        <option value="give">GIVE</option>
                                        <option value="jehra">JEHRA</option>
                                        <option value="jmap">JMAP</option>
                                        <option value="jpia">JPIA</option>
                                        <option value="piie">PIIE</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- COR -->
                        <div class="row pt-2">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="col-xl-7 col-md-7">
                                    <label for="cor" class="fs-8 spacing-3">Certificate of Registration<span
                                            class="asterisk fw-medium"> *</span></label>
                                    <input class="form-control pl-2" style="background-color:#fff" type="file"
                                        name="cor" id="cor" accept=".pdf" max="25MB" required>
                                    <div class="form-text mt-2" style="font-size: 12px;"><span
                                            class="main-blue fw-semibold">Note:</span> Only PDF files up to 25MB are
                                        allowed.</div>
                                </div>
                            </div>
                        </div>


                        <!--Password -->
                        <div class="row pt-2">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="col-xl-7 col-md-7">
                                    <div class="form-group">
                                        <label for="password" class="fs-8 spacing-3">Password <span
                                                class="asterisk fw-medium">*</span></label>
                                        <input type="password" class="form-control pt-2 bg-white text-black"
                                            name="password" id="password" placeholder="Password"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Re-type Password -->
                        <div class="row pt-2">
                            <div class="col-12   d-flex justify-content-end">
                                <div class="col-xl-7 col-md-7 col-lg-7">
                                    <div class="form-group">
                                        <label for="retype-pass" class="fs-8 spacing-3">Re-type password <span
                                                class="asterisk fw-medium">*</span></label>
                                        <input type="password" class="form-control pt-2 bg-white text-black"
                                            id="retype-pass" name="retype-pass" placeholder="Re-type password"
                                        required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agree to Terms & Conditions/Privacy Policy -->
                        <div class="row pt-3">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="col-xl-7 col-md-7">
                                    <div class="form-group">
                                        <div
                                            class="d-flex flex-sm-row flex-md-row flex-lg-row align-items-start align-items-sm-center check-terms-privacy">
                                            <input type="checkbox" value="" id="privacyTerms"
                                                class="mb-sm-0 me-sm-2 me-md-2">
                                            <label class="form-check-label fs-7 mb-0 policy-terms-conditions">
                                                I agree with the
                                                <a role="button" id="termsConditionsLink" class="underline text-primary">Terms &
                                                    Conditions</a>
                                                and
                                                <a role="button" id="privacyTermsLink" class="underline text-primary"> Privacy
                                                    Policy</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sign Up Button -->
                        <div class="row pt-4">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="col-xl-7 col-md-7">
                                    <div id="submit-container">
                                        <button
                                            class="btn btn-primary px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 w-100 text-white"
                                            type="submit" id="sign-up" name="sign-up" disabled>Sign
                                            Up</button>
                                    </div>
                                    <p class="pt-2 fs-7 spacing-8 main-blue-200 text-center">Already have an account? Go
                                        to
                                        <a href="landing-page.php" class="fw-bold main-blue underline">iVOTE</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>

        <div class="col-md-6 d-flex align-items-center">
            <div class="register-img-container">
                <img src="images/resc/voting.png" alt="ivote-register" class="register-img" style="margin-left: 50px">
            </div>
        </div>
    </div>
    </div>

    <!-- Registered Successfully Modal -->
    <div class="modal" id="registerSuccessModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-sm-flex text-end justify-content-end">
                        <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray"
                            onclick="location.href = 'landing-page.php';">
                        </i>
                    </div>
                    <div class="text-center">
                        <div class="col-md-12">
                            <img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <p class="fw-bold success-color spacing-4" id="successTitle">Successfully Registered!</p>
                                <p class="fw-medium spacing-5 pt-2" id="successSubtitle">We'll notify you via email once your account has been
                                    verified.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Only PDF Files Are Allowed Modal -->
    <div class="modal" id="onlyPDFAllowedModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-sm-flex text-end justify-content-end">
                        <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray" id="onlyPDFClose">
                        </i>
                    </div>
                    <div class="text-center">
                        <div class="col-md-12">
                            <img src="images/resc/warning.png" class="warning-icon" alt="Warning Icon">
                        </div>

                        <div class="row">
                            <div class="col-md-12 pb-3 pt-4">
                                <p class="fw-bold danger spacing-4" id="dangerTitle">Only PDF files are allowed</p>
                                <p class="fw-medium spacing-5 pt-2" id="dangerSubtitle">Please also ensure the file is no larger than 25 mb. Let's try that again!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal" id="termsConditionsModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">

                        <div class="row">
                            <div class="col-md-12 pt-4">
                                <p class="fw-bold fs-3 danger spacing-4 px-2 text-start">Terms <span
                                        class="main-blue">and Conditions</span></p>
                                <p class="fw-medium spacing-5 pt-2 px-2 pb-4 text-start fs-7">
                                    <!-- JSON content will be loaded here -->
                                </p>

                                <button
                                    class="btn btn-primary main-blue-bg px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 w-100 text-white"
                                    id="closeTermsConditions">Close</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal" id="privacyPolicyModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">

                        <div class="row">
                            <div class="col-md-12 pt-4">
                                <p class="fw-bold fs-3 danger spacing-4 px-2 text-start">Privacy <span
                                        class="main-blue">Policy</span></p>
                                <p class="fw-medium spacing-5 pt-2 px-2 pb-4 text-start fs-7">
                                    <!-- JSON content will be loaded here -->
                                </p>

                                <button
                                    class="btn btn-primary main-blue-bg px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 w-100 text-white"
                                    id="closePrivacyPolicy">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/all-footer.php'); ?>

</body>

</html>