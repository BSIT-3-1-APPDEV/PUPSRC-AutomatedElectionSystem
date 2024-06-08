<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath('../error-reporting.php');
include_once 'config-controller.php';
include_once FileUtils::normalizeFilePath('../default-time-zone.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('../model/configuration/endpoint-response.php');
require_once FileUtils::normalizeFilePath('../model/configuration/election-schedule-model.php');

class ElectionYearController extends ElectionYearModel
{
    use EndpointResponse, ConfigGuard;
    private $data;

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

    public function submit()
    {
        if ($this->validate()) {
            echo json_encode($this->data);
        }
        // if ($) {

        //     $response = [
        //         'status' => 'success',
        //         'data' => $data
        //     ];
        //     self::sendResponse(200, $response);
        // } else {
        //     $response = [
        //         'status' => 'error',
        //         'message' => 'Invalid data format'
        //     ];
        //     self::sendResponse(400, $response);
        // }
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

    private function validate()
    {
        if (!isset($this->data['electionStart']) || !isset($this->data['electionEnd'])) {
            return false; // Required keys are missing
        }

        $startDateTime = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $this->data['electionStart']);
        $endDateTime = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $this->data['electionEnd']);

        if (!$startDateTime || !$endDateTime) {
            return false;
        }

        // Compare start and end dates
        if ($startDateTime >= $endDateTime) {
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
        $controller->validateRequestOrigin($controller->getReqData());
        $data = $controller->getData();
        echo json_encode($data);
    }
} catch (Exception $e) {
}
