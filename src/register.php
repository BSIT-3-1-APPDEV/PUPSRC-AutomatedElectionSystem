<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-config.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');
SessionManager::checkUserRoleAndRedirect();

$organization = 'sco';

// Retrieves database configuration based on the organization name
$config = DatabaseConfig::getOrganizationDBConfig($organization);

// Creates database connection
$connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

// Checks for connection errors
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT email FROM voter";
$result = $connection->query($sql);

// Array to hold all emails
$emails = array();

if ($result->num_rows > 0) {
    // Fetch and store all emails in the array
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['email'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">

    <link rel="stylesheet" href="styles/core.css" />
    <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    <link rel="stylesheet" href="styles/dist/register.css">
    <title>Register</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg" id="login-navbar">
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <a href="landing-page.php"><img src="images/resc/ivote-icon-2.png" id="ivote-logo-landing-header"
                    alt="ivote-logo"></a>
        </div>
    </nav>


    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-6">
                <form id="register-form" action="includes/registration-process.php" method="POST"
                    enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-12 header-register">
                            <p class="fs-2 fw-bold main-red spacing-6">Get Started</p>
                            <p class="fs-7 fw-semibold main-blue spacing-6">Sign up to start voting</p>
                        </div>
                    </div>


                    <!-- Email Address -->
                    <div class="row pt-3">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="form-group col-7">
                                <label class="fs-8 spacing-3">Email Address<span
                                        class="asterisk fw-medium">*</span></label>
                                <input type="text" class="form-control pt-2 bg-primary text-black" name="email"
                                    id="email" placeholder="Email Address" required>
                            </div>
                        </div>
                    </div>


                    <!-- Select Organization -->
                    <div class="row pt-2">
                        <div class="col-12   d-flex justify-content-end">
                            <div class="col-7  ">
                                <label class="fs-8 spacing-3">Organization<span
                                        class="asterisk fw-medium">*</span></label>
                                <select class="form-select form-control bg-primary text-black"
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


                    <!--Password -->
                    <div class="row pt-2">
                        <div class="col-12   d-flex justify-content-end">
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="fs-8 spacing-3">Password <span
                                            class="asterisk fw-medium">*</span></label>
                                    <input type="password" class="form-control pt-2 bg-primary text-black"
                                        name="password" id="password" placeholder="Password" required>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Re-type Password -->
                    <div class="row pt-2">
                        <div class="col-12   d-flex justify-content-end">
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="fs-8 spacing-3">Re-type password <span
                                            class="asterisk fw-medium">*</span></label>
                                    <input type="password" class="form-control pt-2 bg-primary text-black"
                                        id="retype-pass" name="retype-pass" placeholder="Re-type password" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COR -->
                    <div class="row pt-2">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="fs-8 spacing-3">Certificate of Registration<span
                                            class="asterisk fw-medium"> *</span></label>
                                    <input class="form-control form-control-sm pl-2" style="background-color:#EDEDED"
                                        type="file" name="cor" id="cor" accept=".pdf" max="25MB" required>
                                    <small class="form-text text-muted">Only PDF files up to 25MB are allowed.</small>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Select Organization -->
                    <div class="row pt-4">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="col-7">
                                <div id="submit-container">
                                    <button
                                        class="btn btn-primary px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 w-100"
                                        type="submit" id="sign-up" name="sign-up" value="approve" disabled>Sign
                                        Up</button>
                                </div>
                                <p class="pt-2 fs-7 spacing-8 main-blue-200 text-center">Already have an account? Go to
                                    <a href="landing-page.php" class="fw-bold main-blue underline">iVOTE</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <div class="register-img-container">
                    <img src="images/resc/voting.png" alt="ivote-register" class="register-img"
                        style="margin-left: 50px">
                </div>
            </div>
        </div>
    </div>

    
    <!-- CODE FOR TESTING ONLY. Remove the comment if no longer needed.
        
        <button class="del-no-border px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6" id="reject-btn" data-toggle="modal"
        data-target="#onlyPDFAllowedModal">Try Only PDF files Button</button> -->


    <!-- LIST OF MODALS -->

    <!-- Registered Successfully Modal -->
    <div class="modal" id="approvalModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray"
                            onclick="redirectToPage('landing-page.php')" id="close-modal">
                        </i>
                    </div>
                    <div class="text-center">
                        <div class="col-md-12">
                            <img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
                        </div>

                        <div class="row">
                            <div class="col-md-12 pb-3">
                                <p class="fw-bold fs-3 success-color spacing-4">Successfully Registered!</p>
                                <p class="fw-medium spacing-5">We'll notify you via email once your account has been
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
                    <div class="d-flex justify-content-end">
                        <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray" onclick="closeModal()">
                        </i>
                    </div>
                    <div class="text-center">
                        <div class="col-md-12">
                            <img src="images/resc/warning.png" alt="Warning Icon">
                        </div>

                        <div class="row">
                            <div class="col-md-12 pb-3 pt-4">
                                <p class="fw-bold fs-3 danger spacing-4 px-2">Only PDF files are allowed</p>
                                <p class="fw-medium spacing-5 pt-2 px-5 ">Please also ensure the file is no larger than
                                    25 mb.
                                    Let's try that again!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include_once __DIR__ . '/includes/components/all-footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/register.js"></script>
    <?php
    // Check if registration success flag is set in the session
    if (isset($_SESSION['registrationSuccess']) && $_SESSION['registrationSuccess'] === true) {
        // Display JavaScript to trigger the modal
        echo '<script>
        $(document).ready(function() {
            $("#approvalModal").modal("show");
        });
        </script>';
        unset($_SESSION['registrationSuccess']);
    }
    ?>

    <script>document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            const orgSelect = document.getElementById('org');
            const passwordInput = document.getElementById('password');
            const retypePassInput = document.getElementById('retype-pass');
            const corInput = document.getElementById('cor');
            const submitBtn = document.getElementById('submit-container');
            const submitButton = document.getElementById('sign-up');
            const form = document.getElementById('register-form');

            emailInput.addEventListener('input', function () {
                validateEmail(emailInput);

                checkFormValidity();
            });

            orgSelect.addEventListener('input', function () {
                validateOrg(orgSelect);
                checkFormValidity();
            });

            passwordInput.addEventListener('input', function () {
                validatePassword(passwordInput);
                validateRetypePassword(retypePassInput, passwordInput);
                checkFormValidity();
            });

            retypePassInput.addEventListener('input', function () {

                validateRetypePassword(retypePassInput, passwordInput);
                checkFormValidity();
            });

            corInput.addEventListener('input', function () {
                validateCOR(corInput);
                checkFormValidity();
            });

            submitBtn.addEventListener('mouseover', function () {
                checkEmptyFields(); // Show errors on hover
            });


            form.addEventListener('submit', function (event) {
                if (!validateForm()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });

            function validateEmail(input) {
                const emailValue = input.value.trim();
                const errorElement = input.nextElementSibling;
                const emails = <?php echo json_encode($emails); ?>;

                const isValidFormat = validateEmailFormat(emailValue);
                const isExistingEmail = emails.includes(emailValue);

                if (!isValidFormat) {
                    showError(input, errorElement, 'Please enter a valid email address.');
                    return false; // Return false if email format is invalid
                } else if (isExistingEmail) {
                    showError(input, errorElement, 'Email address already exists.');
                    return false; // Return false if email already exists
                } else {
                    clearError(input, errorElement);
                    return true; // Return true if email format is valid and does not exist
                }
            }

            function validateEmailFormat(email) {
                const allowedDomains = ['@gmail.com', '@iskolarngbayan.pup.edu.ph'];
                const domainMatch = allowedDomains.some(domain => email.endsWith(domain));
                return domainMatch && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }




            function validateOrg(input) {
                const orgValue = input.value;
                const errorElement = input.nextElementSibling;

                if (orgValue === '') {
                    showError(input, errorElement, 'Please select an organization.');
                } else {
                    clearError(input, errorElement);
                }
            }

            function validatePassword(input) {
                const passwordValue = input.value;
                const errorElement = input.nextElementSibling;

                const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;
                if (!passwordRegex.test(passwordValue)) {
                    showError(input, errorElement, 'Password must be 8-20 characters long with letters, numbers, and symbols.');
                } else {
                    clearError(input, errorElement);
                }
            }


            function validateRetypePassword(input, originalPasswordInput) {
                const retypePassValue = input.value;
                const errorElement = input.nextElementSibling;

                if (retypePassValue !== originalPasswordInput.value) {
                    showError(input, errorElement, 'Passwords do not match.');
                } else {
                    clearError(input, errorElement);
                }
            }

            function validateCOR(input) {
                const corValue = input.value;
                const errorElement = input.nextElementSibling;

                if (corValue === '') {
                    showError(input, errorElement, 'Please upload your Certificate of Registration.');
                } else {
                    clearError(input, errorElement);
                }
            }

            function showError(input, errorElement, message) {
                if (!errorElement) {
                    const newErrorElement = document.createElement('div');
                    newErrorElement.className = 'error-message';
                    input.parentNode.appendChild(newErrorElement);
                    errorElement = newErrorElement; // Update errorElement to the newly created element
                }
                errorElement.textContent = message;
                input.classList.add('error-border');
            }

            function clearError(input, errorElement) {
                if (errorElement) {
                    errorElement.textContent = '';
                    input.classList.remove('error-border');
                }
            }

            function validateForm() {
                return validateEmailFormat(emailInput.value.trim()) &&
                    orgSelect.value !== '' &&
                    passwordInput.value.length >= 8 &&
                    retypePassInput.value === passwordInput.value &&
                    corInput.value !== '';
            }
            function checkEmptyFields() {
                const inputs = [emailInput, orgSelect, passwordInput, retypePassInput, corInput];

                inputs.forEach(input => {
                    if (input.value.trim() === '') {
                        showError(input, input.nextElementSibling, 'This field is required.');
                    }
                });
            }
            function clearErrors() {
                // Select all error messages and remove them
                const errorMessages = document.querySelectorAll('.error-message');
                errorMessages.forEach(message => message.remove());

                // Remove the error border from all input fields
                const inputFields = document.querySelectorAll('.error-border');
                inputFields.forEach(input => input.classList.remove('error-border'));
            } function checkFormValidity() {
                const emailValue = emailInput.value.trim();
                const orgValue = orgSelect.value;
                const passwordValue = passwordInput.value;
                const retypePassValue = retypePassInput.value;
                const corValue = corInput.value;
                const emailValid = validateEmailFormat(emailValue);
                const orgValid = orgValue !== '';
                const passwordValid = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/.test(passwordValue); // Validate password directly with regex
                const retypePassValid = retypePassValue === passwordValue;
                const corValid = corValue !== '';

                const allFieldsValid = emailValid && orgValid && passwordValid && retypePassValid && corValid;

                if (allFieldsValid) {
                    submitButton.removeAttribute('disabled');
                } else {
                    submitButton.setAttribute('disabled', 'disabled');
                }
            }


        });



    </script>



</body>

</html>