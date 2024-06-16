<?php

include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');

class ElectionYearModel
{
    private static $connection;
    protected static $query_message;
    protected static $status;

    public static function getData()
    {
        if (!self::$connection = DatabaseConnection::connect()) {
            throw new Exception("Failed to connect to the database.");
        }

        $election_shedules = [];

        $sql = "SELECT start, close FROM election_schedule";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $election_id = $start = $end = '';

            $stmt->bind_result($start, $end);

            while ($stmt->fetch()) {
                $election_shedule = [
                    // 'schedule_id' => $election_id,
                    'electionStart' => $start,
                    'electionEnd' => $end
                ];

                $election_shedules[] = $election_shedule;
            }

            $stmt->close();
        } else {
            self::$query_message = "Error preparing statement: " . self::$connection->error;
        }

        return $election_shedules;
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
