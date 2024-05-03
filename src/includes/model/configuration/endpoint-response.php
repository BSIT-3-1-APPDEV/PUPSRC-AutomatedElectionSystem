<?php
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');

trait EndpointResponse
{
    protected static function sendResponse($statusCode, $body, $terminate = false)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        $terminate && exit;
    }
}
