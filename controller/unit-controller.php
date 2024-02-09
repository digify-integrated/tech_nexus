<?php
session_start();

# -------------------------------------------------------------
#
# Function: UnitController
# Description: 
# The UnitController class handles unit related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class UnitController {
    private $unitModel;
    private $unitCategoryModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided UnitModel, UserModel and SecurityModel instances.
    # These instances are used for unit related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param UnitModel $unitModel     The UnitModel instance for unit related operations.
    # - @param UnitCategoryModel $unitModel     The UnitCategoryModel instance for unit category related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(UnitModel $unitModel, UnitCategoryModel $unitCategoryModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->unitModel = $unitModel;
        $this->unitCategoryModel = $unitCategoryModel;
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
                case 'save unit':
                    $this->saveUnit();
                    break;
                case 'get unit details':
                    $this->getUnitDetails();
                    break;
                case 'delete unit':
                    $this->deleteUnit();
                    break;
                case 'delete multiple unit':
                    $this->deleteMultipleUnit();
                    break;
                case 'duplicate unit':
                    $this->duplicateUnit();
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
    # Function: saveUnit
    # Description: 
    # Updates the existing unit if it exists; otherwise, inserts a new unit.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveUnit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitID = isset($_POST['unit_id']) ? htmlspecialchars($_POST['unit_id'], ENT_QUOTES, 'UTF-8') : null;
        $unitName = htmlspecialchars($_POST['unit_name'], ENT_QUOTES, 'UTF-8');
        $shortName = htmlspecialchars($_POST['short_name'], ENT_QUOTES, 'UTF-8');
        $unitCategoryID = htmlspecialchars($_POST['unit_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUnitExist = $this->unitModel->checkUnitExist($unitID);
        $total = $checkUnitExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->unitModel->updateUnit($unitID, $unitName, $shortName, $unitCategoryID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'unitID' => $this->securityModel->encryptData($unitID)]);
            exit;
        } 
        else {
            $unitID = $this->unitModel->insertUnit($unitName, $shortName, $unitCategoryID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'unitID' => $this->securityModel->encryptData($unitID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteUnit
    # Description: 
    # Delete the unit if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteUnit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitID = htmlspecialchars($_POST['unit_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUnitExist = $this->unitModel->checkUnitExist($unitID);
        $total = $checkUnitExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->unitModel->deleteUnit($unitID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleUnit
    # Description: 
    # Delete the selected units if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleUnit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitIDs = $_POST['unit_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($unitIDs as $unitID){
            $this->unitModel->deleteUnit($unitID);
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
    # Function: duplicateUnit
    # Description: 
    # Duplicates the unit if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateUnit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitID = htmlspecialchars($_POST['unit_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUnitExist = $this->unitModel->checkUnitExist($unitID);
        $total = $checkUnitExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $unitID = $this->unitModel->duplicateUnit($unitID, $userID);

        echo json_encode(['success' => true, 'unitID' =>  $this->securityModel->encryptData($unitID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUnitDetails
    # Description: 
    # Handles the retrieval of unit details such as unit name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getUnitDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['unit_id']) && !empty($_POST['unit_id'])) {
            $userID = $_SESSION['user_id'];
            $unitID = $_POST['unit_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $unitDetails = $this->unitModel->getUnit($unitID);
            $unitCategoryID = $unitDetails['unit_category_id'];

            $unitCategoryDetails = $this->unitCategoryModel->getUnitCategory($unitCategoryID);
            $unitCategoryName = $unitCategoryDetails['unit_category_name'];

            $response = [
                'success' => true,
                'unitName' => $unitDetails['unit_name'],
                'shortName' => $unitDetails['short_name'],
                'unitCategoryID' => $unitCategoryID,
                'unitCategoryName' => $unitCategoryName
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
require_once '../model/unit-model.php';
require_once '../model/unit-category-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new UnitController(new UnitModel(new DatabaseModel), new UnitCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>