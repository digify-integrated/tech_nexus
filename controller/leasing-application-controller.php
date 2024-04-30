<?php
session_start();

# -------------------------------------------------------------
#
# Function: LeasingApplicationController
# Description: 
# The LeasingApplicationController class handles leasing application related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LeasingApplicationController {
    private $leasingApplicationModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LeasingApplicationModel, UserModel and SecurityModel instances.
    # These instances are used for leasing application related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LeasingApplicationModel $leasingApplicationModel     The LeasingApplicationModel instance for leasing application related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LeasingApplicationModel $leasingApplicationModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->leasingApplicationModel = $leasingApplicationModel;
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
                case 'save leasing application':
                    $this->saveLeasingApplication();
                    break;
                case 'get leasing application details':
                    $this->getLeasingApplicationDetails();
                    break;
                case 'delete leasing application':
                    $this->deleteLeasingApplication();
                    break;
                case 'delete multiple leasing application':
                    $this->deleteMultipleLeasingApplication();
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
    # Function: saveLeasingApplication
    # Description: 
    # Updates the existing leasing application if it exists; otherwise, inserts a new leasing application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeasingApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationID = isset($_POST['leasing']) ? htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $renewalTag = htmlspecialchars($_POST['renewal_tag'], ENT_QUOTES, 'UTF-8');
        $tenantID = htmlspecialchars($_POST['tenant_id'], ENT_QUOTES, 'UTF-8');
        $propertyID = htmlspecialchars($_POST['property_id'], ENT_QUOTES, 'UTF-8');
        $termLength = htmlspecialchars($_POST['term_length'], ENT_QUOTES, 'UTF-8');
        $termType = htmlspecialchars($_POST['term_type'], ENT_QUOTES, 'UTF-8');
        $paymentFrequency = htmlspecialchars($_POST['payment_frequency'], ENT_QUOTES, 'UTF-8');
        $startDate = $this->systemModel->checkDate('empty', $_POST['start_date'], '', 'Y-m-d', '');
        $maturityDate = $this->systemModel->checkDate('empty', $_POST['maturity_date'], '', 'Y-m-d', '');
        $initialBasicRental = htmlspecialchars($_POST['initial_basic_rental'], ENT_QUOTES, 'UTF-8');
        $escalationRate = htmlspecialchars($_POST['escalation_rate'], ENT_QUOTES, 'UTF-8');
        $securityDeposit = htmlspecialchars($_POST['security_deposit'], ENT_QUOTES, 'UTF-8');
        $floorArea = htmlspecialchars($_POST['floor_area'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->leasingApplicationModel->updateLeasingApplication($leasingApplicationID, $tenantID, $propertyID, $termLength, $termType, $paymentFrequency, $renewalTag, $startDate, $maturityDate, $securityDeposit, $floorArea, $initialBasicRental, $escalationRate, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leasingApplicationID' => $this->securityModel->encryptData($leasingApplicationID)]);
            exit;
        } 
        else {
            $leasingApplicationNumber = $this->systemSettingModel->getSystemSetting(7)['value'] + 1;

            $leasingApplicationID = $this->leasingApplicationModel->insertLeasingApplication($leasingApplicationNumber, $tenantID, $propertyID, $termLength, $termType, $paymentFrequency, $renewalTag, $startDate, $maturityDate, $securityDeposit, $floorArea, $initialBasicRental, $escalationRate, $remarks, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leasingApplicationID' => $this->securityModel->encryptData($leasingApplicationID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingApplication
    # Description: 
    # Delete the leasing application if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeasingApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->deleteLeasingApplication($leasingApplicationID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLeasingApplication
    # Description: 
    # Delete the selected leasing applications if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLeasingApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationIDs = $_POST['leasing_application_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leasingApplicationIDs as $leasingApplicationID){
            $this->leasingApplicationModel->deleteLeasingApplication($leasingApplicationID);
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
    # Function: getLeasingApplicationDetails
    # Description: 
    # Handles the retrieval of leasing application details such as leasing application name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLeasingApplicationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['leasing_application_id']) && !empty($_POST['leasing_application_id'])) {
            $userID = $_SESSION['user_id'];
            $leasingApplicationID = $_POST['leasing_application_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leasingApplicationDetails = $this->leasingApplicationModel->getLeasingApplication($leasingApplicationID);

            $response = [
                'success' => true,
                'leasingApplicationNumber' => $leasingApplicationDetails['leasing_application_number'],
                'tenantID' => $leasingApplicationDetails['tenant_id'],
                'propertyID' => $leasingApplicationDetails['property_id'],
                'termLength' => $leasingApplicationDetails['term_length'],
                'termType' => $leasingApplicationDetails['term_type'],
                'paymentFrequency' => $leasingApplicationDetails['payment_frequency'],
                'renewalTag' => $leasingApplicationDetails['renewal_tag'],
                'remarks' => $leasingApplicationDetails['remarks'],
                'securityDeposit' => $leasingApplicationDetails['security_deposit'],
                'floorArea' => $leasingApplicationDetails['floor_area'],
                'initialBasicRental' => $leasingApplicationDetails['initial_basic_rental'],
                'escalationRate' => $leasingApplicationDetails['escalation_rate'],
                'startDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['start_date'], '', 'm/d/Y', '')
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
require_once '../model/body-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeasingApplicationController(new LeasingApplicationModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>