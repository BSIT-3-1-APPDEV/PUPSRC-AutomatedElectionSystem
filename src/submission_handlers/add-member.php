<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('includes/mailer.php');
require_once FileUtils::normalizeFilePath('includes/classes/email-sender.php');

    $conn = DatabaseConnection::connect();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $lastName = $_POST["last_name"];
        $firstName = $_POST["first_name"];
        $middleName = $_POST["middle_name"];
        $email = $_POST["email"];
        $role = $_POST["role"];
    
      
        $password = bin2hex(random_bytes(8)); // 16-character random password
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
       
        $sql = "INSERT INTO voter (last_name, first_name, middle_name, email, password, role, status)
                VALUES (?, ?, ?, ?, ?, ?, 'Active')";
    
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param("ssssss", $lastName, $firstName, $middleName, $email, $hashedPassword, $role);
    
        if ($stmt->execute()) {
            // Set session variable to indicate account creation
            $_SESSION['account_created'] = true;
        
            // Send email with password
            $emailSender = new EmailSender($mail);
            $emailSender->sendPasswordEmail($email, $password);
        
            // Redirect to admin-creation.php
            header("Location: admin-creation.php");
            exit;
        } else {
            // Handle error
        }
        
        $stmt->close();
    }
?>