<?php
require_once 'voter-login-class.php';

class LoginController extends Login {
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function loginUser() {
        if($this->isEmpty()) {
            $_SESSION['error_message'] = 'Input fields cannot be empty.';
            header("Location: voter-login.php");           
            exit();
        }
        
        if(!$this->isInvalidEmail()) {
            $_SESSION['error_message'] = 'Please provide a valid email address';
            header("Location: voter-login.php");          
            exit();
        }

        $this->getUser($this->email, $this->password);

    }

    private function isEmpty() {
        return empty($this->email) || empty($this->password);
    }

    private function isInvalidEmail() {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }
}
?>