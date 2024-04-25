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

        <div class="card-box ">
            <div class="content">
                <!-- CONTENT TO BE PUT HERE -->
                <p class="head fs-2 fw-bold main-color pt-xl-3">Put Contents Here</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc id dictum
                    nulla.
                    Fusce facilisis consectetur risus, sit amet aliquet metus mattis et. Aenean
                    et
                    pharetra urna. Class aptent taciti sociosqu ad litora torquent per conubia
                    nostra, per inceptos himenaeos. Donec nunc dolor, fringilla a lobortis id,
                    rutrum tincidunt neque. Mauris tortor ligula, iaculis a tempor vel, ultrices
                    quis dui. Aenean aliquet eu mi sit amet volutpat.</p>
            </div>
        </div>
    </div>
</main>
<?php
global $page_scripts;
$page_scripts = '

    ';
