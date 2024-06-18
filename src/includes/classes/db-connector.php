<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-config.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');

class DatabaseConnection {
    private static $connection;

    public static function connect() {
        if(!isset(self::$connection)) {
            // Retrieves organization name from session
            $org_name = $_SESSION['organization'] ?? '';        

            // Return an error message if organization not set
            if(!$org_name) {
                $_SESSION['error_message'] = "We can't connect you to your organization.";
                return;
            }

            try {
                // Retrieves database configuration based on the organization name
                $config = DatabaseConfig::getOrganizationDBConfig($org_name);
                
                // Creates database connection
                self::$connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

                // Checks for connection errors
                if(self::$connection->connect_error){
                    throw new Exception("Connection failed: " . self::$connection->connect_error);
                }
            }
            // Handles the thrown exception
            catch (Exception $e){
                echo "Error: " . $e->getMessage();
                exit();
            }
        }
        return self::$connection;
    }
}
?>
