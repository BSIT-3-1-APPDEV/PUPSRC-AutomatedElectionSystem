<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once '../classes/db-config.php';
require_once '../classes/db-connector.php';

$_SESSION['organization'] = 'acap';

class CandidatePosition
{
    private static $connection;
    private static $update_sequence;

    protected static function savePosition($data)
    {
        if (self::$connection = DatabaseConnection::connect()) {
            $savedPositions = [];

            self::$connection->begin_transaction();

            if (isset($data['update_sequence'])) {
                $data = $data['update_sequence'];
                self::$update_sequence = true;
            }

            foreach ($data as $item) {
                if (self::$update_sequence) {
                    self::updateSequence($item);
                } else if (!empty($item['data_id'])) {
                    // Check if data is not blank
                    if (filter_var($item['data_id'], FILTER_VALIDATE_INT) !== false) {
                        $item['data_id'] = (int) $item['data_id'];
                        self::updatePosition($item);
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

    private static function addPosition($position)
    {

        $sql = "INSERT INTO position (sequence, title, description) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = self::$connection->prepare($sql);

        if ($stmt) {


            $stmt->bind_param("iss", $position['sequence'], $position['value'], $position['description']);
            $stmt->execute();
            $inserted_id = self::$connection->insert_id;
            $position = [
                'input_id' => $position['input_id'],
                'data_id' => $inserted_id,
                'sequence' => $position['sequence'],
                'value' => $position['value'],
                'description' => $position['description']
            ];
            $stmt->close();
            return $position;
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }
    }

    private static function updatePosition($position)
    {
        $sql = "UPDATE position SET sequence = ?, title = ?, description = ? WHERE position_id = ?";

        // Prepare the statement
        $stmt = self::$connection->prepare($sql);

        if ($stmt) {


            $stmt->bind_param("issi", $position['sequence'], $position['value'], $position['description'], $position['data_id']);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }
    }

    protected static function updateSequence($seqeunce)
    {
        $sql = "UPDATE position SET sequence = ? WHERE position_id = ?";

        // Prepare the statement
        $stmt = self::$connection->prepare($sql);

        if ($stmt) {


            $stmt->bind_param("ii", $seqeunce['sequence'], $seqeunce['data_id']);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }
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

            $position_id =  $sequence = $title = $description = '';

            // Bind result variables
            $stmt->bind_result($position_id, $sequence, $title, $description);

            while ($stmt->fetch()) {
                $position = [
                    'input_id' => $position_id,
                    'data_id' => $position_id,
                    'sequence' => $sequence,
                    'value' => $title,
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
}
