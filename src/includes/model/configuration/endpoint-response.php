<?php
include_once __DIR__ . str_replace('/', DIRECTORY_SEPARATOR,  '/../../classes/file-utils.php');
require_once __DIR__ . FileUtils::normalizeFilePath('/../../error-reporting.php');

trait EndpointResponse
{
    public static function sendResponse($statusCode, $body, $terminate = false)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        $terminate && exit;
    }
}
