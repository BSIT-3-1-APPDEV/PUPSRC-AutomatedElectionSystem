<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/../src/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../vendor/autoload.php');

/** Import TestCase class from PHP Unit\Framework namespace
 * TestCase class is the base class for all test cases in PHPUnit
*/
// use PHPUnit\Framework\TestCase;

// class LoginTest extends TestCase {

// }