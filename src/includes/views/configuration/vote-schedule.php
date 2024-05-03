<link rel="stylesheet" href="src/styles/election-schedule.css">
<?php require_once FileUtils::normalizeFilePath('includes/error-reporting.php'); ?>

<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2 p-4 ">
        <?php include_once 'configuration-page-title.php'; ?>
        <div class=" ">

            <?php

            global $configuration_pages;
            global $link_name;
            $secondary_nav = new SecondaryNav($configuration_pages, $link_name, true);
            $secondary_nav->getNavLink();
            ?>
        </div>
        <table id="example" class="table table-hover" style="width: 100%;">
            <thead>
                <tr class="">
                    <th></th>
                    <th>Section</th>
                    <th>Schedule</th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $org_name;
                $org_name = strtoupper($org_name);
                for ($i = 0; $i < 6; ++$i) {
                    echo "
                        <tr class=\"\">
                            <td></td>
                            <td>
                            " . (isset($org_name) ? $org_name : "Section") . rand(1, 5) . "-" . rand(1, 3) . "
                            </td>
                            <td>Schedule</td>
                        </tr>
                    ";
                }

                ?>



            </tbody>

        </table>



    </div>
</main>
<?php
global $page_scripts;
$page_scripts = '
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/select/2.0.1/js/dataTables.select.js"></script>
<script src=" https://cdn.datatables.net/select/2.0.1/js/select.bootstrap5.js"></script>
<script type="module" src="src/scripts/election-schedule.js"></script>
    ';
