<link rel="stylesheet" href="src/styles/configuration.css">
<link rel="stylesheet" href="src/styles/config-ballot-form.css">
<link rel="stylesheet" href="src/styles/configuration.css">
<link rel="stylesheet" href="src/styles/config-ballot-form.css">

<main class="main">
    <div class="container px-md-3 px-lg-5 px-sm-2 p-4 ">
        <div class="row">
            <?php include_once 'configuration-page-title.php'; ?>
            <div class=" px-0">

                <?php

                global $configuration_pages;
                global $link_name;
                $secondary_nav = new SecondaryNav($configuration_pages, $link_name, true);
                $secondary_nav->getNavLink();
                ?>
            </div>

            <div class="card-box px-0">
                <div class="">
                    <div class="list-group ballot-form fs-6" id="items">
                        <div class="list-group-item">
                            <div class="handle">
                                <span class="fas fa-grip-lines"></span>
                            </div>
                            <div class="field-item ">
                                <div class="me-auto">Name</div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                </div>
                            </div>
                            <div class="field-item ">
                                <div class="me-auto">Section</div>
                                <div class=" form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" checked>
                                    <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item ">
                            <div class="handle">
                                <span class="fas fa-grip-lines"></span>
                            </div>
                            <div class="field-item ">
                                <div class="me-auto">Candidate Form</div>
                            </div>
                        </div>
                        <div class="list-group-item ">
                            <div class="field-item add-item ">
                                <div class="">
                                    <button class="btn btn-primary btn-sm text-capitalize">
                                        Add input field
                                    </button>
                                </div>
                            </div>
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
<script type="module" src="src/scripts/config-ballot-form.js"></script>
    ';
?>