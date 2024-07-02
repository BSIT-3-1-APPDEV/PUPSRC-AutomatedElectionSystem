<?php
$org_name = $_SESSION['organization'] ?? '';

$org_sections = [
    'BSP' => [
        '1' => ['1', '2'],
        '2' => ['1', '2'],
        '3' => ['1', '2'],
        '4' => ['1', '2']
    ],
    'BSECE' => [
        '1' => ['1', '2'],
        '2' => ['1'],
        '3' => ['1'],
        '4' => ['1']
    ],
    'BSIT' => [
        '1' => ['1', '2'],
        '2' => ['1', '2'],
        '3' => ['1', '2'],
        '4' => ['1', '2']
    ],
    'BSED-FL' => [
        '1' => ['1'],
        '2' => ['1'],
        '3' => ['1'],
        '4' => ['1']
    ],
    'BSED-ENG' => [
        '1' => ['1'],
        '2' => ['1'],
        '3' => ['1'],
        '4' => ['1']
    ],
    'BSED-MT' => [
        '1' => ['1'],
        '2' => ['1'],
        '3' => ['1'],
        '4' => ['1']
    ],
    'BSED-HE' => [
        '1' => ['1'],
        '2' => ['1'],
        '3' => ['1'],
        '4' => ['1']
    ],
    'BSBA-HRM' => [
        '1' => ['1', '2'],
        '2' => ['1', '2'],
        '3' => ['1', '2'],
        '4' => ['1', '2']
    ],
    'BSBA-MM' => [
        '1' => ['1', '2', '3', '4'],
        '2' => ['1', '2', '3', '4'],
        '3' => ['1', '2', '3', '4'],
        '4' => ['1', '2', '3', '4']
    ],
    'BSA' => [
        '1' => ['1', '2'],
        '2' => ['1'],
        '3' => ['1'],
        '4' => ['1']
    ],
    'BSMA' => [
        '1' => ['1'],
        '2' => ['1'],
        '3' => ['1'],
        '4' => ['1'],
    ],
    'BSIE' => [
        '1' => ['1', '2'],
        '2' => ['1', '2'],
        '3' => ['1', '2'],
        '4' => ['1', '2'],
    ]
];

if ($org_name === 'sco') {
    $all_org_sections = [];
    foreach ($org_sections as $program => $sections) {
        foreach ($sections as $year_level => $year_sections) {
            if (!isset($all_org_sections[$program])) {
                $all_org_sections[$program] = [];
            }
            if (!isset($all_org_sections[$program][$year_level])) {
                $all_org_sections[$program][$year_level] = [];
            }
            $all_org_sections[$program][$year_level] = array_merge($all_org_sections[$program][$year_level], $year_sections);
        }
    }
    $org_sections = $all_org_sections;
}
?>