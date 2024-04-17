<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  'file-utils.php');
require_once FileUtils::normalizeFilePath('src/includes/session-handler.php');

class EndpointResponse
{
    protected  function sendResponse($statusCode, $body)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        exit;
    }
}
class CandidatePositionController extends EndpointResponse
{

    public function submit($data)
    {
        if ($this->validate($data)) {
            $response = [
                'status' => 'success',
                'data' => $data
            ];
            $this->sendResponse(200, $response);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid data format'
            ];
            $this->sendResponse(400, $response);
        }
    }
    public function fetch()
    {
    }

    private function validate($data)
    {
        foreach ($data as $item) {
            if (
                is_array($item) &&
                array_key_exists('input_id', $item) &&
                array_key_exists('data_id', $item) &&
                array_key_exists('sequence', $item) &&
                array_key_exists('value', $item)
            ) {

                if (!is_string($item['sequence']) || empty($item['sequence'])) {
                    return false;
                }

                if (!is_string($item['value']) || empty($item['value'])) {
                    return false;
                }

                if (!is_string($item['input_id']) || empty($item['input_id'])) {
                    return false;
                }
            } else {
                // If any required key is missing or item is not an array, return false
                return false;
            }
        }

        return true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');

    $decoded_data = json_decode($json_data, true);

    $position = new CandidatePositionController();
    $position->submit($decoded_data);

    // echo json_encode($decoded_data);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($_GET);
}
