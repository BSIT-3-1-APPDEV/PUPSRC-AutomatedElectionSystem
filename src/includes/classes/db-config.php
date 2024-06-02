<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');

class DatabaseConfig {
    public static function getOrganizationDBConfig ($organization) {
        return array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            // Adds 'db_' prefix to access specific org database
            'database' => 'db_' . $organization
        );
    }
}
?>