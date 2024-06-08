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
        self::$connection = DatabaseConnection::connect();

        $election_shedules = [];

        $sql = "SELECT * FROM election_schedule ORDER BY year DESC";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $election_id = $start = $end = '';

            $stmt->bind_result($election_id, $start, $end);

            while ($stmt->fetch()) {
                $election_shedule = [
                    'schedule_id' => $election_id,
                    'start' => $start,
                    'end' => $end
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
            $connection = DatabaseConnection::connect();

            if (!$connection) {
                throw new Exception("Failed to connect to the database.");
            }

            $sql = "SELECT COUNT(*) FROM election_schedule";
            $stmt = $connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error preparing statement.");
            }

            $row_count = null;
            $stmt->execute();
            $stmt->bind_result($row_count);
            $stmt->fetch();

            if ($row_count > 0) {
                self::updateData($data);
            } else {
                self::setData($data);
            }

            $stmt->close();
            $connection->close();
        } catch (Exception $e) {

            self::$query_message = $e->getMessage();
        }
    }



    private static function setData($data)
    {
        try {
            self::$connection = DatabaseConnection::connect();

            if (!self::$connection) {
                throw new Exception("Failed to connect to the database.");
            }

            $election_schedules = [];

            $sql = "INSERT INTO election_schedule (start, end) VALUES (?, ?)";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error preparing statement: " . self::$connection->error);
            }

            $start = $data['start'];
            $end = $data['end'];

            $stmt->bind_param("ss", $start, $end);

            $stmt->execute();

            $election_schedules[] = [
                'start' => $start,
                'end' => $end
            ];

            $stmt->close();
            self::$connection->close();

            return $election_schedules;
        } catch (Exception $e) {
            self::$query_message = $e->getMessage();
            return [];
        }
    }


    private static function updateData($data)
    {
        try {
            self::$connection = DatabaseConnection::connect();

            if (!self::$connection) {
                throw new Exception("Failed to connect to the database.");
            }

            $sql = "UPDATE election_schedule SET start = ?, end = ? WHERE schedule_id = ?";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Error preparing statement: " . self::$connection->error);
            }

            $stmt->bind_param("ss", $data['start'], $data['end']);

            $stmt->execute();

            $stmt->close();
            self::$connection->close();

            return $data;
        } catch (Exception $e) {

            self::$query_message = $e->getMessage();
            return $data;
        }
    }
}
