<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

$referer = $_SERVER['HTTP_REFERER'];
if ($referer && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
    
    session_destroy();
    
    // Redirect back to previously stored URL
    if (isset($_SESSION['return_to'])) {
        $return_to = $_SESSION['return_to'];
        unset($_SESSION['return_to']);
        header("Location: $return_to");
    } else {
        header("Location: ../landing-page.php");
    }
    exit;
} 
else {   
    header("Location: ../landing-page.php");
    exit;
}
?>
