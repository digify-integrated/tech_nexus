<?php
session_start();

# -------------------------------------------------------------
#
# Function: ModeOfAcquisitionController
# Description: 
# The ModeOfAcquisitionController class handles mode of acquisition related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ModeOfAcquisitionController {
    private $modeOfAcquisitionModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ModeOfAcquisitionModel, UserModel and SecurityModel instances.
    # These instances are used for mode of acquisition related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ModeOfAcquisitionModel $modeOfAcquisitionModel     The ModeOfAcquisitionModel instance for mode of acquisition related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ModeOfAcquisitionModel $modeOfAcquisitionModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->modeOfAcquisitionModel = $modeOfAcquisitionModel;
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
                case 'save mode of acquisition':
                    $this->saveModeOfAcquisition();
                    break;
                case 'get mode of acquisition details':
                    $this->getModeOfAcquisitionDetails();
                    break;
                case 'delete mode of acquisition':
                    $this->deleteModeOfAcquisition();
                    break;
                case 'delete multiple mode of acquisition':
                    $this->deleteMultipleModeOfAcquisition();
                    break;
                case 'duplicate mode of acquisition':
                    $this->duplicateModeOfAcquisition();
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
    # Function: saveModeOfAcquisition
    # Description: 
    # Updates the existing mode of acquisition if it exists; otherwise, inserts a new mode of acquisition.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveModeOfAcquisition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modeOfAcquisitionID = isset($_POST['mode_of_acquisition_id']) ? htmlspecialchars($_POST['mode_of_acquisition_id'], ENT_QUOTES, 'UTF-8') : null;
        $modeOfAcquisitionName = htmlspecialchars($_POST['mode_of_acquisition_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkModeOfAcquisitionExist = $this->modeOfAcquisitionModel->checkModeOfAcquisitionExist($modeOfAcquisitionID);
        $total = $checkModeOfAcquisitionExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->modeOfAcquisitionModel->updateModeOfAcquisition($modeOfAcquisitionID, $modeOfAcquisitionName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'modeOfAcquisitionID' => $this->securityModel->encryptData($modeOfAcquisitionID)]);
            exit;
        } 
        else {
            $modeOfAcquisitionID = $this->modeOfAcquisitionModel->insertModeOfAcquisition($modeOfAcquisitionName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'modeOfAcquisitionID' => $this->securityModel->encryptData($modeOfAcquisitionID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteModeOfAcquisition
    # Description: 
    # Delete the mode of acquisition if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteModeOfAcquisition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modeOfAcquisitionID = htmlspecialchars($_POST['mode_of_acquisition_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkModeOfAcquisitionExist = $this->modeOfAcquisitionModel->checkModeOfAcquisitionExist($modeOfAcquisitionID);
        $total = $checkModeOfAcquisitionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->modeOfAcquisitionModel->deleteModeOfAcquisition($modeOfAcquisitionID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleModeOfAcquisition
    # Description: 
    # Delete the selected mode of acquisitions if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleModeOfAcquisition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modeOfAcquisitionIDs = $_POST['mode_of_acquisition_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($modeOfAcquisitionIDs as $modeOfAcquisitionID){
            $this->modeOfAcquisitionModel->deleteModeOfAcquisition($modeOfAcquisitionID);
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
    # Function: duplicateModeOfAcquisition
    # Description: 
    # Duplicates the mode of acquisition if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateModeOfAcquisition() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $modeOfAcquisitionID = htmlspecialchars($_POST['mode_of_acquisition_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkModeOfAcquisitionExist = $this->modeOfAcquisitionModel->checkModeOfAcquisitionExist($modeOfAcquisitionID);
        $total = $checkModeOfAcquisitionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $modeOfAcquisitionID = $this->modeOfAcquisitionModel->duplicateModeOfAcquisition($modeOfAcquisitionID, $userID);

        echo json_encode(['success' => true, 'modeOfAcquisitionID' =>  $this->securityModel->encryptData($modeOfAcquisitionID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getModeOfAcquisitionDetails
    # Description: 
    # Handles the retrieval of mode of acquisition details such as mode of acquisition name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getModeOfAcquisitionDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['mode_of_acquisition_id']) && !empty($_POST['mode_of_acquisition_id'])) {
            $userID = $_SESSION['user_id'];
            $modeOfAcquisitionID = $_POST['mode_of_acquisition_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $modeOfAcquisitionDetails = $this->modeOfAcquisitionModel->getModeOfAcquisition($modeOfAcquisitionID);

            $response = [
                'success' => true,
                'modeOfAcquisitionName' => $modeOfAcquisitionDetails['mode_of_acquisition_name']
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
require_once '../model/mode-of-acquisition-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ModeOfAcquisitionController(new ModeOfAcquisitionModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>