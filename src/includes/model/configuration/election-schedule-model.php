<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');

class ElectionScheduleModel
{
    private static $connection;

    public static function fetchData()
    {
        self::$connection = DatabaseConnection::connect();

        $election_schedules = [];

        $sql = "SELECT * FROM election_schedule ORDER BY section and year_level";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $schedule_id = $year_level = $section = $schedule = '';

            $stmt->bind_result($schedule_id, $year_level, $section, $schedule);

            while ($stmt->fetch()) {
                $election_schedule = [
                    'data_id' => $schedule_id,
                    'year_level' => $year_level,
                    'section' => $section,
                    'schedule' => $schedule
                ];

                $election_schedules[] = $election_schedule;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }

        return $election_schedules;
    }

    public static function fetchVoterYearSection()
    {
        self::$connection = DatabaseConnection::connect();

        $voterYearSections = [];

        $sql = "SELECT year_level, section, COUNT(*) as voter_count 
                FROM voter 
                GROUP BY year_level, section 
                ORDER BY section, year_level";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $voter_count = $year_level = $section = '';

            $stmt->bind_result($year_level, $section, $voter_count);

            while ($stmt->fetch()) {
                $voterYearSection = [
                    'data_id' => $year_level . $section,
                    'year_level' => $year_level,
                    'section' => $section,
                    'voter_count' => $voter_count
                ];

                $voterYearSections[] = $voterYearSection;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . self::$connection->error;
        }

        return $voterYearSections;
    }
}
