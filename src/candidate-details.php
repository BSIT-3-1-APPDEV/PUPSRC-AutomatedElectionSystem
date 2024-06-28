<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

include_once FileUtils::normalizeFilePath('includes/error-reporting.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('includes/org-sections.php');


if (isset($_SESSION['voter_id'])) {

    include FileUtils::normalizeFilePath('includes/session-exchange.php');

    // Check if the user's role is either 'Committee Member' or 'Admin Member'
    $allowedRoles = array('head_admin', 'admin');
    if (in_array($_SESSION['role'], $allowedRoles)) {
        $conn = DatabaseConnection::connect();

        // Fetch candidate details
        if (isset($_GET['candidate_id'])) {
            $candidate_id = $_GET['candidate_id'];

            $stmt = $conn->prepare("SELECT c.candidate_id, c.last_name, c.first_name, c.middle_name, c.suffix, c.party_list, c.position_id, p.title as position, c.photo_url, c.program, c.year_level, c.section, c.`candidate_creation` 
                                    FROM candidate c
                                    JOIN position p ON c.position_id = p.position_id 
                                    WHERE c.candidate_id = ?");
            $stmt->bind_param("i", $candidate_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $candidate = $result->fetch_assoc();
            $stmt->close();
        }
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
            <link rel="stylesheet" href="styles/tables.css" />
            <link rel="stylesheet" href="styles/loader.css" />
            <link rel="stylesheet" href="styles/candidate-detail.css" />
            <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        </head>

        <body>

            <?php
            include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
            include FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
            ?>

            <div class="main">
                <div class="container mb-5 ml-5">
                    <div class="row justify-content-center">
                        <div class="col-md-11">
                            <div class="breadcrumbs d-flex">
                                <button type="button" class=" btn-white d-flex align-items-center spacing-8 fs-8">
                                    <i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
                                </button>
                                <button type="button" class="btn-back spacing-8 fs-8" onclick="window.location.href='manage-candidate'">MANAGE CANDIDATES</button>
                                <button type="button" class="btn btn-current rounded-pill spacing-8 fs-8">CANDIDATE
                                    INFORMATION</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10 card-box mt-mx-10">
                            <div class="container-fluid">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <p class="fs-3 main-color fw-bold ls-10 spacing-6">Candidate Details</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-md-4 mx-auto">
                                            <div class="row">
                                                <div class="col-md-12 text-center mx-auto">
                                                    <?php
                                                    // Debugging: Print the constructed file path
                                                    $imagePath = 'user_data/' . $org_name . '/candidate_imgs/' . $candidate['photo_url'];
                                                    ?>
                                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Candidate Photo" class="candidate-image">
                                                    <p class="fw-bold fs-6 pt-sm-2">
                                                        <?php echo strtoupper($candidate['last_name'] . ',' . ' ' . $candidate['first_name'] . ' ' . $candidate['middle_name'] . ' ' . $candidate['suffix']); ?>
                                                    </p>
                                                    <a href="edit-candidate?candidate_id=<?php echo htmlspecialchars($candidate['candidate_id']); ?>" class="button-create rounded-3 btn-sm">Edit Information</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center">
                                            <div class="divider"></div>
                                        </div>
                                        <div class="col-md-5 mt-md-4">
                                            <div class="row">
                                                <div class="col-md-12 mx-auto">
                                                    <!-- Candidacy Position -->
                                                    <p class="fw-bold fs-7 main-color">Candidacy Position</p>
                                                    <p class="fw-bold fs-6"><?php echo htmlspecialchars($candidate['position']); ?></p>
                                                </div>
                                            </div>
                                            <!-- Block Section -->

                                        
                                            <div class="row">
                                                <div class="col-md-12 mx-auto">
                                                    <p class="fw-bold fs-7 main-color">Block Section</p>
                                                    <p class="fw-bold fs-6 pb-2"><?php echo htmlspecialchars($candidate['program'] . ' ' . $candidate['year_level'] . '-' . $candidate['section']); ?></p>
                                                </div>
                                            </div>
                                            <!-- Registered Date -->
                                            <div class="row mb-5">
                                                <div class="col-md-12 mx-auto">
                                                    <p class="fw-bold fs-7 main-color">Registered Date</p>
                                                    <p class="fw-bold fs-6 "><?php echo date('F j, Y', strtotime($candidate['candidate_creation'])); ?></p>
                                                </div>
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
            <script src="scripts/member-form-validation.js"></script>
            <script src="scripts/loader.js" defer></script>

        </body>

        </html>

<?php
    } else {
        header("Location: landing-page");
    }
} else {
    header("Location: landing-page");
}
?>