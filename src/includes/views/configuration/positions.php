<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css">
<link rel="stylesheet" href="src/styles/candidate-position.css">

<main class="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 pt-4">
                <div class="container-fluid">
                    <div class="d-flex">
                        <h4 class="page-title text-truncate mx-auto">Configuration</h2>
                    </div>
                    <div class="">

                        <?php

                        global $configuration_pages;
                        global $link_name;
                        $secondary_nav = new SecondaryNav($configuration_pages, $link_name, true);
                        $secondary_nav->getNavLink();
                        ?>
                    </div>
                    <div class="card-box">
                        <table id="example" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr class="d-none">
                                    <th>Seq</th>
                                    <th>Name</th>
                                    <th>Duties and Responsibilities</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <span class="d-none">1</span>
                                        <span class="fas fa-grip-lines"></span>
                                    </td>
                                    <td>
                                        <input class="text-editable" type="text" name="1" id="1" value="Position" title="" data-bs-toggle="tooltip" data-bs-title="Default tooltip" data-bs-placement="right" placeholder="Enter a candidate position" pattern="[a-zA-Z .\-]{1,50}" required>
                                    </td>
                                    <td class="col-2">
                                        <div class="text-truncate">Lorem </div>
                                    </td>
                                </tr>

                                <?php
                                for ($i = 2; $i < 25; $i++) {
                                    echo "
                                        <tr>
                                        <td>
                                            <span class=\"d-none\">{$i}</span>
                                            <span class=\"fas fa-grip-lines\"></span>
                                        </td>
                                        <td>
                                        <input class=\"text-editable\" type=\"text\" name=\"{$i}\" id=\"{$i}\" value=\"Position\" placeholder=\"Enter a candidate position\" pattern=\"[a-zA-Z .\-]{1,50}\" required>
                                        </td>
                                        <td class=\"\">
                                        <div class=\"text-truncate\">Lorem.</div>
                                    </td>
                                    </tr>
                                    ";
                                }

                                ?>

                            </tbody>

                        </table>


                    </div>


                    <!-- <div id="edit-modal" class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                        hello
                    </div> -->

                    <div class="toolbar">
                        <div class="tools">
                            <button type="button" class="btn btn-primary del me-2 me-md-3" data-selected="" data-bs-toggle="tooltip" data-bs-title="Default tooltip" data-bs-placement="right">
                                <span class="icon trash ">
                                    <i data-feather="trash" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i>
                                </span>
                                <span class="d-none d-sm-inline">Delete</span>
                            </button>
                            <button type="button" class="btn btn-primary del me-2 me-md-3 d-none" data-selected="">
                                <span class="icon trash ">
                                    <i data-feather="trash" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i>
                                </span>
                                <span class="d-none d-sm-inline">Edit</span>
                            </button>
                            <b class="item-count"><span class="count"></span> items selected</b>

                        </div>
                        <span class="save-status">
                            <span class="text-uppercase weight-700 save-icon">Note: </span>
                            <span class="save-msg text-truncate">Your changes are saved automatically.</span>
                        </span>
                    </div>

                </div>
            </div>
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
    ';
