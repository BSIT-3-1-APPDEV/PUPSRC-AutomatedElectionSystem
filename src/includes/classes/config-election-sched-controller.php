<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath('../error-reporting.php');
include_once 'config-controller.php';
include_once 'date-time-utils.php';
include_once FileUtils::normalizeFilePath('../default-time-zone.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('../model/configuration/endpoint-response.php');
require_once FileUtils::normalizeFilePath('../model/configuration/election-schedule-model.php');

class ElectionYearController extends ElectionYearModel
{
    use EndpointResponse, ConfigGuard;
    private $data;
    private $client_error;
    private $client_error_dictionary = [
        'ERR_INVALID_START_DATE_FORMAT' => 'The date format is invalid. Please use YYYY-MM-DD.',
        'ERR_INVALID_END_DATE_FORMAT' => 'The date format is invalid. Please use YYYY-MM-DD.',
        'ERR_INVALID_DATE_FORMAT' => 'The date format is invalid. Please use YYYY-MM-DD.',
        'ERR_INVALID_START_TIME_FORMAT' => 'The time format is invalid. Please use HH:MM.',
        'ERR_INVALID_END_TIME_FORMAT' => 'The time format is invalid. Please use HH:MM.',
        'ERR_INVALID_TIME_FORMAT' => 'The time format is invalid. Please use HH:MM.',
        'ERR_START_DATE_IN_PAST' => 'The start date cannot be in the past.',
        'ERR_END_DATE_BEFORE_START_DATE' => 'The end date cannot be before the start date.',
        'ERR_START_DATE_EXCEEDS_LIMIT' => 'The start of the schedule exceeds the allowed limit.',
        'ERR_END_DATE_EXCEEDS_LIMIT' => 'The end of the schedule exceeds the allowed limit.',
        'ERR_DATES_EXCEEDS_LIMIT' => 'The duration of the schedule exceeds the allowed limit.',
        'ERR_MISSING_START_DATE' => 'Start date is required.',
        'ERR_MISSING_END_DATE' => 'End date is required.',
        'ERR_MISSING_DATES' => 'The schedule is required.',
        'ERR_MISSING_START_TIME' => 'Start time is required.',
        'ERR_MISSING_END_TIME' => 'End time is required.',
        'ERR_MISSING_TIME' => 'The schedule is required.',
        'ERR_START_TIME_IN_PAST' => 'The start time cannot be in the past.',
        'ERR_END_TIME_BEFORE_START_TIME' => 'The end time cannot be before the start time.',
    ];

    public function __construct()
    {
        $this->data = $this->decodeData();
    }

    public function decodeData()
    {
        $json_data = file_get_contents('php://input');
        return json_decode($json_data, true);
    }

    public function getReqData()
    {
        return $this->data;
    }

    public function getClientErrorCodes()
    {
        return $this->client_error_dictionary;
    }

    public function submit()
    {
        if ($this->validate()) {
            $data = self::saveData($this->data);

            if (empty(self::$query_message)) {
                $response = [
                    'status' => 'success',
                    'data' => $data
                ];
                self::sendResponse(200, $response);
            } else {
                self::$query_message = 'Invalid Schedule';
                $response = [
                    'status' => 'error',
                    'message' => self::$query_message,
                    'data' => $data
                ];

                if (!isset(self::$status) || !is_int(self::$status) || self::$status < 100) {
                    self::sendResponse(400, $response);
                } else {
                    self::sendResponse(self::$status, $response);
                }
            }
        } else {
            self::sendResponse(400, $response = [
                'status' => 'error',
                'message' => $this->client_error,
                'data' => $this->data
            ]);
        }
    }

    private function validate()
    {
        // print_r($this->data);
        $isValid = [];
        if (!isset($this->data['electionStart'])) {
            $this->client_error = 'ERR_MISSING_START_DATE';
            $isValid[] = false;
        }

        if (!isset($this->data['electionEnd'])) {
            $this->client_error = 'ERR_MISSING_END_DATE';
            $isValid[] = false;
        }

        if (!in_array(true, $isValid) && count($isValid) > 1) {
            // Both are date are not set or is blank
            $this->client_error = 'ERR_MISSING_DATES';
            return false;
        } elseif (in_array(false, $isValid) && count($isValid) > 0) {
            return false; // At least one validation check failed, return false
        }


        $startDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $this->data['electionStart']);
        $endDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $this->data['electionEnd']);

        if (!$startDateTime) {
            $isValid[] = false;
            $this->client_error = 'ERR_INVALID_START_DATE_FORMAT';
        }

        if (!$endDateTime) {
            $isValid[] = false;
            $this->client_error = 'ERR_INVALID_END_DATE_FORMAT';
        }

        if (!in_array(true, $isValid) && count($isValid) > 1) {
            // Both are invalid dates
            $this->client_error = 'ERR_INVALID_DATE_FORMAT';
            return false;
        } elseif (in_array(false, $isValid) && count($isValid) > 0) {
            // At least one validation check failed, return false
            return false;
        }


        $timeValidator = new TimeValidator;
        if (!$timeValidator->validate($startDateTime)) {
            $this->client_error = 'ERR_START_TIME_IN_PAST';
            return false;
        }

        if ($startDateTime->format('Y-m-d') >= $endDateTime->format('Y-m-d')) {
            $this->client_error = 'ERR_END_DATE_BEFORE_START_DATE';
            return false;
        }

        $dateValidator = new DateValidator;
        if (!$dateValidator->validate($startDateTime)) {
            $this->client_error = 'ERR_START_DATE_EXCEEDS_LIMIT';
            $isValid[] = false;
        }
        if (!$dateValidator->validate($endDateTime)) {
            $this->client_error = 'ERR_END_DATE_EXCEEDS_LIMIT';
            $isValid[] = false;
        }

        if (!in_array(true, $isValid) && count($isValid) > 1) {
            // Both exceed min and max dates
            $this->client_error = 'ERR_DATES_EXCEEDS_LIMIT';
            return false;
        } elseif (in_array(false, $isValid) && count($isValid) > 0) {
            return false; // At least one validation check failed, return false
        }


        if ($startDateTime >= $endDateTime) {
            $this->client_error = 'ERR_END_DATE_BEFORE_START_DATE';
            return false;
        }

        return true;
    }
}


try {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $controller = new ElectionYearController();

        if (json_last_error() === JSON_ERROR_NONE) {
            $data = $controller->getReqData();
            $controller->validateRequestOrigin($data['csrf_token']);
            $controller->submit();
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller  = new ElectionYearController();
        $csrfToken = isset($_GET['csrf']) ? $_GET['csrf'] : null;

        $controller->validateRequestOrigin($csrfToken);
        $data = $controller->getData();
        // $data[] = $controller->getClientErrorCodes();
        $errorCodes = $controller->getClientErrorCodes();
        $data = array_merge($data, ['error_codes' => $errorCodes]);
        echo json_encode($data);
    }
} catch (Exception $e) {
    echo $e->getMessage();
    // (new class
    // {
    //     use EndpointResponse;
    // })::sendResponse(400, $response = [
    //     'status' => 'error',
    //     'message' => 'Bad Request',
    //     'data' => $data
    // ]);
    // exit();
}
