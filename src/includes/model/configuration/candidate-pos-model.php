<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');


class CandidatePosition
{
    private static $connection;
    protected static $query_message;
    protected static $status;

    protected static function savePosition($data, $mode)
    {

        if (self::$connection = DatabaseConnection::connect()) {
            $savedPositions = [];

            self::$connection->begin_transaction();
            foreach ($data as $item) {

                $item['description'] = json_encode($item['description']);


                if ($mode === 'sequence') {
                    $savedPositions[] = self::updateSequence($item);
                } else if ($mode === 'delete') {
                    $savedPositions[] = self::checkCandidates($item);
                } else if (!empty($item['data_id'])) {
                    // Check if data is not blank
                    if (filter_var($item['data_id'], FILTER_VALIDATE_INT) !== false) {
                        $item['data_id'] = (int) $item['data_id'];
                        $savedPositions[] = self::updatePosition($item);
                    } else {
                        // Data is not an integer
                    }
                } else {
                    // Data is blank
                    $savedPositions[] = self::addPosition($item);
                }
            }

            self::$connection->commit();
            return $savedPositions;
        }
    }

    private static function addPosition($data)
    {

        $check_sql = "SELECT COUNT(*) FROM position WHERE title = ?";
        $check_stmt = self::$connection->prepare($check_sql);
        if ($check_stmt) {
            $count = null;
            $check_stmt->bind_param("s", $data['value']);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                self::$query_message = 'Position Already Exist';
                return $data;
            }
        } else {
            self::$query_message = "Error preparing check statement: " . self::$connection->error;
            return $data;
        }

        $sql = "INSERT INTO position (sequence, title, max_votes, description) VALUES (?, ?, ?, ?)";

