<?php
require_once 'includes/session-handler.php';

// Kill only the session of logged-in user
// Retain the session of which database organization a user is connected to
unset($_SESSION['voter_id']);
header("Location: landing-page.php");
?>