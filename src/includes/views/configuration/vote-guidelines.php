<?php
include_once 'modals.php';

(new class
{
    use ConfigGuard;
})::generateCSRFToken(time() + (60 * 20));
?>

<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css">
<link rel="stylesheet" href="src/styles/config-voting-guideline.css?v=2">

<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2">
        <?php include_once 'configuration-page-title.php'; ?>
        <div class="">

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
        </div>

        <div class="card-box ">
            <table id="config-table" class="table table-hover" style="width: 100%;">
                <thead>
                    <tr class="d-none">
                        <th></th>
                        <th>Rule</th>
                    </tr>
                </thead>
                <tbody>




                </tbody>

            </table>
        </div>

        <div class="toolbar">
            <div class="tools">
                <label for="deleteButton" id="delete-label" data-bs-toggle="tooltip" data-bs-title="No items selected." data-bs-placement="right">
                    <button type="button" id="delete" class="btn btn-primary del me-2 me-md-3" data-selected="" disabled>
                        <span class="">Delete</span>
                    </button>
                </label>

            </div>
            <span class="save-status d-none">
                <span class="text-uppercase weight-700 save-icon d-none d-md-inline">Note: </span>
                <span class="save-msg text-truncate d-none d-md-inline">Your changes are saved automatically.</span>
            </span>
        </div>

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
    $deleteAction = <<<HTML
                <button type="button" class="btn btn-secondary secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="" class="btn btn-secondary primary" value="true" disabled>Delete</button>
    HTML;
    Modals::getDeleteModal($deleteAction);
    ?>

    <!-- <script>
        warningModal = new bootstrap.Modal(document.getElementById('delete-modal'));
        warningModal.show();
    </script> -->


    <div class="modal fade" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header editor">
                    <h5 class="modal-title">Voting Guideline <span class="guideline-num"></span></h5>
                    <button type="button" class="modal-close" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">


                </div>
            </div>
        </div>

    </div>



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
<script  type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script  type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script  type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script  type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js"></script>
<script  type="module" src="src/scripts/config-vote-guidelines.js?v=2"></script>
<script  type="text/javascript" src="src/scripts/feather.js" defer></script>
    ';
