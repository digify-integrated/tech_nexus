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
    public function __construct(PurchaseRequestModel $purchaseRequestModel, PartsModel $partsModel, ProductModel $productModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->purchaseRequestModel = $purchaseRequestModel;
        $this->partsModel = $partsModel;
        $this->productModel = $productModel;
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
                case 'get purchase request details':
                    $this->getPurchaseRequestDetails();
                    break;
                case 'delete purchase request':
                    $this->deletePurchaseRequest();
                    break;
                case 'delete part item':
                    $this->deletePurchaseRequestCart();
                    break;
                case 'delete multiple purchase request':
                    $this->deleteMultiplePurchaseRequest();
                    break;
                case 'tag transaction as for approval':
                    $this->tagAsForApproval();
                    break;
                case 'tag transaction as released':
                    $this->tagAsReleased();
                    break;
                case 'tag transaction as cancelled':
                    $this->tagAsCancelled();
                    break;
                case 'tag transaction as draft':
                    $this->tagAsDraft();
                    break;
                case 'tag transaction as approved':
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
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $checkPurchaseRequestExist = $this->purchaseRequestModel->checkPurchaseRequestExist($purchase_request_id);
        $total = $checkPurchaseRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->purchaseRequestModel->updatePurchaseRequest($purchase_request_id, $purchase_request_type, $company_id, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false, 'purchaseRequestID' => $this->securityModel->encryptData($purchase_request_id)]);
            exit;
        } 
        else {
            $reference_number = (int)$this->systemSettingModel->getSystemSetting(44)['value'] + 1;

            $this->purchaseRequestModel->insertPurchaseRequest($reference_number, $purchase_request_type, $company_id, $remarks, $userID);

            $this->systemSettingModel->updateSystemSettingValue(44, $reference_number, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true, 'purchaseRequestID' => $this->securityModel->encryptData($purchase_request_id)]);
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

        $partTransactionDetails = $this->purchaseRequestModel->getPurchaseRequest($purchase_request_id);
        $number_of_items = $partTransactionDetails['number_of_items'] ?? 0;
        $customer_type = $partTransactionDetails['customer_type'] ?? '';
        $issuance_for = $partTransactionDetails['issuance_for'] ?? '';
        $customer_id = $partTransactionDetails['customer_id'] ?? '';

        if($number_of_items == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

         $quantityCheck = $this->purchaseRequestModel->get_exceeded_part_quantity_count($purchase_request_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->purchaseRequestModel->check_exceed_part_quantity($purchase_request_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }

        $cost = $this->purchaseRequestModel->getPurchaseRequestCartTotal($purchase_request_id, 'cost')['total'] ?? 0;

        if($customer_type == 'Internal' && $customer_id == '958' && $issuance_for == 'Tools' && $cost < 5000){
            echo json_encode(['success' => false, 'tools' => true]);
            exit;
        }
    
        $this->purchaseRequestModel->updatePurchaseRequestStatus($purchase_request_id, 'For Validation', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsReleased() {
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

        $check_exceed_part_quantity = $this->purchaseRequestModel->check_exceed_part_quantity($purchase_request_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }

        $quantityCheck = $this->purchaseRequestModel->get_exceeded_part_quantity_count($purchase_request_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $purchaseRequestDetails = $this->purchaseRequestModel->getPurchaseRequest($purchase_request_id);
        $company_id = $purchaseRequestDetails['company_id'] ?? '';
        $customer_type = $purchaseRequestDetails['customer_type'] ?? '';
        $customer_id = $purchaseRequestDetails['customer_id'] ?? '';
        $remarks = $purchaseRequestDetails['remarks'] ?? '';
       
        if($customer_type == 'Internal' && $customer_id == 958){
            $issuance_for = $purchaseRequestDetails['issuance_for'] ?? '';
        }
        else{
            $issuance_for = null;
        }
        
        if($company_id == '2' || $company_id == '1'){
            $p_reference_number = $purchaseRequestDetails['issuance_no'] ?? '';
        }
        else{
            $p_reference_number = $purchaseRequestDetails['reference_number'] ?? '';
        }

        $cost = $this->purchaseRequestModel->getPurchaseRequestCartTotal($purchase_request_id, 'cost')['total'] ?? 0;

        $this->purchaseRequestModel->updatePurchaseRequestStatus($purchase_request_id, 'Released', '', $userID);
       
        if($customer_type == 'Internal'){
            $productDetails = $this->productModel->getProduct($customer_id);
            $is_service = $productDetails['is_service'] ?? 'No';
            $product_status = $productDetails['product_status'] ?? 'Draft';

            if($is_service == 'Yes'){
                $overallTotal = $this->purchaseRequestModel->getPurchaseRequestCartTotal($purchase_request_id, 'gasoline cost')['total'] ?? 0;

                $this->purchaseRequestModel->createPurchaseRequestProductExpense($customer_id, 'Issuance Slip', $purchase_request_id, 0, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, $userID); 
            }
            else{
                $overallTotal = $this->purchaseRequestModel->getPurchaseRequestCartTotal($purchase_request_id, 'overall total')['total'] ?? 0;

                $this->purchaseRequestModel->createPurchaseRequestProductExpense($customer_id, 'Issuance Slip', $purchase_request_id, $overallTotal, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, $userID); 
            }
        }
        else{
            $overallTotal = $this->purchaseRequestModel->getPurchaseRequestCartTotal($purchase_request_id, 'overall total')['total'] ?? 0;
            $is_service = 'No';
            $product_status = 'Draft';
        }

        if($company_id == '2' || $company_id == '3'){         
            $this->purchaseRequestModel->createPurchaseRequestEntry($purchase_request_id, $company_id, $p_reference_number, $cost, $overallTotal, $customer_type, $is_service, $product_status, $issuance_for, $userID);
        }

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

    
        $quantityCheck = $this->purchaseRequestModel->get_exceeded_part_quantity_count($purchase_request_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $this->purchaseRequestModel->updatePurchaseRequestStatus($purchase_request_id, 'Cancelled', $cancellation_reason, $userID);
        
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

        $this->purchaseRequestModel->updatePurchaseRequestStatus($purchase_request_id, 'Draft', $draft_reason, $userID);
        
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

        $quantityCheck = $this->purchaseRequestModel->get_exceeded_part_quantity_count($purchase_request_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $purchaseRequestDetails = $this->purchaseRequestModel->getPurchaseRequest($purchase_request_id);
        $customer_type = $purchaseRequestDetails['customer_type'] ?? null;
        $company_id = $purchaseRequestDetails['company_id'] ?? null;
        $issuance_no = $purchaseRequestDetails['issuance_no'] ?? null;

        if($customer_type == 'Internal'){

            $check_linked_job_order = $this->purchaseRequestModel->check_linked_job_order($purchase_request_id)['total'] ?? 0;

            if($check_linked_job_order == 0){
                echo json_encode(['success' => false, 'jobOrder' => true]);
                exit;
            }
            
            if(empty($issuance_no)){
                if($company_id == '2'){
                    $reference_number = (int)$this->systemSettingModel->getSystemSetting(32)['value'] + 1;
                }
                else{
                    $reference_number = (int)$this->systemSettingModel->getSystemSetting(34)['value'] + 1;
                }

                $this->purchaseRequestModel->updatePurchaseRequestSlipReferenceNumber($purchase_request_id, $reference_number, $userID);

                if($company_id == '2'){
                    $this->systemSettingModel->updateSystemSettingValue(32, $reference_number, $userID);
                }
                else{
                    $this->systemSettingModel->updateSystemSettingValue(34, $reference_number, $userID);
                }
            }
        }

        if($customer_type == 'Department'){
            if(empty($issuance_no)){
                if($company_id == '1'){
                    $reference_number = (int)$this->systemSettingModel->getSystemSetting(37)['value'] + 1;
                }

                $this->purchaseRequestModel->updatePurchaseRequestSlipReferenceNumber($purchase_request_id, $reference_number, $userID);

                if($company_id == '1'){
                    $this->systemSettingModel->updateSystemSettingValue(37, $reference_number, $userID);
                }
            }
        }
    
        $this->purchaseRequestModel->updatePurchaseRequestStatus($purchase_request_id, 'Validated', $approval_remarks, $userID);
        
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
        $purchase_request_id = htmlspecialchars($_POST['purchase_request_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->purchaseRequestModel->deletePurchaseRequestCart($purchase_request_cart_id);

        $this->purchaseRequestModel->updatePartTransactionSummary($purchase_request_id);
            
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
                'customer_type' => $purchaseRequestDetails['customer_type'],
                'customer_id' => $purchaseRequestDetails['customer_id'],
                'company_id' => $purchaseRequestDetails['company_id'],
                'customer_ref_id' => $purchaseRequestDetails['customer_ref_id'],
                'issuance_no' => $purchaseRequestDetails['issuance_no'],
                'reference_number' => $purchaseRequestDetails['reference_number'],
                'remarks' => $purchaseRequestDetails['remarks'],
                'issuance_for' => $purchaseRequestDetails['issuance_for'],
                'request_by' => $purchaseRequestDetails['request_by'],
                'discount' => $purchaseRequestDetails['discount'],
                'discount_type' => $purchaseRequestDetails['discount_type'],
                'overall_total' => $purchaseRequestDetails['overall_total'],
                'addOnDiscount' => number_format($purchaseRequestDetails['overall_total'] ?? 0, 2) . ' PHP',
                'issuance_date' =>  $this->systemModel->checkDate('empty', $purchaseRequestDetails['issuance_date'], '', 'm/d/Y', ''),
                'reference_date' =>  $this->systemModel->checkDate('empty', $purchaseRequestDetails['reference_date'], '', 'm/d/Y', ''),
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
require_once '../model/product-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PurchaseRequestController(new PurchaseRequestModel(new DatabaseModel), new PartsModel(new DatabaseModel), new ProductModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>