<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" />
<link rel="stylesheet" href="src/styles/config-candidate-position.css?v=2">


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
            <table id="example" class="table table-hover" style="width:100%; display: none;">
                <thead>
                    <tr class="d-none">
                        <th>Seq</th>
                        <th>Name</th>
                        <th>Duties and Responsibilities</th>
                    </tr>
                </thead>
                <tbody>




                </tbody>

            </table>


        </div>




        <div class="toolbar" style="display : none;">
            <div class="tools">
                <label for="deleteButton" id="delete-label" data-bs-toggle="tooltip" data-bs-title="No items selected." data-bs-placement="right">
                    <button type="button" id="delete" class="btn btn-primary del me-2 me-md-3" data-selected="" disabled>
                        <span class="">Delete</span>
                    </button>
                </label>
                <button type="button" class="btn btn-primary del me-2 me-md-3 d-none" data-selected="">
                    <span class="icon trash ">
                        <i data-feather="trash"></i>
                    </span>
                    <span class="d-none d-sm-inline">Edit</span>
                </button>
                <b class="item-count d-none"><span class="count"></span> items selected</b>

            </div>
            <span class="save-status d-none">
                <span class="text-uppercase weight-700 save-icon d-none d-md-inline">Note: </span>
                <span class="save-msg text-truncate d-none d-md-inline">Your changes are saved automatically.</span>
            </span>
        </div>
    </div>

    <dialog class="modal-native" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title"><span class="position-name"></span></h5>
                    <button type="button" class="modal-close" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="affected candidate-list">

                    </div>


                </div>
            </div>
        </div>

    </dialog>




</main>
<?php
global $phpDateTimeNow;
global $page_scripts;

$phpDateTimeNow->printDatetimeTzJS();

$page_scripts = '
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
<script type="module" src="src/scripts/config-candidate-position.js?v=2" defer></script>
<script  type="text/javascript"src="src/scripts/feather.js" defer></script>
    ';
