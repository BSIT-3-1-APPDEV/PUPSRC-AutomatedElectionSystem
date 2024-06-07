<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('includes/mailer.php');
require_once FileUtils::normalizeFilePath('includes/classes/email-sender.php');

$conn = DatabaseConnection::connect();
$emailError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["last_name"], $_POST["first_name"], $_POST["email"], $_POST["role"])) {
        $lastName = $_POST["last_name"];
        $firstName = $_POST["first_name"];
        $middleName = isset($_POST["middle_name"]) ? $_POST["middle_name"] : NULL;
        $suffix = isset($_POST["suffix"]) ? $_POST["suffix"] : NULL;
        $email = $_POST["email"];
        $role = $_POST["role"];

        // Email validation
        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(?!\.c0m$)(?!@test)$/';
        if (!preg_match($emailPattern, $email)) {
            $emailError = 'Invalid email format.';
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM voter WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $emailError = 'Email already exists in the database.';
            }
        }

        if (empty($emailError)) {
            $password = bin2hex(random_bytes(8));
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO voter (last_name, first_name, middle_name, suffix, email, password, role, account_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Active')";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $lastName, $firstName, $middleName, $suffix, $email, $hashedPassword, $role);

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
                $emailError = 'SQL error: ' . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>