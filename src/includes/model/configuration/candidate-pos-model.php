<?php
require_once 'includes/classes/db-config.php';
require_once 'includes/classes/db-connector.php';

class CandidatePositon
{
    public static function addPosition()
    {
        if ($connection = DatabaseConnection::connect()) {
            $sql = "INSERT INTO position (position_id, sequence, title) VALUES (NULL, ?, ?)";

            // Prepare the statement
            $stmt = $connection->prepare($sql);

            if ($stmt) {
                // Start transaction
                $connection->begin_transaction();

                foreach ($positions as $position) {
                    $stmt->bind_param("is", $position['sequence'], $position['title']);
                    $stmt->execute();
                }

                $connection->commit();

                $stmt->close();

                echo "Multiple rows inserted successfully.";
            } else {
                echo "Error preparing statement: " . $connection->error;
            }
        }
    }
}
