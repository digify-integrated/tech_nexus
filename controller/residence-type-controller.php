<?php
session_start();

# -------------------------------------------------------------
#
# Function: ResidenceTypeController
# Description: 
# The ResidenceTypeController class handles residence type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ResidenceTypeController {
    private $residenceTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ResidenceTypeModel, UserModel and SecurityModel instances.
    # These instances are used for residence type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ResidenceTypeModel $residenceTypeModel     The ResidenceTypeModel instance for residence type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ResidenceTypeModel $residenceTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->residenceTypeModel = $residenceTypeModel;
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
                case 'save residence type':
                    $this->saveResidenceType();
                    break;
                case 'get residence type details':
                    $this->getResidenceTypeDetails();
                    break;
                case 'delete residence type':
                    $this->deleteResidenceType();
                    break;
                case 'delete multiple residence type':
                    $this->deleteMultipleResidenceType();
                    break;
                case 'duplicate residence type':
                    $this->duplicateResidenceType();
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
    # Function: saveResidenceType
    # Description: 
    # Updates the existing residence type if it exists; otherwise, inserts a new residence type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveResidenceType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $residenceTypeID = isset($_POST['residence_type_id']) ? htmlspecialchars($_POST['residence_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $residenceTypeName = htmlspecialchars($_POST['residence_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkResidenceTypeExist = $this->residenceTypeModel->checkResidenceTypeExist($residenceTypeID);
        $total = $checkResidenceTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->residenceTypeModel->updateResidenceType($residenceTypeID, $residenceTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'residenceTypeID' => $this->securityModel->encryptData($residenceTypeID)]);
            exit;
        } 
        else {
            $residenceTypeID = $this->residenceTypeModel->insertResidenceType($residenceTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'residenceTypeID' => $this->securityModel->encryptData($residenceTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteResidenceType
    # Description: 
    # Delete the residence type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteResidenceType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $residenceTypeID = htmlspecialchars($_POST['residence_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkResidenceTypeExist = $this->residenceTypeModel->checkResidenceTypeExist($residenceTypeID);
        $total = $checkResidenceTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->residenceTypeModel->deleteResidenceType($residenceTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleResidenceType
    # Description: 
    # Delete the selected residence types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleResidenceType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $residenceTypeIDs = $_POST['residence_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($residenceTypeIDs as $residenceTypeID){
            $this->residenceTypeModel->deleteResidenceType($residenceTypeID);
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
    # Function: duplicateResidenceType
    # Description: 
    # Duplicates the residence type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateResidenceType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $residenceTypeID = htmlspecialchars($_POST['residence_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkResidenceTypeExist = $this->residenceTypeModel->checkResidenceTypeExist($residenceTypeID);
        $total = $checkResidenceTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $residenceTypeID = $this->residenceTypeModel->duplicateResidenceType($residenceTypeID, $userID);

        echo json_encode(['success' => true, 'residenceTypeID' =>  $this->securityModel->encryptData($residenceTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getResidenceTypeDetails
    # Description: 
    # Handles the retrieval of residence type details such as residence type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getResidenceTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['residence_type_id']) && !empty($_POST['residence_type_id'])) {
            $userID = $_SESSION['user_id'];
            $residenceTypeID = $_POST['residence_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $residenceTypeDetails = $this->residenceTypeModel->getResidenceType($residenceTypeID);

            $response = [
                'success' => true,
                'residenceTypeName' => $residenceTypeDetails['residence_type_name']
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
require_once '../model/residence-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ResidenceTypeController(new ResidenceTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>