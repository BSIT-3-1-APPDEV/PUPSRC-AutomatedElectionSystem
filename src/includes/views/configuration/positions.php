<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css">
<link rel="stylesheet" href="src/styles/candidate-position.css">

<main class="main">
    <div class="container-fluid h-100 p-0">
        <div class="content-margin">
            <div class="row">
                <h4 class="page-title custom-text-primary col-sm-12 col-lg-auto me-lg-auto"><span class="text-truncate">Candidate</span> Positions</h2>
                    <div class="col-sm-12 col-lgbr-auto d-flex flex-row-reverse">
                        <button class="btn del del-box mt-lg-3">
                            <span class="d-none d-sm-inline-flex me-sm-4">Delete</span>
                            <span class="icon trash px-1 py-3"><i data-feather="trash" width="calc(1rem + 0.5vw)" height="calc(1rem + 0.5vw)"></i></span>
                        </button>
                    </div>
            </div>
            <!-- <div id="result" class="box">
                Event result:
            </div> -->
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
                            <input class="text-editable" type="text" name="" id="" value="Position" placeholder="Enter a candidate position" pattern="[a-zA-Z .\-]{1,50}" required>
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
                        <input class=\"text-editable\" type=\"text\" name=\"\" id=\"\" value=\"Position\" placeholder=\"Enter a candidate position\" pattern=\"[a-zA-Z .\-]{1,50}\" required>
                        </td>
                    </tr>
                        ";
                    }

                    ?>


                </tbody>
            </table>

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
