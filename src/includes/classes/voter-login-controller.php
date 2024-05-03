<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/voter-login-class.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

class LoginController extends Login {
    private $email;
    private $password;
    private $login;

    public function __construct($email, $password) {
        $this->login = new Login();
        $this->email = $email;
        $this->password = $password;
    }

    public function loginUser() {
        if ($this->hasEmptyEmailAndPassword()) {
            $this->redirectToLoginPage('Input fields cannot be empty.');
        }

        if ($this->isEmailEmpty() || $this->isPasswordEmpty()) {
            $this->redirectToLoginPage('Email or password cannot be empty.');
        }
    
        if ($this->isInvalidEmail()) {
            $this->redirectToLoginPage('Please provide a valid email.');
        }
    
        // Proceed with user login process
        $this->login->getUser($this->email, $this->password);
    }
    
    // Check for empty email and password
    private function hasEmptyEmailAndPassword() {
        return empty($this->email) && empty($this->password);
    }
    
    // Redirect to login page and display error message
    private function redirectToLoginPage($errorMessage) {
        $_SESSION['error_message'] = $errorMessage;
        header("Location: ../voter-login.php");
        exit();
    }
    
    // Check for empty email
    private function isEmailEmpty() {
        return empty($this->email);
    }
    
    // Check for invalid email
    private function isInvalidEmail() {
        return !filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }
    
    // Check for empty password
    private function isPasswordEmpty() {
        return empty($this->password);
    }
    
}
?>