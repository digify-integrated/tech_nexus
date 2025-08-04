<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsPurchasedMonitoringController
# Description: 
# The PartsPurchasedMonitoringController class handles parts purchase monitoring related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsPurchasedMonitoringController {
    private $partsPurchasedMonitoringModel;
    private $userModel;
    private $uploadSettingModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PartsPurchasedMonitoringModel, FileTypeModel, UserModel and SecurityModel instances.
    # These instances are used for parts purchase monitoring related operations, menu group related operations, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsPurchasedMonitoringModel $partsPurchasedMonitoringModel     The PartsPurchasedMonitoringModel instance for parts purchase monitoring related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsPurchasedMonitoringModel $partsPurchasedMonitoringModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->partsPurchasedMonitoringModel = $partsPurchasedMonitoringModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->securityModel = $securityModel;
        $this->systemModel = $systemModel;
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
                case 'tag parts purchase as cancelled':
                    $this->tagPartsPurchasedMonitoringAsCancelled();
                    break;
                case 'tag parts purchase as issued':
                    $this->tagPartsPurchasedMonitoringAsIssued();
                    break;
                case 'tag as issued':
                    $this->tagPartsPurchasedMonitoringAsIssued2();
                    break;
                case 'get parts purchased monitoring item details':
                    $this->getPartsPurchasedMonitoringItemDetails();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

     public function tagPartsPurchasedMonitoringAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_purchase_monitoring_item_id = htmlspecialchars($_POST['parts_purchase_monitoring_item_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsPurchasedMonitoringModel->cancelPartsPurchasedMonitoringStatus($parts_purchase_monitoring_item_id, $cancellation_reason, $userID);

        echo json_encode(['success' => true]);
        exit;
    }

     public function tagPartsPurchasedMonitoringAsIssued() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_purchase_monitoring_item_id = htmlspecialchars($_POST['parts_purchase_monitoring_item_id'], ENT_QUOTES, 'UTF-8');
        $reference_number = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
        $quantity_issued = htmlspecialchars($_POST['quantity_issued'], ENT_QUOTES, 'UTF-8');
        $issuance_date = $this->systemModel->checkDate('empty', $_POST['issuance_date'], '', 'Y-m-d', '');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsPurchasedMonitoringModel->issuePartsPurchasedMonitoringStatus($parts_purchase_monitoring_item_id, $reference_number, $quantity_issued, $issuance_date, $remarks, $userID);

        echo json_encode(['success' => true]);
        exit;
    }

     public function tagPartsPurchasedMonitoringAsIssued2() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_purchase_monitoring_item_id = htmlspecialchars($_POST['parts_purchase_monitoring_item_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsPurchasedMonitoringModel->tagPartsPurchasedMonitoringAsIssued($parts_purchase_monitoring_item_id, $userID);

        echo json_encode(['success' => true]);
        exit;
    }

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsPurchasedMonitoringDetails
    # Description: 
    # Handles the retrieval of parts purchase monitoring details such as parts purchase monitoring name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsPurchasedMonitoringItemDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['parts_purchase_monitoring_item_id']) && !empty($_POST['parts_purchase_monitoring_item_id'])) {
            $userID = $_SESSION['user_id'];
            $parts_purchase_monitoring_item_id = $_POST['parts_purchase_monitoring_item_id'];

            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsPurchasedMonitoringDetails = $this->partsPurchasedMonitoringModel->checkPartsPurchasedMonitoringItem($parts_purchase_monitoring_item_id);

            $response = [
                'success' => true,
                'purchased_quantity' => $partsPurchasedMonitoringDetails['quantity'],
                'reference_number' => $partsPurchasedMonitoringDetails['reference_number'],
                'quantity_issued' => $partsPurchasedMonitoringDetails['quantity_issued'],
                'remarks' => $partsPurchasedMonitoringDetails['remarks'],
                'issuance_date' =>  $this->systemModel->checkDate('empty', $partsPurchasedMonitoringDetails['issuance_date'], '', 'm/d/Y', ''),
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
require_once '../model/parts-purchased-monitoring-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsPurchasedMonitoringController(new PartsPurchasedMonitoringModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new SecurityModel(new DatabaseModel), new SystemModel());
$controller->handleRequest();

?>