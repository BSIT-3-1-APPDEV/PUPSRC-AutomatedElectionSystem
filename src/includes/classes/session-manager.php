<?php

class SessionManager {
    public static function checkUserRoleAndRedirect() {

        // Check if the user is already logged in
        if(isset($_SESSION['voter_id'])) {

            // Check if the 'role' key exists in the session
            if(isset($_SESSION['role'])) {
                
                // Redirect based on role
                if($_SESSION['role'] == 'Student Voter') {
                    header("Location: ballot-forms.php");
                }
                elseif($_SESSION['role'] == 'Committee Member') {
                    header("Location: admindashboard.php");
                }
                exit();
                
            } else {
                // If 'role' key does not exist, display
                echo "Role not found in session.";
                exit();
            }
        }

    }
}