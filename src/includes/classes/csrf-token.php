<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/../default-time-zone.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

/* 
 * Utility class that generates and validates CSRF token stored in a session variable.
 * Include this class in your file if you're working with forms.
*/

class CsrfToken {

    public static function generateCSRFToken() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_expiry'] = time() + 1800; // Token expires in 30 mins
        return $token;
    }


    public static function validateCsrfToken() {
        if (!self::isCsrfTokenSet()) {
            self::displayErrorMessage();
        }
        if (self::isCsrfTokenExpired()) {
            self::displayErrorMessage();
        }
        if (!self::isCsrfTokenMatch()) {
            self::displayErrorMessage();
        }
        // Token is valid
        self::unsetCsrfToken();
    }


    private static function isCsrfTokenSet() {
        return isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']);
    }


    private static function isCsrfTokenExpired() {
        return !isset($_SESSION['csrf_expiry']) || time() >= $_SESSION['csrf_expiry'];
    }

    
    private static function isCsrfTokenMatch() {
        return $_POST['csrf_token'] === $_SESSION['csrf_token'];
    }


    private static function unsetCsrfToken() {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_expiry']);
    }


    private static function displayErrorMessage() {
        $_SESSION['error_message'] = 'Something went wrong. Please reload the page.';
        header("Location: " . $_SESSION['referringPage']);
        exit();
    }
}
