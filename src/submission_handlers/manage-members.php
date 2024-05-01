<?php
$conn = DatabaseConnection::connect();

// Query for verified voters with roles 'Committee Member' or 'Admin Member'
$query = "SELECT * FROM voter WHERE status != ? AND role IN (?, ?)";
$stmt = $conn->prepare($query);
$status = "For Verification";
$role1 = "Committee Member";
$role2 = "Admin Member";
$stmt->bind_param("sss", $status, $role1, $role2);
$stmt->execute();
$verified_tbl = $stmt->get_result();
$stmt->close();

$conn->close();