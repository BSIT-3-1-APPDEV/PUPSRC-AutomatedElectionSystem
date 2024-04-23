<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';
require_once 'includes/classes/session-manager.php';

if (isset($_SESSION['voter_id'])) {

    // ------ SESSION EXCHANGE
    include 'includes/session-exchange.php';
    // ------ END OF SESSION EXCHANGE
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
        <link rel="stylesheet" href="styles/manage-acc.css" />
        <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

    </head>

    <body>

        <?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>


        <div class="main">

            <!-- Use this cardbox -->
            <div class="container-wrapper">
                <div class="container mt-xl-3">
                    <div class="row justify-content-center">
                        <div class="col-md-10 card-box">
                            <div class="container-fluid">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="content">
                                            <!-- CONTENT TO BE PUT HERE -->
                                            <p class="head fs-2 fw-bold main-color pt-xl-3">Put Contents Here</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc id dictum
                                                nulla.
                                                Fusce facilisis consectetur risus, sit amet aliquet metus mattis et. Aenean
                                                et
                                                pharetra urna. Class aptent taciti sociosqu ad litora torquent per conubia
                                                nostra, per inceptos himenaeos. Donec nunc dolor, fringilla a lobortis id,
                                                rutrum tincidunt neque. Mauris tortor ligula, iaculis a tempor vel, ultrices
                                                quis dui. Aenean aliquet eu mi sit amet volutpat.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-wrapper" class="mt-xl-5">
                <div class="container pt-xl-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 card-box">
                            <div class="container-fluid">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="content">
                                            <!-- CONTENT TO BE PUT HERE -->
                                            <p class="head fs-2 fw-bold main-color pt-xl-3">Put Contents Here</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc id dictum
                                                nulla.
                                                Fusce facilisis consectetur risus, sit amet aliquet metus mattis et. Aenean
                                                et
                                                pharetra urna. Class aptent taciti sociosqu ad litora torquent per conubia
                                                nostra, per inceptos himenaeos. Donec nunc dolor, fringilla a lobortis id,
                                                rutrum tincidunt neque. Mauris tortor ligula, iaculis a tempor vel, ultrices
                                                quis dui. Aenean aliquet eu mi sit amet volutpat.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-wrapper" class="mt-xl-5">
                <div class="container pt-xl-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 card-box">
                            <div class="container-fluid">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="content">
                                            <!-- CONTENT TO BE PUT HERE -->
                                            <p class="head fs-2 fw-bold main-color pt-xl-3">Put Contents Here</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc id dictum
                                                nulla.
                                                Fusce facilisis consectetur risus, sit amet aliquet metus mattis et. Aenean
                                                et
                                                pharetra urna. Class aptent taciti sociosqu ad litora torquent per conubia
                                                nostra, per inceptos himenaeos. Donec nunc dolor, fringilla a lobortis id,
                                                rutrum tincidunt neque. Mauris tortor ligula, iaculis a tempor vel, ultrices
                                                quis dui. Aenean aliquet eu mi sit amet volutpat.</p>
                                        </div>
                                    </div>
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
    header("Location: landing-page.php");
}
?>