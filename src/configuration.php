<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');
require_once FileUtils::normalizeFilePath('../config.php');
require_once FileUtils::normalizeFilePath('includes/classes/Path.php');
include_once FileUtils::normalizeFilePath('includes/classes/page-head-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/page-router.php');
require_once FileUtils::normalizeFilePath('includes/classes/page-secondary-nav.php');
require_once FileUtils::normalizeFilePath('includes/classes/date-time-utils.php');
include_once FileUtils::normalizeFilePath('includes/classes/config-controller.php');


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

$allowed_roles = ['admin', 'head_admin'];
$is_page_accessible = isset($_SESSION['voter_id'], $_SESSION['role'], $_SESSION['organization']) &&
    (in_array($_SESSION['role'], $allowed_roles)) &&
    !empty($_SESSION['organization']);

if (!$is_page_accessible) {
    $page = basename($_SERVER['PHP_SELF']);

    if ($page === 'configuration.php') {
        header("Location: landing-page.php");
    } else {
        header("Location: ../landing-page.php");
    }
    exit();
}

require_once FileUtils::normalizeFilePath('includes/session-exchange.php');

$phpDateTimeNow = new DateTimeUtils();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    define("TITLE", "Configuration");
    define("DESCRIPTION", "Change election configuration.");

    global $pageHead;
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
    <meta property="og:image" content="src/images/resc/ivote-logo.png">
    <meta property="og:description" content="<?= $pageHead->getDescription(); ?>">
    <meta name="description" content="<?= $pageHead->getDescription(); ?>">

    <meta name="robots" content="noindex" />

    <!-- Preloader -->
    <link rel="preload" href="src/styles/loader.css" as="style" />
    <link rel="stylesheet" href="src/styles/loader.css" />
    <link rel="preload" href="src/images/resc/ivote-icon.png" as="image" />


    <script>
        class ResourceLoader {
            constructor(localSrc, cdnSrc, type, integrity = null, crossorigin = null) {
                this.localSrc = localSrc;
                this.cdnSrc = cdnSrc;
                this.type = type; // 'css' or 'script'
                this.integrity = integrity;
                this.crossorigin = crossorigin;
                this.loadResource();
            }

            loadResource() {
                if (this.type === 'css') {
                    this.loadCSS();
                } else if (this.type === 'script') {
                    this.loadScript();
                } else {
                    console.error('Invalid resource type. Supported types are "css" and "script".');
                }
            }

            loadCSS() {
                // Load resource (CSS) from CDN
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = this.cdnSrc;
                if (this.integrity) {
                    link.integrity = this.integrity;
                }
                if (this.crossorigin) {
                    link.crossOrigin = this.crossorigin;
                }
                document.head.appendChild(link);

                // Check if resource from CDN is loaded
                link.addEventListener('load', () => {
                    console.log('CSS resource loaded from CDN');
                });

                // Handle CDN load error
                link.addEventListener('error', () => {
                    console.log('CSS resource failed to load from CDN, loading from local');
                    this.loadLocalResource();
                });
            }

            loadScript() {
                // Load resource (script) from CDN
                var script = document.createElement('script');
                script.src = this.cdnSrc;
                if (this.integrity) {
                    script.integrity = this.integrity;
                }
                if (this.crossorigin) {
                    script.crossOrigin = this.crossorigin;
                }
                document.head.appendChild(script);

                // Check if resource from CDN is loaded
                script.addEventListener('load', () => {
                    console.log('Script resource loaded from CDN');
                });

                // Handle CDN load error
                script.addEventListener('error', () => {
                    console.log('Script resource failed to load from CDN, loading from local');
                    this.loadLocalResource();
                });
            }

            loadLocalResource() {
                if (this.type === 'css') {
                    // Load CSS resource from local fallback
                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = this.localSrc;
                    document.head.appendChild(link);
                } else if (this.type === 'script') {
                    // Load script resource from local fallback
                    var script = document.createElement('script');
                    script.src = this.localSrc;
                    document.head.appendChild(script);
                }
            }
        }
    </script>

    <!-- Montserrat Font -->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="src/styles/font-montserrat.css">

    <!-- Icons -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" as="style" />
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />


    <!-- Bootstrap -->
    <link rel="stylesheet" href="vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <!-- <script>
        new ResourceLoader('vendor/node_modules/bootstrap/dist/css/bootstrap.min.css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', 'css');
    </script> -->


    <!-- Main Style -->
    <link rel="stylesheet" href="src/styles/core.css">
    <link rel="stylesheet" href="src/styles/style.css" />
    <link rel="stylesheet" href="src/styles/orgs/<?= $org_name ?? 'sco' ?>.css">
    <link rel="icon" href="src/images/logos/<?= $org_name; ?>.png" type="image/x-icon">
    <link rel="icon" type="image/x-icon" href="src/images/resc/ivote-favicon.png">

    <!-- Vendor Scripts -->
    <script src="vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/node_modules/jquery/dist/jquery.min.js"></script>
    <!-- <script>
        new ResourceLoader('vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', 'script', "sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz", 'anonymous');
    </script>
    <script>
        new ResourceLoader('vendor/node_modules/jquery/dist/jquery.min.js', 'https://code.jquery.com/jquery-3.7.1.slim.min.js', 'script', "sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=", 'anonymous');
    </script> -->
    <!-- Main Scripts -->
    <script src="src/scripts/script.js" defer></script>
    <script src="src/scripts/loader.js" defer></script>
    <script rel="preload" src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js" as="script"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>


</head>

<body>


    <?php
    include_once FileUtils::normalizeFilePath('includes/views/configuration/configuration-sidebar.php');
    include_once FileUtils::normalizeFilePath('includes/components/loader.html');
    ?>

    <?php
    global $configuration_pages;
    $configuration_pages = [
        'vote-schedule',
        'vote-guidelines',
        'positions'
    ];

    global $link_name;
    $link_name = [
        'Schedule',
        'Voting Guidelines',
        'Candidate Positions'
    ];

    // Create an instance of PageRouter with the sub_pages array
    $page_router = new PageRouter($configuration_pages);
    $page_router->handleRequest();

    ?>


    <?php include_once FileUtils::normalizeFilePath('includes/views/configuration/configuration-footer.php')
    ?>



    <?php if (isset($page_scripts)) {
        echo $page_scripts;
    }
    ?>

</body>

</html>