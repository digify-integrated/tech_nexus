<?php
session_start();

# -------------------------------------------------------------
#
# Function: SupplierController
# Description: 
# The SupplierController class handles supplier related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class SupplierController {
    private $supplierModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided SupplierModel, UserModel and SecurityModel instances.
    # These instances are used for supplier related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param SupplierModel $supplierModel     The SupplierModel instance for supplier related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(SupplierModel $supplierModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->supplierModel = $supplierModel;
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
                case 'save supplier':
                    $this->saveSupplier();
                    break;
                case 'get supplier details':
                    $this->getSupplierDetails();
                    break;
                case 'delete supplier':
                    $this->deleteSupplier();
                    break;
                case 'delete multiple supplier':
                    $this->deleteMultipleSupplier();
                    break;
                case 'duplicate supplier':
                    $this->duplicateSupplier();
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
    # Function: saveSupplier
    # Description: 
    # Updates the existing supplier if it exists; otherwise, inserts a new supplier.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSupplier() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $supplierID = isset($_POST['supplier_id']) ? htmlspecialchars($_POST['supplier_id'], ENT_QUOTES, 'UTF-8') : null;
        $supplierName = htmlspecialchars($_POST['supplier_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSupplierExist = $this->supplierModel->checkSupplierExist($supplierID);
        $total = $checkSupplierExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->supplierModel->updateSupplier($supplierID, $supplierName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'supplierID' => $this->securityModel->encryptData($supplierID)]);
            exit;
        } 
        else {
            $supplierID = $this->supplierModel->insertSupplier($supplierName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'supplierID' => $this->securityModel->encryptData($supplierID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSupplier
    # Description: 
    # Delete the supplier if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSupplier() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $supplierID = htmlspecialchars($_POST['supplier_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSupplierExist = $this->supplierModel->checkSupplierExist($supplierID);
        $total = $checkSupplierExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->supplierModel->deleteSupplier($supplierID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleSupplier
    # Description: 
    # Delete the selected suppliers if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleSupplier() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $supplierIDs = $_POST['supplier_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($supplierIDs as $supplierID){
            $this->supplierModel->deleteSupplier($supplierID);
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
    # Function: duplicateSupplier
    # Description: 
    # Duplicates the supplier if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateSupplier() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $supplierID = htmlspecialchars($_POST['supplier_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSupplierExist = $this->supplierModel->checkSupplierExist($supplierID);
        $total = $checkSupplierExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $supplierID = $this->supplierModel->duplicateSupplier($supplierID, $userID);

        echo json_encode(['success' => true, 'supplierID' =>  $this->securityModel->encryptData($supplierID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSupplierDetails
    # Description: 
    # Handles the retrieval of supplier details such as supplier name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSupplierDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['supplier_id']) && !empty($_POST['supplier_id'])) {
            $userID = $_SESSION['user_id'];
            $supplierID = $_POST['supplier_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $supplierDetails = $this->supplierModel->getSupplier($supplierID);

            $response = [
                'success' => true,
                'supplierName' => $supplierDetails['supplier_name']
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
require_once '../model/supplier-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new SupplierController(new SupplierModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>