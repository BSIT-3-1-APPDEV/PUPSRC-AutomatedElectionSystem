<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');

if (isset($_SESSION['voter_id'])) {

    include 'includes/session-exchange.php';
    $allowedRoles = array('Committee Member','Admin Member');
    if (in_array($_SESSION['role'], $allowedRoles)) {
        include 'submission_handlers/add-member.php';
        ?>
        

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
            <title>Manage Account</title>

            <!-- Icons -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
            <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

            <!-- Styles -->
            <link rel="stylesheet" href="<?php echo 'styles/orgs/' . $org_name . '.css'; ?>" id="org-style">
            <link rel="stylesheet" href="styles/style.css" />
            <link rel="stylesheet" href="styles/core.css" />
            <link rel="stylesheet" href="styles/admin-creation.css" />
            <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


        </head>

        <body>


            <?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>

            <div class="main">
                <div class="container mb-5 pl-5">
                    <div class="row justify-content-center">
                        <div class="col-md-11">
                            <div class="breadcrumbs d-flex">
                                <button type="button" class="btn btn-lvl-white d-flex align-items-center spacing-8 fs-8">
                                    <i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
                                </button>
                                <button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">ADD
                                    COMMITTEE</button>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12 card-box card-box-larger mt-md-10">
                            <div class="container-fluid">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2 class="form-title">Create Admin Account</h2>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-4 mx-auto">
                                                    <div class="form-group local-forms">
                                                        <label for="last_name" class="login-danger">Last Name <span
                                                                class="required"> * </span> </label>
                                                        <input type="text" id="last_name" name="last_name"
                                                            placeholder="Enter Last Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-4 mx-auto">
                                                    <div class="form-group local-forms">
                                                        <label for="first_name" class="login-danger">First Name<span
                                                                class="required"> * </span> </label>
                                                        <input type="text" id="first_name" name="first_name"
                                                            placeholder="Enter First Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-3 mx-auto">
                                                    <div class="form-group local-forms">
                                                        <label for="middle_name" class="login-danger">Middle Name<span
                                                                class="required"> * </span> </label>
                                                        <input type="text" id="middle_name" name="middle_name"
                                                            placeholder="Enter Middle Name" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group local-forms">
                                                        <label for="email" class="login-danger">Email<span class="required"> *
                                                            </span> </label>
                                                        <input type="email" id="email" name="email" placeholder="Email"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="form-group local-forms shorter-form-group">
                                                        <label for="role" class="login-danger">Role<span class="required"> *
                                                            </span> </label>
                                                        <select id="role" name="role" required>
                                                            <option value="Admin Member">Admin Member</option>
                                                            <option value="Committee Member">Committee Member</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <div class="d-flex flex-row">
                                                    <button type="reset" class="reset-button">Reset Form</button>
                                                    <button type="submit" value="Submit" class="button-create">Create
                                                        Account</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
            <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="scripts/script.js"></script>
            <script src="scripts/feather.js"></script>




        </body>


        </html>

        <?php
    } else {
        // User is not authorized to access this page
        echo "You don't have permission to access this page.";
    }
} else {
    header("Location: landing-page.php");
}
?>