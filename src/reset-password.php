<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/session-manager.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-exchange.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/db-connector.php');

SessionManager::checkUserRoleAndRedirect();

$token = $_GET['token'];
$token_hash = hash('sha256', $token);

$connection = DatabaseConnection::connect();

$sql = "SELECT * FROM voter WHERE reset_token_hash = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === NULL) {
    die("Token not found.");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}

?>

<!--Modify the html and css of this. This page is for resetting the password-->
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
</head>
<body>

    <h1>Reset Password</h1>

    <form method="post" action="includes/process-reset-password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <br/>
        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <button>Send</button>
    </form>

</body>
</html>