<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/classes/file-utils.php');
include_once FileUtils::normalizeFilePath('default-time-zone.php');

error_reporting(E_ALL);
ini_set("display_errors", 0);

function logErrors($errno, $errstr, $errfile, $errline) {

    // Relative file path
    $log_directory = __DIR__ . "/error-logs.txt";
    $timestamp = date('Y-m-d g:i a');
    $log_message = "$timestamp Error: [$errno] $errstr - $errfile: $errline";
    error_log($log_message . PHP_EOL, 3, $log_directory);
    
}

set_error_handler("logErrors");