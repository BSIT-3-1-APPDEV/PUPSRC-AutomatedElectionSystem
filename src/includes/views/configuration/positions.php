<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css">
<link rel="stylesheet" href="src/styles/candidate-position.css">

<main class="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 pt-4">
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
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <span class="d-none">1</span>
                                        <span class="fas fa-grip-lines"></span>
                                    </td>
                                    <td>
                                        <input class="text-editable" type="text" name="1" id="1" value="Position" placeholder="Enter a candidate position" pattern="[a-zA-Z .\-]{1,50}" required>
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
                                    </tr>
                                    ";
                                }

                                ?>

                            </tbody>

                        </table>


                    </div>

                    <div class="toolbar">
                        <button type="button" class="btn btn-primary del" data-selected="">
                            <span class="icon trash ">
                                <i data-feather="trash" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i>
                            </span>
                            <span class="d-none d-sm-inline">Delete</span>
                        </button>
                        <small><span class="text-uppercase weight-700">Note:</span> Your changes are saved automatically.</small>
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
