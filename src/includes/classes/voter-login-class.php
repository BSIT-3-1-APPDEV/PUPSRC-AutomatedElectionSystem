<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-connector.php');

class Login {
    protected function getUser($email, $password) {

        if ($connection = DatabaseConnection::connect()) {
            $stmt = $connection->prepare("SELECT voter_id, email, password, role, status, vote_status FROM voter WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a user of this email exists
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify if password matches the email
                if ($row['password'] == $password) {

                    // Store role and status in session        
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['status'] = $row['status'];
                    
                    // Check if role is set to student voter
                    if($row['role'] == 'Student Voter') {

                        if($row['status'] == 'For Verification') {
                            $_SESSION['info_message'] = 'This account is under verification.';
                            header("Location: voter-login.php");
                            exit();
                        }
    
                        // Show an alert message to user if account status is inactive and redirects to landing page
                        elseif($row['status'] == 'Inactive') {
                            $_SESSION['error_message'] = 'This account has been disabled.';
                            header("Location: voter-login.php");
                            exit();
                        }

                        elseif($row['status'] == 'Rejected') {
                            $_SESSION['info_message'] = 'This account has been validated. Kindly check your registered email.';
                            header("Location: voter-login.php");
                            exit();
                        }

                        // Check the account status of the user
                        elseif($row['status'] == 'Active') {
                            $_SESSION['voter_id'] = $row['voter_id'];
                            $_SESSION['vote_status'] = $row['vote_status'];

                            /* Check for the vote status of the user and redirect to ballot form page if
                            the vote status is NULL */
                            if($row['vote_status'] == NULL) {
                                header("Location: ballot-forms.php");
                                exit();
                            }

                            // Redirect to endpoint page if vote status of the user is set to Voted
                            elseif($row['vote_status'] == 'Voted' || $row['vote_status'] == 'Abstained') {
                                header("Location: end-point.php");
                                exit();
                            }

                            // Show an alert message to user if vote status is not found and redirects to landing page
                            else {                   
                                header("Location: landing-page.php");
                                echo '<script>alert("Something went wrong.")</script>';
                                exit();
                            }
                        }

                        else {
                            $_SESSION['error_message'] = 'Something went wrong.';
                            header("Location: voter-login.php");
                            exit();
                        }

                    }

                    // Check if role is set to committee member
                    elseif($row['role'] == 'Committee Member') {
                        $_SESSION['voter_id'] = $row['voter_id'];
                        header("Location: admindashboard.php");
                        exit();
                    }
            
                    else {
                        $_SESSION['error_message'] = 'Role not found in session.';
                        header("Location: voter-login.php");
                        exit();
                    }
                }
                
                // If email and password mismatched
                else {
                    // return 'Email and Password mismatched.';
                    $_SESSION['error_message'] = 'Oops, Email and Password do not matched!';
                    header("Location: voter-login.php");
                    exit();
                }
            }
            // If username does not find a match
            else {
                $_SESSION['error_message'] = 'User with this email address does not exist.';
                header("Location: voter-login.php");
                exit();
            }

            // Close database connection
            $stmt->close();
        }
    }
}
