<?php

error_reporting(E_ALL);
ini_set("display_errors", 0);

function logErrors($errno, $errstr, $errfile, $errline) {

    // Relative file path
    $log_directory = __DIR__ . "/error-logs.txt";

    // Get timestamp for PH
    date_default_timezone_set('Asia/Manila');
    $timestamp = date('Y-m-d g:i a');
    $log_message = "$timestamp Error: [$errno] $errstr - $errfile: $errline";
    error_log($log_message . PHP_EOL, 3, $log_directory);
    
}

set_error_handler("logErrors");