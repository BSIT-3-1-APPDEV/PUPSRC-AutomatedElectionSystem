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
            $this->redirectToLoginPage('Email and password cannot be empty.');
        }
    
        if ($this->isInvalidEmail()) {
            $this->redirectToLoginPage('Please provide a valid email.');
        }

        if ($this->validateEmailLength()) {
            $this->redirectToLoginPage('Email address must not exceed 255 characters');
        }

        if ($this->validatePasswordLength()) {
            $this->redirectToLoginPage('Password must not exceed 20 characters.');
        }
    
        // Proceed with user login process
        $this->login->getUser($this->email, $this->password);
    }
    
    // Check for empty email or password
    private function hasEmptyEmailAndPassword() {
        return empty($this->email) || empty($this->password);
    }
    
    // Redirect to login page and display error message
    private function redirectToLoginPage($errorMessage) {
        $_SESSION['error_message'] = $errorMessage;
        header("Location: ../voter-login.php");
        exit();
    }
    
    // Check for invalid email format
    private function isInvalidEmail() {
        return !filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    // Check for invalid email length
    private function validateEmailLength() {
        return strlen($this->email) > 255;
    }

    // Check for invalid password length
    private function validatePasswordLength() {
        return strlen($this->password) > 20;
    }
}