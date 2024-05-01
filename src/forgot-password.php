<?php 
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/includes/classes/session-manager.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/session-exchange.php');

SessionManager::checkUserRoleAndRedirect();
?>

<!--Sample form to test the forgot password functionality-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Forgot Password Demo</h1>
    <form action="includes/send-password-reset.php" method="post">
        <label for="">Email</label>
        <input type="text" name="email">
        <button type="submit" name="send-email-btn">Send</button>
    </form>
</body>
</html>