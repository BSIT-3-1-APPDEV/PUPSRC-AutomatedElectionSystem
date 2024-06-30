<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath('../error-reporting.php');
include_once 'config-controller.php';
include_once 'date-time-utils.php';
include_once FileUtils::normalizeFilePath('../default-time-zone.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('../model/configuration/endpoint-response.php');
require_once FileUtils::normalizeFilePath('../model/configuration/ballot-form-model.php');

class BallotFormController extends BallotFormModel
{
    use EndpointResponse, ConfigGuard;
    private $data;
    private $client_error;
    private $client_error_dictionary = [
        'ERR_INVALID_START_DATE_FORMAT' => 'The date format is invalid. Please use YYYY-MM-DD.',
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

            self::$query_data = $this->data;

            $data = self::saveData();

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


        return true;
    }
}


try {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $controller = new BallotFormController();

        if (json_last_error() === JSON_ERROR_NONE) {
            $data = $controller->getReqData();
            $controller->validateRequestOrigin($data['csrf_token']);
            $controller->submit();
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller  = new BallotFormController();
        $csrfToken = isset($_GET['csrf']) ? $_GET['csrf'] : null;

        $controller->validateRequestOrigin($csrfToken);
        $data = $controller->getData();

        $errorCodes = $controller->getClientErrorCodes();
        $data = array_merge($data, ['error_codes' => $errorCodes]);
        echo json_encode($data);
    } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $controller = new BallotFormController();

        if (json_last_error() === JSON_ERROR_NONE) {
            $data = $controller->getReqData();
            $controller->validateRequestOrigin($data['csrf_token']);
            $controller->submit();
        }
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
