<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');

class ElectionYearModel
{
    private static $connection;

    public static function fetchData()
    {
        self::$connection = DatabaseConnection::connect();

        $election_years = [];

        $sql = "SELECT * FROM election_year ORDER BY year DESC";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $election_id = $year = $is_current_year = '';

            $stmt->bind_result($election_id, $year, $is_current_year);

            while ($stmt->fetch()) {
                // $election_year = [
                //     'year' => $year,
                //     'is_current_year' => $is_current_year
                // ];

                // $election_years[] = $election_year;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }

        return $election_years;
    }
}
