<?php

session_start();
session_destroy();
unset($_SESSION['voter_id']);
header("Location: landing-page.php");
?>