<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');

include_once FileUtils::normalizeFilePath('includes/error-reporting.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');

// Function to validate image file type and size
function validateImage($file) {
    $validTypes = ['image/jpeg', 'image/png'];
    $maxSize = 500000; // 500KB
    return in_array($file['type'], $validTypes) && $file['size'] <= $maxSize;
}

if (isset($_SESSION['voter_id'])) {

    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // Check if the user is authorized
    $allowedRoles = array('head_admin', 'admin');
    if (in_array($_SESSION['role'], $allowedRoles)) {
        // Connect to the database
        $conn = DatabaseConnection::connect();

        // Process form data and insert into the database
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Assuming you have sanitized the input, you can retrieve the form data like this:
            $last_name = $_POST['last_name'];
            $first_name = $_POST['first_name'];
            $middle_name = $_POST['middle_name'];
            $suffix = $_POST['suffix'];
            $party_list = $_POST['party_list'];
            $position_id = $_POST['position_id'];
            $section = $_POST['section'];
            $year_level = $_POST['year_level'];
            $photo = $_FILES['photo'];

            // Handling file upload for photo_url
            $target_dir = "images/candidate-profile/";
            $photo_url = $target_dir . basename($photo["name"]);

            // Validate image file
            if (!validateImage($photo)) {
                echo "Invalid image file. Please upload a JPG or PNG file (max size 500KB).";
            } elseif (move_uploaded_file($photo["tmp_name"], $photo_url)) {
                // Insert the data into the database
                $stmt = $conn->prepare("INSERT INTO candidate (last_name, first_name, middle_name, suffix, party_list, position_id, photo_url, section, year_level, `candidate-creation`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssssiiss", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $photo_url, $section, $year_level);
                $stmt->execute();
                $stmt->close();
                echo "Candidate added successfully.";
            } else {
                echo "Error uploading image.";
            }
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
                <link rel="stylesheet" href="styles/candidate-creation.css" />
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
                                        <i data-feather="users" class="white im-cust feather-2xl"></i> CANDIDATES
                                    </button>
                                    <button type="button" class="btn btn-lvl-current rounded-pill spacing-8 fs-8">ADD
                                        CANDIDATE</button>
                                    <div class = "align-items-end ms-auto me-4 mx-auto">
                                        <button type="button" class="btn btn-lvl-current rounded-2 fs-7" onclick="duplicateForm()">
                                            <i class = "bi bi-plus-circle me-3"></i>Add Another Candidate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="candidate-form">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-10 card-box mt-md-10">
                                <div class="container-fluid">
                                    <div class="card-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h3 class="form-title">Add Candidate</h3>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="last_name" class="login-danger">Last Name <span
                                                                    class="required"> * </span> </label>
                                                            <input type="text" id="last_name" name="last_name"
                                                                placeholder="E.g. Carpena" required pattern="^[a-zA-Z]+$"
                                                                maxlength="20">
                                                            <span class="error-message" id="last_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="first_name" class="login-danger">First Name<span
                                                                    class="required"> * </span> </label>
                                                            <input type="text" id="first_name" name="first_name"
                                                                placeholder="E.g. Trizia Mae" required pattern="^[a-zA-Z]+$"
                                                                maxlength="50">
                                                            <span class="error-message" id="first_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="middle_name" class="login-danger">Middle Name</label>
                                                            <input type="text" id="middle_name" name="middle_name"
                                                                placeholder="E.g. Santiago" maxlength="20">
                                                            <span class="error-message" id="middle_name_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="suffix" class="login-danger">Suffix</label>
                                                                <select id="suffix" name="suffix" required style="opacity: 0.5">
                                                                    <option value="suffix" class="disabled-option" disabled selected>E.g. II</option>
                                                                    <option value="II">II</option>
                                                                    <option value="III">III</option>
                                                                    <option value="IV">IV</option>
                                                                    <option value="V">V</option>
                                                                </select>
                                                            <span class="error-message" id="suffix_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row pt-4">
                                                    <div class="col-md-4 col-sm-4 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="position" class="login-danger">Position<span class="required"> *</span></label>
                                                            <select id="position" name="position_id" required style="opacity: 0.5">
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
                                                            <span class="error-message" id="position_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="section" class="login-danger">Block Section<span class="required"> *
                                                                </span> </label>
                                                                <select id="section" name="section" required style="opacity: 0.5">
                                                                <option value="section" class="disabled-option" disabled selected>Select Block Section</option>
                                                                    <?php
                                                                    $year_levels = array('1', '2', '3', '4', '5');
                                                                    $sections = array('1', '2', '3', '4');
                                                                    foreach ($year_levels as $year_level) {
                                                                        foreach ($sections as $section) {
                                                                            echo '<option value="' . $year_level . '-' . $section . '">' . $year_level . '-' . $section . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                            </select>
                                                            <span class="error-message" id="section_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-3 mx-auto">
                                                        <div class="form-group local-forms">
                                                            <label for="photo" class="login-danger">Photo<span class="required"> * </span> </label>
                                                            <div class="input-group">
                                                                <input type="file" id="photo" name="photo" accept=".jpg, .png" required onchange="displayFileName(this)" style="opacity: 0.5">  
                                                            </div>
                                                            <span class="error-message" id="photo_error"></span>
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="d-flex flex-md-row flex-column justify-content-end align-items-center">
                                                    <button type="reset" class="reset-button">Reset Form</button>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class = "d-flex justify-content-center mb-5 pl-5">
                        <button type="submit" value="Submit" class="button-create mb-2 mb-md-2">Submit</button>
                    </div>
                </form>
            </div>

            <script>

                function displayFileName(input) {
                    const fileInput = document.getElementById('photo');
                    const filenameContainer = document.getElementById('photo_filename');
                    
                    if (fileInput.files.length > 0) {
                        filenameContainer.textContent = fileInput.files[0].name;
                    } else {
                        filenameContainer.textContent = '';
                    }
                }

            </script>
        

                <?php include_once __DIR__ . '/includes/components/footer.php'; ?>
                <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
                <script src="scripts/script.js"></script>
                <script src="scripts/feather.js"></script>
                <script src="scripts/candidate-form-validation.js"></script>

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
            </body>


            </html>

            <script>
                $(document).ready(function () {
                    var createdModal = new bootstrap.Modal(document.getElementById('createdModal'), {});

                    <?php if (isset($_SESSION['account_created']) && $_SESSION['account_created']) { ?>
                        // Show the created modal
                        createdModal.show();

                        // Reload the page after a short delay
                        setTimeout(function () {
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
                        const formContainer = document.getElementById('candidate-form');
                        const clonedForm = formContainer.cloneNode(true);

                        // Reset form inputs
                        clonedForm.querySelectorAll('input, select').forEach(input => {
                            input.value = '';
                        });

                        // Add close button inside the cloned form container
                        const closeButton = document.createElement('button');
                        closeButton.innerHTML = 'Close';
                        closeButton.className = 'btn btn-secondary d-flex justify-content-center close-button';
                        closeButton.addEventListener('click', function() {
                            formContainer.parentElement.removeChild(clonedForm);
                            formContainer.parentElement.removeChild(hrWrapper);
                            formCount--;
                            if (formCount === 1) {
                                // Remove the last horizontal line if no forms are left
                                formContainers.pop().remove();
                            }
                        });

                        // Create a div for the close button and position it
                        const closeButtonWrapper = document.createElement('div');
                        closeButtonWrapper.className = 'close-button-wrapper';
                        closeButtonWrapper.appendChild(closeButton);

                        clonedForm.prepend(closeButtonWrapper);

                        clonedForm.querySelectorAll('input, select').forEach(input => {
                            const originalId = input.id;
                            const originalName = input.name;
                            const newId = originalId.replace(/\d+/g, '') + formCount;
                            const newName = originalName.replace(/\d+/g, '') + formCount;
                            input.id = newId;
                            input.name = newName;
                            input.required = true; // Make sure inputs are required

                            // Add event listeners to the new inputs and selects
                            if (input.type === 'text') {
                                input.addEventListener('input', validateInput);
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
                    } else {
                        alert('Maximum limit. You can only add up to 5 candidates at a time')
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