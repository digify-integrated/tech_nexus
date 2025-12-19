<?php
session_start();

# -------------------------------------------------------------
#
# Function: PurchaseOrderController
# Description: 
# The PurchaseOrderController class handles purchase order related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PurchaseOrderController {
    private $purchaseOrderModel;
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
    # The constructor initializes the object with the provided PurchaseOrderModel, UserModel and SecurityModel instances.
    # These instances are used for purchase order related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PurchaseOrderModel $purchaseOrderModel     The PurchaseOrderModel instance for purchase order related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PurchaseOrderModel $purchaseOrderModel, PartsModel $partsModel, ProductModel $productModel, UnitModel $unitModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->purchaseOrderModel = $purchaseOrderModel;
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
                case 'save purchase order':
                    $this->savePurchaseOrder();
                    break;
                case 'save purchase order item':
                    $this->savePurchaseOrderItem();
                    break;
                case 'get purchase order details':
                    $this->getPurchaseOrderDetails();
                    break;
                case 'get purchase order cart details':
                    $this->getPurchaseOrderCartDetails();
                    break;
                case 'delete purchase order':
                    $this->deletePurchaseOrder();
                    break;
                case 'delete item':
                    $this->deletePurchaseOrderCart();
                    break;
                case 'delete multiple purchase order':
                    $this->deleteMultiplePurchaseOrder();
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
    # Function: savePurchaseOrder
    # Description: 
    # Updates the existing purchase order if it exists; otherwise, inserts a new purchase order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePurchaseOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_id = isset($_POST['purchase_order_id']) ? htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8') : null;
        $purchase_order_type = htmlspecialchars($_POST['purchase_order_type'], ENT_QUOTES, 'UTF-8');
        $company_id = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $checkPurchaseOrderExist = $this->purchaseOrderModel->checkPurchaseOrderExist($purchase_order_id);
        $total = $checkPurchaseOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->purchaseOrderModel->updatePurchaseOrder($purchase_order_id, $purchase_order_type, $company_id, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false, 'purchaseOrderID' => $this->securityModel->encryptData($purchase_order_id)]);
            exit;
        } 
        else {
            $reference_number = (int)$this->systemSettingModel->getSystemSetting(44)['value'] + 1;

            $purchase_order_id = $this->purchaseOrderModel->insertPurchaseOrder($reference_number, $purchase_order_type, $company_id, $remarks, $userID);

            $this->systemSettingModel->updateSystemSettingValue(44, $reference_number, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true, 'purchaseOrderID' => $this->securityModel->encryptData($purchase_order_id)]);
            exit;
        }
    }

    public function savePurchaseOrderItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        $purchase_order_cart_id = htmlspecialchars($_POST['purchase_order_cart_id'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
        $quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES, 'UTF-8');
        $unit_id = htmlspecialchars($_POST['unit_id'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['item_remarks'], ENT_QUOTES, 'UTF-8');

        $checkPurchaseOrderItemExist = $this->purchaseOrderModel->checkPurchaseOrderItemExist($purchase_order_cart_id);
        $total = $checkPurchaseOrderItemExist['total'] ?? 0;

        $unitCode = $this->unitModel->getUnit($unit_id);
        $short_name = $unitCode['short_name'] ?? null;
    
        if ($total > 0) {
            $this->purchaseOrderModel->updatePurchaseOrderItem($purchase_order_cart_id, $description, $quantity, $unit_id, $short_name, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->purchaseOrderModel->insertPurchaseOrderItem($purchase_order_id, $description, $quantity, $unit_id, $short_name, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true]);
            exit;
        }
    }

    public function tagAsForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $getPurchaseOrderItemCount = $this->purchaseOrderModel->getPurchaseOrderItemCount($purchase_order_id)['total'] ?? 0;

        if($getPurchaseOrderItemCount == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }
    
        $this->purchaseOrderModel->updatePurchaseOrderForApproval($purchase_order_id, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderCancel($purchase_order_id, $cancellation_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        $draft_reason = htmlspecialchars($_POST['draft_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderDraft($purchase_order_id, $draft_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsApproved() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        $approval_remarks = htmlspecialchars($_POST['approval_remarks'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderApprove($purchase_order_id, $approval_remarks, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePurchaseOrder
    # Description: 
    # Delete the purchase order if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePurchaseOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchaseOrderID = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPurchaseOrderExist = $this->purchaseOrderModel->checkPurchaseOrderExist($purchaseOrderID);
        $total = $checkPurchaseOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->purchaseOrderModel->deletePurchaseOrder($purchaseOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePurchaseOrderCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_cart_id = htmlspecialchars($_POST['purchase_order_cart_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->purchaseOrderModel->deletePurchaseOrderCart($purchase_order_cart_id);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePurchaseOrder
    # Description: 
    # Delete the selected purchase orders if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePurchaseOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchaseOrderIDs = $_POST['purchase_order_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($purchaseOrderIDs as $purchaseOrderID){
            $this->purchaseOrderModel->deletePurchaseOrder($purchaseOrderID);
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
    # Function: getPurchaseOrderDetails
    # Description: 
    # Handles the retrieval of purchase order details such as purchase order name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPurchaseOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['purchase_order_id']) && !empty($_POST['purchase_order_id'])) {
            $userID = $_SESSION['user_id'];
            $purchase_order_id = $_POST['purchase_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrder($purchase_order_id);

            $response = [
                'success' => true,
                'reference_no' => $purchaseOrderDetails['reference_no'],
                'purchase_order_type' => $purchaseOrderDetails['purchase_order_type'],
                'company_id' => $purchaseOrderDetails['company_id'],
                'remarks' => $purchaseOrderDetails['remarks']
            ];

            echo json_encode($response);
            exit;
        }
    }
    public function getPurchaseOrderCartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['purchase_order_cart_id']) && !empty($_POST['purchase_order_cart_id'])) {
            $userID = $_SESSION['user_id'];
            $purchase_order_cart_id = $_POST['purchase_order_cart_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrderCart($purchase_order_cart_id);

            $response = [
                'success' => true,
                'description' => $purchaseOrderDetails['description'],
                'quantity' => $purchaseOrderDetails['quantity'],
                'unit_id' => $purchaseOrderDetails['unit_id'],
                'remarks' => $purchaseOrderDetails['remarks']
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
require_once '../model/purchase-order-model.php';
require_once '../model/parts-model.php';
require_once '../model/unit-model.php';
require_once '../model/product-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PurchaseOrderController(new PurchaseOrderModel(new DatabaseModel), new PartsModel(new DatabaseModel), new ProductModel(new DatabaseModel), new UnitModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
