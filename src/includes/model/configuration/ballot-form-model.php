<?php

include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');

class BallotFormModel
{
    private static $connection;
    protected static $query_message;
    protected static $status;

    public static function getData()
    {
        if (!self::$connection = DatabaseConnection::connect()) {
            throw new Exception("Failed to connect to the database.");
        }

        $fields = [];

        $sql = "SELECT * FROM ballot_config";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $field_id = $seq = $field_name = $field_type = $description = '';

            $stmt->bind_result($field_id, $seq, $field_name, $field_type, $description);

            while ($stmt->fetch()) {
                $field = [
                    // 'schedule_id' => $election_id,
                    'field_id' => $field_id,
                    'seq' => $seq,
                    'field_name' => $field_name,
                    'field_type' => $field_type,
                    'description' => $description,
                ];

                $fields[] = $field;
            }

            $stmt->close();
        } else {
            self::$query_message = "Error preparing statement: " . self::$connection->error;
        }

        return $fields;
    }

    protected static function saveData($data)
    {
        try {

            if (!self::$connection = DatabaseConnection::connect()) {
                throw new Exception("Failed to connect to the database.");
            }

            $sql = "SELECT COUNT(*) FROM election_schedule";
            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error preparing statement.");
            }

            $row_count = null;
            $stmt->execute();
            $stmt->bind_result($row_count);
            $stmt->fetch();
            $stmt->close();

            self::$connection->begin_transaction();
            $result = '';
            if ($row_count > 0) {
                $result = self::updateData($data);
            } else {
                $result = self::setData($data);
            }

            self::$connection->commit();

            // self::$connection->close();
            return $result;
        } catch (Exception $e) {

            self::$query_message = $e->getMessage();
            // self::$connection->close();
            return $result;
        }
    }



    private static function setData($data)
    {
        try {

            $election_schedules = [];

            $sql = "INSERT INTO election_schedule (start, close) VALUES (?, ?)";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error preparing statement: " . self::$connection->error);
            }

            $stmt->bind_param("ss", $data['electionStart'], $data['electionEnd']);

            $stmt->execute();

            $stmt->close();

            return $data;
        } catch (Exception $e) {
            self::$query_message = 'set ' . $e->getMessage();
            return $data;
        }
    }


    private static function updateData($data)
    {
        try {

            $sql = "UPDATE election_schedule SET start = ?, close = ? ";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error preparing statement: " . self::$connection->error);
            }

            $stmt->bind_param("ss", $data['electionStart'], $data['electionEnd']);

            $stmt->execute();

            $stmt->close();

            return $data;
        } catch (Exception $e) {

            self::$query_message = 'update ' . $e->getMessage();
            return $data;
        }
    }
}
