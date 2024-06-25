<?php

include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');

class BallotFormModel
{
    private static $connection;
    private static $default_form_names = ['Student Name', 'Section', 'Candidate Form'];
    protected static $query_data;
    protected static $query_message;
    protected static $status;

    public static function getData()
    {
        if (!self::$connection = DatabaseConnection::connect()) {
            throw new Exception("Failed to connect to the data source.");
        }

        $fields = [];

        $sql = "SELECT * FROM ballot_config ORDER BY seq ASC";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $field_id = $group_id = $seq = $field_name = $field_type = $description =  $attributes = '';

            $stmt->bind_result($field_id, $group_id, $seq, $field_name, $field_type, $description, $attributes);

            while ($stmt->fetch()) {
                $field = [
                    // 'schedule_id' => $election_id,
                    'field_id' => $field_id,
                    'group_id' => $group_id,
                    'sequence' => $seq,
                    'field_name' => $field_name,
                    'field_type' => $field_type,
                    'description' => $description,
                    'attributes' => $attributes,
                ];

                $fields[] = $field;
            }

            $stmt->close();
        } else {
            self::$query_message = "Failed to perform requested action: " . self::$connection->error;
        }

        return $fields;
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

            foreach (self::$query_data as $data) {


                if (self::checkIsDefault($data)) {
                    if (self::checkDefaultFieldExist($data)) {
                    }
                } else {
                    $result = self::setData(self::$query_data);
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



    /**
     * Checks if the 'attributes' key in query_data has a 'default' key with a boolean value of True.
     *
     * @param array $query_data The data array containing potentially nested attributes.
     * @return bool True if the 'attributes' key has a 'default' key with a boolean value of True, False otherwise.
     *              False is also returned if the value cannot be converted to a boolean.
     */
    private static function checkIsDefault($data): bool
    {

        if (!array_key_exists('attributes', $data)) {
            return false;
        }

        $attributes = $data['attributes'];

        if (!array_key_exists('default', $attributes)) {
            return false;
        }

        if (!in_array($data, self::$default_form_names, true)) {
            return false;
        }


        try {
            return (bool) $attributes['default'];
        } catch (Exception $e) {
            return false;
        }
    }

    private static function checkDefaultFieldExist($data): bool
    {

        $field_name = $data['field_name'];
        $sql = "SELECT COUNT(*) FROM ballot_config WHERE field_name = ? ";
        $stmt = self::$connection->prepare($sql);

        if (!$stmt) {
            throw new Exception("Failed to perform requested action: " . self::$connection->error);
        }

        $stmt->bind_param("s", $field_name);

        $row_count = null;
        if (!$stmt->execute()) {
            throw new Exception("Failed to perform requested action: " . $stmt->error);
        }
        $stmt->bind_result($row_count);
        $stmt->fetch();
        $stmt->close();

        if ($row_count > 0) {
            return false;
        }

        return true;
    }




    private static function setData($data)
    {
        try {

            $result = [];

            $sql = "INSERT INTO ballot_config (seq, field_name, field_type, description, attrib) VALUES (?, ?, ?, ?, ?)";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to perform requested action: " . self::$connection->error);
            }

            $stmt->bind_param("ss", self::$query_data['electionStart'], self::$query_data['electionEnd']);

            if (!$stmt->execute()) {
                throw new Exception("Failed to add requested action: " . $stmt->error);
            }

            $stmt->close();

            return self::$query_data;
        } catch (Exception $e) {
            self::$query_message = 'set ' . $e->getMessage();
            return self::$query_data;
        }
    }


    private static function updateData()
    {
        try {

            $sql = "UPDATE election_schedule SET start = ?, close = ? ";

            $stmt = self::$connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to perform requested action: " . self::$connection->error);
            }

            $stmt->bind_param("ss", self::$query_data['electionStart'], self::$query_data['electionEnd']);

            $stmt->execute();

            $stmt->close();

            return self::$query_data;
        } catch (Exception $e) {

            self::$query_message = 'update ' . $e->getMessage();
            return self::$query_data;
        }
    }
}
