<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php'); // This file starts the session
require_once FileUtils::normalizeFilePath('classes/db-config.php');
require_once FileUtils::normalizeFilePath('error-reporting.php');

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
    displayUnsetToken();
}

// Validate CSRF token if it matches what is stored in session variable
if ($_POST['csrf_token'] == $_SESSION['csrf_token']) {
    // Check if CSRF token isn't expired yet
    if (time() >= $_SESSION['csrf_expiry']) {
        displayUnsetToken();
    }

    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_expiry']);
} else {
    displayUnsetToken();
}

try {
    // Get the organization from the form data
    $organization = $_POST['org'];

    // Retrieves database configuration based on the organization name for the selected organization
    $config = DatabaseConfig::getOrganizationDBConfig($organization);

    // Creates database connection for the selected organization
    $connectionOrg = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

    // Checks for connection errors
    if ($connectionOrg->connect_error) {
        throw new Exception("Connection failed for $organization: " . $connectionOrg->connect_error);
    }

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists in the organization's database
    $checkEmailQuery = "SELECT * FROM voter WHERE email = ?";
    $checkEmailStatement = $connectionOrg->prepare($checkEmailQuery);
    $checkEmailStatement->bind_param("s", $email);
    $checkEmailStatement->execute();
    $checkEmailResult = $checkEmailStatement->get_result();

    if ($checkEmailResult->num_rows > 0) {
        // Email already exists, redirect with error message
        $errorMessage = "Email already exists in the database.";
        header("Location: ../register.php?error=" . urlencode($errorMessage));
        exit();
    }

    // Handle file upload
    $targetDirectory = "../user_data/$organization/cor/"; // Directory where files will be uploaded
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0777, true); // Create the directory if it doesn't exist
    }
    $targetFile = $targetDirectory . basename($_FILES["cor"]["name"]); // Path to the uploaded file
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); // Get the file extension

    // Check if file is a PDF and within size limit
    if ($fileType != "pdf") {
        throw new Exception("Only PDF files are allowed.");
    }
    if ($_FILES["cor"]["size"] > 25000000) { // 25MB file size limit
        throw new Exception("File is too large.");
    }

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($_FILES["cor"]["tmp_name"], $targetFile)) {
        throw new Exception("Error uploading file.");
    }

    // Generate a hash of the uploaded file
    $fileHash = hash_file('sha256', $targetFile);

    // Extract the filename from the target file path
    $fileName = basename($targetFile);

    // Prepare and execute the SQL query to insert data into the respective organization's table
    $insertQuery = "INSERT INTO voter (email, password, cor, cor_hash, account_status, role) VALUES (?, ?, ?, ?, 'for_verification', 'student_voter')";
    $insertStatement = $connectionOrg->prepare($insertQuery);
    $insertStatement->bind_param("ssss", $email, $hashedPassword, $fileName, $fileHash);

    if ($insertStatement->execute()) {
        // Insertion successful in the selected organization's database

        // Now insert the same data into the second database 'db_sco'

        // Retrieves database configuration for 'db_sco'
        $configSco = DatabaseConfig::getOrganizationDBConfig('sco');

        // Creates database connection for 'db_sco'
        $connectionSco = new mysqli($configSco['host'], $configSco['username'], $configSco['password'], $configSco['database']);

        // Checks for connection errors for 'db_sco'
        if ($connectionSco->connect_error) {
            throw new Exception("Connection failed for db_sco: " . $connectionSco->connect_error);
        }

        // Prepare and execute the SQL query to insert data into 'voter' table in 'db_sco'
        $insertQuerySco = "INSERT INTO voter (email, password, cor, cor_hash, account_status, role) VALUES (?, ?, ?, ?, 'for_verification', 'student_voter')";
        $insertStatementSco = $connectionSco->prepare($insertQuerySco);
        $insertStatementSco->bind_param("ssss", $email, $hashedPassword, $fileName, $fileHash);

        if ($insertStatementSco->execute()) {
            // Both insertions successful, redirect to register.php
            $_SESSION['registrationSuccess'] = true;
            header("Location: ../register.php");
            exit();
        } else {
            // Error occurred during insertion into 'db_sco'
            $errorMessage = "Error occurred during registration for db_sco.";
            header("Location: ../register.php?error=" . urlencode($errorMessage));
            exit();
        }

        // Close statements and connections for 'db_sco'
        $insertStatementSco->close();
        $connectionSco->close();
    } else {
        // Error occurred during insertion into the selected organization's database
        $errorMessage = "Error occurred during registration for $organization.";
        header("Location: ../register.php?error=" . urlencode($errorMessage));
        exit();
    }

    // Close statements and connections for the selected organization
    $checkEmailStatement->close();
    $insertStatement->close();
    $connectionOrg->close();
} catch (Exception $e) {
    // Redirect with error message
    $errorMessage = "Error: " . $e->getMessage();
    header("Location: ../register.php?error=" . urlencode($errorMessage));
    exit();
}

function displayUnsetToken() {
    $_SESSION['error_message'] = 'Something went wrong. Please reload the page.';
    header("Location: ../voter-login.php");
    exit();
}
?>
