<?php 
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../includes/session-handler.php');
require_once FileUtils::normalizeFilePath('../includes/classes/db-connector.php');
require_once FileUtils::normalizeFilePath('../includes/error-reporting.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if both rating and feedback fields are set
    if (isset($_POST["rating"]) || isset($_POST["feedback"])) {

        $rating = htmlspecialchars(trim($_POST["rating"]));
        $feedback = htmlspecialchars(trim($_POST["feedback"]));

        // Check if both rating and feedback are blank
        if (empty($rating) && empty($feedback)) {
            header("Location: ../../src/end-point.php");
            exit();
        }

        // Establish the database connection
        $conn = DatabaseConnection::connect();

        // Prepare SQL statement to insert feedback into the database
        $sql = "INSERT INTO feedback (rating, feedback) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ss", $rating, $feedback);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../../src/end-point.php");
            exit(); 
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

    } else {
        echo "Rating or feedback is/are not inserted.";
    }
} else {
    echo "Form submission error.";
}
?>
