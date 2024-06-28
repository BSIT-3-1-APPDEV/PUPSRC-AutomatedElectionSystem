<?php 
class RoleManager {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::connect();
    }

    public function prepare($query) {
        return $this->conn->prepare($query);
    }

    public function updateRole($voter_id, $new_role) {
        $query = "UPDATE voter SET role = ? WHERE voter_id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }

        $types = 'si'; // 's' for string (role), 'i' for integer (voter_id)
        $stmt->bind_param($types, $new_role, $voter_id);

        $stmt->execute();
        $result = $stmt->affected_rows;

        $stmt->close();
        $this->conn->close();

        return $result > 0 ? "Update Success" : "Update Failed";
    }
}
