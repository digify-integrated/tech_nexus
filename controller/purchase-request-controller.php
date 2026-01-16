<?php
session_start();

# -------------------------------------------------------------
#
# Function: PurchaseRequestController
# Description: 
# The PurchaseRequestController class handles purchase request related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PurchaseRequestController {
    private $purchaseRequestModel;
    private $partsModel;
    private $productModel;
    private $unitModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;
    private $systemSettingModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PurchaseRequestModel, UserModel and SecurityModel instances.
    # These instances are used for purchase request related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PurchaseRequestModel $purchaseRequestModel     The PurchaseRequestModel instance for purchase request related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PurchaseRequestModel $purchaseRequestModel, PartsModel $partsModel, ProductModel $productModel, UnitModel $unitModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->purchaseRequestModel = $purchaseRequestModel;
        $this->partsModel = $partsModel;
        $this->productModel = $productModel;
        $this->unitModel = $unitModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
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
                case 'save purchase request':
                    $this->savePurchaseRequest();
                    break;
                case 'save purchase request item':
                    $this->savePurchaseRequestItem();
                    break;
                case 'get purchase request details':
                    $this->getPurchaseRequestDetails();
                    break;
                case 'get purchase request cart details':
                    $this->getPurchaseRequestCartDetails();
                    break;
                case 'delete purchase request':
                    $this->deletePurchaseRequest();
                    break;
                case 'delete item':
                    $this->deletePurchaseRequestCart();
                    break;
                case 'delete multiple purchase request':
                    $this->deleteMultiplePurchaseRequest();
                    break;
                case 'tag request as for approval':
                    $this->tagAsForApproval();
                    break;
                case 'tag request as cancelled':
                    $this->tagAsCancelled();
                    break;
                case 'tag request as draft':
                    $this->tagAsDraft();
                    break;
                case 'tag request as approved':
                    $this->tagAsApproved();
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
    # Function: savePurchaseRequest
    # Description: 
    # Updates the existing purchase request if it exists; otherwise, inserts a new purchase request.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePurchaseRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_request_id = isset($_POST['purchase_request_id']) ? htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8') : null;
        $purchase_request_type = htmlspecialchars($_POST['purchase_request_type'], ENT_QUOTES, 'UTF-8');
        $company_id = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $month_coverage = htmlspecialchars($_POST['month_coverage'], ENT_QUOTES, 'UTF-8');
        $coverage_period = htmlspecialchars($_POST['coverage_period'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $checkPurchaseRequestExist = $this->purchaseRequestModel->checkPurchaseRequestExist($purchase_request_id);
        $total = $checkPurchaseRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->purchaseRequestModel->updatePurchaseRequest($purchase_request_id, $purchase_request_type, $company_id, $month_coverage, $coverage_period, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false, 'purchaseRequestID' => $this->securityModel->encryptData($purchase_request_id)]);
            exit;
        } 
        else {
            $reference_number = (int)$this->systemSettingModel->getSystemSetting(44)['value'] + 1;

            $purchase_request_id = $this->purchaseRequestModel->insertPurchaseRequest($reference_number, $purchase_request_type, $company_id, $month_coverage, $coverage_period, $remarks, $userID);

            $this->systemSettingModel->updateSystemSettingValue(44, $reference_number, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true, 'purchaseRequestID' => $this->securityModel->encryptData($purchase_request_id)]);
            exit;
        }
    }

    public function savePurchaseRequestItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_request_id = htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8');
        $purchase_request_cart_id = htmlspecialchars($_POST['purchase_request_cart_id'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
        $quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES, 'UTF-8');
        $unit_id = htmlspecialchars($_POST['unit_id'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['item_remarks'], ENT_QUOTES, 'UTF-8');

        $checkPurchaseRequestItemExist = $this->purchaseRequestModel->checkPurchaseRequestItemExist($purchase_request_cart_id);
        $total = $checkPurchaseRequestItemExist['total'] ?? 0;

        $unitCode = $this->unitModel->getUnit($unit_id);
        $short_name = $unitCode['short_name'] ?? null;
    
        if ($total > 0) {
            $this->purchaseRequestModel->updatePurchaseRequestItem($purchase_request_cart_id, $description, $quantity, $unit_id, $short_name, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->purchaseRequestModel->insertPurchaseRequestItem($purchase_request_id, $description, $quantity, $unit_id, $short_name, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true]);
            exit;
        }
    }

    public function tagAsForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_request_id = htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $getPurchaseRequestItemCount = $this->purchaseRequestModel->getPurchaseRequestItemCount($purchase_request_id)['total'] ?? 0;

        if($getPurchaseRequestItemCount == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }
    
        $this->purchaseRequestModel->updatePurchaseRequestForApproval($purchase_request_id, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_request_id = htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseRequestModel->updatePurchaseRequestCancel($purchase_request_id, $cancellation_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_request_id = htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8');
        $draft_reason = htmlspecialchars($_POST['draft_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseRequestModel->updatePurchaseRequestDraft($purchase_request_id, $draft_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsApproved() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_request_id = htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8');
        $approval_remarks = htmlspecialchars($_POST['approval_remarks'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseRequestModel->updatePurchaseRequestApprove($purchase_request_id, $approval_remarks, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePurchaseRequest
    # Description: 
    # Delete the purchase request if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePurchaseRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchaseRequestID = htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPurchaseRequestExist = $this->purchaseRequestModel->checkPurchaseRequestExist($purchaseRequestID);
        $total = $checkPurchaseRequestExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->purchaseRequestModel->deletePurchaseRequest($purchaseRequestID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePurchaseRequestCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_request_cart_id = htmlspecialchars($_POST['purchase_request_cart_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->purchaseRequestModel->deletePurchaseRequestCart($purchase_request_cart_id);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePurchaseRequest
    # Description: 
    # Delete the selected purchase requests if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePurchaseRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchaseRequestIDs = $_POST['purchase_request_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($purchaseRequestIDs as $purchaseRequestID){
            $this->purchaseRequestModel->deletePurchaseRequest($purchaseRequestID);
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
    # Function: getPurchaseRequestDetails
    # Description: 
    # Handles the retrieval of purchase request details such as purchase request name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPurchaseRequestDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['purchase_request_id']) && !empty($_POST['purchase_request_id'])) {
            $userID = $_SESSION['user_id'];
            $purchase_request_id = $_POST['purchase_request_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $purchaseRequestDetails = $this->purchaseRequestModel->getPurchaseRequest($purchase_request_id);

            $response = [
                'success' => true,
                'reference_no' => $purchaseRequestDetails['reference_no'],
                'purchase_request_type' => $purchaseRequestDetails['purchase_request_type'],
                'company_id' => $purchaseRequestDetails['company_id'],
                'month_coverage' => $purchaseRequestDetails['month_coverage'],
                'coverage_period' => $purchaseRequestDetails['coverage_period'],
                'remarks' => $purchaseRequestDetails['remarks']
            ];

            echo json_encode($response);
            exit;
        }
    }
    public function getPurchaseRequestCartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['purchase_request_cart_id']) && !empty($_POST['purchase_request_cart_id'])) {
            $userID = $_SESSION['user_id'];
            $purchase_request_cart_id = $_POST['purchase_request_cart_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $purchaseRequestDetails = $this->purchaseRequestModel->getPurchaseRequestCart($purchase_request_cart_id);

            $response = [
                'success' => true,
                'description' => $purchaseRequestDetails['description'],
                'quantity' => $purchaseRequestDetails['quantity'],
                'unit_id' => $purchaseRequestDetails['unit_id'],
                'remarks' => $purchaseRequestDetails['remarks']
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
require_once '../model/purchase-request-model.php';
require_once '../model/parts-model.php';
require_once '../model/unit-model.php';
require_once '../model/product-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PurchaseRequestController(new PurchaseRequestModel(new DatabaseModel), new PartsModel(new DatabaseModel), new ProductModel(new DatabaseModel), new UnitModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
