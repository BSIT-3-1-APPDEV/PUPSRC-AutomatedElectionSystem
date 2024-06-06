<?php
$conn = DatabaseConnection::connect();

$voter_id = $_GET['voter_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_role = $_POST['dropdown'];

    // Map the dropdown values to the actual roles
    $role_map = [
        'admin' => 'Admin',
        'head_admin' => 'Head Admin',
    ];

    if (isset($role_map[$new_role])) {
        // Update the role in the database
        $query = "UPDATE voter SET role = ?, role_updated = NOW() WHERE voter_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $role_map[$new_role], $voter_id);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
    }
} else {
    // Fetch the voter details from the database
    $query = "SELECT *, DATE_FORMAT(acc_created, '%M %d, %Y') as formatted_account_created FROM voter WHERE voter_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $voter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $voter = $result->fetch_assoc();
}
?>