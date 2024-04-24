<?php
require_once 'db-connector.php';

class Login
{
    protected function getUser($email, $password)
    {

        if ($connection = DatabaseConnection::connect()) {
            $stmt = $connection->prepare("SELECT voter_id, email, password, role FROM voter WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a user of this email exists
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify if password matches the email
                if ($row['password'] == $password) {

                    // Store voter ID and role in session
                    $_SESSION['voter_id'] = $row['voter_id'];
                    $_SESSION['role'] = $row['role'];

                    // Check the role of the user
                    if ($row['role'] == 'Committee Member') {
                        header("Location: admindashboard.php");
                        exit();
                    } elseif ($row['role'] == 'Student Voter') {
                        header("Location: ballot-forms.php");
                        exit();
                    } else {
                        $_SESSION['error_message'] = 'Something went wrong.';
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
