<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../get-ip-address.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../unset-email-password.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

class Login{

    private const LOGIN_BLOCK_TIME = 60; // 1 minute
    private const LOGIN_ATTEMPT_COUNT = 5;
    private $connection;
    private $ip_address;

    // Creates connection to the database and gets user ip address
    public function __construct() {
        $this->connection = DatabaseConnection::connect();
        $this->ip_address = getIPAddress();
    }

    // Authenticates the user submitted email
    protected function getUser($email, $password) {

        // If db connection is not established, terminate execution
        if(!$this->connection) {
            $this->redirectWithError('A problem has occured. Try reloading the page.');
        }

        if($this->isBlocked()) {
            $this->redirectWithError('Too many failed login attempts.</br>Please wait for ' . self::LOGIN_BLOCK_TIME . ' seconds.');
        }

        // Verify user in the voter table
        $stmt = $this->connection->prepare("SELECT 
                                                voter_id, email, password, role, account_status, voter_status, vote_status 
                                            FROM 
                                                voter 
                                            WHERE 
                                                BINARY email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if email exists
        if ($result->num_rows === 0) {
            $this->handleUserNotFound();
        } else {
            $row = $result->fetch_assoc();
            $this->handlePasswordVerification($row, $password);
        }

        $stmt->close();
    }

    // Check login attempts and if is blocked
    private function isBlocked() {
        $time = time() - self::LOGIN_BLOCK_TIME;
        $stmt = $this->connection->prepare("SELECT COUNT(*) AS total_count FROM login_logs WHERE login_time > ? AND ip_address = ?");
        $stmt->bind_param('is', $time, $this->ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        $check_login = $result->fetch_assoc();
        return $check_login['total_count'] >= self::LOGIN_ATTEMPT_COUNT;
    }

    // Check if password matches the hashed password
    private function handlePasswordVerification($row, $password) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['role'] = $row['role'];
            $_SESSION['account_status'] = $row['account_status'];
            $this->handleUserRole($row);
        } else {
            $this->handleMismatchedCredentials();
        }       
    }

    // Check user role
    private function handleUserRole($row) {
        switch ($row['role']) {
            case 'student_voter':
                $this->handleStudentVoter($row);
                break;
            case 'admin':
            case 'head_admin':
                $this->handleAdminOrHead($row);
                break;
            default:
                $this->redirectWithError('Role not found in session.');
                break;
        }
    }

    // Check student-voter account and vote status
    private function handleStudentVoter($row) {
        $this->deleteIPDetails();

        switch ($row['account_status']) {
            case 'for_verification':
                $this->redirectWithMessage('info_message', 'This account is under verification.');
                break;
            case 'invalid':
                $this->setUserSession($row['email'], $row['password'], 'This account was rejected.');
                break;
            case 'verified':
                $this->handleVerifiedStudentVoter($row);
                break;
            default:
                $this->redirectWithError('Something went wrong.');
                break;
        }
    }

    // Check voter status of a verified account
    private function handleVerifiedStudentVoter($row) {
        $_SESSION['voter_id'] = $row['voter_id'];
        $_SESSION['voter_status'] = $row['voter_status'];
        $_SESSION['vote_status'] = $row['vote_status'];

        if ($row['voter_status'] === 'inactive') {
            $this->redirectWithMessage('info_message', 'This account is inactive.');
        } else {
            $this->redirectBasedOnVoteStatus($row['vote_status']);
        }
    }

    // Check voter's vote status (e.g., if the user has voted already or no)
    private function redirectBasedOnVoteStatus($vote_status) {
        unsetSessionVar();
        $this->regenerateSessionId();

        switch ($vote_status) {
            case NULL:
                header("Location: ../ballot-forms.php");
                break;
            case 'voted':
            case 'abstained':
                header("Location: ../end-point.php");
                break;
            default:
                header("Location: ../landing-page.php");
                break;
        }
        exit();
    }

    // Redirects a committee member to the admin dashboard
    private function handleAdminOrHead($row) {
        $this->deleteIPDetails();
        unsetSessionVar();

        if ($row['account_status'] === 'verified') {
            $this->regenerateSessionId();
            $_SESSION['voter_id'] = $row['voter_id'];
            header("Location: ../admindashboard.php");
        } else {
            $this->redirectWithError('This account has been disabled.');
        }
        exit();
    }

    // Check mismatched email and password
    private function handleMismatchedCredentials() {
        $this->logFailedAttempt();

        $remaining_attempt = self::LOGIN_ATTEMPT_COUNT - $this->getFailedAttemptsCount();
        if ($remaining_attempt <= 0) {
            $this->redirectWithError('Too many login attempts.<br/>You are blocked for ' . self::LOGIN_BLOCK_TIME . ' seconds.');
        } else {
            $this->redirectWithMessage('error_message', 'Email and password do not match<br/>' . $remaining_attempt . ' remaining attempts.');
        }
    }

    // If email does not exist, redirects/remains on the login page
    private function handleUserNotFound() {
        $this->redirectWithError('User with this email does not exist.');
    }

    // Insert user ip address to login logs table
    private function logFailedAttempt() {
        $try_time = time();
        $insert_query = "INSERT INTO login_logs (ip_address, login_time) VALUES (?, ?)";
        $stmt = $this->connection->prepare($insert_query);
        $stmt->bind_param('si', $this->ip_address, $try_time);
        $stmt->execute();
    }

    // Counts user failed login attempts
    private function getFailedAttemptsCount() {
        $time = time() - self::LOGIN_BLOCK_TIME;
        $stmt = $this->connection->prepare("SELECT COUNT(*) AS total_count FROM login_logs WHERE login_time > ? AND ip_address = ?");
        $stmt->bind_param('is', $time, $this->ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        $check_login = $result->fetch_assoc();
        return $check_login['total_count'];
    }

    // Delete user ip address, if user log in successfully
    private function deleteIPDetails() {
        $stmt = $this->connection->prepare("DELETE FROM login_logs WHERE ip_address = ?");
        $stmt->bind_param('s', $this->ip_address);
        $stmt->execute();
    }

    // Regenerate a stronger session
    private function regenerateSessionId() {   
        session_regenerate_id(true);
    }

    // Sets the error messages to be displayed
    private function redirectWithError($message) {
        $_SESSION['error_message'] = $message;
        header("Location: ../voter-login.php");
        exit();
    }

    // Handles different types of messages
    private function redirectWithMessage($type, $message) {
        unsetSessionVar();
        $_SESSION[$type] = $message;
        header("Location: ../voter-login.php");
        exit();
    }

    private function setUserSession($email, $password, $message) {
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['error_message'] = $message;
        header("Location: ../voter-login.php");
        exit();
    }
}

