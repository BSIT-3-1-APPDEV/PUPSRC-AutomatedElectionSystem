<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

class SessionManager {
    public static function checkUserRoleAndRedirect() {
        if(isset($_SESSION['voter_id'])) {

            $role = $_SESSION['role'] ?? NULL;
            $account_status = $_SESSION['account_status'] ?? NULL;
            $voter_status = $_SESSION['voter_status'] ?? NULL;
            $vote_status = $_SESSION['vote_status'] ?? NULL;

            if($role == 'student_voter') {
                self::handleStudentVoter($account_status, $voter_status, $vote_status);
            }
            elseif($role == 'admin' || $role == 'head_admin') {
                self::handleAdminOrHeadAdmin($account_status);
            }
            else {
                header("Location: landing-page");
                exit();
            }
        }
    }

    private static function handleStudentVoter($account_status, $voter_status, $vote_status) {
        if($account_status != 'verified') {
            header("Location: landing-page");
            exit();
        }
        if($voter_status == 'pending' || $voter_status == 'active') {
            if($vote_status == NULL) {
                header("Location: ballot-forms");
                exit();
            }
            else {
                header("Location: end-point");
                exit();
            }
        }
        else {
            header("Location: landing-page");
            exit();
        }
    }

    // This method doesn't check yet whether admin/head account is disabled
    private static function handleAdminOrHeadAdmin($account_status) {
        if($account_status == 'verified') {
            header("Location: admindashboard");
            exit();
        }
        else {
            header("Location: landing-page");
            exit();
        }
    }
}