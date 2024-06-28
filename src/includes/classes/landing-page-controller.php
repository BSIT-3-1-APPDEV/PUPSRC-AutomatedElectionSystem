<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../session-handler.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/db-config.php');
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');

class LandingPageController {
    public function processSelectedOrg(){
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_btn'])) {
            $clicked_org = $_POST['submit_btn'];
            $valid_values = array('sco', 'acap', 'aeces', 'elite', 'give', 'jehra', 'jmap', 'jpia', 'piie');
            
            // Checks whether the values of a clicked button is valid.
            // Redirect to landing page again, if no
            if (!in_array($clicked_org, $valid_values)) {
                header("Location: ../../landing-page");
                exit();
            }
            else {
                if(isset($_SESSION['organization'])) {
                    if($clicked_org != $_SESSION['organization']) {
                        $_SESSION['error_message'] = 'Your session is already set to ' . strtoupper($_SESSION['organization']) . '.';
                        header("Location: ../../voter-login");
                        exit();                        
                    }
                }
                $_SESSION['organization'] = $clicked_org;
                header("Location: ../../voter-login");
                exit();                        
            }
        }
        else {
            header("Location: ../../landing-page");
            exit();
        }
    }
}
// Instantiates LandingPageController class
$landing_page_controller = new LandingPageController();
// Invokes function
$landing_page_controller->processSelectedOrg();
?>