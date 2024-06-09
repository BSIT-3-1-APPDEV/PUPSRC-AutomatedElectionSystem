<?php
include_once __DIR__ . '/../includes/classes/file-utils.php';
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/session-exchange.php');

session_start();

if (isset($_SESSION['voter_id'])) {
    $conn = DatabaseConnection::connect();

    // Process form data and insert into the database
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and validate input
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $middle_name = htmlspecialchars(trim($_POST['middle_name']));
        $suffix = htmlspecialchars(trim($_POST['suffix']));
        $party_list = htmlspecialchars(trim($_POST['party_list']));
        $position_id = intval($_POST['position_id']);
        $election_year = htmlspecialchars(trim($_POST['election_year']));
        $section_year = explode('-', $_POST['section']);
        $year_level = $section_year[0];
        $section = $section_year[1];

        $photo_url = '';

        if (isset($_FILES['photo']['name']) && $_FILES['photo']['name']) {
            // Handle file upload  
            $target_dir = __DIR__ . '/../user_data/' . $org_name . '/candidate_imgs/';
            $target_file = $target_dir . basename($_FILES['photo']['name']);
        
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $photo_url = basename($_FILES['photo']['name']);
            } else {
                echo "Failed to move uploaded file.";
                var_dump($_FILES['photo']['error']);
            }
        }
        
        // Insert Candidate
        $sql = "INSERT INTO candidate (last_name, first_name, middle_name, suffix, party_list, position_id, section, year_level, photo_url, election_year) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $section, $year_level, $photo_url, $election_year);
        
        if ($stmt->execute()) {
            $_SESSION['account_created'] = true;
            header("Location: ../add-candidate.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
        
    } else {
        header("Location: ../landing-page.php");
    }
} else {
    header("Location: ../landing-page.php");
}
?>
