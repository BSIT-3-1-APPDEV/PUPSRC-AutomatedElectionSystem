<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, '../includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/query-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/change-role-class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voter_id = $_POST['voter_id'] ?? '';
    $new_role = $_POST['new_role'] ?? '';

    // Validate the new role
    if (!in_array($new_role, ['admin', 'head_admin'])) {
        echo 'Invalid role';
        exit;
    }

    // Update the role in the database
    try {
        $roleManager = new RoleManager();
        $result = $roleManager->updateRole($voter_id, $new_role);
        if ($result === "Update Success") {
            echo 'success';
        } else {
            echo 'Update failed: ' . $result;
        }
    } catch (Exception $e) {
        echo 'Database error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method';
}
