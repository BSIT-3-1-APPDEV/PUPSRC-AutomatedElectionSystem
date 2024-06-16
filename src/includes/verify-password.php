<?php

// Include necessary files
require_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');
require_once FileUtils::normalizeFilePath('classes/db-connector.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');
require_once FileUtils::normalizeFilePath('classes/db-config.php');
require_once FileUtils::normalizeFilePath('classes/manage-ip-address.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $passwordVerify = new PasswordVerify();
    $password = $_POST['password'];
    $voter_id = $_POST['voter_id'];
    
    $result = $passwordVerify->confirmPassword($voter_id, $password);
    
    if ($result === true) {
        echo json_encode(['status' => 'success']);
    } elseif ($result === 'blocked') {
        echo json_encode(['status' => 'blocked']);
    } else {
        echo json_encode(['status' => 'fail', 'attempts_left' => $result]);
    }
}

class PasswordVerify {

    private $conn;
    private $ipAddress;
    
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOGIN_BLOCK_TIME = 1800; // 30 minutes in seconds
    
    public function __construct() {
        $this->conn = DatabaseConnection::connect();
        $this->ipAddress = new IpAddress();
    }

    public function confirmPassword($voter_id, $password) {
        $ip_address = $this->ipAddress::getIpAddress();
        
        if ($this->isBlocked($ip_address)) {
            return 'blocked';
        }
        
        $attempt_count = $this->getFailedAttemptCount($ip_address);
        
        if ($attempt_count >= self::MAX_LOGIN_ATTEMPTS) {
            return 'blocked';
        }
        
        $row = $this->getUserPassword($voter_id);
        
        if ($row) {
            $stored_hash = $row['password'];
            $password_verified = password_verify($password, $stored_hash);
            
            if ($password_verified) {
                $this->resetAttempts($ip_address);
                return true;
            } else {
                $this->incrementAttempt($ip_address);
                $attempts_left = self::MAX_LOGIN_ATTEMPTS - ($attempt_count + 1);
                return $attempts_left;
            }
        } else {
            return false;
        }
    }

    private function isBlocked($ip_address) {
        $time_threshold = time() - self::LOGIN_BLOCK_TIME;
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_count, MAX(login_time) AS last_attempt FROM login_logs WHERE ip_address = ?");
        $stmt->bind_param('s', $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        // Check if the IP is blocked based on the count and the time since the last attempt
        if ($row['total_count'] >= self::MAX_LOGIN_ATTEMPTS) {
            $last_attempt_time = strtotime($row['last_attempt']);
            if (time() - $last_attempt_time < self::LOGIN_BLOCK_TIME) {
                return true;
            } else {
                $this->resetAttempts($ip_address); // Reset attempts if the block time has passed
                return false;
            }
        }
        
        return false;
    }

    private function getFailedAttemptCount($ip_address) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_count FROM login_logs WHERE ip_address = ?");
        $stmt->bind_param('s', $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['total_count'];
    }

    private function incrementAttempt($ip_address) {
        $sql = "INSERT INTO login_logs (ip_address, login_time) VALUES (?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $ip_address);
        $stmt->execute();
        $stmt->close();
    }

    private function resetAttempts($ip_address) {
        $sql = "DELETE FROM login_logs WHERE ip_address = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $ip_address);
        $stmt->execute();
        $stmt->close();
    }

    protected function getUserPassword($voter_id) {
        $stmt = $this->conn->prepare("SELECT password FROM voter WHERE voter_id = ?");
        $stmt->bind_param('s', $voter_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
}
?>
