<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath('../error-reporting.php');
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
            if (empty(self::$query_message)) {
                $response = [
                    'status' => 'success',
                    'data' => $data
                ];
                self::sendResponse(200, $response);
            } else {
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
            $response = [
                'status' => 'error',
                'message' => 'Invalid data format'
            ];
            self::sendResponse(400, $response);
        }
    }

    private function sanitizeData($data)
    {
        $sanitizedData = [];
        foreach ($data as $item) {
            $sanitizedItem = [];
            foreach ($item as $key => $value) {

                if (is_string($value)) {
                    $sanitizedItem[$key] = htmlspecialchars($value, ENT_NOQUOTES);
                } elseif (is_array($value)) {
                    // quick fix for sanitation
                    $sanitizedItem[$key] = $this->sanitizeArray($value);
                } else {
                    $sanitizedItem[$key] = $value;
                }
            }

            $sanitizedData[] = $sanitizedItem;
        }
        return $sanitizedData;
    }

    private function sanitizeArray($array)
    {
        $sanitizedArray = [];
        // echo " \n</br> starting description array ";
        // print_r($array);
        // echo "\n</br>";

        if (array_key_exists('ops', $array)) {
            $data = $array['ops'];

            $sanitizedString = [];
            foreach ($data as $key => $element) {
                // echo " \n</br> acessing ops array ";
                // print_r($key);
                // print_r($element);
                // echo "\n</br>";
                $sanitizedString[$key] = $element;
                if (is_array($element) && array_key_exists('insert', $element)) {
                    $sanitizedString[$key]['insert'] = htmlspecialchars($element['insert'], ENT_NOQUOTES);
                }
            }

            $sanitizedArray = [
                'ops' => $sanitizedString
            ];
        } else {
            foreach ($array as $element) {
                // echo " \n</br> acessing array ";
                // print_r($element);
                // echo "\n</br>";
                if (is_array($element)) {
                    // If it's an array, sanitize each element recursively
                    $sanitizedArray[] = $this->sanitizeArray($element);
                } elseif (is_string($element)) {
                    $sanitizedArray[] = htmlspecialchars($element, ENT_NOQUOTES);
                } else {
                    // If not a string or array, keep the value as is
                    $sanitizedArray[] = $element;
                }
            }
        }
        return $sanitizedArray;
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
            if (
                is_array($item) &&
                array_key_exists('input_id', $item) &&
                array_key_exists('data_id', $item) &&
                array_key_exists('sequence', $item) &&
                array_key_exists('value', $item) &&
                array_key_exists('max_votes', $item) &&
                array_key_exists('description', $item)
            ) {


                if (isset($item['data_id']) || trim($item['data_id']) === '') {
                    $item['data_id'] = trim($item['data_id']);
                    if (!ctype_digit($item['data_id'])) {

                        return false;
                    }
                }

                if (!isset($item['sequence']) || trim($item['sequence']) === '') {

                    return false;
                }

                $item['sequence'] = trim($item['sequence']);

                if (!ctype_digit($item['sequence'])) {

                    return false;
                }

                if (!isset($item['value']) || trim($item['value']) === '') {

                    return false;
                } else if ($this->mode !== 'delete') {
                    $item['value'] = $this->clearInvalidValue($item['value']);
                }

                if (!isset($item['max_votes']) || trim($item['max_votes']) === '') {

                    return false;
                }

                $item['max_votes'] = trim($item['max_votes']);

                if (!ctype_digit($item['max_votes'])) {

                    return false;
                }

                if (!isset($item['input_id']) || trim($item['input_id']) === '') {

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
                if (!isset($item['data_id']) || trim($item['data_id']) === '') {
                    return false;
                }

                if (!isset($item['sequence']) || trim($item['sequence']) === '') {
                    return false;
                }
            } else {
                // If any required key is missing or item is not an array, return false
                return false;
            }
        }

        return true;
    }

    private function clearInvalidValue($value)
    {
        $value = preg_replace('/-+/', '-', $value);  // Replace consecutive dashes with a single dash
        $value = preg_replace('/\.+/', '.', $value); // Replace consecutive periods with a single period
        $value = preg_replace('/ +/', ' ', $value);  // Replace consecutive spaces with a single space

        // Remove invalid characters
        $value = preg_replace('/[^a-zA-Z .\-]/', '', $value);

        // Trim the value to ensure it doesn't exceed maximum length
        $value = substr($value, 0, 50);
    }
}

$allowed_roles = ['admin', 'head_admin'];
$is_page_accessible = isset($_SESSION['voter_id'], $_SESSION['role'], $_SESSION['organization']) &&
    (in_array($_SESSION['role'], $allowed_roles)) &&
    !empty($_SESSION['organization']);

if (!$is_page_accessible) {
    $response = [
        'status' => 'error',
        'message' => 'Unauthorized'
    ];
    (new class
    {
        use EndpointResponse;
    })::sendResponse(401, $response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'UPDATE') {
    $controller = new CandidatePositionController('sequence');

    $decoded_data = $controller->decodeData();

    if (isset($decoded_data['update_sequence']) && json_last_error() === JSON_ERROR_NONE) {
        $controller->submit($decoded_data['update_sequence']);
    }

    // echo json_encode($decoded_data);
} else

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $controller = new CandidatePositionController();

    $decoded_data = $controller->decodeData();

    if (json_last_error() === JSON_ERROR_NONE) {
        $controller->submit($decoded_data);
    }

    // echo json_encode($decoded_data);
} else

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $controller = new CandidatePositionController('delete');

    $decoded_data = $controller->decodeData();

    if (json_last_error() === JSON_ERROR_NONE) {
        if (isset($decoded_data['confirmed_delete'])) {
            $controller->submit($decoded_data['confirmed_delete']);
        } else if (isset($decoded_data['delete_position'])) {
            $controller->submit($decoded_data['delete_position']);
        }
    }

    // echo json_encode($decoded_data);
} else

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller  = new CandidatePositionController();
    $position_data = $controller->fetch();
    echo json_encode($position_data);
}
