<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');

class DatabaseConfig {
    private $prefix = 'u155023598_';

    public static function getOrganizationDBConfig ($organization) {
        return array(
            'host' => 'localhost',
            'username' => $prefix . $organization,
            'password' => 'Student_0rg',
            // Adds 'db_' prefix to access specific org database
            'database' => $prefix . 'db_' . $organization
        );
    }
}
?>