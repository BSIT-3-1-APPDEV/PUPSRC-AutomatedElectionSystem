<?php
include_once 'modals.php';

(new class
{
    use ConfigGuard;
})::generateCSRFToken(time() + (60 * 20));
?>

<link rel="stylesheet" href="src/styles/config-ballot-form.css?v=2">

<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2">
        <?php include_once 'configuration-page-title.php'; ?>
        <section class=" ">
            <nav>
                <?php
                global $requested_basepage;
                $route_link;
                if (isset($requested_basepage) && !empty($requested_basepage)) {
                    $route_link = $requested_basepage;
                } else {
                    $route_link = true;
                }
                global $configuration_pages;
                global $link_name;
                $secondary_nav = new SecondaryNav($configuration_pages, $link_name,  $route_link);
                $secondary_nav->getNavLink();
                ?>
            </nav>
        </section>

        <section class="card-box ">
            <div class="">
                <div class="list-group ballot-form" id="items">
                    <div class="list-group-item">
                        <div class="handle">
                            <span class="fas fa-grip-lines"></span>
                        </div>
                        <div class="field-item ">
                            <div class="me-auto">Student Name</div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                            </div>
                        </div>
                        <div class="field-item ">
                            <div class="me-auto">Section</div>
                            <div class=" form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" checked>
                                <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item ">
                        <div class="handle">
                            <span class="fas fa-grip-lines"></span>
                        </div>
                        <div class="field-item ">
                            <div class="me-auto">Candidate Form</div>
                            <div class=" form-check form-switch d-none">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" checked>
                                <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item ">
                        <div class="handle">
                            <span class="fas fa-grip-lines"></span>
                        </div>
                        <div class="field-item ">
                            <div class="me-auto">Candidate Form2</div>
                        </div>
                    </div>
                    <div class="list-group-item ">
                        <div class="handle">
                            <span class="fas fa-grip-lines"></span>
                        </div>
                        <div class="field-item ">
                            <div class="me-auto">Candidate Form3</div>
                        </div>
                    </div>
                </div>
                <div class="list-group-item add-item">
                    <div class="field-item ">
                        <div class="">
                            <button class="btn btn-primary text-capitalize">
                                Add input field
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="toast-container pe-md-3 pe-lg-5 pe-sm-2">
                <!-- <div class="toast-body text-bg-danger">
                    <div class="toast-content">The end date cannot be before the start date.</div>
                    <div><button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button></div>
                </div> -->
            </div>
        </section>
    </div>



</main>

<section class="modals-container">
    <?php
    // $deleteAction = <<<HTML
    //             <button type="button" class="btn btn-secondary secondary" data-bs-dismiss="modal">Cancel</button>
    //             <button type="button" id="" class="btn btn-secondary primary">Delete</button>
    // HTML;
    // Modals::getDeleteModal(true, 'Schedule set successfully', 'The date and time for this election year have been successfully set.', $deleteAction);
    ?>

    <!-- <script>
        warningModal = new bootstrap.Modal(document.getElementById('delete-modal'));
        warningModal.show();
    </script> -->



</section>

<?php
global $page_scripts;
$page_scripts = '
<script  type="module" src="src/scripts/config-ballot-form.js?v=2"></script>
<script  type="text/javascript" src="src/scripts/feather.js" defer></script>
    ';
