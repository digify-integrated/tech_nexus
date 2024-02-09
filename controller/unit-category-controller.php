<?php
session_start();

# -------------------------------------------------------------
#
# Function: UnitCategoryController
# Description: 
# The UnitCategoryController class handles unit category related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class UnitCategoryController {
    private $unitCategoryModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided UnitCategoryModel, UserModel and SecurityModel instances.
    # These instances are used for unit category related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param UnitCategoryModel $unitCategoryModel     The UnitCategoryModel instance for unit category related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(UnitCategoryModel $unitCategoryModel, UserModel $userModel, SecurityModel $securityModel) {
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
                case 'save unit category':
                    $this->saveUnitCategory();
                    break;
                case 'get unit category details':
                    $this->getUnitCategoryDetails();
                    break;
                case 'delete unit category':
                    $this->deleteUnitCategory();
                    break;
                case 'delete multiple unit category':
                    $this->deleteMultipleUnitCategory();
                    break;
                case 'duplicate unit category':
                    $this->duplicateUnitCategory();
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
    # Function: saveUnitCategory
    # Description: 
    # Updates the existing unit category if it exists; otherwise, inserts a new unit category.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveUnitCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitCategoryID = isset($_POST['unit_category_id']) ? htmlspecialchars($_POST['unit_category_id'], ENT_QUOTES, 'UTF-8') : null;
        $unitCategoryName = htmlspecialchars($_POST['unit_category_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUnitCategoryExist = $this->unitCategoryModel->checkUnitCategoryExist($unitCategoryID);
        $total = $checkUnitCategoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->unitCategoryModel->updateUnitCategory($unitCategoryID, $unitCategoryName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'unitCategoryID' => $this->securityModel->encryptData($unitCategoryID)]);
            exit;
        } 
        else {
            $unitCategoryID = $this->unitCategoryModel->insertUnitCategory($unitCategoryName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'unitCategoryID' => $this->securityModel->encryptData($unitCategoryID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteUnitCategory
    # Description: 
    # Delete the unit category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteUnitCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitCategoryID = htmlspecialchars($_POST['unit_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUnitCategoryExist = $this->unitCategoryModel->checkUnitCategoryExist($unitCategoryID);
        $total = $checkUnitCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->unitCategoryModel->deleteUnitCategory($unitCategoryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleUnitCategory
    # Description: 
    # Delete the selected unit categorys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleUnitCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitCategoryIDs = $_POST['unit_category_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($unitCategoryIDs as $unitCategoryID){
            $this->unitCategoryModel->deleteUnitCategory($unitCategoryID);
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
    # Function: duplicateUnitCategory
    # Description: 
    # Duplicates the unit category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateUnitCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unitCategoryID = htmlspecialchars($_POST['unit_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUnitCategoryExist = $this->unitCategoryModel->checkUnitCategoryExist($unitCategoryID);
        $total = $checkUnitCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $unitCategoryID = $this->unitCategoryModel->duplicateUnitCategory($unitCategoryID, $userID);

        echo json_encode(['success' => true, 'unitCategoryID' =>  $this->securityModel->encryptData($unitCategoryID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUnitCategoryDetails
    # Description: 
    # Handles the retrieval of unit category details such as unit category name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getUnitCategoryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['unit_category_id']) && !empty($_POST['unit_category_id'])) {
            $userID = $_SESSION['user_id'];
            $unitCategoryID = $_POST['unit_category_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $unitCategoryDetails = $this->unitCategoryModel->getUnitCategory($unitCategoryID);

            $response = [
                'success' => true,
                'unitCategoryName' => $unitCategoryDetails['unit_category_name']
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
require_once '../model/unit-category-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new UnitCategoryController(new UnitCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>