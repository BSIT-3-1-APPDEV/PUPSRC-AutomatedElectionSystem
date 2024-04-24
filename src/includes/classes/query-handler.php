<?php

class VoterManager {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::connect();
    }

    public function validateVoter($voter_id, $update_query) {
        if(mysqli_query($this->conn, $update_query)) {
            return "Insert Success";
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }
}

class QueryExecutor {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function executeQuery($query) {
        $result = $this->conn->query($query);
        return $result;
    }
}
?>