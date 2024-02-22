<?php
session_start();

# -------------------------------------------------------------
#
# Function: ApprovingOfficerController
# Description: 
# The ApprovingOfficerController class handles approving officer related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ApprovingOfficerController {
    private $approvingOfficerModel;
    private $customerModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ApprovingOfficerModel, UserModel and SecurityModel instances.
    # These instances are used for approving officer related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ApprovingOfficerModel $approvingOfficerModel     The ApprovingOfficerModel instance for approving officer related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ApprovingOfficerModel $approvingOfficerModel, CustomerModel $customerModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->approvingOfficerModel = $approvingOfficerModel;
        $this->customerModel = $customerModel;
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
                case 'save approving officer':
                    $this->saveApprovingOfficer();
                    break;
                case 'get approving officer details':
                    $this->getApprovingOfficerDetails();
                    break;
                case 'delete approving officer':
                    $this->deleteApprovingOfficer();
                    break;
                case 'delete multiple approving officer':
                    $this->deleteMultipleApprovingOfficer();
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
    # Function: saveApprovingOfficer
    # Description: 
    # Updates the existing approving officer if it exists; otherwise, inserts a new approving officer.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveApprovingOfficer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $approvingOfficerID = isset($_POST['approving_officer_id']) ? htmlspecialchars($_POST['approving_officer_id'], ENT_QUOTES, 'UTF-8') : null;
        $contactID = htmlspecialchars($_POST['contact_id'], ENT_QUOTES, 'UTF-8');
        $approvingOfficerType = htmlspecialchars($_POST['approving_officer_type'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $approvingOfficerID = $this->approvingOfficerModel->insertApprovingOfficer($contactID, $approvingOfficerType, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'approvingOfficerID' => $this->securityModel->encryptData($approvingOfficerID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteApprovingOfficer
    # Description: 
    # Delete the approving officer if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteApprovingOfficer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $approvingOfficerID = htmlspecialchars($_POST['approving_officer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkApprovingOfficerExist = $this->approvingOfficerModel->checkApprovingOfficerExist($approvingOfficerID);
        $total = $checkApprovingOfficerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->approvingOfficerModel->deleteApprovingOfficer($approvingOfficerID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleApprovingOfficer
    # Description: 
    # Delete the selected approving officers if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleApprovingOfficer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $approvingOfficerIDs = $_POST['approving_officer_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($approvingOfficerIDs as $approvingOfficerID){
            $this->approvingOfficerModel->deleteApprovingOfficer($approvingOfficerID);
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
    # Function: getApprovingOfficerDetails
    # Description: 
    # Handles the retrieval of approving officer details such as approving officer name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getApprovingOfficerDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['approving_officer_id']) && !empty($_POST['approving_officer_id'])) {
            $userID = $_SESSION['user_id'];
            $approvingOfficerID = $_POST['approving_officer_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $approvingOfficerDetails = $this->approvingOfficerModel->getApprovingOfficer($approvingOfficerID);
            $contactID = $approvingOfficerDetails['contact_id'];

            $approverDetails = $this->customerModel->getPersonalInformation($contactID);
            $approverName = $approverDetails['file_as'] ?? null;

            $response = [
                'success' => true,
                'approverName' => $approverName,
                'approvingOfficerType' => $approvingOfficerDetails['approving_officer_type'],
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
require_once '../model/approving-officer-model.php';
require_once '../model/customer-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ApprovingOfficerController(new ApprovingOfficerModel(new DatabaseModel), new CustomerModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>