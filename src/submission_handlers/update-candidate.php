<?php
include_once __DIR__ . '/../includes/classes/file-utils.php';
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/classes/db-connector.php');

session_start();

if (isset($_SESSION['voter_id'])) {
    $conn = DatabaseConnection::connect();

    if (isset($_POST['candidate_id'])) {
        $candidate_id = $_POST['candidate_id'];
        $last_name = $_POST['last_name'];   
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $suffix = $_POST['suffix'];
        $party_list = $_POST['party_list'];
        $position_id = $_POST['position_id'];
        $section_year = explode('-', $_POST['section']);
        $year_level = $section_year[0];
        $section = $section_year[1];

        // Handle file upload
        $photo_url = '';
        if (isset($_FILES['photo']['name'])) {
            $target_dir = __DIR__ . '/../images/candidate-profile/';
            $target_file = $target_dir . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
            $photo_url = basename($_FILES['photo']['name']);
        }

        // Update candidate
        $sql = "UPDATE candidate SET last_name=?, first_name=?, middle_name=?, suffix=?, party_list=?, position_id=?, section=?, year_level=?";
        if ($photo_url) {
            $sql .= ", photo_url=?";
        }
        $sql .= " WHERE candidate_id=?";
        
        $stmt = $conn->prepare($sql);
        if ($photo_url) {
            $stmt->bind_param("sssssssssi", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $section, $year_level, $photo_url, $candidate_id);
        } else {
            $stmt->bind_param("ssssssssi", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $section, $year_level, $candidate_id);
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
