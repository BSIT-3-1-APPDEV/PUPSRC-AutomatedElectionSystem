<link rel="stylesheet" href="vendor/plugin/air-datepicker/dist/css/datepicker.min.css">
<link rel="stylesheet" href="src/styles/election-year.css">

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

        <section class="card-box ">
            <div class="card-header">
                <h3 class="text-capitalize">Select new election year</h3>
            </div>
            <div class="card-body">
                <div class="form">
                    <input type="text" class="col-8 col-sm-6 col-md-5 col-lg-4 col-xxl-3" id="year-picker" readonly>
                    <button type="button" class="btn btn-success btn-lg mx-auto">Save Changes</button>
                </div>

            </div>


        </section>
    </div>
</main>
<?php
global $page_scripts;
$page_scripts = '
<script src="vendor/plugin/air-datepicker/dist/js/datepicker.min.js"></script>
<script src="vendor/plugin/air-datepicker/dist/js/i18n/datepicker.en.js"></script>
<script src="src/scripts/election-year.js"></script>
    ';
