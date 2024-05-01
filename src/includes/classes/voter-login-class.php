<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../get-ip-address.php');

class Login {

    private const LOGIN_BLOCK_TIME = 60; // 1 minute
    private const LOGIN_ATTEMPT_COUNT = 5;

    protected function getUser($email, $password) {
        $connection = DatabaseConnection::connect();

        // If db connection is not established, terminate execution
        if(!$connection) {
            return;
        }

        $time = time() - self::LOGIN_BLOCK_TIME;
        // Store user IP address
        $ip_address = getIPAddress();

        $sql = "SELECT COUNT(*) AS total_count from login_logs WHERE login_time > ? AND ip_address = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('is', $time, $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        $check_login = $result->fetch_assoc();
        $total_count = $check_login['total_count'];

        // If login attempts reached 5, redirects to login page and display error message
        if($total_count == self::LOGIN_ATTEMPT_COUNT) {
            $_SESSION['error_message'] = 'To many failed login attempts.</br>Please wait for ' . self::LOGIN_BLOCK_TIME . ' seconds.';
            header("Location: voter-login.php");
            exit();
        }

        $stmt->close();

        // Verify user in the voter table
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
                    $this->handleStudentVoter($row, $connection, $ip_address);
                } 
                elseif ($row['role'] == 'Committee Member') {
                    $this->handleCommitteeMember($row, $connection, $ip_address);
                } 
                else {
                    $this->handleRoleNotFound();
                }
            } 
            else {
                $this->handleMismatchedCredentials($connection, $ip_address, $total_count);
            }
        } 
        else {
            $this->handleUserNotFound();
        }

        $stmt->close();
    }

    // Check student-voter account and vote status
    private function handleStudentVoter($row, $connection, $ip_address) {
        if($row['status'] == 'For Verification') {
            $_SESSION['info_message'] = 'This account is under verification.';
            $this->deleteIPDetails($connection, $ip_address);
        } 
        elseif($row['status'] == 'Inactive') {
            $_SESSION['error_message'] = 'This account has been disabled.';
            $this->deleteIPDetails($connection, $ip_address);
        } 
        elseif($row['status'] == 'Rejected') {
            $_SESSION['info_message'] = 'This account has been validated. Kindly check your registered email.';
            $this->deleteIPDetails($connection, $ip_address);
        } 
        elseif($row['status'] == 'Active') {
            $_SESSION['voter_id'] = $row['voter_id'];
            $_SESSION['vote_status'] = $row['vote_status'];

            if ($row['vote_status'] == NULL) {
                $this->deleteIPDetails($connection, $ip_address);
                regenerateSessionId();
                header("Location: ballot-forms.php");
                exit();
            } elseif ($row['vote_status'] == 'Voted' || $row['vote_status'] == 'Abstained') {
                $this->deleteIPDetails($connection, $ip_address);
                regenerateSessionId();
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
    private function handleCommitteeMember($row, $connection, $ip_address) {
        $this->deleteIPDetails($connection, $ip_address);
        regenerateSessionId();
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
    private function handleMismatchedCredentials($connection, $ip_address, $total_count) {
        $total_count++;
        $remaining_attempt = self::LOGIN_ATTEMPT_COUNT - $total_count;
        $try_time = time();
        $insert_query = "INSERT INTO login_logs (ip_address, login_time) VALUES ('$ip_address', '$try_time')";
        $connection->query($insert_query);  

        if($remaining_attempt == 0) {
            $_SESSION['error_message'] = 'Too many login attempts.<br/>You are blocked for ' . self::LOGIN_BLOCK_TIME . ' seconds.';
            header("Location: voter-login.php");
            exit();
        }
        else {
            $_SESSION['error_message'] = 'Email and password do not match<br/>' . $remaining_attempt . ' remaining attempts.';
            header("Location: voter-login.php");
            exit();
        }
    }

    // If email does not exist, redirects/remains on the login page
    private function handleUserNotFound() {
        $_SESSION['error_message'] = 'User with this email does not exist.';
        header("Location: voter-login.php");
        exit();
    }

    // Delete user ip address, if user log in successfully
    private function deleteIPDetails($connection, $ip_address) {
        $delete_ip_address = "DELETE FROM login_logs WHERE ip_address = ?";
        $stmt = $connection->prepare($delete_ip_address);
        $stmt->bind_param('s', $ip_address);
        $stmt->execute();
    }

    private function regenerateSessionId() {
        // Regenerate a stronger session
        session_regenerate_id(true);
    }

}

