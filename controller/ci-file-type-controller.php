<?php
session_start();

# -------------------------------------------------------------
#
# Function: CIFileTypeController
# Description: 
# The CIFileTypeController class handles ci file type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CIFileTypeController {
    private $ciFileTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CIFileTypeModel, UserModel and SecurityModel instances.
    # These instances are used for ci file type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CIFileTypeModel $ciFileTypeModel     The CIFileTypeModel instance for ci file type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CIFileTypeModel $ciFileTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->ciFileTypeModel = $ciFileTypeModel;
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
                case 'save ci file type':
                    $this->saveCIFileType();
                    break;
                case 'get ci file type details':
                    $this->getCIFileTypeDetails();
                    break;
                case 'delete ci file type':
                    $this->deleteCIFileType();
                    break;
                case 'delete multiple ci file type':
                    $this->deleteMultipleCIFileType();
                    break;
                case 'duplicate ci file type':
                    $this->duplicateCIFileType();
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
    # Function: saveCIFileType
    # Description: 
    # Updates the existing ci file type if it exists; otherwise, inserts a new ci file type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCIFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $ciFileTypeID = isset($_POST['ci_file_type_id']) ? htmlspecialchars($_POST['ci_file_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $ciFileTypeName = htmlspecialchars($_POST['ci_file_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIFileTypeExist = $this->ciFileTypeModel->checkCIFileTypeExist($ciFileTypeID);
        $total = $checkCIFileTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciFileTypeModel->updateCIFileType($ciFileTypeID, $ciFileTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'ciFileTypeID' => $this->securityModel->encryptData($ciFileTypeID)]);
            exit;
        } 
        else {
            $ciFileTypeID = $this->ciFileTypeModel->insertCIFileType($ciFileTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'ciFileTypeID' => $this->securityModel->encryptData($ciFileTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCIFileType
    # Description: 
    # Delete the ci file type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCIFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $ciFileTypeID = htmlspecialchars($_POST['ci_file_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIFileTypeExist = $this->ciFileTypeModel->checkCIFileTypeExist($ciFileTypeID);
        $total = $checkCIFileTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->ciFileTypeModel->deleteCIFileType($ciFileTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCIFileType
    # Description: 
    # Delete the selected ci file types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCIFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $ciFileTypeIDs = $_POST['ci_file_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($ciFileTypeIDs as $ciFileTypeID){
            $this->ciFileTypeModel->deleteCIFileType($ciFileTypeID);
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
    # Function: duplicateCIFileType
    # Description: 
    # Duplicates the ci file type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateCIFileType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $ciFileTypeID = htmlspecialchars($_POST['ci_file_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIFileTypeExist = $this->ciFileTypeModel->checkCIFileTypeExist($ciFileTypeID);
        $total = $checkCIFileTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $ciFileTypeID = $this->ciFileTypeModel->duplicateCIFileType($ciFileTypeID, $userID);

        echo json_encode(['success' => true, 'ciFileTypeID' =>  $this->securityModel->encryptData($ciFileTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCIFileTypeDetails
    # Description: 
    # Handles the retrieval of ci file type details such as ci file type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCIFileTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_file_type_id']) && !empty($_POST['ci_file_type_id'])) {
            $userID = $_SESSION['user_id'];
            $ciFileTypeID = $_POST['ci_file_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciFileTypeDetails = $this->ciFileTypeModel->getCIFileType($ciFileTypeID);

            $response = [
                'success' => true,
                'ciFileTypeName' => $ciFileTypeDetails['ci_file_type_name']
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
require_once '../model/ci-file-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CIFileTypeController(new CIFileTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>