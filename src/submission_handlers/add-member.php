<?php
    $conn = DatabaseConnection::connect();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $lastName = $_POST["last_name"];
        $firstName = $_POST["first_name"];
        $middleName = $_POST["middle_name"];
        $email = $_POST["email"];
        $role = $_POST["role"];
    
      
        $password = bin2hex(random_bytes(8)); // 16-character random password
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
       
        $sql = "INSERT INTO voter (last_name, first_name, middle_name, email, password, role, status)
                VALUES (?, ?, ?, ?, ?, ?, 'Active')";
    
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param("ssssss", $lastName, $firstName, $middleName, $email, $hashedPassword, $role);
    
        if ($stmt->execute()) {
            echo "Admin account created successfully.";
        } else {
            echo "Error creating admin account: " . $stmt->error;
        }
    
        $stmt->close();
    }
?>