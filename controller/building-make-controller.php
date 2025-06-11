<?php
session_start();

# -------------------------------------------------------------
#
# Function: BuildingMakeController
# Description: 
# The BuildingMakeController class handles building make related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BuildingMakeController {
    private $buildingMakeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BuildingMakeModel, UserModel and SecurityModel instances.
    # These instances are used for building make related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BuildingMakeModel $buildingMakeModel     The BuildingMakeModel instance for building make related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BuildingMakeModel $buildingMakeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->buildingMakeModel = $buildingMakeModel;
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
                case 'save building make':
                    $this->saveBuildingMake();
                    break;
                case 'get building make details':
                    $this->getBuildingMakeDetails();
                    break;
                case 'delete building make':
                    $this->deleteBuildingMake();
                    break;
                case 'delete multiple building make':
                    $this->deleteMultipleBuildingMake();
                    break;
                case 'duplicate building make':
                    $this->duplicateBuildingMake();
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
    # Function: saveBuildingMake
    # Description: 
    # Updates the existing building make if it exists; otherwise, inserts a new building make.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBuildingMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $buildingMakeID = isset($_POST['building_make_id']) ? htmlspecialchars($_POST['building_make_id'], ENT_QUOTES, 'UTF-8') : null;
        $buildingMakeName = htmlspecialchars($_POST['building_make_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBuildingMakeExist = $this->buildingMakeModel->checkBuildingMakeExist($buildingMakeID);
        $total = $checkBuildingMakeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->buildingMakeModel->updateBuildingMake($buildingMakeID, $buildingMakeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'buildingMakeID' => $this->securityModel->encryptData($buildingMakeID)]);
            exit;
        } 
        else {
            $buildingMakeID = $this->buildingMakeModel->insertBuildingMake($buildingMakeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'buildingMakeID' => $this->securityModel->encryptData($buildingMakeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBuildingMake
    # Description: 
    # Delete the building make if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBuildingMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $buildingMakeID = htmlspecialchars($_POST['building_make_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBuildingMakeExist = $this->buildingMakeModel->checkBuildingMakeExist($buildingMakeID);
        $total = $checkBuildingMakeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->buildingMakeModel->deleteBuildingMake($buildingMakeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBuildingMake
    # Description: 
    # Delete the selected building makes if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBuildingMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $buildingMakeIDs = $_POST['building_make_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($buildingMakeIDs as $buildingMakeID){
            $this->buildingMakeModel->deleteBuildingMake($buildingMakeID);
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
    # Function: duplicateBuildingMake
    # Description: 
    # Duplicates the building make if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBuildingMake() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $buildingMakeID = htmlspecialchars($_POST['building_make_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBuildingMakeExist = $this->buildingMakeModel->checkBuildingMakeExist($buildingMakeID);
        $total = $checkBuildingMakeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $buildingMakeID = $this->buildingMakeModel->duplicateBuildingMake($buildingMakeID, $userID);

        echo json_encode(['success' => true, 'buildingMakeID' =>  $this->securityModel->encryptData($buildingMakeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBuildingMakeDetails
    # Description: 
    # Handles the retrieval of building make details such as building make name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBuildingMakeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['building_make_id']) && !empty($_POST['building_make_id'])) {
            $userID = $_SESSION['user_id'];
            $buildingMakeID = $_POST['building_make_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $buildingMakeDetails = $this->buildingMakeModel->getBuildingMake($buildingMakeID);

            $response = [
                'success' => true,
                'buildingMakeName' => $buildingMakeDetails['building_make_name']
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
require_once '../model/building-make-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BuildingMakeController(new BuildingMakeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>