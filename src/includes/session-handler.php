<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'classes/file-utils.php');
include_once FileUtils::normalizeFilePath('default-time-zone.php');
include_once FileUtils::normalizeFilePath('error-reporting.php');

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 0,
    'domain' => 'ivote-pupsrc.com',
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();

// Regenerate session ID every 30 minutes
$interval = 1800;

if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] >= $interval) {
    session_regenerate_id(true);
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

// Update last activity time on every page load
$_SESSION['last_activity'] = time();

/* For debugging purposes

 * $last_activity = $_SESSION['last_activity'];
 * $readable_date = date('Y-m-d H:i:s', $last_activity);
 * echo "<script>console.log('$readable_date')</script>";
 
*/