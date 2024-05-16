<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'localhost',
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();

// Regenerate session ID every 30 minutes
$interval = 1800;

if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] >= $interval) {
    session_regenerate_id(true);
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

// Update last activity time on every page load
$_SESSION['last_activity'] = time();