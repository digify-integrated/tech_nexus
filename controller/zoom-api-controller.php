<?php
session_start();

# -------------------------------------------------------------
#
# Function: ZoomAPIController
# Description: 
# The ZoomAPIController class handles zoom API related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ZoomAPIController {
    private $zoomAPIModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ZoomAPIModel, UserModel and SecurityModel instances.
    # These instances are used for zoom API related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ZoomAPIModel $zoomAPIModel     The ZoomAPIModel instance for zoom API related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ZoomAPIModel $zoomAPIModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->zoomAPIModel = $zoomAPIModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: handleRequest
    # Description: 
    # This method checks the request method and dispatches the corresponding transaction based on the provided transaction parameter.
    # The transaction determines which action should be performed.
    #
    # Parameters:
    # - $transaction (string): The type of transaction.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save zoom API':
                    $this->saveZoomAPI();
                    break;
                case 'get zoom API details':
                    $this->getZoomAPIDetails();
                    break;
                case 'delete zoom API':
                    $this->deleteZoomAPI();
                    break;
                case 'delete multiple zoom API':
                    $this->deleteMultipleZoomAPI();
                    break;
                case 'duplicate zoom API':
                    $this->duplicateZoomAPI();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveZoomAPI
    # Description: 
    # Updates the existing zoom API if it exists; otherwise, inserts a new zoom API.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveZoomAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $zoomAPIID = isset($_POST['zoom_api_id']) ? htmlspecialchars($_POST['zoom_api_id'], ENT_QUOTES, 'UTF-8') : null;
        $zoomAPIName = htmlspecialchars($_POST['zoom_api_name'], ENT_QUOTES, 'UTF-8');
        $zoomAPIDescription = htmlspecialchars($_POST['zoom_api_description'], ENT_QUOTES, 'UTF-8');
        $apiKey = htmlspecialchars($_POST['api_key'], ENT_QUOTES, 'UTF-8');
        $apiSecret = htmlspecialchars($_POST['api_secret'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkZoomAPIExist = $this->zoomAPIModel->checkZoomAPIExist($zoomAPIID);
        $total = $checkZoomAPIExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->zoomAPIModel->updateZoomAPI($zoomAPIID, $zoomAPIName, $zoomAPIDescription, $apiKey, $apiSecret, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'zoomAPIID' => $this->securityModel->encryptData($zoomAPIID)]);
            exit;
        } 
        else {
            $zoomAPIID = $this->zoomAPIModel->insertZoomAPI($zoomAPIName, $zoomAPIDescription, $apiKey, $apiSecret, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'zoomAPIID' => $this->securityModel->encryptData($zoomAPIID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteZoomAPI
    # Description: 
    # Delete the zoom API if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteZoomAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $zoomAPIID = htmlspecialchars($_POST['zoom_api_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkZoomAPIExist = $this->zoomAPIModel->checkZoomAPIExist($zoomAPIID);
        $total = $checkZoomAPIExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->zoomAPIModel->deleteZoomAPI($zoomAPIID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleZoomAPI
    # Description: 
    # Delete the selected zoom APIs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleZoomAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $zoomAPIIDs = $_POST['zoom_api_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($zoomAPIIDs as $zoomAPIID){
            $this->zoomAPIModel->deleteZoomAPI($zoomAPIID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateZoomAPI
    # Description: 
    # Duplicates the zoom API if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateZoomAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $zoomAPIID = htmlspecialchars($_POST['zoom_api_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkZoomAPIExist = $this->zoomAPIModel->checkZoomAPIExist($zoomAPIID);
        $total = $checkZoomAPIExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $zoomAPIID = $this->zoomAPIModel->duplicateZoomAPI($zoomAPIID, $userID);

        echo json_encode(['success' => true, 'zoomAPIID' =>  $this->securityModel->encryptData($zoomAPIID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getZoomAPIDetails
    # Description: 
    # Handles the retrieval of zoom API details such as zoom API name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getZoomAPIDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])) {
            $userID = $_SESSION['user_id'];
            $zoomAPIID = $_POST['zoom_api_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $zoomAPIDetails = $this->zoomAPIModel->getZoomAPI($zoomAPIID);

            $response = [
                'success' => true,
                'zoomAPIName' => $zoomAPIDetails['zoom_api_name'],
                'zoomAPIDescription' => $zoomAPIDetails['zoom_api_description'],
                'apiKey' => $zoomAPIDetails['api_key'],
                'apiSecret' => $zoomAPIDetails['api_secret']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/zoom-api-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ZoomAPIController(new ZoomAPIModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>