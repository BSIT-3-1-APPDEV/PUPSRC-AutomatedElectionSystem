<?php
include_once __DIR__ . '/../includes/classes/file-utils.php';
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../includes/session-exchange.php');

if (isset($_SESSION['voter_id'])) {
    $conn = DatabaseConnection::connect();

    if ($conn) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errors = [];

            $target_dir = FileUtils::normalizeFilePath(__DIR__ . "/../user_data/{$org_name}/candidate_imgs/");
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Compute the election year outside the loop
            $currentYear = date("Y");
            $nextYear = $currentYear + 1;
            $election_year = $currentYear . '-' . $nextYear;

            foreach ($_POST['last_name'] as $index => $last_name) {
                $first_name = htmlspecialchars(trim($_POST['first_name'][$index]));
                $middle_name = htmlspecialchars(trim($_POST['middle_name'][$index]));
                $suffix = htmlspecialchars(trim($_POST['suffix'][$index]));
                $party_list = htmlspecialchars(trim($_POST['party_list'][$index]));
                $position_id = intval($_POST['position_id'][$index]);
                $section_year_program = explode('-', $_POST['section'][$index]);
                $program = htmlspecialchars(trim($section_year_program[0]));
                $year_level = $section_year_program[1];
                $section = $section_year_program[2];

                $photo_url = '';

                if (isset($_FILES['photo']['name'][$index]) && !empty($_FILES['photo']['name'][$index])) {
                    $unique_file_name = uniqid() . '_' . basename($_FILES['photo']['name'][$index]);
                    $target_file = $target_dir . $unique_file_name;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];

                    if (in_array($imageFileType, $allowedExtensions)) {
                        if (move_uploaded_file($_FILES['photo']['tmp_name'][$index], $target_file)) {
                            $photo_url = $unique_file_name;
                        } else {
                            $errors[] = "Failed to move uploaded file for candidate " . ($index + 1);
                        }
                    } else {
                        $errors[] = "Invalid file type for candidate " . ($index + 1) . ". Only JPG, JPEG, and PNG files are allowed.";
                    }
                }

                $sql = "INSERT INTO candidate (last_name, first_name, middle_name, suffix, party_list, position_id, program, section, year_level, photo_url, election_year) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("sssssssssss", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $program, $section, $year_level, $photo_url, $election_year);

                    if (!$stmt->execute()) {
                        $errors[] = "Error inserting candidate " . ($index + 1) . ": " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $errors[] = "Failed to prepare statement for candidate " . ($index + 1);
                }
            }

            if (empty($errors)) {
                $_SESSION['account_created'] = true;
                header("Location: ../add-candidate.php");
            } else {
                $_SESSION['errors'] = $errors;
                header("Location: ../landing-page.php");
            }
        }
    } else {
        $_SESSION['db_error'] = "Failed to connect to the database.";
        header("Location: ../landing-page.php");
    }
} else {
    header("Location: ../landing-page.php");
}
?>
