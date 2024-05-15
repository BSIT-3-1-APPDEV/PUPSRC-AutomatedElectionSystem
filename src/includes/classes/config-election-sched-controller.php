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

    public function __construct()
    {
        $this->data = $this->decodeData();
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
}

// if ($_SERVER['REQUEST_METHOD'] === 'UPDATE') {



// if (isset($decoded_data)) {

// }
// }

// if ($_SERVER['REQUEST_METHOD'] === 'UPDATE') {

// $controller = new ElectionYearController();

// $decoded_data = $controller->getData();

// if (isset($decoded_data) && json_last_error() === JSON_ERROR_NONE) {
//     echo json_encode($decoded_data);
// }
// }


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['getVoterCount']) && $_GET['getVoterCount'] === 'true') {
        $decoded_data = ElectionScheduleController::fetchVoterYearSection();
        echo json_encode($decoded_data);
    } else {
        $decoded_data = ElectionScheduleController::fetchData();
        echo json_encode($decoded_data);
    }
}
