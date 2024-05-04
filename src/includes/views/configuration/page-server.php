<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, '../../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../../classes/page-router.php');
require_once FileUtils::normalizeFilePath('../../classes/page-secondary-nav.php');
// require_once FileUtils::normalizeFilePath('../../error-reporting.php');
header('Content-Type: text/html');

$page_request = $_SERVER['REQUEST_URI'];

$this_page_name = basename($_SERVER['SCRIPT_NAME']);

$requested_page_index = strpos($page_request, $this_page_name);

global $requested_page;
global $requested_basepage;
$requested_page_relative;

if ($requested_page_index !== false) {

    $requested_page = substr($page_request, $requested_page_index + strlen($this_page_name));
    $requested_page_name = basename($requested_page);
    $requested_basepage_index = strpos($requested_page, $requested_page_name);
    $requested_basepage = rtrim(substr($requested_page, 0, $requested_basepage_index), '/');;

    $src_index = strpos($requested_page, 'src');
    $requested_page_relative = FileUtils::normalizeFilePath(__DIR__ . '/' .  basename($requested_page));

    global $configuration_pages;
    $configuration_pages = [
        'ballot-form',
        'vote-schedule',
        'election-year',
        'vote-guidelines',
        'positions'
    ];

    global $link_name;
    $link_name = [
        'Ballot Form',
        'Schedule',
        'Election Year',
        'Voting Guidelines',
        'Candidate Positions'
    ];

    require_once($requested_page_relative . '.php');

    echo $page_scripts;
}
