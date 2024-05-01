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

class Voter {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::connect();
    }

    public function getEmailById($voter_id) {
        $sql = "SELECT email FROM voter WHERE voter_id = $voter_id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['email'];
        } else {
            return null; // or handle error
        }
    }
}
?>