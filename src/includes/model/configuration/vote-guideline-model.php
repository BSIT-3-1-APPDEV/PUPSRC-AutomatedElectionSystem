<?php

include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');

class VoteGuidelineModel
{
    private static $connection;
    protected static $mode;
    protected static $query_data;
    protected static $query_message;
    protected static $status;

    public static function getData()
    {
        if (!self::$connection = DatabaseConnection::connect()) {
            throw new Exception("Failed to connect to the data source.");
        }

        $data = [];

        $sql = "SELECT * FROM vote_guidelines";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $guideline_id = $seq = $description = '';

            $stmt->bind_result($guideline_id, $seq, $description);

            while ($stmt->fetch()) {
                $item = [
                    'guideline_id' => $guideline_id,
                    'sequence' => $seq,
                    'description' => $description,
                ];

                $data[] = $item;
            }

            $stmt->close();
        } else {
            self::$query_message = "Failed to perform requested action: " . self::$connection->error;
        }

        return $data;
    }

    protected static function saveData()
    {
        try {


            if (!self::$connection = DatabaseConnection::connect()) {
                throw new Exception("Failed to connect to the data source.");
            }

            self::$connection->autocommit(FALSE);

            self::$connection->begin_transaction();
            $results = [];
            foreach (self::$query_data as $item) {
                // print_r($item);

                if (self::$mode === 'update') {
                    $results = self::updateData($item);
                } else if (self::$mode === 'update_sequence') {
                    $results[] = self::updateSequence($item);
                } else if (self::$mode === 'delete') {
                    // $results[] = $item;
                    $results[] = self::deleteData($item);
                } else {
                    $results = self::setData($item);
                }
            }

            self::$connection->commit();

            return $results;
        } catch (Exception $e) {
            // self::$connection->rollback();
            self::$query_message = $e->getMessage();
            return $results;
        }
    }

    private static function setData($item)
    {
        try {

            $sql = "INSERT INTO vote_guidelines (seq, description) VALUES (?, ?)";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to perform requested action: " . self::$connection->error);
            }

            $stmt->bind_param("ss", $item['sequence'], $item['description']);

            if (!$stmt->execute()) {
                throw new Exception("Failed to add requested action: " . $stmt->error);
            }

            $inserted_id = mysqli_insert_id(self::$connection);

            $inserted_item = [
                'guideline_id' => $inserted_id,
                'sequence' => $item['sequence'],
                'description' => $item['description'],
            ];

            $stmt->close();

            return $inserted_item;
        } catch (Exception $e) {
            self::$query_message = 'set ' . $e->getMessage();
            return $item;
        }
    }


    private static function updateData($item)
    {
        try {

            $sql = "UPDATE vote_guidelines SET seq = ?, description = ?  WHERE guideline_id  = ?";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to perform requested action: " . self::$connection->error);
            }

            $stmt->bind_param("isi", $item['sequence'], $item['description'], $item['guideline_id']);

            $stmt->execute();

            $stmt->close();

            return $item;
        } catch (Exception $e) {

            self::$query_message = 'update ' . $e->getMessage();
            return $item;
        }
    }

    private static function updateSequence($item)
    {
        try {

            $sql = "UPDATE vote_guidelines SET seq = ?  WHERE guideline_id = ?";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to perform requested action: " . self::$connection->error);
            }

            $stmt->bind_param("ii", $item['sequence'], $item['guideline_id']);

            $stmt->execute();

            $stmt->close();

            return $item;
        } catch (Exception $e) {

            self::$query_message = 'update ' . $e->getMessage();
            return $item;
        }
    }


    private static function deleteData($item)
    {
        try {

            $sql = "DELETE FROM vote_guidelines WHERE guideline_id = ?";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception(self::$connection->error);
            }

            $stmt->bind_param("i", $item['guideline_id']);

            $stmt->execute();

            $stmt->close();

            return $item;
        } catch (Exception $e) {

            self::$query_message = 'Failed to delete selected resource: ' . $e->getMessage();
            return $item;
        }
    }
}
