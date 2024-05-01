<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath('db-config.php');

class DatabaseConnection {
    private static $connection;

    public static function connect() {
        if(!isset(self::$connection)) {
            // Retrieves organization name from session
            $org_name = $_SESSION['organization'] ?? '';        

            // Displays an alert message if session not set
            if(!$org_name) {
                echo '<script>alert("Organization not set in session")</script>';
                exit();
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
