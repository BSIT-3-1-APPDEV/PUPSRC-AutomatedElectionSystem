<?php
	$org_name = $_SESSION['organization'] ?? '';
	include 'includes/organization-list.php';
	$org_full_name = $org_full_names[$org_name];
	$org_acronym = $org_acronyms[$org_name];
	$org_email = $org_emails[$org_name];
	$facebook = $org_social_media[$org_name]["facebook"];
	$twitter = $org_social_media[$org_name]["twitter"];
	$instagram = $org_social_media[$org_name]["instagram"];
?>