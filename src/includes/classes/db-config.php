<?php

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