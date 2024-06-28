<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

include_once FileUtils::normalizeFilePath('includes/error-reporting.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('includes/org-sections.php');


if (isset($_SESSION['voter_id'])) {
    include FileUtils::normalizeFilePath('includes/session-exchange.php');

    $allowedRoles = array('head_admin', 'admin');
    if (in_array($_SESSION['role'], $allowedRoles)) {
        $conn = DatabaseConnection::connect();

        // Fetch candidate details
        if (isset($_GET['candidate_id'])) {
            $candidate_id = $_GET['candidate_id'];

            $stmt = $conn->prepare("SELECT c.candidate_id, c.last_name, c.first_name, c.middle_name, c.suffix, c.party_list, c.position_id, p.title as position, c.photo_url, c.program, c.section, c.year_level, c.`candidate_creation` 
                                    FROM candidate c
                                    JOIN position p ON c.position_id = p.position_id 
                                    WHERE c.candidate_id = ?");
            $stmt->bind_param("i", $candidate_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $candidate = $result->fetch_assoc();
            $stmt->close();
        }

        // Fetch all positions
        $positions_stmt = $conn->prepare("SELECT position_id, title FROM position");
        $positions_stmt->execute();
        $positions_result = $positions_stmt->get_result();
        $positions = [];
        while ($row = $positions_result->fetch_assoc()) {
            $positions[] = $row;
        }
        $positions_stmt->close();
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
            <title>Edit Candidate</title>

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
            <link rel="stylesheet" href="styles/edit-candidates.css" />
            <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="scripts/loader.js" defer></script>

            <style>
                .modal-content {
                    border-radius: 20px;
                }

                .modal-header {
                    text-align: center;
                    /* Center the content */
                }

                .modal-header i {
                    font-size: 7rem;
                    /* Make the icon bigger */
                    display: block;
                    /* Ensure the icon is on its own line */
                    margin: auto;
                }

                .modal-body {
                    text-align: center;
                }

                .modal-body h2 {
                    color: #333;
                    font-size: 10px;
                    margin-bottom: 5px;
                }

                .modal-body p {
                    font-size: 18px;
                    color: #555;
                    margin-bottom: 5px;

                }

                .modal-footer {
                    justify-content: center;
                    border-top: none;

                }

                .cancel {
                    background-color: lightgray;
                    color: #B2BEB5;
                    font-weight: bold;
                    padding: 5px 18px;
                }

                .discard-button {
                    background-color: #FFA500;
                    color: #fff;
                    padding: 5px 28px;
                    font-weight: 600;
                }

                /* Button Styles */
                .close {
                    font-size: 1.4rem;
                    color: #aaa;
                    opacity: 1;
                }

                .close:hover {
                    color: #000;
                    opacity: 1;
                }
            </style>

        </head>

        <body>

            <?php
            include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
            include FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
            ?>

            <div class="main">
                <div class="container mb-5 ps-5">
                    <div class="row justify-content-center">
                        <div class="col-md-11">
                            <div class="breadcrumbs d-flex">
                                <button type="button" class="btn-white d-flex align-items-center spacing-8 fs-8">
                                    <i data-feather="users" class="white im-cust feather-2xl"></i> MANAGE USERS
                                </button>
                                <button type="button" class="btn-back spacing-8 fs-8" onclick="window.location.href='manage-candidate'">MANAGE CANDIDATES</button>
                                <button type="button" class="btn btn-current rounded-pill spacing-8 fs-8">EDIT CANDIDATE
                                    INFORMATION</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container" id="candidateForm">
                    <div class="row justify-content-center">
                        <div class="col-md-10 card-box mt-md-10">
                            <div class="container-fluid">
                                <div class="card-box">
                                    <form action="../src/submission_handlers/update-candidate.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="candidate_id" value="<?php echo htmlspecialchars($candidate['candidate_id']); ?>">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h3 class="form-title fs-3">Candidate Details</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 text-center mt-5 mx-auto">
                                                <div class="image-upload-wrapper">
                                                    <label for="file-input">
                                                        <?php
                                                        // Debugging: Print the constructed file path
                                                        $imagePath = 'user_data/' . $org_name . '/candidate_imgs/' . $candidate['photo_url'];
                                                        ?>
                                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Candidate Photo" class="candidate-image">
                                                        <div class="overlay">
                                                            <i class="fas fa-camera fa-2x"></i>
                                                        </div>
                                                    </label>
                                                    <input id="file-input" type="file" name="photo" class="form-control" style="display: none;" accept=".jpg, .jpeg, .png">
                                                </div>
                                            </div>
                                            <div class="col-md-9 mt-4">
                                                <div class="row">
                                                    <div class="col-md-3 mx-auto">
                                                        <div class="form-group local-group">
                                                            <label for="last_name" class="login-danger">Last Name <span class="required"> * </span></label>
                                                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($candidate['last_name']); ?>" class="form-control" placeholder="Carpena" required pattern="^[a-zA-Z]+$" maxlength="20">
                                                            <span class="error-message" id="last_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mx-auto">
                                                        <div class="form-group">
                                                            <label for="first_name" class="login-danger">First Name <span class="required"> * </span></label>
                                                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($candidate['first_name']); ?>" class="form-control" placeholder="E.g. Trizia Mae" required pattern="^[a-zA-Z]+$" maxlength="50">
                                                            <span class="error-message" id="first_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mx-auto">
                                                        <div class="form-group">
                                                            <label for="middle_name" class="login-danger">Middle Name</label>
                                                            <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($candidate['middle_name']); ?>" class="form-control" placeholder="E.g. Santiago" maxlength="20">
                                                            <span class="error-message" id="middle_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="suffix" class="login-danger">Suffix</label>
                                                            <select id="suffix" name="suffix">
                                                                <option value="" class="disabled-option" disabled selected>E.g.
                                                                    II</option>
                                                                <option value="" <?php if ($candidate['suffix'] == '')
                                                                                        echo 'selected'; ?>>No Suffix</option>
                                                                <option value="II" <?php if ($candidate['suffix'] == 'II')
                                                                                        echo 'selected'; ?>>II</option>
                                                                <option value="III" <?php if ($candidate['suffix'] == 'III')
                                                                                        echo 'selected'; ?>>III</option>
                                                                <option value="IV" <?php if ($candidate['suffix'] == 'IV')
                                                                                        echo 'selected'; ?>>IV</option>
                                                                <option value="V" <?php if ($candidate['suffix'] == 'V')
                                                                                        echo 'selected'; ?>>V</option>
                                                            </select>
                                                            <span class="error-message" id="suffix_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="position" class="login-danger">Position <span class="required"> * </span></label>
                                                            <select id="position" name="position_id" required>
                                                                <option value="" class="disabled-option" disabled selected>
                                                                    Select Position</option>
                                                                <?php foreach ($positions as $position) : ?>
                                                                    <option value="<?php echo $position['position_id']; ?>" <?php if ($candidate['position_id'] == $position['position_id'])
                                                                                                                                echo 'selected'; ?>>
                                                                        <?php echo htmlspecialchars($position['title']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <span class="error-message" id="position_error"></span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    // Define the program based on org_name
                                                    $program = '';
                                                    $programs = null; // Ensure programs is defined

                                                    switch ($org_name) {
                                                        case 'acap':
                                                            $program = 'BSP';
                                                            break;
                                                        case 'aeces':
                                                            $program = 'BSECE';
                                                            break;
                                                        case 'elite':
                                                            $program = 'BSIT';
                                                            break;
                                                        case 'give':
                                                            // GIVE has multiple programs
                                                            $programs = ['BSED-FL', 'BSED-ENG', 'BSED-MT', 'BSED-HE'];
                                                            break;
                                                        case 'jehra':
                                                            $program = 'BSBA-HRM';
                                                            break;
                                                        case 'jmap':
                                                            $program = 'BSBA-MM';
                                                            break;
                                                        case 'jpia':
                                                            // JPIA has multiple programs
                                                            $programs = ['BSA', 'BSMA'];
                                                            break;
                                                        case 'piie':
                                                            $program = 'BSIE';
                                                            break;
                                                        case 'sco':
                                                            // No need to set program, it will be handled separately
                                                            break;
                                                        default:
                                                            // Handle unknown org_name, if needed
                                                            break;
                                                    }
                                                    ?>
                                                    <div class="col-md-6 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="section" class="login-danger fs-7">Block Section<span class="required"> *</span></label>
                                                            <select id="section" name="section" onmousedown="if(this.options.length>3){this.size=3;}" onchange='this.size=0;' onblur="this.size=0;" required style="opacity: 0.5">
                                                                <option value="" class="disabled-option" disabled selected hide>Select Block Section</option>
                                                                <?php
                                                                if ($org_name === 'sco') {
                                                                    // Handle the special case for SCO
                                                                    foreach ($org_sections as $program => $years) {
                                                                        foreach ($years as $year_level => $sections) {
                                                                            foreach ($sections as $section) {
                                                                                $value = htmlspecialchars($year_level) . '-' . htmlspecialchars($section);
                                                                                $selected = ($candidate['year_level'] == $year_level && $candidate['section'] == $section) ? 'selected' : '';
                                                                                echo "<option value=\"$value\" $selected>" . htmlspecialchars($program) . " $value</option>";
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (isset($programs)) {
                                                                        // Handle org_names with multiple programs
                                                                        foreach ($programs as $prog) {
                                                                            foreach ($org_sections[$prog] as $year_level => $sections) {
                                                                                foreach ($sections as $section) {
                                                                                    $value = htmlspecialchars($year_level) . '-' . htmlspecialchars($section);
                                                                                    $selected = ($candidate['program'] == $prog && $candidate['year_level'] == $year_level && $candidate['section'] == $section) ? 'selected' : '';
                                                                                    echo "<option value=\"$value\" $selected>" . htmlspecialchars($prog) . " $value</option>";
                                                                                }
                                                                            }
                                                                        }
                                                                    } else {
                                                                        // Handle org_names with a single program
                                                                        if (!empty($program)) {
                                                                            foreach ($org_sections[$program] as $year_level => $sections) {
                                                                                foreach ($sections as $section) {
                                                                                    $value = htmlspecialchars($year_level) . '-' . htmlspecialchars($section);
                                                                                    $selected = ($candidate['program'] == $program && $candidate['year_level'] == $year_level && $candidate['section'] == $section) ? 'selected' : '';
                                                                                    echo "<option value=\"$value\" $selected>" . htmlspecialchars($program) . " $value</option>";
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="error-message" id="section_error"></span>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mx-auto">
                                                <button type="cancel" class="cancel-button">Cancel</button>
                                                <button type="submit" class="save-button" id="button">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript for image change functionality -->
            <script>
                document.getElementById('file-input').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const fileType = file.type;
                        const validImageTypes = ['image/jpeg', 'image/png'];
                        if (!validImageTypes.includes(fileType)) {
                            alert('Only JPG and PNG files are allowed.');
                            event.target.value = ''; // Clear the input
                        } else {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.querySelector('.candidate-image').src = e.target.result;
                            }
                            reader.readAsDataURL(file);
                        }
                    }
                });

                $(document).ready(function() {
                    feather.replace();

                    // Detect changes in form fields
                    var isDirty = false;
                    var targetUrl = '';

                    $('#candidateForm input, #candidateForm select').on('change', function() {
                        isDirty = true;
                        $('.submit-btn').prop('disabled', false);
                    });

                    // Handle form submission
                    $('#candidateForm').on('submit', function() {
                        isDirty = false;
                    });

                    // Handle cancel button click
                    $('.cancel-button').on('click', function(e) {
                        if (isDirty) {
                            e.preventDefault();
                            $('#warningModal').modal('show');
                        } else {
                            window.location.href = 'manage-candidate';
                        }
                    });

                    // Handle leave button click in the modal
                    $('#leaveButton').on('click', function() {
                        isDirty = false;
                        window.removeEventListener('beforeunload', showWarningModal);
                        window.location.href = targetUrl;
                    });

                    $('.modal .cancel').on('click', function() {
                        $('#warningModal').modal('hide');
                    });

                    function showWarningModal(e) {
                        if (isDirty) {
                            e.preventDefault();
                            $('#warningModal').modal('show');
                            return ''; // Required for some browsers to show the modal
                        }
                    }

                    window.addEventListener('beforeunload', showWarningModal);

                    $(document).on('click', 'a', function(e) {
                        if (isDirty) {
                            e.preventDefault();
                            targetUrl = $(this).attr('href');
                            $('#warningModal').modal('show');
                        }
                    });
                });
            </script>

            <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
            <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>
            <script src="scripts/script.js"></script>
            <script src="scripts/feather.js"></script>
            <script src="scripts/edit-candidate-form-validation.js"></script>

            <!-- Warning Modal -->
            <div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-header">
                                <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                            </div>
                            <div class="mb-2">
                                <b>
                                    <p class="fs-2 pt-2">you have<br>pending changes</p>
                                </b>
                                <b>
                                    <p class="mb-4">Discard changes?</p>
                                </b>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn cancel" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn discard-button" id="leaveButton">Discard</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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