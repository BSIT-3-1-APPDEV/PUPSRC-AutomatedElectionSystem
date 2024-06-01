<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/error-reporting.php');

if (isset($_SESSION['voter_id'])) {
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
    // Check if the user is authorized
    $allowedRoles = array('head_admin', 'admin');
    if (in_array($_SESSION['role'], $allowedRoles)) {
        // Connect to the database
        $conn = DatabaseConnection::connect();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Process form data and insert into the database
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and validate input
            $last_name = htmlspecialchars(trim($_POST['last_name']));
            $first_name = htmlspecialchars(trim($_POST['first_name']));
            $middle_name = htmlspecialchars(trim($_POST['middle_name']));
            $suffix = htmlspecialchars(trim($_POST['suffix']));
            $party_list = htmlspecialchars(trim($_POST['party_list']));
            $position_id = intval($_POST['position_id']);
            $section = htmlspecialchars(trim($_POST['section']));
            $year_level = htmlspecialchars(trim($_POST['year_level']));
            $photo_url = htmlspecialchars(trim($_POST['photo_url'])); // Placeholder for now, you'll need to handle file uploads separately

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO candidate (last_name, first_name, middle_name, suffix, party_list, position_id, photo_url, section, year_level, `candidate-creation`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssiiss", $last_name, $first_name, $middle_name, $suffix, $party_list, $position_id, $photo_url, $section, $year_level);

            if ($stmt->execute()) {
                $_SESSION['account_created'] = true;
                header("Location: manage-candidate.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        // User is not authorized to access this page
        header("Location: landing-page.php");
        exit();
    }
} else {
    // Redirect to the landing page if the user is not logged in
    header("Location: landing-page.php");
    exit();
}
?>