        $stmt = self::$connection->prepare($sql);
        $position = [];
        if ($stmt) {
            // $encoded_description = json_encode($data['description']);
            // $stmt->bind_param("iss", $data['sequence'], $data['value'], $encoded_description);
            $stmt->bind_param("isis", $data['sequence'], $data['value'], $data['max_votes'], $data['description']);
            $stmt->execute();
            $inserted_id = self::$connection->insert_id;
            $position = [
                'input_id' => $data['input_id'],
                'data_id' => $inserted_id,
                'sequence' => $data['sequence'],
                'value' => $data['value'],
                'max_votes' => $data['max_votes'],
                'description' => $data['description']
            ];
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }
        $stmt->close();
        return $position;
    }

    private static function updatePosition($data)
    {
        $sql = "UPDATE position SET sequence = ?, title = ?, max_votes = ?, description = ? WHERE position_id = ?";

        $stmt = self::$connection->prepare($sql);
        $position = [];
        if ($stmt) {

            // $encoded_description = json_encode($data['description']);
            // $stmt->bind_param("issi", $data['sequence'], $data['value'], $encoded_description, $data['data_id']);
            $stmt->bind_param("isisi", $data['sequence'], $data['value'], $data['max_votes'], $data['description'], $data['data_id']);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {

                $position = [
                    'input_id' => $data['input_id'],
                    'data_id' => $data['data_id'],
                    'sequence' => $data['sequence'],
                    'value' => $data['value'],
                    'max_votes' => $data['max_votes'],
                    'description' => $data['description']
                ];
            } else {
                // No rows were affected (no matching data_id found)

            }
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }
        $stmt->close();
        return $position;
    }

    protected static function updateSequence($data)
    {
        $sql = "UPDATE position SET sequence = ? WHERE position_id = ?";

        $stmt = self::$connection->prepare($sql);
        $position = [];
        if ($stmt) {


            $stmt->bind_param("ii", $data['sequence'], $data['data_id']);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {

                $position = [
                    'input_id' => $data['input_id'],
                    'data_id' => $data['data_id'],
                    'sequence' => $data['sequence'],
                    'value' => $data['value'],
                    'max_votes' => $data['max_votes'],
                    'description' => $data['description']
                ];
            }
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }
        $stmt->close();
        return $position;
    }

    protected static function checkCandidates($data)
    {

        if (isset($data['confirmed_delete']) && $data['confirmed_delete']) {
            if (self::deleteCandidates($data)) {
                return self::deletePosition($data);
            } else {
                self::$query_message = 'Error deleting position';
                return $data;
            }
        }
        $sql = "SELECT last_name, first_name, middle_name, suffix, photo_url  FROM candidate WHERE position_id = ?";
        $stmt = self::$connection->prepare($sql);
        $affected_candidates = [];

        if ($stmt) {

            $stmt->bind_param("i", $data['data_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $candidates = [
                    'last_name' => $row['last_name'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'suffix' => $row['suffix'],
                    'photo_url' => 'images/candidate-profile/' . $row['photo_url']
                ];
                $affected_candidates[] = $candidates;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }

        if (empty($affected_candidates)) {
            return self::deletePosition($data);
        } else {
            $data = [
                'confirmed_delete' => false,
                'input_id' => $data['input_id'],
                'data_id' => $data['data_id'],
                'sequence' => $data['sequence'],
                'value' => $data['value'],
                'max_votes' => $data['max_votes'],
                'description' => $data['description'],
                'affected_candidates' => $affected_candidates
            ];
            self::$status = 409;
            self::$query_message = 'Confirm Deletion of Positions';  // Will replace with better message
            return $data;
        }
    }


    protected static function deletePosition($data)
    {
        $sql = "DELETE FROM position WHERE position_id = ?";

        $stmt = self::$connection->prepare($sql);
        $position = [];
        if ($stmt) {
            // Bind position_id
            $stmt->bind_param("i", $data['data_id']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Row was deleted successfully
                $position = [
                    'input_id' => $data['input_id'],
                    'data_id' => $data['data_id'],
                    'sequence' => $data['sequence'],
                    'value' => $data['value'],
                    'max_votes' => $data['max_votes'],
                    'description' => $data['description']
                ];
            } else {
                self::$query_message = 'No position deleted';
            }
        } else {
            self::$query_message = "Error preparing statement: " . self::$connection->error;
        }
        $stmt->close();
        return $position;
    }


    protected static function getPositions()
    {
        self::$connection = DatabaseConnection::connect();

        $positions = [];

        // SQL query to select all positions
        $sql = "SELECT * FROM position";

        // Prepare and execute the statement
        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $position_id =  $sequence = $title = $max_votes = $description = '';

            // Bind result variables
            $stmt->bind_result($position_id, $sequence, $title, $max_votes, $description);

            while ($stmt->fetch()) {
                $position = [
                    'input_id' => $position_id,
                    'data_id' => $position_id,
                    'sequence' => $sequence,
                    'value' => $title,
                    'max_votes' => $max_votes,
                    'description' => $description
                ];

                $positions[] = $position;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }


        return $positions;
    }

    protected static function deleteCandidates($data, $retryCount = 0)
    {
        self::$connection = DatabaseConnection::connect();

        $sql = "DELETE FROM candidate WHERE position_id = ?";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            try {
                // bind position_id
                $stmt->bind_param("i", $data['data_id']);
                $stmt->execute();

                if ($stmt->affected_rows === count($data['affected_candidates'])) {
                    // Row was deleted successfully
                    return true;
                }
                // else if ($stmt->affected_rows > 0) {
                //     self::$query_message = 'Some Candidates deleted';
                // } else {
                //     $deleteVoteIsSuccess = self::deleteVotes($data);
                //     if ($deleteVoteIsSuccess) {
                //         if ($deleteVoteIsSuccess && $retryCount < 1) {
                //             return self::deleteCandidates($data, $retryCount + 1);
                //         }
                //     }
                // }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() === 1451) { // Error code for foreign key constraint failure
                    $deleteVoteIsSuccess = self::deleteVotes($data);
                    if ($deleteVoteIsSuccess && $retryCount < 1) { // prevent infinite recursion
                        return self::deleteCandidates($data, $retryCount + 1);
                    } else {
                    }
                } else {
                    self::$query_message = "Error executing statement: " . $e->getMessage();
                }
            }

            $stmt->close();
        } else {
            self::$query_message =  "Error preparing statement: " . self::$connection->error;
        }
    }

    protected static function deleteVotes($data)
    {

        self::$connection = DatabaseConnection::connect();

        $sql = "DELETE FROM vote WHERE position_id = ?";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            // bind position_id
            $stmt->bind_param("i", $data['data_id']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                self::$query_message = 'No votes removed';
                return false;
            }

            $stmt->close();
        } else {
            self::$query_message =  "Error preparing statement: " . self::$connection->error;
        }
    }
}
