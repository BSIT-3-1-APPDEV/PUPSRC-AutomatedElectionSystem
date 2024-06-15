<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-config.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/../default-time-zone.php');

class Registration {
    private $email;
    private $password;
    private $organization;
    private $file;
    private $file_hash;
    private $file_name;
    private const ACCOUNT_STATUS = 'for_verification';
    private const ROLE = 'student_voter';

    public function __construct ($email, $password, $organization, $file) {
        $this->email = $email;
        $this->password = $password;
        $this->organization = $organization;
        $this->file = $file;
    }

    public function processRegistrationCredentials() {
        try {
            $this->validateEmailNotExist();
            $this->validateFile();
            $this->saveFile();
            $this->insertIntoOrganizationDB();
            $this->insertIntoScoDB();
            $_SESSION['registration_success'] = true;
            header("Location: ../register.php");
            exit();
        }
        catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
            $_SESSION['error_message'] = $error_message;
            header("Location: ../register.php");
            exit();
        }
    }


    private function validateEmailNotExist() {
        $config = DatabaseConfig::getOrganizationDBConfig($this->organization);
        $connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
        
        $sql = "SELECT email FROM voter WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            throw new Exception("Email address already exists.");
        }

        $stmt->close();
        $connection->close();  
    }


    private function validateFile() {
        $target_directory = "../user_data/{$this->organization}/cor/";
        if(!file_exists($target_directory)) {
            mkdir($target_directory, 0777, true);
        }

        // Check if file is PDF and within size limit
        $file_type = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        if ($file_type != 'pdf' || $this->file['size'] > 25000000) {
            throw new Exception("Invalid file. Only PDF files up to 25MB are allowed.");
        }
        
        $this->file_name = basename($this->file['name']);
    }


    private function saveFile() {
        $target_directory = "../user_data/{$this->organization}/cor/";
        $target_file = $target_directory . $this->file_name;
    
        if (!move_uploaded_file($this->file["tmp_name"], $target_file)) {
            throw new Exception("There was an error uploading your file. Try again");
        }
        
        $this->file_hash = hash_file('sha256', $target_file);
    }


    private function insertIntoOrganizationDB() {
        $config = DatabaseConfig::getOrganizationDBConfig($this->organization);
        $connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
    
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO voter (email, password, cor, cor_hash, account_status, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
    
        $account_status = self::ACCOUNT_STATUS;
        $role = self::ROLE;
    
        $stmt->bind_param("ssssss", $this->email, $hashed_password, $this->file_name, $this->file_hash, $account_status, $role);
    
        if (!$stmt->execute()) {
            throw new Exception("Error occurred during registration for " . strtoupper($this->organization) . ".");
        }
    
        $stmt->close();
        $connection->close();
    }    

    
    private function insertIntoScoDB() {
        $sco = 'sco';
        $config = DatabaseConfig::getOrganizationDBConfig($sco);
        $connection = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
    
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO voter (email, password, cor, cor_hash, account_status, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
    
        $account_status = self::ACCOUNT_STATUS;
        $role = self::ROLE;
    
        $stmt->bind_param("ssssss", $this->email, $hashed_password, $this->file_name, $this->file_hash, $account_status, $role);
    
        if (!$stmt->execute()) {
            throw new Exception("Error occurred during registration.");
        }
    
        $stmt->close();
        $connection->close();
    }
    
}