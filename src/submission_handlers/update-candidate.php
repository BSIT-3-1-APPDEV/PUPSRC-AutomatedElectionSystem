<?php
include_once __DIR__ . '/../includes/classes/file-utils.php';
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/session-exchange.php');

session_start();

if (isset($_SESSION['voter_id'])) {
    $conn = DatabaseConnection::connect();

    if (isset($_POST['candidate_id'])) {
        $candidate_id = intval($_POST['candidate_id']);
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $middle_name = htmlspecialchars(trim($_POST['middle_name']));
        $suffix = htmlspecialchars(trim($_POST['suffix']));
        $party_list = htmlspecialchars(trim($_POST['party_list']));
        $position_id = intval($_POST['position_id']);
        $section_year_program = explode('-', $_POST['section']);
        $program = htmlspecialchars(trim($section_year_program[0]));
        $year_level = intval($section_year_program[1]);
        $section = htmlspecialchars(trim($section_year_program[2]));

        // Handle file upload
        $photo_url = '';    
        if (isset($_FILES['photo']['name']) && $_FILES['photo']['name']) {
            // Corrected path concatenation with $org_name
            $target_dir = __DIR__ . '/../user_data/' . $org_name . '/candidate_imgs/';

            $target_file = $target_dir . basename($_FILES['photo']['name']);
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $photo_url = basename($_FILES['photo']['name']);
            } else {
                // Debugging output
                echo "Failed to move uploaded file.";
                var_dump($_FILES['photo']['error']);
            }
        }

        // Update candidate
        $sql = "UPDATE candidate SET last_name=?, first_name=?, middle_name=?, suffix=?, party_list=?, position_id=?, program=?, section=?, year_level=?";
        if ($photo_url) {
            $sql .= ", photo_url=?";
        }
        $sql .= " WHERE candidate_id=?";
        
        $stmt = $conn->prepare($sql);
        if ($photo_url) {
            $stmt->bind_param("ssssssssssi", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $program, $section, $year_level, $photo_url, $candidate_id);
        } else {
            $stmt->bind_param("sssssssssi", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $program, $section, $year_level, $candidate_id);
        }
        $stmt->execute();
        $stmt->close();

        header("Location: ../candidate-details.php?candidate_id=$candidate_id");
    } else {
        header("Location: ../landing-page.php");
    }
} else {
    header("Location: ../landing-page.php");
}
?>
