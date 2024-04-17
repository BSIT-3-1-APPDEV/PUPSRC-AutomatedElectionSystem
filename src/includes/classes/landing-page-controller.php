<?php
require_once '../session-handler.php';
require_once 'db-config.php';

class LandingPageController {
    public function processSelectedOrg(){
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            if(isset($_POST['submit_btn'])) {
                $clicked_org = $_POST['submit_btn'];
                $valid_values = array('sco', 'acap', 'aeces', 'elite', 'give', 'jehra', 'jmap', 'jpia', 'piie');
                
                // Checks whether the values of a clicked button is valid.
                if (!in_array($clicked_org, $valid_values)) {
                    echo "Invalid value submitted.";
                }
                else {
                    $_SESSION['organization'] = $clicked_org;
                    header("Location: ../../voter-login.php");
                    exit();       
                }
            }  
        }

    }
}
// Instantiates LandingPageController class
$landing_page_controller = new LandingPageController();
// Invokes function
$landing_page_controller->processSelectedOrg();
?>