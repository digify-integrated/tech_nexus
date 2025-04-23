<?php
session_start();

# -------------------------------------------------------------
#
# Function: AuthorizeUnitTransferController
# Description: 
# The AuthorizeUnitTransferController class handles authorize unit transfer related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class AuthorizeUnitTransferController {
    private $authorizeUnitTransferModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided AuthorizeUnitTransferModel, UserModel and SecurityModel instances.
    # These instances are used for authorize unit transfer related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param AuthorizeUnitTransferModel $authorizeUnitTransferModel     The AuthorizeUnitTransferModel instance for authorize unit transfer related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(AuthorizeUnitTransferModel $authorizeUnitTransferModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->authorizeUnitTransferModel = $authorizeUnitTransferModel;
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
                case 'save authorize unit transfer':
                    $this->saveAuthorizeUnitTransfer();
                    break;
                case 'get authorize unit transfer details':
                    $this->getAuthorizeUnitTransferDetails();
                    break;
                case 'delete authorize unit transfer':
                    $this->deleteAuthorizeUnitTransfer();
                    break;
                case 'delete multiple authorize unit transfer':
                    $this->deleteMultipleAuthorizeUnitTransfer();
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
    # Function: saveAuthorizeUnitTransfer
    # Description: 
    # Updates the existing authorize unit transfer if it exists; otherwise, inserts a new authorize unit transfer.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveAuthorizeUnitTransfer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $authorizeUnitTransferID = isset($_POST['authorize_unit_transfer_id']) ? htmlspecialchars($_POST['authorize_unit_transfer_id'], ENT_QUOTES, 'UTF-8') : null;
        $warehouse_id = htmlspecialchars($_POST['warehouse_id'], ENT_QUOTES, 'UTF-8');
        $user_id = htmlspecialchars($_POST['user_id1'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $authorizeUnitTransferID = $this->authorizeUnitTransferModel->insertAuthorizeUnitTransfer($warehouse_id, $user_id, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'authorizeUnitTransferID' => $this->securityModel->encryptData($authorizeUnitTransferID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAuthorizeUnitTransfer
    # Description: 
    # Delete the authorize unit transfer if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteAuthorizeUnitTransfer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $authorizeUnitTransferID = htmlspecialchars($_POST['authorize_unit_transfer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAuthorizeUnitTransferExist = $this->authorizeUnitTransferModel->checkAuthorizeUnitTransferExist($authorizeUnitTransferID);
        $total = $checkAuthorizeUnitTransferExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->authorizeUnitTransferModel->deleteAuthorizeUnitTransfer($authorizeUnitTransferID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleAuthorizeUnitTransfer
    # Description: 
    # Delete the selected authorize unit transfers if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleAuthorizeUnitTransfer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $authorizeUnitTransferIDs = $_POST['authorize_unit_transfer_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($authorizeUnitTransferIDs as $authorizeUnitTransferID){
            $this->authorizeUnitTransferModel->deleteAuthorizeUnitTransfer($authorizeUnitTransferID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAuthorizeUnitTransferDetails
    # Description: 
    # Handles the retrieval of authorize unit transfer details such as authorize unit transfer name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getAuthorizeUnitTransferDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['authorize_unit_transfer_id']) && !empty($_POST['authorize_unit_transfer_id'])) {
            $userID = $_SESSION['user_id'];
            $authorizeUnitTransferID = $_POST['authorize_unit_transfer_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $authorizeUnitTransferDetails = $this->authorizeUnitTransferModel->getAuthorizeUnitTransfer($authorizeUnitTransferID);

            $response = [
                'success' => true,
                'warehouseID' => $authorizeUnitTransferDetails['warehouse_id'],
                'userID' => $authorizeUnitTransferDetails['user_id'],
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
require_once '../model/authorize-unit-transfer-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new AuthorizeUnitTransferController(new AuthorizeUnitTransferModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>