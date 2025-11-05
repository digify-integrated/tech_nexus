<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsTransactionController
# Description: 
# The PartsTransactionController class handles parts transaction related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsTransactionController {
    private $partsTransactionModel;
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
    # The constructor initializes the object with the provided PartsTransactionModel, UserModel and SecurityModel instances.
    # These instances are used for parts transaction related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsTransactionModel $partsTransactionModel     The PartsTransactionModel instance for parts transaction related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsTransactionModel $partsTransactionModel, PartsModel $partsModel, ProductModel $productModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->partsTransactionModel = $partsTransactionModel;
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
                case 'save parts transaction':
                    $this->savePartsTransaction();
                    break;
                case 'save part item':
                    $this->savePartsItem();
                    break;
                case 'add parts transaction item':
                    $this->addPartsTransactionItem();
                    break;
                case 'add job order':
                    $this->addJobOrder();
                    break;
                case 'add additional job order':
                    $this->addAdditionalJobOrder();
                    break;
                case 'get parts transaction details':
                    $this->getPartsTransactionDetails();
                    break;
                case 'get parts transaction cart details':
                    $this->getPartsTransactionCartDetails();
                    break;
                case 'get parts transaction cart total':
                    $this->getPartsTransactionCartTotal();
                    break;
                case 'delete parts transaction':
                    $this->deletePartsTransaction();
                    break;
                case 'delete part item':
                    $this->deletePartsTransactionCart();
                    break;
                case 'delete job order':
                    $this->deleteJobOrder();
                    break;
                case 'delete internal job order':
                    $this->deleteJobOrder();
                    break;
                case 'delete additional job order':
                    $this->deleteAdditionalJobOrder();
                    break;
                case 'delete internal additional job order':
                    $this->deleteAdditionalJobOrder();
                    break;
                case 'add parts transaction document':
                    $this->addPartsDocument();
                    break;
                case 'delete multiple parts transaction':
                    $this->deleteMultiplePartsTransaction();
                    break;
                case 'tag transaction as on process':
                    $this->tagAsOnProcess();
                    break;
                case 'tag transaction as for approval':
                    $this->tagAsForApproval();
                    break;
                case 'tag transaction as released':
                    $this->tagAsReleased();
                    break;
                case 'tag transaction as checked':
                    $this->tagAsChecked();
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
    # Function: savePartsTransaction
    # Description: 
    # Updates the existing parts transaction if it exists; otherwise, inserts a new parts transaction.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = isset($_POST['parts_transaction_id']) ? htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8') : null;
        $customer_type = htmlspecialchars($_POST['customer_type'], ENT_QUOTES, 'UTF-8');
        $customer_id = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $customer_ref_id = htmlspecialchars($_POST['customer_ref_id'], ENT_QUOTES, 'UTF-8');
        $misc_id = htmlspecialchars($_POST['misc_id'], ENT_QUOTES, 'UTF-8');
        $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $department_id = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
        $company_id = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $reference_number = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
        $issuance_for = htmlspecialchars($_POST['issuance_for'], ENT_QUOTES, 'UTF-8');
        $request_by = htmlspecialchars($_POST['request_by'], ENT_QUOTES, 'UTF-8');
        $issuance_date = $this->systemModel->checkDate('empty', $_POST['issuance_date'], '', 'Y-m-d', '');
        $reference_date = $this->systemModel->checkDate('empty', $_POST['reference_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($customer_type == 'Miscellaneous'){
            $customer_id = $misc_id;
        }

        if($customer_type == 'Internal'){
            $customer_id = $product_id;
        }

        if($customer_type == 'Department'){
            $customer_id = $department_id;
        }

        $checkPartsTransactionExist = $this->partsTransactionModel->checkPartsTransactionExist($parts_transaction_id);
        $total = $checkPartsTransactionExist['total'] ?? 0;
    
        if ($total > 0) {
            $overall_discount = htmlspecialchars($_POST['overall_discount'], ENT_QUOTES, 'UTF-8');
            $overall_discount_type = htmlspecialchars($_POST['overall_discount_type'], ENT_QUOTES, 'UTF-8');
            $overall_discount_total = htmlspecialchars($_POST['overall_discount_total'], ENT_QUOTES, 'UTF-8');

            $partsTransactionDetails = $this->partsTransactionModel->getPartsTransaction($parts_transaction_id);

            $this->partsTransactionModel->updatePartsTransaction($parts_transaction_id, $customer_type, $customer_id, $company_id, $issuance_date, $partsTransactionDetails['issuance_no'], $reference_date, $reference_number, $remarks, $issuance_for, $overall_discount, $overall_discount_type, $overall_discount_total, $request_by, $customer_ref_id, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false, 'partsTransactionID' => $this->securityModel->encryptData($parts_transaction_id)]);
            exit;
        } 
        else {
            $partsTransactionID = $this->generateTransactionID();
            $this->partsTransactionModel->insertPartsTransaction($partsTransactionID, $customer_type, $customer_id, $company_id, $issuance_date, '', $reference_date, $reference_number, $remarks, $issuance_for, $request_by, $customer_ref_id, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true, 'partsTransactionID' => $this->securityModel->encryptData($partsTransactionID)]);
            exit;
        }
    }

    private function generateTransactionID(string $prefix = 'PRT-'): string
    {
        // Sanitize prefix: allow only alphanumeric characters
        $prefix = preg_replace('/[^A-Za-z0-9]/', '', $prefix);

        // Limit prefix length to prevent excessively long IDs
        $maxPrefixLength = 10;
        $prefix = substr($prefix, 0, $maxPrefixLength);

        // Generate timestamp in the format YYYYMMDDHHMMSS (14 characters)
        $timestamp = date('YmdHis');

        // Concatenate prefix and timestamp
        return $prefix . $timestamp;
    }


    public function addPartsTransactionItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        $part_ids = explode(',', $_POST['part_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($part_ids as $part_id) {
            if(!empty($part_id)){
                $this->partsTransactionModel->insertPartItem($parts_transaction_id, $part_id, $userID);
            
                $this->partsTransactionModel->updatePartTransactionSummary($parts_transaction_id);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function addJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        $generate_job_order = $_POST['generate_job_order'];
        $job_order_ids = explode(',', $_POST['job_order_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($job_order_ids as $job_order_id) {
            if(!empty($job_order_id)){
                $this->partsTransactionModel->insertPartsTransactionJobOrder($parts_transaction_id, $job_order_id, $generate_job_order, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function addAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        $generate_job_order = $_POST['generate_job_order'];
        $additional_job_order_ids = explode(',', $_POST['additional_job_order_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($additional_job_order_ids as $additional_job_order_id) {
            if(!empty($additional_job_order_id)){
                $this->partsTransactionModel->insertPartsTransactionAdditionalJobOrder($parts_transaction_id, $additional_job_order_id, $generate_job_order, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsOnProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $partTransactionDetails = $this->partsTransactionModel->getPartsTransaction($parts_transaction_id);
        $number_of_items = $partTransactionDetails['number_of_items'] ?? 0;

        if($number_of_items == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

        $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

         $check_exceed_part_quantity = $this->partsTransactionModel->check_exceed_part_quantity($parts_transaction_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }
    
        $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'On-Process', '', $userID);

        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

         $partTransactionDetails = $this->partsTransactionModel->getPartsTransaction($parts_transaction_id);
        $number_of_items = $partTransactionDetails['number_of_items'] ?? 0;

        if($number_of_items == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

         $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->partsTransactionModel->check_exceed_part_quantity($parts_transaction_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }
    
       $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'For Validation', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->partsTransactionModel->check_exceed_part_quantity($parts_transaction_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }

        $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $partsTransactionDetails = $this->partsTransactionModel->getPartsTransaction($parts_transaction_id);
        $company_id = $partsTransactionDetails['company_id'] ?? '';
        $customer_type = $partsTransactionDetails['customer_type'] ?? '';
        $customer_id = $partsTransactionDetails['customer_id'] ?? '';
        $remarks = $partsTransactionDetails['remarks'] ?? '';
       
        if($customer_type == 'Internal' && $customer_id == 958){
            $issuance_for = $partsTransactionDetails['issuance_for'] ?? '';
        }
        else{
            $issuance_for = null;
        }
        
        if($company_id == '2' || $company_id == '1'){
            $p_reference_number = $partsTransactionDetails['issuance_no'] ?? '';
        }
        else{
            $p_reference_number = $partsTransactionDetails['reference_number'] ?? '';
        }

        $cost = $this->partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'cost')['total'] ?? 0;

        $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'Released', '', $userID);
       
        if($customer_type == 'Internal'){
            $productDetails = $this->productModel->getProduct($customer_id);
            $is_service = $productDetails['is_service'] ?? 'No';
            $product_status = $productDetails['product_status'] ?? 'Draft';

            if($is_service == 'Yes'){
                $overallTotal = $this->partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'gasoline cost')['total'] ?? 0;

                $this->partsTransactionModel->createPartsTransactionProductExpense($customer_id, 'Issuance Slip', $parts_transaction_id, 0, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, $userID); 
            }
            else{
                $overallTotal = $this->partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'overall total')['total'] ?? 0;

                $this->partsTransactionModel->createPartsTransactionProductExpense($customer_id, 'Issuance Slip', $parts_transaction_id, $overallTotal, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, $userID); 
            }
        }
        else{
            $overallTotal = $this->partsTransactionModel->getPartsTransactionCartTotal($parts_transaction_id, 'overall total')['total'] ?? 0;
            $is_service = 'No';
            $product_status = 'Draft';
        }

        if($company_id == '2' || $company_id == '3'){         
            $this->partsTransactionModel->createPartsTransactionEntry($parts_transaction_id, $company_id, $p_reference_number, $cost, $overallTotal, $customer_type, $is_service, $product_status, $issuance_for, $userID);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsChecked() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'Checked', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

    
        $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'Cancelled', $cancellation_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        $draft_reason = htmlspecialchars($_POST['draft_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'Draft', $draft_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsApproved() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        $approval_remarks = htmlspecialchars($_POST['approval_remarks'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $partsTransactionDetails = $this->partsTransactionModel->getPartsTransaction($parts_transaction_id);
        $customer_type = $partsTransactionDetails['customer_type'] ?? null;
        $company_id = $partsTransactionDetails['company_id'] ?? null;
        $issuance_no = $partsTransactionDetails['issuance_no'] ?? null;

        if($customer_type == 'Internal'){

            $check_linked_job_order = $this->partsTransactionModel->check_linked_job_order($parts_transaction_id)['total'] ?? 0;

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

                $this->partsTransactionModel->updatePartsTransactionSlipReferenceNumber($parts_transaction_id, $reference_number, $userID);

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

                $this->partsTransactionModel->updatePartsTransactionSlipReferenceNumber($parts_transaction_id, $reference_number, $userID);

                if($company_id == '1'){
                    $this->systemSettingModel->updateSystemSettingValue(37, $reference_number, $userID);
                }
            }
        }
    
        $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'Validated', $approval_remarks, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function savePartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $part_transaction_cart_id = htmlspecialchars($_POST['part_transaction_cart_id'], ENT_QUOTES, 'UTF-8');
        $parts_id = $_POST['part_id'];
        $quantity = $_POST['quantity'];
        $discount = $_POST['discount'];
        $add_on = $_POST['add_on'];
        $discount_type = $_POST['discount_type'];
        $discount_total = $_POST['discount_total'];
        $part_item_subtotal = $_POST['part_item_subtotal'];
        $part_item_total = $_POST['part_item_total'];
        $remarks = $_POST['item_remarks'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $partsDetails = $this->partsModel->getParts($parts_id);
        $partQuantity = $partsDetails['quantity'] ?? 0;

        if($quantity > $partQuantity){
            echo json_encode(['success' => false, 'quantity' => true]);
            exit;
        }

        $checkPartsTransactionCartExist = $this->partsTransactionModel->checkPartsTransactionCartExist($part_transaction_cart_id);
        $total = $checkPartsTransactionCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsTransactionModel->updatePartsTransactionCart($part_transaction_cart_id, $quantity, $add_on, $discount, $discount_type, $discount_total, $part_item_subtotal, $part_item_total, $remarks, $userID);

            $partsTransactionCartDetails = $this->partsTransactionModel->getPartsTransactionCart($part_transaction_cart_id);
            $part_transaction_id = $partsTransactionCartDetails['part_transaction_id'];

            $this->partsTransactionModel->updatePartTransactionSummary($part_transaction_id);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function addPartsDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $document_name = htmlspecialchars($_POST['document_name'], ENT_QUOTES, 'UTF-8');
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $transactionDocumentFileName = $_FILES['transaction_document']['name'];
        $transactionDocumentFileSize = $_FILES['transaction_document']['size'];
        $transactionDocumentFileError = $_FILES['transaction_document']['error'];
        $transactionDocumentTempName = $_FILES['transaction_document']['tmp_name'];
        $transactionDocumentFileExtension = explode('.', $transactionDocumentFileName);
        $transactionDocumentActualFileExtension = strtolower(end($transactionDocumentFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(6);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(6);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($transactionDocumentActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($transactionDocumentTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the transaction document.']);
            exit;
        }
        
        if($transactionDocumentFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($transactionDocumentFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The transaction document exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $transactionDocumentActualFileExtension;

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $parts_transaction_id  . '/part_transaction/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $parts_transaction_id . '/part_transaction/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($transactionDocumentTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->partsTransactionModel->insertPartsTransactionDocument($parts_transaction_id, $document_name, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsTransaction
    # Description: 
    # Delete the parts transaction if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsTransactionID = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsTransactionExist = $this->partsTransactionModel->checkPartsTransactionExist($partsTransactionID);
        $total = $checkPartsTransactionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->partsTransactionModel->deletePartsTransaction($partsTransactionID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePartsTransactionCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_transaction_cart_id = htmlspecialchars($_POST['parts_transaction_cart_id'], ENT_QUOTES, 'UTF-8');
        $parts_transaction_id = htmlspecialchars($_POST['parts_transaction_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsTransactionModel->deletePartsTransactionCart($parts_transaction_cart_id);

        $this->partsTransactionModel->updatePartTransactionSummary($parts_transaction_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_transaction_job_order_id = htmlspecialchars($_POST['parts_transaction_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsTransactionModel->deletePartsTransactionJobOrder($parts_transaction_job_order_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    public function deleteAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_transaction_additional_job_order_id = htmlspecialchars($_POST['parts_transaction_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsTransactionModel->deletePartsTransactionAdditionalJobOrder($parts_transaction_additional_job_order_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePartsTransaction
    # Description: 
    # Delete the selected parts transactions if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePartsTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsTransactionIDs = $_POST['parts_transaction_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($partsTransactionIDs as $partsTransactionID){
            $this->partsTransactionModel->deletePartsTransaction($partsTransactionID);
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
    # Function: getPartsTransactionDetails
    # Description: 
    # Handles the retrieval of parts transaction details such as parts transaction name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsTransactionDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_transaction_id']) && !empty($_POST['parts_transaction_id'])) {
            $userID = $_SESSION['user_id'];
            $parts_transaction_id = $_POST['parts_transaction_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsTransactionDetails = $this->partsTransactionModel->getPartsTransaction($parts_transaction_id);

            $response = [
                'success' => true,
                'customer_type' => $partsTransactionDetails['customer_type'],
                'customer_id' => $partsTransactionDetails['customer_id'],
                'company_id' => $partsTransactionDetails['company_id'],
                'customer_ref_id' => $partsTransactionDetails['customer_ref_id'],
                'issuance_no' => $partsTransactionDetails['issuance_no'],
                'reference_number' => $partsTransactionDetails['reference_number'],
                'remarks' => $partsTransactionDetails['remarks'],
                'issuance_for' => $partsTransactionDetails['issuance_for'],
                'request_by' => $partsTransactionDetails['request_by'],
                'discount' => $partsTransactionDetails['discount'],
                'discount_type' => $partsTransactionDetails['discount_type'],
                'overall_total' => $partsTransactionDetails['overall_total'],
                'addOnDiscount' => number_format($partsTransactionDetails['overall_total'] ?? 0, 2) . ' PHP',
                'issuance_date' =>  $this->systemModel->checkDate('empty', $partsTransactionDetails['issuance_date'], '', 'm/d/Y', ''),
                'reference_date' =>  $this->systemModel->checkDate('empty', $partsTransactionDetails['reference_date'], '', 'm/d/Y', ''),
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsTransactionCartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['part_transaction_cart_id']) && !empty($_POST['part_transaction_cart_id'])) {
            $userID = $_SESSION['user_id'];
            $part_transaction_cart_id = $_POST['part_transaction_cart_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsTransactionCartDetails = $this->partsTransactionModel->getPartsTransactionCart($part_transaction_cart_id);

            $response = [
                'success' => true,
                'part_id' => $partsTransactionCartDetails['part_id'],
                'quantity' => $partsTransactionCartDetails['quantity'],
                'price' => $partsTransactionCartDetails['price'],
                'discount' => $partsTransactionCartDetails['discount'],
                'add_on' => $partsTransactionCartDetails['add_on'],
                'remarks' => $partsTransactionCartDetails['remarks'],
                'discount_type' => $partsTransactionCartDetails['discount_type']
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsTransactionCartTotal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_transaction_id']) && !empty($_POST['parts_transaction_id'])) {
            $userID = $_SESSION['user_id'];
            $part_transaction_id = $_POST['parts_transaction_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $subTotal = $this->partsTransactionModel->getPartsTransactionCartTotal($part_transaction_id, 'subtotal')['total'];
            $discountAmount = $this->partsTransactionModel->getPartsTransactionCartTotal($part_transaction_id, 'discount')['total'];
            $total = $this->partsTransactionModel->getPartsTransactionCartTotal($part_transaction_id, 'total')['total'];
            $addOn = $this->partsTransactionModel->getPartsTransactionCartTotal($part_transaction_id, 'add-on')['total'];
            $overallTotal = $this->partsTransactionModel->getPartsTransactionCartTotal($part_transaction_id, 'overall total')['total'];

            $response = [
                'success' => true,
                'subtotal_reference' => $total,
                'subTotal' => number_format($subTotal, 2) . ' PHP',
                'discountAmount' => number_format($discountAmount, 2) . ' PHP',
                'addOn' => number_format($addOn, 2) . ' PHP',
                'total' => number_format($total, 2) . ' PHP',
                'overallTotal' => number_format($overallTotal, 2) . ' PHP'
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
require_once '../model/parts-transaction-model.php';
require_once '../model/parts-model.php';
require_once '../model/product-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsTransactionController(new PartsTransactionModel(new DatabaseModel), new PartsModel(new DatabaseModel), new ProductModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>