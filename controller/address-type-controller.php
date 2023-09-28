<?php
session_start();

# -------------------------------------------------------------
#
# Function: AddressTypeController
# Description: 
# The AddressTypeController class handles address type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class AddressTypeController {
    private $addressTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided AddressTypeModel, UserModel and SecurityModel instances.
    # These instances are used for address type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param AddressTypeModel $addressTypeModel     The AddressTypeModel instance for address type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(AddressTypeModel $addressTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->addressTypeModel = $addressTypeModel;
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
                case 'save address type':
                    $this->saveAddressType();
                    break;
                case 'get address type details':
                    $this->getAddressTypeDetails();
                    break;
                case 'delete address type':
                    $this->deleteAddressType();
                    break;
                case 'delete multiple address type':
                    $this->deleteMultipleAddressType();
                    break;
                case 'duplicate address type':
                    $this->duplicateAddressType();
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
    # Function: saveAddressType
    # Description: 
    # Updates the existing address type if it exists; otherwise, inserts a new address type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveAddressType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $addressTypeID = isset($_POST['address_type_id']) ? htmlspecialchars($_POST['address_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $addressTypeName = htmlspecialchars($_POST['address_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAddressTypeExist = $this->addressTypeModel->checkAddressTypeExist($addressTypeID);
        $total = $checkAddressTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->addressTypeModel->updateAddressType($addressTypeID, $addressTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'addressTypeID' => $this->securityModel->encryptData($addressTypeID)]);
            exit;
        } 
        else {
            $addressTypeID = $this->addressTypeModel->insertAddressType($addressTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'addressTypeID' => $this->securityModel->encryptData($addressTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAddressType
    # Description: 
    # Delete the address type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteAddressType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $addressTypeID = htmlspecialchars($_POST['address_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAddressTypeExist = $this->addressTypeModel->checkAddressTypeExist($addressTypeID);
        $total = $checkAddressTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->addressTypeModel->deleteAddressType($addressTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleAddressType
    # Description: 
    # Delete the selected address types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleAddressType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $addressTypeIDs = $_POST['address_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($addressTypeIDs as $addressTypeID){
            $this->addressTypeModel->deleteAddressType($addressTypeID);
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
    # Function: duplicateAddressType
    # Description: 
    # Duplicates the address type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateAddressType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $addressTypeID = htmlspecialchars($_POST['address_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAddressTypeExist = $this->addressTypeModel->checkAddressTypeExist($addressTypeID);
        $total = $checkAddressTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $addressTypeID = $this->addressTypeModel->duplicateAddressType($addressTypeID, $userID);

        echo json_encode(['success' => true, 'addressTypeID' =>  $this->securityModel->encryptData($addressTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAddressTypeDetails
    # Description: 
    # Handles the retrieval of address type details such as address type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getAddressTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['address_type_id']) && !empty($_POST['address_type_id'])) {
            $userID = $_SESSION['user_id'];
            $addressTypeID = $_POST['address_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $addressTypeDetails = $this->addressTypeModel->getAddressType($addressTypeID);

            $response = [
                'success' => true,
                'addressTypeName' => $addressTypeDetails['address_type_name']
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
require_once '../model/address-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new AddressTypeController(new AddressTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>