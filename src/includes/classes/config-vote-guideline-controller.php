<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath('../error-reporting.php');
include_once 'config-controller.php';
include_once 'date-time-utils.php';
include_once FileUtils::normalizeFilePath('../default-time-zone.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('../model/configuration/endpoint-response.php');
require_once FileUtils::normalizeFilePath('../model/configuration/vote-guideline-model.php');

class VoteGuidelineController extends VoteGuidelineModel
{
    use EndpointResponse, ConfigGuard;
    private $data;
    private $client_error;
    private $maxInt = 16_777_214;
    private $maxTextBytes = 65_535;
    private $client_error_dictionary = [
        'ERR_INCOMPLETE_DATA' => 'The request data is incomplete.',
        'ERR_INVALID_DATA' => 'The request data is invalid.',
        'ERR_MAX_SEQ' => 'Sequence number exceeds the maximum allowed value.',
        'ERR_NEGATIVE_SEQ' => 'Sequence number must be positive integer.',
        'ERR_BLANK_SEQ' => 'Sequence number is missing or blank.',
        'ERR_INVALID_SEQ' => 'Sequence number is invalid.',
        'ERR_BLANK_RULE' => 'Voting guideline is missing or blank.',
        'ERR_MAX_RULE_LENGTH' => 'Voting guideline exceeds the maximum length allowed.',
    ];


    public function __construct()
    {
        $this->data = $this->decodeData();
        // echo json_encode($this->data);
    }

    public function decodeData()
    {
        $json_data = file_get_contents('php://input');
        return json_decode($json_data, true);
    }

    public function getReqData()
    {
        $csrf_token = array_pop($this->data);
        return $csrf_token;
    }

    public function getClientErrorCodes()
    {
        return $this->client_error_dictionary;
    }

    public function submit($mode)
    {
        if ($this->validate()) {

            $this->data = $this->sanitizeData($this->data);
            self::$mode = $mode;

            self::$query_data = $this->data;

            $data = self::saveData();

            if (empty(self::$query_message)) {
                $response = [
                    'status' => 'success',
                    'data' => $data
                ];
                self::sendResponse(200, $response);
            } else {
                // self::$query_message = 'Invalid Guideline';
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
        foreach ($this->data as &$item) {
            if (
                is_array($item) &&
                array_key_exists('guideline_id', $item) &&
                array_key_exists('sequence', $item) &&
                array_key_exists('description', $item)
            ) {


                if (!isset($item['guideline_id']) || trim($item['guideline_id']) === '') {
                    $this->client_error = 'ERR_INVALID_DATA';
                    return false;
                }

                $item['guideline_id'] = trim($item['guideline_id']);
                // if (strpos($item['guideline_id'], 'rule-') === 0) {
                //     $extractedNumber = (int) substr($item['guideline_id'], strlen('rule-'));
                //     $item['guideline_id'] = $extractedNumber;
                //     echo $item['guideline_id'];
                // }


                if ($item['guideline_id'] > $this->maxInt && $item['guideline_id'] < 1) {

                    $this->client_error = 'ERR_INVALID_DATA';
                    return false;
                }

                if (!isset($item['sequence']) || trim($item['sequence']) === '') {
                    $this->client_error = 'ERR_BLANK_SEQ';
                    return false;
                }

                $item['sequence'] = trim($item['sequence']);

                if (!ctype_digit($item['sequence'])) {
                    return false;
                    $this->client_error = 'ERR_INVALID_SEQ';
                }

                if ($item['sequence'] > $this->maxInt) {
                    $this->client_error = 'ERR_MAX_SEQ';
                    return false;
                }

                if ($item['sequence'] < 1) {
                    $this->client_error = 'ERR_NEGATIVE_SEQ';
                    return false;
                }

                if (!isset($item['description']) || trim($item['description']) === '') {

                    $this->client_error = 'ERR_BLANK_RULE';
                    return false;
                }
                // else if ($this->mode !== 'delete') {
                //     $item['value'] = $this->clearInvalidValue($item['value']);
                // }

                if (mb_strlen($item['description']) >= $this->maxTextBytes) {
                    $this->client_error = 'ERR_MAX_RULE_LENGTH';
                    return false;
                }
            } else {
                $this->client_error = 'ERR_INCOMPLETE_DATA';
                return false;
            }
        }


        return true;
    }

    private function sanitizeData($data)
    {
        $sanitizedData = [];
        foreach ($data as $item) {
            $sanitizedItem = [];
            foreach ($item as $key => $value) {

                if (is_string($value)) {
                    $sanitizedItem[$key] = htmlspecialchars($value, ENT_NOQUOTES);
                }
            }

            $sanitizedData[] = $sanitizedItem;
        }
        return $sanitizedData;
    }
}


try {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $controller = new VoteGuidelineController();

        if (json_last_error() === JSON_ERROR_NONE) {
            $reqId = $controller->getReqData();
            $controller->validateRequestOrigin($reqId['csrf_token']);
            $controller->submit('update');
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new VoteGuidelineController();

        if (json_last_error() === JSON_ERROR_NONE) {
            $reqId = $controller->getReqData();
            $controller->validateRequestOrigin($reqId['csrf_token']);
            $controller->submit('insert');
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        $controller = new VoteGuidelineController();

        if (json_last_error() === JSON_ERROR_NONE) {
            $reqId = $controller->getReqData();
            $controller->validateRequestOrigin($reqId['csrf_token']);
            $controller->submit('update_sequence');
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller  = new VoteGuidelineController();
        $csrfToken = isset($_GET['csrf']) ? $_GET['csrf'] : null;

        $controller->validateRequestOrigin($csrfToken);
        $data = $controller->getData();

        $errorCodes = $controller->getClientErrorCodes();
        $data = array_merge($data, ['error_codes' => $errorCodes]);
        echo json_encode($data);
    } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $controller = new VoteGuidelineController();

        if (json_last_error() === JSON_ERROR_NONE) {
            $reqId = $controller->getReqData();
            $controller->validateRequestOrigin($reqId['csrf_token']);
            $controller->submit('delete');
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
