<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css">
<link rel="stylesheet" href="src/styles/candidate-position.css">

<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2 p-4 ">
        <div class="row">
            <?php include_once 'configuration-page-title.php'; ?>
            <div class="px-0">

                <?php

                global $configuration_pages;
                global $link_name;
                $secondary_nav = new SecondaryNav($configuration_pages, $link_name, true);
                $secondary_nav->getNavLink();
                ?>
            </div>
            <div class="card-box px-0">
                <table id="example" class="table table-striped table-hover" style="width:100%; display: none;">
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
                            <span class="icon trash ">
                                <i data-feather="trash" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i>
                            </span>
                            <span class="d-none d-sm-inline">Delete</span>
                        </button>
                    </label>
                    <button type="button" class="btn btn-primary del me-2 me-md-3 d-none" data-selected="">
                        <span class="icon trash ">
                            <i data-feather="trash" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i>
                        </span>
                        <span class="d-none d-sm-inline">Edit</span>
                    </button>
                    <b class="item-count d-none"><span class="count"></span> items selected</b>

                </div>
                <span class="save-status">
                    <span class="text-uppercase weight-700 save-icon d-none d-md-inline">Note: </span>
                    <span class="save-msg text-truncate d-none d-md-inline">Your changes are saved automatically.</span>
                </span>
            </div>
        </div>
    </div>


</main>
<?php
global $page_scripts;
$page_scripts = '
        <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
        <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js"></script>
        <script type="module" src="src/scripts/candidate-position.js"></script>
        <script type="module" src="src/scripts/candidate-position.js"></script>
    ';
