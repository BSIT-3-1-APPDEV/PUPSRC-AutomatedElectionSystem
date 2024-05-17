<style>
    table tbody td[colspan="3"] {
        text-align: center;
    }
</style>
<link rel="preload" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" as="style" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="preload" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" as="style" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="vendor/plugin/air-datepicker/dist/css/datepicker.min.css">
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" /> -->
<link rel="stylesheet" href="src/styles/config-election-schedule.css?v=2">


<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2">
        <?php include_once 'configuration-page-title.php'; ?>
        <div class=" ">

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
        <table id="example" class="table table-hover card-box" style="width: 100%;">
            <thead>
                <tr class="">
                    <th></th>
                    <th>Section</th>
                    <th>Schedule</th>
                </tr>
            </thead>
            <tbody>

                <tr class="">
                    <td colspan="3">Fetching Data...</td>
                    <!-- <td></td>
                    <td></td> -->
                </tr>

            </tbody>

        </table>


    </div>
</main>
<?php
global $page_scripts;
$page_scripts = '
<script  type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/luxon/2.3.1/luxon.min.js"></script>
<script  type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script  type="text/javascript" src="https://cdn.datatables.net/select/2.0.1/js/dataTables.select.js"></script>
<script  type="text/javascript" src=" https://cdn.datatables.net/select/2.0.1/js/select.bootstrap5.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="vendor/plugin/air-datepicker/dist/js/datepicker.min.js"></script>
<script type="text/javascript" src="vendor/plugin/air-datepicker/dist/js/i18n/datepicker.en.js"></script>
<script  type="module" src="src/scripts/config-election-schedule.js?v=2"></script>
<script  type="text/javascript"src="src/scripts/feather.js" defer></script>


    ';
