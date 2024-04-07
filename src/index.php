<?php
require_once 'includes/classes/db-config.php';
require_once 'includes/classes/db-connector.php'; // Include the connector for database connection
require_once 'includes/session-handler.php';

// Check if a connection was established and the temporary variable is set
if ($connection = DatabaseConnection::connect()) {
    $result = $connection->query("SELECT DATABASE() AS dbname"); 
    $row = $result->fetch_assoc();
    $connectedDatabase = $row['dbname'];    

  echo "<p style='color: green; font-weight: bold;'>Connected to database: " . $connectedDatabase . "</p>";
} else {
  echo "<p>Database connection not established yet.</p>";
}

$admin_table = mysqli_query($connection, "SELECT * FROM administrator");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/<?php echo $org_name; ?>.css">
    <title>Login</title>
</head>
<body>
    <form action="login-inc.php" method="post">
        <label for="email" id="email">
            <p>Email:
                <input type="email" name="email">
            </p>
        </label>
        <label for="password" id="password">
            <p>Password:
                <input type="password" name="password">
            </p>
        </label>
        <button type="submit" name="sign_in_btn">
            Sign In
        </button>
    </form>

    <table>
        <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Suffix</th>
            <th>Email</th>
            <th>Password</th>
        </tr>
        <tr>
            <?php
                while($row = mysqli_fetch_assoc($admin_table)) {
                    echo ' 
                        <tr>
                            <td>'.$row['last_name'].'</td>
                            <td>'.$row['first_name'].'</td>
                            <td>'.$row['middle_name'].'</td>
                            <td>'.$row['suffix'].'</td>
                            <td>'.$row['email'].'</td>
                            <td>'.$row['password'].'</td>
                        </tr>
                    ';
                }
            ?>
        </tr>
    </table>
</body>
</html>