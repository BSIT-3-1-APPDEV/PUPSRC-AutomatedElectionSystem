<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath('../error-reporting.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('../model/configuration/endpoint-response.php');
require_once FileUtils::normalizeFilePath('../model/configuration/election-schedule-model.php');

class ElectionScheduleController extends ElectionScheduleModel
{
    use EndpointResponse;
    private $data;
    protected $mode;
    protected $valid_year_levels;
    protected $valid_sections;
    protected $min_date;
    protected $max_date;
    protected $message;


    public function __construct($mode)
    {
        date_default_timezone_set('Asia/Manila');
        $this->valid_year_levels = range(1, 5);
        $this->valid_sections = range(1, 4);
        $this->min_date = new DateTime('today');
        $this->max_date = clone $this->min_date;
        $this->max_date->modify('+5 years');
        $this->max_date->setTime(23, 59, 59);

        $this->mode = $mode;
        $this->data = $this->decodeData();
    }

    private function checkInitializations()
    {
        echo "Valid Sections: " . implode(", ", $this->valid_sections) . "\n";
        echo "Valid Year Levels: " . implode(", ", $this->valid_year_levels) . "\n";
        echo "Min Date: " . $this->min_date->format('Y-m-d H:i:s') . "\n";
        echo "Max Date: " . $this->max_date->format('Y-m-d H:i:s') . "\n";
    }

    private function decodeData()
    {
        $json_data = file_get_contents('php://input');
        return json_decode($json_data, true);
    }

    public function getData()
    {
        return $this->data;
    }

    public function submit()
    {

        if ($this->validate()) {
            $saved_data = self::save($this->data, $this->mode);
            if (empty(self::$query_message)) {
                $response = [
                    'status' => 'success',
                    'data' => $saved_data
                ];
                self::sendResponse(200, $response);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => self::$query_message
                ];
                self::sendResponse(400, $response);
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => $this->message
            ];
            self::sendResponse(400, $response);
        }
    }

    private function validate()
    {

        if (!$this->checkSheduleDate()) {
            return false;
        }

        if (!array_key_exists('yearSection_data', $this->data)) {
            $this->message = "Data Incomplete or is corrupted.";
            return false;
        }

        if (!array_key_exists('schedule_input_id', $this->data)) {
            $this->message = "Data Incomplete or is corrupted.";
            return false;
        }

        if (!array_key_exists('yearSection_input_id', $this->data)) {
            $this->message = "Data Incomplete or is corrupted.";
            return false;
        }

        foreach ($this->data['yearSection_data'] as $item) {
            if (
                is_array($item) &&
                array_key_exists('year', $item) &&
                array_key_exists('section', $item)
            ) {

                if (!isset($item['year']) || trim($item['year']) === '') {
                    $this->message = "Year level is missing or empty.";
                    return false;
                }

                if (!is_numeric($item['year']) || intval($item['year']) != $item['year']) {
                    $this->message = "Year must be an number.";
                    return false;
                }

                if (!in_array($item['year'], $this->valid_year_levels)) {
                    $this->message = "Year level is invalid.";
                    return false;
                }

                if (trim($item['section']) === '') {
                    $this->message = "Section is missing or empty.";
                    return false;
                }

                // Check if 'section' is a valid integer
                if (!is_numeric($item['section']) || intval($item['section']) != $item['section']) {
                    $this->message = "Section must be an number.";
                    return false;
                }

                // Check if 'section' is within the valid range
                if (!in_array($item['section'], $this->valid_sections)) {
                    $this->message = "Section is invalid.";
                    return false;
                }
            } else {
                $this->message = "Year level and Section is missing or empty.";
                return false;
            }
        }

        return true;
    }

    private function checkSheduleDate()
    {

        $dateString = $this->data['schedule'];

        // Attempt to create a DateTime object
        $dateTime = new DateTime($dateString);
        if ($dateTime) {
            // Valid date format
            if ($dateTime >= $this->min_date) {
                if ($dateTime <= $this->max_date) {
                    $this->data['formatted_schedule'] = $this->data['schedule'];
                    $this->data['schedule'] = $dateTime->format('Y-m-d H:i:s');
                } else {
                    // Invalid date - exceeds maximum date
                    $this->message = "Schedule date must be on or before " . $this->max_date->format('Y-m-d H:i:s') . ".";
                }
            } else {
                // Invalid date - falls before minimum date
                $this->message = "Schedule date must be on or after " . $this->min_date->format('Y-m-d') . ".";
                return false;
            }
        } else {
            $this->message = "Invalid Schedule Date";
            return false;
        }

        return true;
    }

    private function sanitizeData()
    {
        // $sanitizedData = [];
        // foreach ($data as $item) {
        //     $sanitizedItem = [];
        //     foreach ($item as $key => $value) {

        //         if (is_string($value)) {
        //             $sanitizedItem[$key] = htmlspecialchars($value);
        //         } elseif (is_array($value)) {
        //             // quick fix for sanitation
        //             $sanitizedItem[$key] = $this->sanitizeArray($value);
        //         } else {
        //             $sanitizedItem[$key] = $value;
        //         }
        //     }

        //     $sanitizedData[] = $sanitizedItem;
        // }
        // return $sanitizedData;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    // $controller = new ElectionScheduleController();

    // $decoded_data = $controller->decodeData();

    // if (isset($decoded_data['update_sequence']) && json_last_error() === JSON_ERROR_NONE) {
    //     $controller->submit($decoded_data['update_sequence']);
    // }

    // echo json_encode($decoded_data);
} else

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $controller = new ElectionScheduleController('insert');

    if (json_last_error() === JSON_ERROR_NONE) {
        $controller->submit();
    }

    // echo json_encode($controller->getData());
} else

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // $controller = new CandidatePositionController('delete');

    // $decoded_data = $controller->decodeData();

    // if (isset($decoded_data['delete_position'])) {
    //     $controller->submit($decoded_data['delete_position']);
    // }

    // echo json_encode($decoded_data);
} else


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['getVoterCount']) && $_GET['getVoterCount'] === 'true') {
        $decoded_data = ElectionScheduleController::fetchVoterYearSection();
        echo json_encode($decoded_data);
    } else {
        $decoded_data = ElectionScheduleController::fetchData();
        echo json_encode($decoded_data);
    }
}
