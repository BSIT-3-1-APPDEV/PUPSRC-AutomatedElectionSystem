<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  'file-utils.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('../model/configuration/candidate-pos-model.php');
trait EndpointResponse
{
    protected static function sendResponse($statusCode, $body, $terminate = false)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($body);
        $terminate && exit;
    }
}

class CandidatePositionController extends CandidatePosition
{
    use EndpointResponse;

    public function submit($data, $mode = 'data')
    {
        $validation_func = $this->selectValidation($mode);

        if ($validation_func) {

            self::savePosition($data);

            $response = [
                'status' => 'success',
                'data' => $data
            ];
            self::sendResponse(200, $response);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid data format'
            ];
            self::sendResponse(400, $response);
        }
    }

    private function selectValidation($mode)
    {
        if ($mode === 'data') {
            return function ($data) {
                return $this->validate($data);
            };
        } elseif ($mode === 'sequence') {
            return function ($data) {
                return $this->validateSequence($data);
            };
        }
    }


    public function fetch()
    {
        return self::getPositions();
    }

    private function validate($data)
    {
        foreach ($data as $item) {
            print_r($item);
            if (
                is_array($item) &&
                array_key_exists('input_id', $item) &&
                array_key_exists('data_id', $item) &&
                array_key_exists('sequence', $item) &&
                array_key_exists('value', $item) &&
                array_key_exists('description', $item)
            ) {
                echo "key exist";

                if (!isset($item['sequence']) || trim($item['sequence']) === '') {
                    echo "seq {$item['sequence']}";
                    return false;
                }

                if (!isset($item['value']) || trim($item['value']) === '') {
                    echo "val {$item['value']}";
                    return false;
                }

                if (!isset($item['input_id']) || trim($item['input_id']) === '') {
                    echo "inp id {$item['input_id']}";
                    return false;
                }
            } else {
                // If any required key is missing or item is not an array, return false
                return false;
            }
        }

        return true;
    }

    public function validateSequence($data)
    {
        foreach ($data['update_sequence'] as $item) {

            print_r($item);
            if (
                is_array($item) &&
                array_key_exists('data_id', $item)
            ) {
                echo "keys exist ";
                if (!isset($item['data_id']) || trim($item['data_id']) === '') {
                    echo "data id {$item['data_id']}";
                    return false;
                }

                if (!isset($item['sequence']) || trim($item['sequence']) === '') {
                    echo "seq {$item['sequence']}";
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
    $controller = new CandidatePositionController();

    $json_data = file_get_contents('php://input');

    $decoded_data = json_decode($json_data, true);

    if (isset($decoded_data['update_sequence'])) {
        $controller->submit($decoded_data, 'sequence');
    } else {
        $controller->submit($decoded_data);
    }

    // echo json_encode($decoded_data);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller  = new CandidatePositionController();
    $position_data = $controller->fetch();
    echo json_encode($position_data);
}
