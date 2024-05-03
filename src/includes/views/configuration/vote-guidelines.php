<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css">
<link rel="stylesheet" href="src/styles/candidate-position.css">
<link rel="stylesheet" href="src/styles/voting-guideline.css">

<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2 p-4 ">
        <?php include_once 'configuration-page-title.php'; ?>
        <div class="">

            <?php

            global $configuration_pages;
            global $link_name;
            $secondary_nav = new SecondaryNav($configuration_pages, $link_name, true);
            $secondary_nav->getNavLink();
            ?>
        </div>

        <div class="card-box ">
            <table id="example" class="table table-hover" style="width: 100%;">
                <thead>
                    <tr class="d-none">
                        <th></th>
                        <th>Rule</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < 6; ++$i) {
                        echo "
                        <tr class=\"\">
                            <td></td>
                            <td>
                             Voting Rule " . $i + 1 . "
                            </td>
                        </tr>
                    ";
                    }

                    ?>



                </tbody>

            </table>
        </div>

        <div class="toolbar">
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
            <span class="save-status d-none">
                <span class="text-uppercase weight-700 save-icon d-none d-md-inline">Note: </span>
                <span class="save-msg text-truncate d-none d-md-inline">Your changes are saved automatically.</span>
            </span>
        </div>
    </div>
</main>
<?php
global $page_scripts;
$page_scripts = '
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js"></script>
<script src="src/scripts/config-vote-guidelines.js"></script>
    ';
