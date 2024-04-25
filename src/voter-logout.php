<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');

$referer = $_SERVER['HTTP_REFERER'];
if ($referer && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {

    // Kill only the session of logged-in user
    // Retain the session of which database organization a user is connected
    unset($_SESSION['voter_id']);
    
    // Redirect back to previously stored URL
    if (isset($_SESSION['return_to'])) {
        $return_to = $_SESSION['return_to'];
        unset($_SESSION['return_to']);
        header("Location: $return_to");
    } else {
        header("Location: landing-page.php");
    }
    exit;
} 
else {   
    header("Location: landing-page.php");
    exit;
}
?>
