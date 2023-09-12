<?php
session_start();

# -------------------------------------------------------------
#
# Function: IDTypeController
# Description: 
# The IDTypeController class handles ID type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class IDTypeController {
    private $idTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided IDTypeModel, UserModel and SecurityModel instances.
    # These instances are used for ID type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param IDTypeModel $idTypeModel     The IDTypeModel instance for ID type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(IDTypeModel $idTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->idTypeModel = $idTypeModel;
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
                case 'save ID type':
                    $this->saveIDType();
                    break;
                case 'get ID type details':
                    $this->getIDTypeDetails();
                    break;
                case 'delete ID type':
                    $this->deleteIDType();
                    break;
                case 'delete multiple ID type':
                    $this->deleteMultipleIDType();
                    break;
                case 'duplicate ID type':
                    $this->duplicateIDType();
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
    # Function: saveIDType
    # Description: 
    # Updates the existing ID type if it exists; otherwise, inserts a new ID type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveIDType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $idTypeID = isset($_POST['id_type_id']) ? htmlspecialchars($_POST['id_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $idTypeName = htmlspecialchars($_POST['id_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkIDTypeExist = $this->idTypeModel->checkIDTypeExist($idTypeID);
        $total = $checkIDTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->idTypeModel->updateIDType($idTypeID, $idTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'idTypeID' => $this->securityModel->encryptData($idTypeID)]);
            exit;
        } 
        else {
            $idTypeID = $this->idTypeModel->insertIDType($idTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'idTypeID' => $this->securityModel->encryptData($idTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteIDType
    # Description: 
    # Delete the ID type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteIDType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $idTypeID = htmlspecialchars($_POST['id_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkIDTypeExist = $this->idTypeModel->checkIDTypeExist($idTypeID);
        $total = $checkIDTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->idTypeModel->deleteIDType($idTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleIDType
    # Description: 
    # Delete the selected ID types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleIDType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $idTypeIDs = $_POST['id_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($idTypeIDs as $idTypeID){
            $this->idTypeModel->deleteIDType($idTypeID);
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
    # Function: duplicateIDType
    # Description: 
    # Duplicates the ID type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateIDType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $idTypeID = htmlspecialchars($_POST['id_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkIDTypeExist = $this->idTypeModel->checkIDTypeExist($idTypeID);
        $total = $checkIDTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $idTypeID = $this->idTypeModel->duplicateIDType($idTypeID, $userID);

        echo json_encode(['success' => true, 'idTypeID' =>  $this->securityModel->encryptData($idTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getIDTypeDetails
    # Description: 
    # Handles the retrieval of ID type details such as ID type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getIDTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['id_type_id']) && !empty($_POST['id_type_id'])) {
            $userID = $_SESSION['user_id'];
            $idTypeID = $_POST['id_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $idTypeDetails = $this->idTypeModel->getIDType($idTypeID);

            $response = [
                'success' => true,
                'idTypeName' => $idTypeDetails['id_type_name']
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
require_once '../model/id-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new IDTypeController(new IDTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>