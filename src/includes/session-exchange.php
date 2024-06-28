<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'classes/file-utils.php');
require_once FileUtils::normalizeFilePath('session-handler.php');

	$org_name = $_SESSION['organization'] ?? '';
	// Redirect to landing page if organization is not set or null
	if(!$org_name) {
		header("Location: landing-page");
		exit();
	}
	include FileUtils::normalizeFilePath('organization-list.php');
	$org_full_name = $org_full_names[$org_name];
	$org_acronym = $org_acronyms[$org_name];
	$org_email = $org_emails[$org_name];
	$facebook = $org_social_media[$org_name]["facebook"];
	$twitter = $org_social_media[$org_name]["twitter"];
	$instagram = $org_social_media[$org_name]["instagram"];
	$org_personality = $org_personality[$org_name];
?>