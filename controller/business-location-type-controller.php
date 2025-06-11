<?php
session_start();

# -------------------------------------------------------------
#
# Function: BusinessLocationTypeController
# Description: 
# The BusinessLocationTypeController class handles business location type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BusinessLocationTypeController {
    private $businessLocationTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BusinessLocationTypeModel, UserModel and SecurityModel instances.
    # These instances are used for business location type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BusinessLocationTypeModel $businessLocationTypeModel     The BusinessLocationTypeModel instance for business location type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BusinessLocationTypeModel $businessLocationTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->businessLocationTypeModel = $businessLocationTypeModel;
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
                case 'save business location type':
                    $this->saveBusinessLocationType();
                    break;
                case 'get business location type details':
                    $this->getBusinessLocationTypeDetails();
                    break;
                case 'delete business location type':
                    $this->deleteBusinessLocationType();
                    break;
                case 'delete multiple business location type':
                    $this->deleteMultipleBusinessLocationType();
                    break;
                case 'duplicate business location type':
                    $this->duplicateBusinessLocationType();
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
    # Function: saveBusinessLocationType
    # Description: 
    # Updates the existing business location type if it exists; otherwise, inserts a new business location type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBusinessLocationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessLocationTypeID = isset($_POST['business_location_type_id']) ? htmlspecialchars($_POST['business_location_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $businessLocationTypeName = htmlspecialchars($_POST['business_location_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBusinessLocationTypeExist = $this->businessLocationTypeModel->checkBusinessLocationTypeExist($businessLocationTypeID);
        $total = $checkBusinessLocationTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->businessLocationTypeModel->updateBusinessLocationType($businessLocationTypeID, $businessLocationTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'businessLocationTypeID' => $this->securityModel->encryptData($businessLocationTypeID)]);
            exit;
        } 
        else {
            $businessLocationTypeID = $this->businessLocationTypeModel->insertBusinessLocationType($businessLocationTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'businessLocationTypeID' => $this->securityModel->encryptData($businessLocationTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBusinessLocationType
    # Description: 
    # Delete the business location type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBusinessLocationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessLocationTypeID = htmlspecialchars($_POST['business_location_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBusinessLocationTypeExist = $this->businessLocationTypeModel->checkBusinessLocationTypeExist($businessLocationTypeID);
        $total = $checkBusinessLocationTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->businessLocationTypeModel->deleteBusinessLocationType($businessLocationTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBusinessLocationType
    # Description: 
    # Delete the selected business location types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBusinessLocationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessLocationTypeIDs = $_POST['business_location_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($businessLocationTypeIDs as $businessLocationTypeID){
            $this->businessLocationTypeModel->deleteBusinessLocationType($businessLocationTypeID);
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
    # Function: duplicateBusinessLocationType
    # Description: 
    # Duplicates the business location type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBusinessLocationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessLocationTypeID = htmlspecialchars($_POST['business_location_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBusinessLocationTypeExist = $this->businessLocationTypeModel->checkBusinessLocationTypeExist($businessLocationTypeID);
        $total = $checkBusinessLocationTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $businessLocationTypeID = $this->businessLocationTypeModel->duplicateBusinessLocationType($businessLocationTypeID, $userID);

        echo json_encode(['success' => true, 'businessLocationTypeID' =>  $this->securityModel->encryptData($businessLocationTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBusinessLocationTypeDetails
    # Description: 
    # Handles the retrieval of business location type details such as business location type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBusinessLocationTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['business_location_type_id']) && !empty($_POST['business_location_type_id'])) {
            $userID = $_SESSION['user_id'];
            $businessLocationTypeID = $_POST['business_location_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $businessLocationTypeDetails = $this->businessLocationTypeModel->getBusinessLocationType($businessLocationTypeID);

            $response = [
                'success' => true,
                'businessLocationTypeName' => $businessLocationTypeDetails['business_location_type_name']
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
require_once '../model/business-location-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BusinessLocationTypeController(new BusinessLocationTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>