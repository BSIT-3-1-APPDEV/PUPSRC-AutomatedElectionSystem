<?php

include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-connector.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/manage-ip-address.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/../default-time-zone.php');

class FormHandler {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::connect();
    }

    public function processForm($postData, $fileData) {
        // Retrieve form data
        $voter_id = $postData['voter_id'];

        // Fetch user data based on voter ID
        $row = $this->getUserData($voter_id);

        if ($row) {
            // Check if organization is selected
            if (isset($postData["org"]) && !empty($postData["org"])) {
                $org = $postData["org"];
                $this->processOrganization($org, $fileData, $row, $voter_id);
            } else {
                // Organization is not selected, display an error message or handle it as needed
                //echo "Please select an organization.";
            }
        } else {
           // echo "Error: Voter ID not found.";
        }
    }

    protected function getUserData($voter_id) {
        $stmt = $this->conn->prepare("SELECT * FROM voter WHERE voter_id = ?");
        $stmt->bind_param('s', $voter_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    private function processOrganization($org, $fileData, $row, $voter_id) {
        $config = DatabaseConfig::getOrganizationDBConfig($org);
        $connection = new \mysqli($config['host'], $config['username'], $config['password'], $config['database']);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        if (isset($fileData["cor"]) && $fileData["cor"]["error"] == UPLOAD_ERR_OK) {
            $cor_file = $fileData["cor"];
            $upload_directory = "../user_data/$org/cor/";
            $filename = basename($cor_file["name"]);
            $target_file = $upload_directory . $filename;

            if (move_uploaded_file($cor_file["tmp_name"], $target_file)) {
                $this->insertVoterData($connection, $filename, $row, $voter_id);
                $this->deletePreviousVoterEntry($voter_id);
            } else {
               // echo "Error: Failed to move uploaded file.";
            }
        } else {
            // echo "Error: File upload failed with error code " . $fileData["cor"]["error"];
        }

        $connection->close();
    }

    private function insertVoterData($connection, $filename, $row, $voter_id) {
        $last_name = !empty($row['last_name']) ? $row['last_name'] : null;
        $first_name = !empty($row['first_name']) ? $row['first_name'] : null;
        $middle_name = !empty($row['middle_name']) ? $row['middle_name'] : null;
        $suffix = !empty($row['suffix']) ? $row['suffix'] : null;
        $year_level = !empty($row['year_level']) ? $row['year_level'] : null;
        $section = !empty($row['section']) ? $row['section'] : null;
        $email = !empty($row['email']) ? $row['email'] : null;
        $password = !empty($row['password']) ? $row['password'] : null;
        $role = !empty($row['role']) ? $row['role'] : null;
        $voter_status = !empty($row['voter_status']) ? $row['voter_status'] : null;
        $vote_status = !empty($row['vote_status']) ? $row['vote_status'] : null;
        $vote_status_updated = !empty($row['vote_status_updated']) ? $row['vote_status_updated'] : null;
        $account_status = 'for_verification';

        $sql = "INSERT INTO voter (last_name, first_name, middle_name, suffix, year_level, section, email, password, 
                role, voter_status, vote_status, vote_status_updated, cor, account_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssssssssssss", $last_name, $first_name, $middle_name, $suffix, $year_level, $section, $email, $password, 
                                            $role, $voter_status, $vote_status, $vote_status_updated, $filename, $account_status);
        
        if ($stmt->execute()) {
            //echo "Success: Data inserted successfully for voter ID $voter_id.";
        } else {
            //echo "Error: Data insertion failed for voter ID $voter_id.";
        }

        $stmt->close();
    }

    private function deletePreviousVoterEntry($voter_id) {
        $stmt_delete = $this->conn->prepare("DELETE FROM voter WHERE voter_id = ?");
        $stmt_delete->bind_param('s', $voter_id);
        
        if ($stmt_delete->execute()) {
           // echo "Success: Previous entry deleted for voter ID $voter_id.";
        } else {
           // echo "Error: Failed to delete previous entry for voter ID $voter_id.";
        }

        $stmt_delete->close();
    }
}

?>
