<?php
include_once str_replace('/', DIRECTORY_SEPARATOR,  'file-utils.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('../model/configuration/candidate-pos-model.php');
require_once FileUtils::normalizeFilePath('../model/configuration/endpoint-response.php');


class CandidatePositionController extends CandidatePosition
{
    use EndpointResponse;
    private $mode;

    public function __construct($mode = 'data')
    {
        $this->mode = $mode;
    }

    public function decodeData()
    {
        $json_data = file_get_contents('php://input');
        return json_decode($json_data, true);
    }

    public function submit($data)
    {
        $validation_func = $this->selectValidation();

        if ($validation_func) {
            $data = $this->sanitizeData($data);
            $data = self::savePosition($data, $this->mode);

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

    private function sanitizeData($data)
    {
        $sanitizedData = []; // Create an empty array to store sanitized data
        // Loop through each object in the $data array
        foreach ($data as $item) {
            $sanitizedItem = []; // Create an empty array to store sanitized item
            // Loop through each key-value pair in the object
            foreach ($item as $key => $value) {
                // If the value is a string, apply htmlspecialchars to it
                if (is_string($value)) {
                    $sanitizedItem[$key] = htmlspecialchars($value);
                } else {
                    // If not a string, keep the value as is
                    $sanitizedItem[$key] = $value;
                }
            }
            // Add the sanitized item to the sanitizedData array
            $sanitizedData[] = $sanitizedItem;
        }
        // Return the sanitized data
        return $sanitizedData;
    }


    private function selectValidation()
    {
        if ($this->mode === 'data' || $this->mode === 'delete') {
            return function ($data) {
                return $this->validate($data);
            };
        } elseif ($this->mode === 'sequence') {
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
        foreach ($data as $item) {

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

if ($_SERVER['REQUEST_METHOD'] === 'UPDATE') {
    $controller = new CandidatePositionController('sequence');

    $decoded_data = $controller->decodeData();

    if (isset($decoded_data['update_sequence'])) {
        $controller->submit($decoded_data['update_sequence']);
    }

    // echo json_encode($decoded_data);
} else

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $controller = new CandidatePositionController();

    $decoded_data = $controller->decodeData();

    $controller->submit($decoded_data);

    // echo json_encode($decoded_data);
} else

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $controller = new CandidatePositionController('delete');

    $decoded_data = $controller->decodeData();

    if (isset($decoded_data['delete_position'])) {
        $controller->submit($decoded_data['delete_position']);
    }

    // echo json_encode($decoded_data);
} else

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller  = new CandidatePositionController();
    $position_data = $controller->fetch();
    echo json_encode($position_data);
}
