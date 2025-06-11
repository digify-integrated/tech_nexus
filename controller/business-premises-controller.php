<?php
session_start();

# -------------------------------------------------------------
#
# Function: BusinessPremisesController
# Description: 
# The BusinessPremisesController class handles business premises related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BusinessPremisesController {
    private $businessPremisesModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BusinessPremisesModel, UserModel and SecurityModel instances.
    # These instances are used for business premises related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BusinessPremisesModel $businessPremisesModel     The BusinessPremisesModel instance for business premises related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BusinessPremisesModel $businessPremisesModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->businessPremisesModel = $businessPremisesModel;
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
                case 'save business premises':
                    $this->saveBusinessPremises();
                    break;
                case 'get business premises details':
                    $this->getBusinessPremisesDetails();
                    break;
                case 'delete business premises':
                    $this->deleteBusinessPremises();
                    break;
                case 'delete multiple business premises':
                    $this->deleteMultipleBusinessPremises();
                    break;
                case 'duplicate business premises':
                    $this->duplicateBusinessPremises();
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
    # Function: saveBusinessPremises
    # Description: 
    # Updates the existing business premises if it exists; otherwise, inserts a new business premises.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBusinessPremises() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessPremisesID = isset($_POST['business_premises_id']) ? htmlspecialchars($_POST['business_premises_id'], ENT_QUOTES, 'UTF-8') : null;
        $businessPremisesName = htmlspecialchars($_POST['business_premises_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBusinessPremisesExist = $this->businessPremisesModel->checkBusinessPremisesExist($businessPremisesID);
        $total = $checkBusinessPremisesExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->businessPremisesModel->updateBusinessPremises($businessPremisesID, $businessPremisesName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'businessPremisesID' => $this->securityModel->encryptData($businessPremisesID)]);
            exit;
        } 
        else {
            $businessPremisesID = $this->businessPremisesModel->insertBusinessPremises($businessPremisesName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'businessPremisesID' => $this->securityModel->encryptData($businessPremisesID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBusinessPremises
    # Description: 
    # Delete the business premises if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBusinessPremises() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessPremisesID = htmlspecialchars($_POST['business_premises_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBusinessPremisesExist = $this->businessPremisesModel->checkBusinessPremisesExist($businessPremisesID);
        $total = $checkBusinessPremisesExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->businessPremisesModel->deleteBusinessPremises($businessPremisesID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBusinessPremises
    # Description: 
    # Delete the selected business premisess if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBusinessPremises() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessPremisesIDs = $_POST['business_premises_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($businessPremisesIDs as $businessPremisesID){
            $this->businessPremisesModel->deleteBusinessPremises($businessPremisesID);
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
    # Function: duplicateBusinessPremises
    # Description: 
    # Duplicates the business premises if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBusinessPremises() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $businessPremisesID = htmlspecialchars($_POST['business_premises_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBusinessPremisesExist = $this->businessPremisesModel->checkBusinessPremisesExist($businessPremisesID);
        $total = $checkBusinessPremisesExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $businessPremisesID = $this->businessPremisesModel->duplicateBusinessPremises($businessPremisesID, $userID);

        echo json_encode(['success' => true, 'businessPremisesID' =>  $this->securityModel->encryptData($businessPremisesID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBusinessPremisesDetails
    # Description: 
    # Handles the retrieval of business premises details such as business premises name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBusinessPremisesDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['business_premises_id']) && !empty($_POST['business_premises_id'])) {
            $userID = $_SESSION['user_id'];
            $businessPremisesID = $_POST['business_premises_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $businessPremisesDetails = $this->businessPremisesModel->getBusinessPremises($businessPremisesID);

            $response = [
                'success' => true,
                'businessPremisesName' => $businessPremisesDetails['business_premises_name']
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
require_once '../model/business-premises-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BusinessPremisesController(new BusinessPremisesModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>