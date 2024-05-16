<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  '../classes/file-utils.php');
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../classes/db-config.php');
require_once FileUtils::normalizeFilePath('../classes/db-connector.php');

class ElectionScheduleModel
{
    private static $connection;
    protected static $query_message;

    protected static function save($data, $mode)
    {

        if (self::$connection = DatabaseConnection::connect()) {

            $some_data = [
                'schedule' => $data['schedule'],
                'schedule_input_id' => $data['schedule_input_id'],
                'yearSection_input_id' => $data['yearSection_input_id'],
            ];

            $year_sections = $data['yearSection_data'];

            $saved_data = [];

            self::$connection->begin_transaction();

            foreach ($year_sections as $item) {

                if ($mode === 'update') {
                    // $saved_data[] = self::updateSequence($item);
                } else {

                    $saved_data[] = self::insert($some_data, $item);
                }
            }

            self::$connection->commit();
            return $saved_data;
        }
    }

    private static function checkYearSectionExist($data)
    {
        $count = '';
        $sql = "SELECT COUNT(*) as count FROM election_schedule WHERE year_level = ? AND section = ?";
        $stmt = self::$connection->prepare($sql);
        $stmt->bind_param("is", $data['year'], $data['section']);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            self::$query_message = "Schedule already exist";
            return false;
        }
        return true;
    }

    private static function insert($some_data, $data)
    {

        $sql = "INSERT INTO election_schedule (year_level, section, schedule) VALUES (?, ?, ?)";
        $stmt = self::$connection->prepare($sql);
        $saved_data = [];
        if ($stmt) {
            if (!self::checkYearSectionExist($data)) return;

            $stmt->bind_param("iis", $data['year'], $data['section'], $some_data['schedule']);
            $stmt->execute();
            $inserted_id = self::$connection->insert_id;
            $saved_data = [
                'data_id' => $inserted_id,
                'schedule_input_id' => $some_data['schedule_input_id'],
                'schedule' => $some_data['schedule'],
                'yearSection_input_id' => $some_data['yearSection_input_id'],
                'year_level' => $data['year'],
                'section' => $data['section']
            ];
        } else {
            self::$query_message = "Error preparing statement: " . self::$connection->error;
        }
        $stmt->close();
        return $saved_data;
    }

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
            self::$query_message = "Error preparing statement: " . self::$connection->error;
        }

        return $election_schedules;
    }

    public static function fetchVoterYearSection()
    {
        self::$connection = DatabaseConnection::connect();

        $voter_year_sections = [];

        $sql = "SELECT year_level, section, COUNT(*) as voter_count 
                FROM voter 
                    WHERE (year_level, section) NOT IN (
                        SELECT DISTINCT year_level, section 
                        FROM election_schedule
                    )
                    GROUP BY year_level, section 
                    ORDER BY section, year_level;
        
                ";

        $stmt = self::$connection->prepare($sql);

        if ($stmt) {
            $stmt->execute();

            $voter_count = $year_level = $section = '';

            $stmt->bind_result($year_level, $section, $voter_count);

            while ($stmt->fetch()) {
                $voter_year_section = [
                    'data_id' => $year_level . $section,
                    'year_level' => $year_level,
                    'section' => $section,
                    'voter_count' => $voter_count
                ];

                $voter_year_sections[] = $voter_year_section;
            }

            $stmt->close();
        } else {
            self::$query_message = "Error preparing statement: " . self::$connection->error;
        }

        return $voter_year_sections;
    }
}
