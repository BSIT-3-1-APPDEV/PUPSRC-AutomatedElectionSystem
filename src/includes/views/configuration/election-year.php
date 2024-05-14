<link rel="stylesheet" href="vendor/plugin/air-datepicker/dist/css/datepicker.min.css">
<link rel="stylesheet" href="src/styles/config-election-year.css?v=2">

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

        <section class="card-box ">
            <div class="card-header">
                <h3 class="text-capitalize">Select new election year</h3>
            </div>
            <div class="card-body">

                <div id="curr-year-container" class="w-100">
                    <h4 class="mx-auto"></h4>
                </div>
                <div class="form">
                    <input type="text" class="col-8 col-sm-6 col-md-5 col-lg-4 col-xxl-3" id="year-picker" placeholder="Year" aria-placeholder="Change Election Year">
                    <!-- <cds-icon shape="calendar" solid="true"></cds-icon> -->
                    <label for="save-button" id="save-button-label" data-bs-toggle="tooltip" data-bs-title="No changes made." data-bs-placement="right">
                        <button type="button" id="save-button" class="btn btn-success mx-auto">Save Changes</button>
                    </label>
                </div>

            </div>


        </section>
    </div>
</main>
<?php
global $page_scripts;
$page_scripts = '
<script type="text/javascript" src="vendor/plugin/air-datepicker/dist/js/datepicker.min.js"></script>
<script type="text/javascript" src="vendor/plugin/air-datepicker/dist/js/i18n/datepicker.en.js"></script>
<script type="module" src="src/scripts/config-election-year.js?v=2"></script>
<script  type="text/javascript" src="src/scripts/feather.js" defer></script>
    ';
