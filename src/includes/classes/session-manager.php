<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

class SessionManager {
    public static function checkUserRoleAndRedirect() {

        // Check if the user is already logged in
        if(isset($_SESSION['voter_id'])) {

            // Check if role is set to student voter
            if($_SESSION['role'] == 'Student Voter') {

                // Check the account status of the user
                if($_SESSION['status'] == 'Active') {

                    /* Check for the vote status of the user and redirect to ballot form page if
                    the vote status is NULL */
                    if($_SESSION['vote_status'] == NULL) {
                        header("Location: ballot-forms.php");
                    }

                    // Redirect to endpoint page if vote status of the user is set to Voted
                    elseif($_SESSION['vote_status'] == 'Voted') {
                        header("Location: end-point.php");
                    }
                }          
            } 
            // Check if role is set to committee member
            elseif($_SESSION['role'] == 'Committee Member' || $_SESSION['role'] == 'Admin Member') {

                // Check the account status if set to active
                if($_SESSION['status'] == 'Active') {
                    header("Location: admindashboard.php");
                    exit();                    
                }
            }
                     
            else {
                // If 'role' key does not exist, redirects to landing page
                header("Location: landing-page.php");
                exit();
            }
        }
    }
}