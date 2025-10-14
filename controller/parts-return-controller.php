<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsReturnController
# Description: 
# The PartsReturnController class handles parts return related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsReturnController {
    private $partsReturnModel;
    private $partsModel;
    private $partTransactionModel;
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
    # The constructor initializes the object with the provided PartsReturnModel, UserModel and SecurityModel instances.
    # These instances are used for parts return related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsReturnModel $partsReturnModel     The PartsReturnModel instance for parts return related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsReturnModel $partsReturnModel, PartsModel $partsModel, PartsTransactionModel $partTransactionModel, ProductModel $productModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->partsReturnModel = $partsReturnModel;
        $this->partsModel = $partsModel;
        $this->partTransactionModel = $partTransactionModel;
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
    # This method checks the request method and dispatches the corresponding return based on the provided return parameter.
    # The return determines which action should be performed.
    #
    # Parameters:
    # - $return (string): The type of return.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save parts return':
                    $this->savePartsReturn();
                    break;
                case 'add parts return item':
                    $this->addPartsReturnItem();
                    break;
                case 'save part item':
                    $this->savePartsItem();
                    break;
                case 'delete part item':
                    $this->deletePartsReturnCart();
                    break;
                case 'tag return as for approval':
                    $this->tagAsForApproval();
                    break;
                case 'tag return as approved':
                    $this->tagAsApproved();
                    break;
                case 'tag return as released':
                    $this->tagAsReleased();
                    break;
                case 'get parts return details':
                    $this->getPartsReturnDetails();
                    break;
                case 'get parts return cart details':
                    $this->getPartsReturnCartDetails();
                    break;
                case 'get parts return cart total':
                    $this->getPartsReturnCartTotal();
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
    # Function: savePartsReturn
    # Description: 
    # Updates the existing parts return if it exists; otherwise, inserts a new parts return.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsReturn() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_return_id = isset($_POST['parts_return_id']) ? htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8') : null;
        $company_id = trim(htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8'));
        $supplier_id = htmlspecialchars($_POST['supplier_id'], ENT_QUOTES, 'UTF-8');
        $purchase_date = $this->systemModel->checkDate('empty', $_POST['purchase_date'], '', 'Y-m-d', '');
        $ref_invoice_number = htmlspecialchars($_POST['ref_invoice_number'], ENT_QUOTES, 'UTF-8');
        $ref_po_number = htmlspecialchars($_POST['ref_po_number'], ENT_QUOTES, 'UTF-8');
        $ref_po_date = $this->systemModel->checkDate('empty', $_POST['ref_po_date'], '', 'Y-m-d', '');
        $prev_total_billing = htmlspecialchars($_POST['prev_total_billing'], ENT_QUOTES, 'UTF-8');
        $adjusted_total_billing = htmlspecialchars($_POST['adjusted_total_billing'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsReturnExist = $this->partsReturnModel->checkPartsReturnExist($parts_return_id);
        $total = $checkPartsReturnExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsReturnModel->updatePartsReturn($parts_return_id, $supplier_id, $purchase_date, $ref_invoice_number, $ref_po_number, $ref_po_date, $prev_total_billing, $adjusted_total_billing, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'partsReturnID' => $this->securityModel->encryptData($parts_return_id)]);
            exit;
        } 
        else {
            $parts_return_id = $this->partsReturnModel->insertPartsReturn($supplier_id, $company_id, $purchase_date, $ref_invoice_number, $ref_po_number, $ref_po_date, $prev_total_billing, $adjusted_total_billing, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => true, 'partsReturnID' => $this->securityModel->encryptData($parts_return_id)]);
            exit;
        }
    }

    public function getPartsReturnDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_return_id']) && !empty($_POST['parts_return_id'])) {
            $userID = $_SESSION['user_id'];
            $parts_return_id = $_POST['parts_return_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsReturnDetails = $this->partsReturnModel->getPartsReturn($parts_return_id);

            $response = [
                'success' => true,
                'supplier_id' => $partsReturnDetails['supplier_id'],
                'ref_invoice_number' => $partsReturnDetails['ref_invoice_number'],
                'ref_po_number' => $partsReturnDetails['ref_po_number'],
                'prev_total_billing' => $partsReturnDetails['prev_total_billing'],
                'adjusted_total_billing' => $partsReturnDetails['adjusted_total_billing'],
                'remarks' => $partsReturnDetails['remarks'],
                'purchase_date' =>  $this->systemModel->checkDate('empty', $partsReturnDetails['purchase_date'], '', 'm/d/Y', ''),
                'ref_po_date' =>  $this->systemModel->checkDate('empty', $partsReturnDetails['ref_po_date'], '', 'm/d/Y', ''),
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsReturnCartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['part_return_cart_id']) && !empty($_POST['part_return_cart_id'])) {
            $userID = $_SESSION['user_id'];
            $part_return_cart_id = $_POST['part_return_cart_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsReturnCartDetails = $this->partsReturnModel->getPartsReturnCart($part_return_cart_id);
            $part_transaction_id = $partsReturnCartDetails['part_transaction_id'];
            $part_transaction_cart_id = $partsReturnCartDetails['part_transaction_cart_id'];
            $return_quantity = $partsReturnCartDetails['return_quantity'];
            $remarks = $partsReturnCartDetails['remarks'];

            $getPartsTransaction = $this->partTransactionModel->getPartsTransaction($part_transaction_id);
            $slip_reference_no = $getPartsTransaction['issuance_no'] ?? '';
            $part_id = $getPartsTransaction['part_id'] ?? '';

            $getPartsTransactionCart = $this->partTransactionModel->getPartsTransactionCart($part_transaction_cart_id);
            $part_id = $getPartsTransactionCart['part_id'];
            $available_return_quantity = $getPartsTransactionCart['return_quantity'] ?? 0;

            $partDetails = $this->partsModel->getParts($part_id);
            $description = $partDetails['description'];

            $response = [
                'success' => true,
                'slip_reference_no' => $slip_reference_no,
                'description' => $description,
                'available_return_quantity' => $available_return_quantity,
                'return_quantity' => $return_quantity,
                'remarks' => $partsReturnCartDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsReturnCartTotal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_return_id']) && !empty($_POST['parts_return_id'])) {
            $userID = $_SESSION['user_id'];
            $parts_return_id = $_POST['parts_return_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $cost = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'total cost')['total'];
            $lines = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'lines')['total'];
            $quantity = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'quantity')['total'];

            $response = [
                'success' => true,
                'cost' => number_format($cost, 2) . ' PHP',
                'quantity' => number_format($quantity, 2),
                'lines' => number_format($lines, 0, '', ','),
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function savePartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $part_return_cart_id = htmlspecialchars($_POST['part_return_cart_id'], ENT_QUOTES, 'UTF-8');
        $return_quantity = $_POST['return_quantity'];
        $part_remarks = $_POST['part_remarks'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsReturnCartExist = $this->partsReturnModel->checkPartsReturnCartExist($part_return_cart_id);
        $total = $checkPartsReturnCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsReturnModel->updatePartsReturnCart($part_return_cart_id, $return_quantity, $part_remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsApproved() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        $approval_remarks = htmlspecialchars($_POST['approval_remarks'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->partsReturnModel->check_exceed_part_quantity($parts_return_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }

        $partsTransactionDetails = $this->partsReturnModel->getPartsReturn($parts_return_id);
        $company_id = $partsTransactionDetails['company_id'] ?? null;
        $reference_number = $partsTransactionDetails['reference_number'] ?? null;

        if(empty($reference_number)){
            if($company_id == '2'){
                $reference_number = (int)$this->systemSettingModel->getSystemSetting(41)['value'] + 1;
            }
            else{
                $reference_number = (int)$this->systemSettingModel->getSystemSetting(42)['value'] + 1;
            }

            $this->partsReturnModel->updatePartsReturnReferenceNumber($parts_return_id, $reference_number, $userID);

            if($company_id == '2'){
                $this->systemSettingModel->updateSystemSettingValue(41, $reference_number, $userID);
            }
            else{
                $this->systemSettingModel->updateSystemSettingValue(42, $reference_number, $userID);
            }
        }
    
        $this->partsReturnModel->updatePartsReturnApprove($parts_return_id, $approval_remarks, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->partsReturnModel->check_exceed_part_quantity($parts_return_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }
    
       $this->partsReturnModel->updatePartsReturnForApproval($parts_return_id, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->partsReturnModel->check_exceed_part_quantity($parts_return_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }

        $partsReturnDetails = $this->partsReturnModel->getPartsReturn($parts_return_id);
        $company_id = $partsReturnDetails['company_id'] ?? 2;
        $parts_transaction_id = $partsReturnDetails['parts_transaction_id'] ?? null;
        $reference_number = $partsReturnDetails['reference_number'] ?? null;
        $remarks = $partsReturnDetails['remarks'] ?? null;

        $this->partsReturnModel->updatePartsReturnReleased($parts_return_id, $userID);

        $this->partsReturnModel->updatePartsReturnValue($parts_return_id, $userID);

        if($company_id == '2' || $company_id == '3'){
            $partsTransactionDetails = $this->partTransactionModel->getPartsTransaction($parts_transaction_id);
            $company_id = $partsTransactionDetails['company_id'] ?? '';
            $customer_type = $partsTransactionDetails['customer_type'] ?? '';
            $customer_id = $partsTransactionDetails['customer_id'] ?? '';

            if($company_id == '2'){
                $p_reference_number = $partsTransactionDetails['issuance_no'] ?? '';
            }
            else{
                $p_reference_number = $partsTransactionDetails['reference_number'] ?? '';
            }

            $cost = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'total cost')['total'];
            $price = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'total price')['total'];
            $is_service = 'No';

            if($customer_type == 'Internal'){
                $productDetails = $this->productModel->getProduct($customer_id);
                $product_status = $productDetails['product_status'] ?? 'Draft';
            }
            else{
                $product_status = 'Draft';
            }

            $this->partTransactionModel->createPartsTransactionProductExpense($customer_id, 'Issuance Slip', $parts_transaction_id, $price, 'Parts & ACC', 'Issuance No.: ' . $reference_number . ' - '.  $remarks, $userID); 
            
            $this->partTransactionModel->createPartsTransactionEntryReversed($parts_transaction_id, $company_id, $p_reference_number, $cost, $price, $customer_type, $is_service, $product_status, $userID);
        }
    

        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePartsReturnCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_return_cart_id = htmlspecialchars($_POST['parts_return_cart_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsReturnModel->deletePartsReturnCart($parts_return_cart_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function addPartsReturnItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        $part_transaction_cart_ids = explode(',', $_POST['part_transaction_cart_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($part_transaction_cart_ids as $part_transaction_cart_id) {
            if(!empty($part_transaction_cart_id)){
                $getPartsTransactionCart = $this->partTransactionModel->getPartsTransactionCart($part_transaction_cart_id);
                $part_transaction_id = $getPartsTransactionCart['part_transaction_id'];

                $this->partsReturnModel->insertPartReturnItem($parts_return_id, $part_transaction_id, $part_transaction_cart_id, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/parts-return-model.php';
require_once '../model/parts-model.php';
require_once '../model/parts-transaction-model.php';
require_once '../model/product-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsReturnController(new PartsReturnModel(new DatabaseModel), new PartsModel(new DatabaseModel), new PartsTransactionModel(new DatabaseModel), new ProductModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>