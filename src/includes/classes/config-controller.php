<?php
include_once 'file-utils.php';

require_once __DIR__ . FileUtils::normalizeFilePath('/../error-reporting.php');
require_once __DIR__ . FileUtils::normalizeFilePath('/../session-handler.php');
require_once __DIR__ . FileUtils::normalizeFilePath('/../model/configuration/endpoint-response.php');


define('DEFAULT_CSRF_EXPIRY', 30);

/**
 * Trait ConfigGuard
 *
 * Provides security mechanisms for validating the origin of a request, 
 * handling unauthorized access, and validating CSRF tokens.
 */
trait ConfigGuard
{

    public static function generateCSRFToken($expiryTime)
    {
        $defaultExpiry = time() + (DEFAULT_CSRF_EXPIRY * 60);

        if (isset($expiryTime) && $expiryTime <= time()) {
            $expiryTime = $defaultExpiry;
        }

        $_SESSION['csrf'] = [
            'token' => bin2hex(random_bytes(32)),
            'expiry' => $expiryTime
        ];
    }
    /**
     * Validates the origin of the request and checks if the user has the necessary 
     * permissions to access the page. If the request is an API call, it also validates 
     * the CSRF token.
     *
     * @param bool $isAPI Indicates whether the request is an API call (default: true).
     * 
     * @return void
     */
    public static function validateRequestOrigin($csrf_token, $isAPI = true)
    {
        $allowed_roles = ['admin', 'head_admin'];
        $is_page_accessible = isset($_SESSION['voter_id'], $_SESSION['role'], $_SESSION['organization']) &&
            (in_array($_SESSION['role'], $allowed_roles)) &&
            !empty($_SESSION['organization']);

        if (!$is_page_accessible) {
            self::handleUnauthorized();
        }

        if ($isAPI) {
            self::validateCSRFToken($csrf_token);
        }
    }

    /**
     * Handles unauthorized access by generating a random error message and sending 
     * a Unauthorized(401) HTTP response with the message.
     * 
     * @return void
     */
    protected static function handleUnauthorized()
    {
        $messages = [
            'Your session has expired or you are not authorized. Please refresh the page to continue.',
            'It looks like your session has expired or you don\'t have the necessary permissions. Please refresh the page to continue.',
            'Heads up! Your session has expired or you\'re not authorized to view this page. A quick refresh should do the trick!'
        ];

        $randomMessage = $messages[array_rand($messages)];

        $response = [
            'status' => 'error',
            'message' => $randomMessage
        ];

        (new class
        {
            use EndpointResponse;
        })::sendResponse(401, $response, true);
    }

    /**
     * Validates the CSRF token by checking if it exists and if it matches the token stored 
     * in the session. If the token is invalid or has expired, it calls the displayUnsetToken 
     * method to handle the error.
     * 
     * @return void
     */
    public static function validateCSRFToken($csrf_token)
    {
        if (!isset($csrf_token) || !isset($_SESSION['csrf']['token'])) {
            self::displayUnsetToken();
        }
        if ($csrf_token != $_SESSION['csrf']['token'] || time() >= $_SESSION['csrf']['expiry']) {
            self::displayUnsetToken();
        }
    }

    /**
     * Handles cases where the CSRF token is not set or is invalid by unsetting the 
     * CSRF token and its expiry time in the session, then calling the handleUnauthorized 
     * method to handle the error.
     * 
     * @return void
     */
    protected static function displayUnsetToken()
    {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_expiry']);
        self::handleUnauthorized();
    }
}
