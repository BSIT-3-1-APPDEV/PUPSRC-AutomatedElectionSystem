<?php
include_once 'modals.php';

(new class
{
    use ConfigGuard;
})::generateCSRFToken(time() + (60 * 20));
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" />
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
                <div class="list-group ballot-form" id="sortableForms">
                    <div class="list-group-item" id="" data-id="b-field-1">
                        <div class="handle">
                            <span class="fas fa-grip-lines"></span>
                        </div>
                        <div class="field-item ">
                            <input class="me-auto form-name default" value="Student Name" readonly disabled>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="checkbox-stud-name">
                                <label class="form-check-label" for="checkbox-stud-name"></label>
                            </div>
                        </div>
                        <div class="field-item ">
                            <input class="me-auto form-name default" value="Section" readonly disabled>
                            <div class=" form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="checkbox-section">
                                <label class="form-check-label" for="checkbox-section"></label>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item" id="" data-id="b-field-2">
                        <div class="handle">
                            <span class="fas fa-grip-lines"></span>
                        </div>
                        <div class="field-item ">
                            <input class="me-auto form-name default" value="Candidate Form" readonly disabled>
                            <!-- <div class="me-auto form-name default">Candidate Form</div> -->
                            <div class=" form-check form-switch d-none">
                                <input class="form-check-input" type="checkbox" role="switch" id="checkbox-candidates">
                                <label class="form-check-label" for="checkbox-candidates"></label>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="list-group-item " id="" data-id="b-field-4">
                        <div class="handle">
                            <span class="fas fa-grip-lines"></span>
                        </div>
                        <div class="field-item ">
                            <div class="field-item-header">
                                <div class="field-name-form col-8 col-md-6">

                                    <div class="">
                                        <div id="b-field-4-name" class="ql-container">

                                        </div>
                                        <div id="b-field-4-name-toolbar" class="ql-toolbar ql-snow">
                                            <button class="ql-bold"><i data-feather="bold"></i></button>
                                            <button class="ql-italic"><i data-feather="italic"></i></button>
                                            <button class="ql-underline"><i data-feather="underline"></i></button>
                                            <button class="ql-link"><i data-feather="link-2"></i></button>
                                            <button class="ql-clean">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" id="format-clear">
                                                    <path fill="none" d="M0 0h24v24H0V0z" fill="currentColor"></path>
                                                    <path d="M20 8V5H6.39l3 3h1.83l-.55 1.28 2.09 2.1L14.21 8zM3.41 4.86L2 6.27l6.97 6.97L6.5 19h3l1.57-3.66L16.73 21l1.41-1.41z" fill="currentColor"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <select class="field-type-form col-12 col-md-4" name="" id="b-field-4-type">
                                </select>

                                <div class=" col-1">
                                    <div class=" form-check form-switch  ">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkbox-candidates">
                                        <label class="form-check-label" for="checkbox-candidates"></label>
                                    </div>
                                </div>


                                <div class="field-desc-form col-12">
                                    <div class="col-12 col-md-6">

                                        <div id="b-field-4-desc" class="ql-container">

                                        </div>
                                        <div id="b-field-4-desc-toolbar" class="ql-toolbar ql-snow">
                                            <button class="ql-bold"><i data-feather="bold"></i></button>
                                            <button class="ql-italic"><i data-feather="italic"></i></button>
                                            <button class="ql-underline"><i data-feather="underline"></i></button>
                                            <button class="ql-link"><i data-feather="link-2"></i></button>
                                            <button class="ql-clean">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" id="format-clear">
                                                    <path fill="none" d="M0 0h24v24H0V0z" fill="currentColor"></path>
                                                    <path d="M20 8V5H6.39l3 3h1.83l-.55 1.28 2.09 2.1L14.21 8zM3.41 4.86L2 6.27l6.97 6.97L6.5 19h3l1.57-3.66L16.73 21l1.41-1.41z" fill="currentColor"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" field-action">
                                <div class="btn-group" role="group" aria-label="Field Menu Button">
                                    <button class="btn btn-secondary">
                                        <i data-feather="copy"></i>
                                    </button>
                                    <button class="btn btn-secondary">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> -->
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

    <script>
    </script>



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

<script>
    const setCSRFToken = () => {
        try {
            return `<?= $_SESSION['csrf']['token']; ?>`;
        } catch (error) {

        }
    };
</script>

<?php
global $phpDateTimeNow;
global $page_scripts;

$phpDateTimeNow->printDatetimeTzJS();
$page_scripts = '
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
<script  type="module" src="src/scripts/config-ballot-form.js?v=2"></script>
<script  type="text/javascript" src="src/scripts/feather.js" defer></script>
    ';
