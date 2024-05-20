<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

class SessionManager {
    public static function checkUserRoleAndRedirect() {
        if(isset($_SESSION['voter_id'])) {

            $role = $_SESSION['role'];
            $account_status = $_SESSION['account_status'];
            $voter_status = $_SESSION['voter_status'];
            $vote_status = $_SESSION['vote_status'];

            if($role == 'student_voter') {
                self::handleStudentVoter($account_status, $voter_status, $vote_status);
            }
            elseif($role == 'admin' || $role == 'head_admin') {
                self::handleAdminOrHeadAdmin($account_status);
            }
            else {
                header("Location: landing-page.php");
                exit();
            }
        }
    }

    private static function handleStudentVoter($account_status, $voter_status, $vote_status) {
        if($account_status != 'verified') {
            header("Location: landing-page.php");
            exit();
        }
        if($voter_status == 'pending' || $voter_status == 'active') {
            if($vote_status == NULL) {
                header("Location: ballot-forms.php");
                exit();
            }
            else {
                header("Location: end-point.php");
                exit();
            }
        }
        else {
            header("Location: landing-page.php");
            exit();
        }
    }

    // This method doesn't check yet whether admin/head account is disabled
    private static function handleAdminOrHeadAdmin($account_status) {
        if($account_status == 'verified') {
            header("Location: admindashboard.php");
            exit();
        }
        else {
            header("Location: landing-page.php");
            exit();
        }
    }
}