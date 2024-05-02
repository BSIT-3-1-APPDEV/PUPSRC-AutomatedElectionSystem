<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/file-utils.php');
require_once FileUtils::normalizeFilePath('../session-handler.php');
require_once FileUtils::normalizeFilePath('db-config.php');

class LandingPageController {
    public function processSelectedOrg(){
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_btn'])) {
            $clicked_org = $_POST['submit_btn'];
            $valid_values = array('sco', 'acap', 'aeces', 'elite', 'give', 'jehra', 'jmap', 'jpia', 'piie');
            
            // Checks whether the values of a clicked button is valid.
            // Redirect to landing page again, if no
            if (!in_array($clicked_org, $valid_values)) {
                header("Location: ../../landing-page.php");
                exit();
            }
            else {
                $_SESSION['organization'] = $clicked_org;
                header("Location: ../../voter-login.php");
                exit();       
            }
        }
        else {
            header("Location: ../../landing-page.php");
            exit();
        }
    }
}
// Instantiates LandingPageController class
$landing_page_controller = new LandingPageController();
// Invokes function
$landing_page_controller->processSelectedOrg();
?>