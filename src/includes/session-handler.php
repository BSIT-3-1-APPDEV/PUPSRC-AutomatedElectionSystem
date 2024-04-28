<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800, // Session will last only for 30 mins
    'domain' => 'localhost',
    'path' => '/',
    'secure' => false, // False because the system is being develop locally
    'httponly' => true
]);

session_start();

if (!isset($_SESSION['last_regeneration'])) {
    regenerateSessionId();
} else {
    $interval = 60 * 30;
    if (time() - $_SESSION['last_regeneration'] >= $interval) {
        regenerateSessionId();
    }
}

function regenerateSessionId() {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}