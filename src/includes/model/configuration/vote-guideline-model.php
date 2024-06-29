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
            $result = '';

            foreach (self::$query_data as $item) {

                if (self::$mode === 'update') {
                    $result = self::updateData($item);
                } else if (self::$mode === 'update_sequence') {
                    // $result = self::updateData($item);
                } else if (self::$mode === 'delete') {
                    // $result = self::setData($item);
                } else {
                    $result = self::setData($item);
                }
            }

            self::$connection->commit();

            return $result;
        } catch (Exception $e) {
            // self::$connection->rollback();
            self::$query_message = $e->getMessage();
            return $result;
        }
    }

    private static function setData($item)
    {
        try {

            $result = [];

            $sql = "INSERT INTO vote_guidelines (seq, description) VALUES (?, ?)";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to perform requested action: " . self::$connection->error);
            }

            $stmt->bind_param("ss", $item['sequence'], $item['description']);

            if (!$stmt->execute()) {
                throw new Exception("Failed to add requested action: " . $stmt->error);
            }

            $stmt->close();

            return $item;
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

            $sql = "UPDATE vote_guidelines SET seq = ?,  WHERE = ?";

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

            $sql = "UPDATE vote_guidelines WHERE = ?";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to perform requested action: " . self::$connection->error);
            }

            $stmt->bind_param("i", $item['guideline_id']);

            $stmt->execute();

            $stmt->close();

            return $item;
        } catch (Exception $e) {

            self::$query_message = 'update ' . $e->getMessage();
            return $item;
        }
    }
}
