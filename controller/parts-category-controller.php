<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsCategoryController
# Description: 
# The PartsCategoryController class handles parts category related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsCategoryController {
    private $partsCategoryModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PartsCategoryModel, UserModel and SecurityModel instances.
    # These instances are used for parts category related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsCategoryModel $partsCategoryModel     The PartsCategoryModel instance for parts category related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsCategoryModel $partsCategoryModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->partsCategoryModel = $partsCategoryModel;
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
                case 'save parts category':
                    $this->savePartsCategory();
                    break;
                case 'get parts category details':
                    $this->getPartsCategoryDetails();
                    break;
                case 'delete parts category':
                    $this->deletePartsCategory();
                    break;
                case 'delete multiple parts category':
                    $this->deleteMultiplePartsCategory();
                    break;
                case 'duplicate parts category':
                    $this->duplicatePartsCategory();
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
    # Function: savePartsCategory
    # Description: 
    # Updates the existing parts category if it exists; otherwise, inserts a new parts category.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsCategoryID = isset($_POST['parts_category_id']) ? htmlspecialchars($_POST['parts_category_id'], ENT_QUOTES, 'UTF-8') : null;
        $partsCategoryName = htmlspecialchars($_POST['parts_category_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsCategoryExist = $this->partsCategoryModel->checkPartsCategoryExist($partsCategoryID);
        $total = $checkPartsCategoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsCategoryModel->updatePartsCategory($partsCategoryID, $partsCategoryName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'partsCategoryID' => $this->securityModel->encryptData($partsCategoryID)]);
            exit;
        } 
        else {
            $partsCategoryID = $this->partsCategoryModel->insertPartsCategory($partsCategoryName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'partsCategoryID' => $this->securityModel->encryptData($partsCategoryID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsCategory
    # Description: 
    # Delete the parts category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsCategoryID = htmlspecialchars($_POST['parts_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsCategoryExist = $this->partsCategoryModel->checkPartsCategoryExist($partsCategoryID);
        $total = $checkPartsCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->partsCategoryModel->deletePartsCategory($partsCategoryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePartsCategory
    # Description: 
    # Delete the selected parts categorys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePartsCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsCategoryIDs = $_POST['parts_category_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($partsCategoryIDs as $partsCategoryID){
            $this->partsCategoryModel->deletePartsCategory($partsCategoryID);
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
    # Function: duplicatePartsCategory
    # Description: 
    # Duplicates the parts category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicatePartsCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsCategoryID = htmlspecialchars($_POST['parts_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsCategoryExist = $this->partsCategoryModel->checkPartsCategoryExist($partsCategoryID);
        $total = $checkPartsCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsCategoryID = $this->partsCategoryModel->duplicatePartsCategory($partsCategoryID, $userID);

        echo json_encode(['success' => true, 'partsCategoryID' =>  $this->securityModel->encryptData($partsCategoryID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsCategoryDetails
    # Description: 
    # Handles the retrieval of parts category details such as parts category name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsCategoryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_category_id']) && !empty($_POST['parts_category_id'])) {
            $userID = $_SESSION['user_id'];
            $partsCategoryID = $_POST['parts_category_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsCategoryDetails = $this->partsCategoryModel->getPartsCategory($partsCategoryID);

            $response = [
                'success' => true,
                'partsCategoryName' => $partsCategoryDetails['part_category_name']
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
require_once '../model/parts-category-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsCategoryController(new PartsCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>