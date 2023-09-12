<?php
session_start();

# -------------------------------------------------------------
#
# Function: BloodTypeController
# Description: 
# The BloodTypeController class handles blood type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BloodTypeController {
    private $bloodTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BloodTypeModel, UserModel and SecurityModel instances.
    # These instances are used for blood type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BloodTypeModel $bloodTypeModel     The BloodTypeModel instance for blood type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BloodTypeModel $bloodTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->bloodTypeModel = $bloodTypeModel;
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
                case 'save blood type':
                    $this->saveBloodType();
                    break;
                case 'get blood type details':
                    $this->getBloodTypeDetails();
                    break;
                case 'delete blood type':
                    $this->deleteBloodType();
                    break;
                case 'delete multiple blood type':
                    $this->deleteMultipleBloodType();
                    break;
                case 'duplicate blood type':
                    $this->duplicateBloodType();
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
    # Function: saveBloodType
    # Description: 
    # Updates the existing blood type if it exists; otherwise, inserts a new blood type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBloodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bloodTypeID = isset($_POST['blood_type_id']) ? htmlspecialchars($_POST['blood_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $bloodTypeName = htmlspecialchars($_POST['blood_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBloodTypeExist = $this->bloodTypeModel->checkBloodTypeExist($bloodTypeID);
        $total = $checkBloodTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->bloodTypeModel->updateBloodType($bloodTypeID, $bloodTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'bloodTypeID' => $this->securityModel->encryptData($bloodTypeID)]);
            exit;
        } 
        else {
            $bloodTypeID = $this->bloodTypeModel->insertBloodType($bloodTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'bloodTypeID' => $this->securityModel->encryptData($bloodTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBloodType
    # Description: 
    # Delete the blood type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBloodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bloodTypeID = htmlspecialchars($_POST['blood_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBloodTypeExist = $this->bloodTypeModel->checkBloodTypeExist($bloodTypeID);
        $total = $checkBloodTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->bloodTypeModel->deleteBloodType($bloodTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBloodType
    # Description: 
    # Delete the selected blood types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBloodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bloodTypeIDs = $_POST['blood_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($bloodTypeIDs as $bloodTypeID){
            $this->bloodTypeModel->deleteBloodType($bloodTypeID);
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
    # Function: duplicateBloodType
    # Description: 
    # Duplicates the blood type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBloodType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $bloodTypeID = htmlspecialchars($_POST['blood_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBloodTypeExist = $this->bloodTypeModel->checkBloodTypeExist($bloodTypeID);
        $total = $checkBloodTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $bloodTypeID = $this->bloodTypeModel->duplicateBloodType($bloodTypeID, $userID);

        echo json_encode(['success' => true, 'bloodTypeID' =>  $this->securityModel->encryptData($bloodTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBloodTypeDetails
    # Description: 
    # Handles the retrieval of blood type details such as blood type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBloodTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['blood_type_id']) && !empty($_POST['blood_type_id'])) {
            $userID = $_SESSION['user_id'];
            $bloodTypeID = $_POST['blood_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $bloodTypeDetails = $this->bloodTypeModel->getBloodType($bloodTypeID);

            $response = [
                'success' => true,
                'bloodTypeName' => $bloodTypeDetails['blood_type_name']
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
require_once '../model/blood-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BloodTypeController(new BloodTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>