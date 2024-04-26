<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-connector.php');

class Login {
    protected function getUser($email, $password) {
        $connection = DatabaseConnection::connect();

        // If db connection is not established, terminate execution
        if(!$connection) {
            return;
        }

        $stmt = $connection->prepare("SELECT voter_id, email, password, role, status, vote_status FROM voter WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if email exists
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify if password matches the email
            if ($row['password'] == $password) {
                $_SESSION['role'] = $row['role'];
                $_SESSION['status'] = $row['status'];

                if ($row['role'] == 'Student Voter') {
                    $this->handleStudentVoter($row);
                } 
                elseif ($row['role'] == 'Committee Member') {
                    $this->handleCommitteeMember($row);
                } 
                else {
                    $this->handleRoleNotFound();
                }
            } 
            else {
                $this->handleMismatchedCredentials();
            }
        } 
        else {
            $this->handleUserNotFound();
        }

        $stmt->close();
    }

    // Check student-voter account and vote status
    private function handleStudentVoter($row) {
        if($row['status'] == 'For Verification') {
            $_SESSION['info_message'] = 'This account is under verification.';
        } 
        elseif($row['status'] == 'Inactive') {
            $_SESSION['error_message'] = 'This account has been disabled.';
        } 
        elseif($row['status'] == 'Rejected') {
            $_SESSION['info_message'] = 'This account has been validated. Kindly check your registered email.';
        } 
        elseif($row['status'] == 'Active') {
            $_SESSION['voter_id'] = $row['voter_id'];
            $_SESSION['vote_status'] = $row['vote_status'];

            if ($row['vote_status'] == NULL) {
                header("Location: ballot-forms.php");
                exit();
            } elseif ($row['vote_status'] == 'Voted' || $row['vote_status'] == 'Abstained') {
                header("Location: end-point.php");
                exit();
            } else {
                header("Location: landing-page.php");
                exit();
            }
        } 
        else {
            $_SESSION['error_message'] = 'Something went wrong.';
        }
        header("Location: voter-login.php");
        exit();
    }

    // Redirects a committee member to the admin dashboard
    private function handleCommitteeMember($row) {
        $_SESSION['voter_id'] = $row['voter_id'];
        header("Location: admindashboard.php");
        exit();
    }

    // If account role is not found, redirects/remains on the login page
    private function handleRoleNotFound() {
        $_SESSION['error_message'] = 'Role not found in session.';
        header("Location: voter-login.php");
        exit();
    }

    // Check mismatched email and password
    private function handleMismatchedCredentials() {
        $_SESSION['error_message'] = 'Email and password do not match.';
        header("Location: voter-login.php");
        exit();
    }

    // If email does not exist, redirects/remains on the login page
    private function handleUserNotFound() {
        $_SESSION['error_message'] = 'User with this email address does not exist.';
        header("Location: voter-login.php");
        exit();
    }

    /* 
    Note: 
    To-follow the implementation of the ff:
        - Login Attempts and Account Lock
        - Password Recovery thru email    
    */
}

