<?php

class VoterManager {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::connect();
    }

    public function prepare($query) {
        return $this->conn->prepare($query);
    }

    public function validateVoter($query, $params) {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($this->conn->error));
        }
        
        call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));
        
        if ($stmt->execute()) {
            return "Update Success";
        } else {
            return "Error: " . htmlspecialchars($stmt->error);
        }
    }

    private function refValues($arr) {
        if (strnatcmp(phpversion(),'5.3') >= 0) {
            $refs = array();
            foreach($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
        return $arr;
    }
}


class QueryExecutor {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function executeQuery($query, $params = [], $types = '') {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($this->conn->error));
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }
}
class Voter {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::connect();
    }

    public function getEmailById($voter_id) {
        $sql = "SELECT email FROM voter WHERE voter_id = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($this->conn->error));
        }

        $stmt->bind_param("i", $voter_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['email'];
        } else {
            return null; // or handle error
        }
    }
}
?>