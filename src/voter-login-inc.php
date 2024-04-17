<?php
require_once 'includes/classes/db-connector.php';
require_once 'includes/session-handler.php';
require_once 'includes/classes/voter-login-controller.php';
require_once 'includes/classes/voter-login-class.php';

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST['sign_in'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Instantiates LoginController class
        $login = new LoginController($email, $password);
    
        // Run error handlers
        $login->loginUser();
    }
}
?>
