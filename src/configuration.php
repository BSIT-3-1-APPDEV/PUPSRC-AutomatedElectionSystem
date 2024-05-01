<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../config.php');
require_once FileUtils::normalizeFilePath('includes/classes/Path.php');
include_once FileUtils::normalizeFilePath('includes/classes/page-head-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/user.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/page-router.php');
require_once FileUtils::normalizeFilePath('includes/classes/page-secondary-nav.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-config.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');

$is_page_accessible = isset($_SESSION['voter_id'], $_SESSION['role']) && strtolower($_SESSION['role']) === 'committee member' && !empty($_SESSION['organization']);

if (!$is_page_accessible) {
    header("location: landing-page.php");
    exit();
}
regenerateSessionId();
include 'includes/session-exchange.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    define("TITLE", "Configuration");
    define("DESCRIPTION", "Change election configuration.");

    $pageHead = new PageHeadUtils(TITLE, DESCRIPTION, true);
    ?>


    <base href="<?= $pageHead->getBaseURL(); ?>/">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageHead->getTitle(); ?></title>

    <meta name="google" content="nositelinkssearchbox">

    <meta name="theme-color" content="#4285f4">

    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:title" content="<?= $pageHead->getTitle(); ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= $pageHead->getUrl(); ?>">
    <meta property="og:image" content="http://example.com/image.jpg">
    <meta property="og:description" content="<?= $pageHead->getDescription(); ?>">
    <meta name="description" content="<?= $pageHead->getDescription(); ?>">

    <meta name="robots" content="noindex" />

    <!-- Montserrat Font -->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="src/styles/font-montserrat.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="vendor/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Main Style -->
    <link rel="stylesheet" href="src/styles/core.css">
    <link rel="stylesheet" href="src/styles/style.css" />
    <link rel="stylesheet" href="src/styles/orgs/<?= $org_name ?? 'sco' ?>.css">
    <link rel="icon" href="src/images/logos/<?= $org_name; ?>.png" type="image/x-icon">
    <link rel="icon" type="image/x-icon" href="src/images/resc/ivote-favicon.png">
    <!-- Page Style -->
    <link rel="stylesheet" href="src/styles/configuration.css">

</head>

<body>

    <?php include_once FileUtils::normalizeFilePath('includes/views/configuration/configuration-sidebar.php')
    ?>

    <?php
    global $configuration_pages;
    $configuration_pages = [
        'ballot-form',
        'vote-schedule',
        'election-year',
        'vote-guidelines',
        'positions'
    ];

    global $link_name;
    $link_name = [
        'Ballot Form',
        'Schedule',
        'Election Year',
        'Voting Guidelines',
        'Candidate Positions'
    ];

    // Create an instance of PageRouter with the sub_pages array
    $page_router = new PageRouter($configuration_pages);
    $page_router->handleRequest();

    ?>


    <?php include_once FileUtils::normalizeFilePath('includes/views/configuration/configuration-footer.php')
    ?>


    <!-- Vendor Scripts -->
    <script src="vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Main Scripts -->
    <script src="src/scripts/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Page Scripts -->
    <script src="src/scripts/configuration.js"></script>
    <?php if (isset($page_scripts)) {
        echo $page_scripts;
    }
    ?>
    <script src="src/scripts/feather.js" defer></script>

</body>

</html>