<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/../default-time-zone.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

/* 
 * Utility class to store, get, and delete user IP Address
 * Include this on your file path if you need to obtain a user IP adddress
 * This will be mostly used for logging the attempts of a user breaking through the system
*/ 

class IpAddress {
    private $connection;

    public function __construct() {
        $this->connection = DatabaseConnection::connect();
    }

    public function storeIpAddress($ip_address, $try_time) {
        $sql = "INSERT INTO attempt_logs(ip_address, attempt_time) VALUES(?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $ip_address, $try_time);
        $stmt->execute();
        $stmt->close();
    }


    public static function getIpAddress() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address;
    }

    public function deleteIpAddress($ip_address) {
        $sql = "DELETE FROM attempt_logs WHERE ip_address = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('s', $ip_address);
        $stmt->execute();
        $stmt->close();
    }

    public function countIpAddressAttempt($ip_address, $time) {
        $sql = "SELECT COUNT(*) AS total_count FROM attempt_logs WHERE attempt_time > ? AND ip_address = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("is", $time, $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        $count_attempts = $result->fetch_assoc()['total_count'];
        $stmt->close();
        return $count_attempts;
    }

}
