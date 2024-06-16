<?php
// Include necessary files
require_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');
require_once FileUtils::normalizeFilePath('classes/db-config.php');
require_once FileUtils::normalizeFilePath('classes/change-org-class.php');

// Check if form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $formHandler = new FormHandler();
    $formHandler->processForm($_POST, $_FILES);
} else {
    // If the form is not submitted via POST method, redirect the user to the form page
    header("Location: ../transfer-org.php?page=2 ");
    exit();
}
?>
