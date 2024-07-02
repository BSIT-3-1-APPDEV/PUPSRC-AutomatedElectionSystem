    <?php

    include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

    include_once FileUtils::normalizeFilePath('includes/error-reporting.php');
    require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
    require_once FileUtils::normalizeFilePath('includes/classes/csrf-token.php');
    require_once FileUtils::normalizeFilePath('includes/session-handler.php');
    require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
    require_once FileUtils::normalizeFilePath('includes/org-sections.php');

    $csrf_token = CsrfToken::generateCSRFToken();

    if (isset($_SESSION['voter_id'])) {

        include FileUtils::normalizeFilePath('includes/session-exchange.php');
        $allowedRoles = array('head_admin', 'admin');
        if (in_array($_SESSION['role'], $allowedRoles)) {
            $conn = DatabaseConnection::connect();
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
                <link rel="stylesheet" href="styles/loader.css" />
                <link rel="stylesheet" href="styles/candidate-creation.css" />
                <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


            </head>

            <body>

                <?php
                include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html');
                include FileUtils::normalizeFilePath(__DIR__ . '/includes/components/sidebar.php');
                ?>

                <div class="main">
                    <div class="container mb-5 ps-5 breadcrumbs-cont">
                        <div class="row justify-content-center">
                            <div class="col-md-11">
                                <div class="breadcrumbs d-flex flex-wrap justify-content-center justify-content-md-start">
                                    <button type="button" class="btn btn-lvl-white d-flex align-items-center spacing-8 fs-8">
                                        <i data-feather="users" class="white im-cust feather-2xl"></i> <span class="hide-text">CANDIDATES</span>
                                    </button>
                                    <button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">ADD
                                        CANDIDATE</button>
                                    <div class="align-items-end ms-auto me-4 mx-auto mt-3 mt-md-0">
                                        <button type="button" class="button-add rounded-2 fs-7" onclick="duplicateForm()">
                                            <i class="bi bi-plus-circle me-3"></i>Add Another Candidate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="../src/submission_handlers/insert-candidates.php" method="post" id="candidate-form" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-10 card-box mt-md-10" id="form-container">
                                    <?php
                                    $currentYear = date("Y");
                                    $nextYear = $currentYear + 1;
                                    $election_year = $currentYear . '-' . $nextYear;
                                    ?>
                                    <input type="hidden" name="election_year" value="<?php echo htmlspecialchars($election_year); ?>">

                                    <div class="container">
                                        <div class="card-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <p class="form-title fs-4">Add Candidate</p>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row d-flex justify-content-around">
                                                <div class="row ">
                                                    <div class="col-md-3 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="last_name" class="login-dange fs-7">Last Name <span class="required"> * </span> </label>
                                                            <input type="text" id="last_name" name="last_name[]" placeholder="E.g. Carpena" required pattern="^[a-z ,.'-]+$/i" maxlength="20">
                                                            <span class="error-message" id="last_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="first_name" class="login-danger fs-7">First Name<span class="required"> * </span> </label>
                                                            <input type="text" id="first_name" name="first_name[]" placeholder="E.g. Trizia Mae" required pattern="^[a-z ,.'-]+$/i" maxlength="50">
                                                            <span class="error-message" id="first_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="middle_name" class="login-danger fs-7">Middle Name</label>
                                                            <input type="text" id="middle_name" name="middle_name[]" placeholder="E.g. Santiago" maxlength="20">
                                                            <span class="error-message" id="middle_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="suffix" class="login-danger fs-7">Suffix</label>
                                                            <select id="suffix" name="suffix[]" style="opacity: 0.5">
                                                                <option value="suffix" class="disabled-option" disabled selected>E.g. Jr</option>
                                                                <option value="">No suffix</option>
                                                                <option value="Jr">Jr</option>
                                                                <option value="Sr">Sr</option>
                                                                <option value="II">II</option>
                                                                <option value="III">III</option>
                                                                <option value="IV">IV</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row pt-4">
                                                    <div class="col-md-4 col-sm-4 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="position" class="login-danger fs-7">Position<span class="required"> *</span></label>
                                                            <select id="position" name="position_id[]" required style="opacity: 0.5">
                                                                <option value="position" class="disabled-option" disabled selected>Select Position</option>
                                                                <?php
                                                                $positionQuery = "SELECT position_id, title FROM position";
                                                                $positionStmt = $conn->prepare($positionQuery);
                                                                $positionStmt->execute();
                                                                $positions = $positionStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                                                $positionStmt->close();
                                                                foreach ($positions as $position) {
                                                                    echo '<option value="' . $position['position_id'] . '">' . $position['title'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="section" class="login-danger fs-7">Block Section<span class="required"> *</span></label>
                                                            <select id="section" name="section[]" required style="opacity: 0.5">
                                                                <option value="" class="disabled-option" disabled selected hide>Select Block Section</option>
                                                                <?php
                                                                // Define the program based on org_name
                                                                $program = '';
                                                                $programs = [];

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
                                                                        // SCO has special handling
                                                                        break;
                                                                    default:
                                                                        // Handle unknown org_name, if needed
                                                                        break;
                                                                }

                                                                if ($org_name === 'sco') {
                                                                    // Handle the special case for SCO
                                                                    foreach ($org_sections as $program => $year_levels) {
                                                                        foreach ($year_levels as $year_level => $sections) {
                                                                            foreach ($sections as $section) {
                                                                                echo '<option value="' . htmlspecialchars($program) . '-' . htmlspecialchars($year_level) . '-' . htmlspecialchars($section) . '">' . htmlspecialchars($program) . ' ' . htmlspecialchars($year_level) . '-' . htmlspecialchars($section) . '</option>';
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (!empty($programs)) {
                                                                        // Handle org_names with multiple programs
                                                                        foreach ($programs as $program) {
                                                                            foreach ($org_sections[$program] as $year_level => $sections) {
                                                                                foreach ($sections as $section) {
                                                                                    echo '<option value="' . htmlspecialchars($program) . '-' . htmlspecialchars($year_level) . '-' . htmlspecialchars($section) . '">' . htmlspecialchars($program) . ' ' . htmlspecialchars($year_level) . '-' . htmlspecialchars($section) . '</option>';
                                                                                }
                                                                            }
                                                                        }
                                                                    } elseif (!empty($program)) {
                                                                        // Handle org_names with a single program
                                                                        foreach ($org_sections[$program] as $year_level => $sections) {
                                                                            foreach ($sections as $section) {
                                                                                echo '<option value="' . htmlspecialchars($program) . '-' . htmlspecialchars($year_level) . '-' . htmlspecialchars($section) . '">' . htmlspecialchars($program) . ' ' . htmlspecialchars($year_level) . '-' . htmlspecialchars($section) . '</option>';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>




                                                    <div class="col-md-4 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="photo" class="login-danger fs-7">Photo<span class="required"> * </span> </label>
                                                            <div class="input-group">
                                                                <input type="file" id="photo" name="photo[]" class="photo" accept=".jpg, .png, .jpeg" required onchange="validatephoto(this)" style="opacity: 0.5">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-md-row flex-column justify-content-end align-items-center">
                                                    <button type="reset" class="reset-button" onclick="resetForm(this.closest('container-form'))">Reset Form</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mb-5 pl-5">
                            <button type="submit" value="Submit" class="button-create mb-2 mb-md-2">Submit</button>
                        </div>
                    </form>
                </div>
                <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
                <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
                <script src="scripts/script.js"></script>
                <script src="scripts/feather.js"></script>
                <script src="scripts/candidate-form-validation.js"></script>
                <script src="scripts/loader.js" defer></script>

                <!-- Created Modal -->
                <div class="modal" id="createdModal" tabindex="-1" role="dialog" <?php if (isset($_SESSION['account_created']) && $_SESSION['account_created'])
                                                                                        echo 'data-show="true"'; ?>>
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="text-center">
                                    <div class="col-md-12">
                                        <img src="images/resc/check-animation.gif" class="check-perc" alt="iVote Logo">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 pb-3">
                                            <p class="fw-bold fs-3 success-color spacing-4">Successfully Created!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Only Image Files Are Allowed Modal -->
                <div class="modal fade" id="onlyImageAllowedModal" data-bs-keyboard="false" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="d-flex justify-content-end">
                                    <i class="fa fa-solid fa-circle-xmark fa-xl close-mark light-gray" id="onlyImageClose">
                                    </i>
                                </div>
                                <div class="text-center">
                                    <div class="col-md-12">
                                        <img src="images/resc/warning.png" alt="Warning Icon">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 pb-3 pt-4">
                                            <p class="fw-bold fs-3 danger spacing-4 px-2">Only Image files are allowed</p>
                                            <p class="fw-medium spacing-5 pt-2 px-5 ">Please also ensure the image is no larger than
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

                <!-- Created Maximum Modal -->
                <div class="modal fade" id="errorModal" data-bs-keyboard="false" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="text-center">
                                    <div class="modal-header">
                                        <div class="col-md-12 pt-5">
                                            <img src="images/resc/yellow-warning.png" alt="Warning Icon">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 pb-3 pt-4">
                                            <p class="fw-bold fs-3 text-warning spacing-4">Maximum limit reached.</p>
                                            <p class="fw-medium spacing-5">You can only add up to 5 candidates at a time.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </body>

            </html>

            <script>
                function closeModal() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById("onlyImageAllowedModal"));
                    modal.hide();
                }

                document.getElementById("onlyImageClose").addEventListener("click", function() {
                    closeModal();
                });

                function showModal(modalId) {
                    const modal = new bootstrap.Modal(document.getElementById(modalId));
                    modal.show();
                }

                function validatephoto(input) {
                    const file = input.files[0];
                    if (!file) return;

                    const fileName = file.name;
                    const fileExtension = fileName.split(".").pop().toLowerCase();

                    // Check if file is a valid image extension
                    if (!["png", "jpg", "jpeg"].includes(fileExtension)) {
                        input.value = "";
                        showModal("onlyImageAllowedModal");
                        return;
                    }

                    // Check if file size exceeds 25MB
                    const fileSizeInMB = file.size / (1024 * 1024);
                    if (fileSizeInMB > 25) {
                        input.value = "";
                        showModal("onlyImageAllowedModal");
                        return;
                    }

                    // Display file name if valid
                    displayFileName(input);
                }

                function displayFileName(input) {
                    const fileName = input.files[0].name;
                    // Display file name logic here
                }


                $(document).ready(function() {
                    var createdModal = new bootstrap.Modal(document.getElementById('createdModal'), {});

                    <?php if (isset($_SESSION['account_created']) && $_SESSION['account_created']) { ?>
                        // Show the created modal
                        createdModal.show();

                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 3000); // 3 seconds

                        // Reset the session variable
                        <?php unset($_SESSION['account_created']); ?>
                    <?php } ?>
                });

                let formCount = 1;
                let formContainers = [];

                function duplicateForm() {
                    if (formCount < 5) {
                        formCount++;
                        const formContainer = document.getElementById('form-container');
                        const clonedForm = formContainer.cloneNode(true);

                        // Reset form inputs
                        clonedForm.querySelectorAll('input').forEach(input => {
                            input.value = '';
                        });

                        // Add close button inside the cloned form container
                        const closeButton = document.createElement('button');
                        closeButton.innerHTML = `
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                                    </svg>`;
                        closeButton.className = 'close-button';
                        closeButton.addEventListener('click', function() {
                            formContainer.parentElement.removeChild(clonedForm);
                            formContainer.parentElement.removeChild(hrWrapper);
                            formCount--;
                            if (formCount === 1) {
                                // Remove the last horizontal line if no forms are left
                                formContainers.pop().remove();
                            }
                        });
                        // Append the close button to the cloned form container
                        clonedForm.style.position = 'relative';
                        clonedForm.appendChild(closeButton);

                        // Create a div for the close button and position it
                        const closeButtonWrapper = document.createElement('div');
                        closeButtonWrapper.className = 'close-button-wrapper';
                        closeButtonWrapper.appendChild(closeButton);

                        clonedForm.prepend(closeButtonWrapper);

                        clonedForm.querySelectorAll('input, select, hidden').forEach(input => {
                            const originalId = input.id;
                            const originalName = input.name;
                            const newId = originalId.replace(/\d+/g, '') + formCount;
                            const newName = originalName;
                            input.id = newId;
                            input.name = newName;

                            // Add event listeners to the new inputs and selects
                            if (input.type === 'text') {
                                input.addEventListener('input', validateInput);
                                input.addEventListener('input', toggleSubmitButton);
                            } else if (input.tagName === 'SELECT') {
                                input.addEventListener('change', toggleSubmitButton);
                            }
                        });

                        // Add a wrapper div for centering the horizontal line
                        const hrWrapper = document.createElement('div');
                        hrWrapper.style.display = 'flex';
                        hrWrapper.style.justifyContent = 'center'; // Center the content horizontally
                        formContainer.parentElement.appendChild(hrWrapper);

                        // Add the horizontal line inside the wrapper
                        const hr = document.createElement('hr');
                        hr.style.width = '50%'; // Set the width to 50%
                        hr.style.padding = '20px'; // Add padding
                        hr.style.marginTop = '50px';
                        hr.style.borderWidth = '3px';
                        hrWrapper.appendChild(hr);

                        formContainer.parentElement.appendChild(clonedForm);
                        formContainers.push(clonedForm);

                        // Scroll to the newly cloned form
                        clonedForm.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    } else {

                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {});
                        errorModal.show();

                        setTimeout(function() {
                            errorModal.hide();
                        }, 2000); // 2 seconds

                    }
                    if (formCount === 1) {
                        // Remove all additional form containers    
                        const additionalForms = document.querySelectorAll('.candidate-form:not(:first-child)');
                        additionalForms.forEach(form => form.remove());

                        // Remove all additional horizontal lines
                        const additionalHrWrappers = document.querySelectorAll('.candidate-form:not(:first-child) > div');
                        additionalHrWrappers.forEach(wrapper => wrapper.remove());
                    }
                }

                $("#warningClose").click(function() {
                    closeModal();
                });
            </script>

    <?php
        } else {
            // User is not authorized to access this page
            header("Location: landing-page.php");
        }
    } else {
        header("Location: landing-page.php");
    }
    ?>