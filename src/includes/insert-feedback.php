<?php 
session_start();
require_once '../includes/classes/db-config.php';
require_once '../includes/classes/db-connector.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (isset($_POST["rating"]) && isset($_POST["feedback"])) {

        $rating = htmlspecialchars($_POST["rating"]);
        $feedback = htmlspecialchars($_POST["feedback"]);

        // Establish the database connection
        $conn = DatabaseConnection::connect();

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement to insert feedback into the database
        $sql = "INSERT INTO feedback (rating, feedback) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $rating, $feedback);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../../src/end-point.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

    } else {
        echo "Please fill in all required fields.";
    }
} else {
    echo "Form submission error.";
}
?>



