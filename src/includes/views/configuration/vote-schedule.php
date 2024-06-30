<?php
include_once 'modals.php';

(new class
{
    use ConfigGuard;
})::generateCSRFToken(time() + (60 * 20));

?>

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

        <section class="schedule card-box">
            <div class="content col-12 col-sm-10 col-md-9">
                <div class="subtitle">
                    Select a starting and ending date and time for the election period.
                </div>
                <div class="all-day" id="">
                    <span>All-day</span>
                    <span class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="all-day-input">
                        <label class="form-check-label" for="all-day-input"></label>
                    </span>
                </div>
                <div class="row schedule-group">
                    <div class="d-flex flex-column col-md-5">
                        <label for="datetime-start" class="col-12">Start Date <span class="required">*</span></label>
                        <div class="datetime" id="datetime-start">
                            <div class="col-6 date-group">
                                <span class="d-inline-flex">
                                    <i data-feather="calendar"></i><input class="form-control" placeholder="e.g. 12/31/2024" type="date" data-value="" required>
                                </span>
                            </div>
                            <div class="col-6 time-group">
                                <span class="d-inline-flex">
                                    <i data-feather="clock"></i><input class="form-control" type="time" placeholder="e.g. 1:00 PM" step="1800" data-value="" required>
                                </span>
                            </div>
                        </div>
                        <div class="form-feedback text-danger">&nbsp;</div>
                    </div>
                    <div class="d-flex flex-column col-12 col-md-auto">
                        <div class="d-none d-md-none">&nbsp;</div>
                        <div class="sched-separator">to</div>
                    </div>

                    <div class="d-flex flex-column col-12 col-md-5">
                        <label for="datetime-end" class="col-12">End Date <span class="required">*</span></label>
                        <div class="datetime" id="datetime-end">
                            <div class="col-6 date-group">
                                <span class="d-inline-flex">
                                    <i data-feather="calendar"></i><input class="form-control " placeholder="e.g. 12/31/2024" type="date" data-value="" required>
                                </span>
                            </div>
                            <div class="col-6 time-group">
                                <span class="d-inline-flex">
                                    <i data-feather="clock"></i><input class="form-control " type="time" placeholder="e.g. 1:00 PM" step="1800" data-value="" required>
                                </span>
                            </div>

                        </div>
                        <div class="form-feedback text-danger">&nbsp;</div>
                    </div>
                </div>
                <div class="action-btn">
                    <button type="button" class="btn btn-secondary" id="cancel-schedule">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submit-schedule">Set Schedule</button>
                </div>
                <div class="action-btn-view">
                    <button type="button" class="btn d-none" id="edit-schedule">Edit Schedule</button>
                </div>
            </div>
        </section>

        <section>
            <div class="toast-container-unstacked pe-md-3 pe-lg-5 pe-sm-2">
                <!-- <div class="toast-body text-bg-danger">
                    <div class="toast-content">The end date cannot be before the start date.</div>
                    <div><button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button></div>
                </div> -->
            </div>
        </section>



    </div>
</main>

<section class="modals-container">

    <?php
    Modals::getWarningModal("You have</br>pending changes", 'Discard changes?');
    ?>





</section>

<script>
    const setCSRFToken = () => {
        try {
            return `<?= $_SESSION['csrf']['token']; ?>`;
        } catch (error) {

        }
    };
</script>

<?php
global $phpDateTimeNow;
global $page_scripts;

$phpDateTimeNow->printDatetimeTzJS();
$page_scripts = '
<script type="module" src="src/scripts/config-election-schedule.js?v=2"></script>
<script  type="text/javascript" src="src/scripts/feather.js" defer></script>
    ';
