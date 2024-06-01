<link rel="stylesheet" href="vendor/plugin/air-datepicker/dist/css/datepicker.min.css">
<link rel="stylesheet" href="src/styles/config-election-schedule.css?v=2">

<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2">
        <?php include_once 'configuration-page-title.php'; ?>
        <section>
            <nav class="">

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
            </nav>
        </section>

        <section>
            <label for="datetime-start">from</label>
            <div class="datetime" id="datetime-start">
                <input type="datetime-local" min="2017-04-01" max="2017-04-30">
                <div class="form-alert date">&nbsp;</div>
                <input type="time">
                <div class="form-alert time">&nbsp;</div>
                <input type="text" name="" id="" placeholder="This will be hidden" readonly>
            </div>

            <label for="datetime-end">to</label>
            <div class="datetime" id="datetime-end">
                <input type="date" min="2017-04-01" max="2017-04-30">
                <div class="form-alert date">&nbsp;</div>
                <input type="time">
                <div class="form-alert time">&nbsp;</div>
                <input type="text" name="" id="" placeholder="This will be hidden" readonly>
            </div>

            <button type="button">Save Changes</button>
        </section>

    </div>
</main>
<?php
global $phpDateTimeNow;
global $page_scripts;

$phpDateTimeNow->printDatetimeTzJS();
$page_scripts = '
<script type="text/javascript" src="vendor/plugin/air-datepicker/dist/js/datepicker.min.js"></script>
<script type="text/javascript" src="vendor/plugin/air-datepicker/dist/js/i18n/datepicker.en.js"></script>
<script type="module" src="src/scripts/config-election-schedule.js?v=2"></script>
<script  type="text/javascript" src="src/scripts/feather.js" defer></script>
    ';
