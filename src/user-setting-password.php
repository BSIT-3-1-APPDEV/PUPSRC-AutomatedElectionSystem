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
        <title>Change Password</title>
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
        <link rel="stylesheet" href="styles/user-setting-password.css" />
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
                                    <div class="d-flex align-items-center pt-2 pb-4">
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
                                    <div class="main-color d-flex align-items-center pb-4">
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
                                            <i data-feather="lock" class="fas fa-exchange-alt me-4" style="font-size: 1rem;"></i>Change Password
                                        </b>
                                    </h5>
                                    <div id="section-1" style="align-items: center; justify-content: center;">
                                        <div class="pb-3">
                                            <div class="des" style="justify-self: auto;">To proceed with changing your password, please enter your current password.</div>
                                        </div>
                                        <div class="row mt-5 mb-3 reset-pass">
                                            <div class="col-md-8 mb-0 mt-0 position-relative">
                                                <div class="input-group" id="reset-password">
                                                    <input type="password" class="form-control reset-password-password" onkeypress="return avoidSpace(event)" id="password_confirmation" name="password_confirmation" placeholder="Enter your current password" required>
                                                    <label for="password_confirmation" class="new-password main-color translate-middle-y" id="scoSignUP">CURRENT PASSWORD</label>
                                                    <button class="btn btn-secondary reset-password-password" type="button" id="reset-password-toggle-2">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="password-mismatch-error" class="text-danger" style="display: none;">Incorrect password. Please try again.</div>
                                        </div>
                                        <div class="col-md-12 reset-pass">
                                            <button class="login-sign-in-button main-bg-color mt-5 mb-4" type="button" name="new-password-submit" onclick="window.location.href='setting-password-reset.php';">Confirm</button>
                                        </div>
                                        <br>
                                        <br>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    const passwordInput = document.getElementById('changepassword_confirmation');
                    const toggleButton = document.getElementById('reset-password-toggle-2');

                    // Show the toggle button when the password input is focused
                    passwordInput.addEventListener('focus', () => {
                        toggleButton.style.display = 'block';
                    });

                    // Hide the toggle button when the password input loses focus
                    passwordInput.addEventListener('blur', () => {
                        toggleButton.style.display = 'none';
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
        <script src="scripts/change-password.js"></script>
        <script src="scripts/loader.js"></script>
    </body>

    </html>

<?php
} else {
    header("Location: landing-page.php");
}
?>