<?php
session_start();

# -------------------------------------------------------------
#
# Function: StockTransferAdviceController
# Description: 
# The StockTransferAdviceController class handles stock transfer advice related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class StockTransferAdviceController {
    private $stockTransferAdviceModel;
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
    # The constructor initializes the object with the provided StockTransferAdviceModel, UserModel and SecurityModel instances.
    # These instances are used for stock transfer advice related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param StockTransferAdviceModel $stockTransferAdviceModel     The StockTransferAdviceModel instance for stock transfer advice related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(StockTransferAdviceModel $stockTransferAdviceModel, PartsModel $partsModel, ProductModel $productModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->stockTransferAdviceModel = $stockTransferAdviceModel;
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
                case 'save stock transfer advice':
                    $this->saveStockTransferAdvice();
                    break;
                case 'save part item':
                    $this->savePartsItem();
                    break;
                case 'add stock transfer advice item':
                    $this->addStockTransferAdviceItem();
                    break;
                case 'add job order':
                    $this->addJobOrder();
                    break;
                case 'add additional job order':
                    $this->addAdditionalJobOrder();
                    break;
                case 'get stock transfer advice details':
                    $this->getStockTransferAdviceDetails();
                    break;
                case 'get stock transfer advice cart details':
                    $this->getStockTransferAdviceCartDetails();
                    break;
                case 'get stock transfer advice cart total':
                    $this->getStockTransferAdviceCartTotalDetails();
                    break;
                case 'delete stock transfer advice':
                    $this->deleteStockTransferAdvice();
                    break;
                case 'delete part item':
                    $this->deleteStockTransferAdviceCart();
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
                case 'add stock transfer advice document':
                    $this->addPartsDocument();
                    break;
                case 'delete multiple stock transfer advice':
                    $this->deleteMultipleStockTransferAdvice();
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
    # Function: saveStockTransferAdvice
    # Description: 
    # Updates the existing stock transfer advice if it exists; otherwise, inserts a new stock transfer advice.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveStockTransferAdvice() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_id = isset($_POST['stock_transfer_advice_id']) ? htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8') : null;
        $transferred_from = htmlspecialchars($_POST['transferred_from'], ENT_QUOTES, 'UTF-8');
        $transferred_to = htmlspecialchars($_POST['transferred_to'], ENT_QUOTES, 'UTF-8');
        $sta_type = htmlspecialchars($_POST['sta_type'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8'); 

        $checkStockTransferAdviceExist = $this->stockTransferAdviceModel->checkStockTransferAdviceExist($stock_transfer_advice_id);
        $total = $checkStockTransferAdviceExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->stockTransferAdviceModel->updateStockTransferAdvice($stock_transfer_advice_id, $transferred_from, $transferred_to, $sta_type, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false, 'stockTransferAdviceID' => $this->securityModel->encryptData($stock_transfer_advice_id)]);
            exit;
        } 
        else {
            $reference_number = (int)$this->systemSettingModel->getSystemSetting(43)['value'] + 1;

            $stock_transfer_advice_id = $this->stockTransferAdviceModel->insertStockTransferAdvice($reference_number, $transferred_from, $transferred_to, $sta_type, $remarks, $userID);

            $this->systemSettingModel->updateSystemSettingValue(43, $reference_number, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true, 'stockTransferAdviceID' => $this->securityModel->encryptData($stock_transfer_advice_id)]);
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


    public function addStockTransferAdviceItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        $part_from_source = htmlspecialchars($_POST['part_from_source'], ENT_QUOTES, 'UTF-8');
        $part_ids = explode(',', $_POST['part_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($part_ids as $part_id) {
            if(!empty($part_id)){
                $this->stockTransferAdviceModel->insertStockTransferAdvicePartItem($stock_transfer_advice_id, $part_id, $part_from_source, $userID);
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
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        $generate_job_order = $_POST['generate_job_order'];
        $job_order_ids = explode(',', $_POST['job_order_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($job_order_ids as $job_order_id) {
            if(!empty($job_order_id)){
                $this->stockTransferAdviceModel->insertStockTransferAdviceJobOrder($stock_transfer_advice_id, $job_order_id, $generate_job_order, $userID);
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
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        $generate_job_order = $_POST['generate_job_order'];
        $additional_job_order_ids = explode(',', $_POST['additional_job_order_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($additional_job_order_ids as $additional_job_order_id) {
            if(!empty($additional_job_order_id)){
                $this->stockTransferAdviceModel->insertStockTransferAdviceAdditionalJobOrder($stock_transfer_advice_id, $additional_job_order_id, $generate_job_order, $userID);
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
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $partTransactionDetails = $this->stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
        $sta_type = $partTransactionDetails['sta_type'] ?? 'Draft';

        $quantity = 0;
        $price = 0;
        $quantityFrom = 0;
        $priceFrom = 0;
        $quantityTo = 0;
        $priceTo = 0;

        if($sta_type == 'Transfer'){
            $quantity = $this->stockTransferAdviceModel->getAssignedPartCount($stock_transfer_advice_id, p_part_from: 'To')['total'] ?? 0;
            $price = $this->stockTransferAdviceModel->getAssignedPartPrice($stock_transfer_advice_id, 'To')['total'] ?? 0;
        }

        if($sta_type == 'Swap'){
            $quantityFrom = $this->stockTransferAdviceModel->getAssignedPartCount($stock_transfer_advice_id, 'From')['total'] ?? 0;
            $priceFrom = $this->stockTransferAdviceModel->getAssignedPartPrice($stock_transfer_advice_id, 'From')['total'] ?? 0;
            $quantityTo = $this->stockTransferAdviceModel->getAssignedPartCount($stock_transfer_advice_id, 'To')['total'] ?? 0;
            $priceTo = $this->stockTransferAdviceModel->getAssignedPartPrice($stock_transfer_advice_id, 'To')['total'] ?? 0;
        }

        $jobOrderCount = $this->stockTransferAdviceModel->getJobOrderCount($stock_transfer_advice_id)['total'] ?? 0;
        $additionalJobOrderCount = $this->stockTransferAdviceModel->getAdditionalJobOrderCount($stock_transfer_advice_id)['total'] ?? 0;
        $jobOrderTotal = $jobOrderCount + $additionalJobOrderCount;

        if($quantity == 0 && $sta_type == 'Transfer'){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        if(($quantityFrom == 0 || $quantityTo == 0) && $sta_type == 'Swap'){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        if($price == 0 && $sta_type == 'Transfer'){
            echo json_encode(['success' => false, 'cartPrice' => true]);
            exit;
        }

        if(($priceFrom > 0 || $priceTo > 0) && $sta_type == 'Swap'){
            echo json_encode(['success' => false, 'cartPrice' => true]);
            exit;
        }

        if($jobOrderTotal == 0){
            echo json_encode(['success' => false, 'jobOrder' => true]);
            exit;
        }
    
        $this->stockTransferAdviceModel->updateStockTransferAdviceOnProcess($stock_transfer_advice_id, $userID);

        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $partTransactionDetails = $this->stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
        $number_of_items = $partTransactionDetails['number_of_items'] ?? 0;
        $customer_type = $partTransactionDetails['customer_type'] ?? '';
        $issuance_for = $partTransactionDetails['issuance_for'] ?? '';
        $customer_id = $partTransactionDetails['customer_id'] ?? '';

        if($number_of_items == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

         $quantityCheck = $this->stockTransferAdviceModel->get_exceeded_part_quantity_count($stock_transfer_advice_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->stockTransferAdviceModel->check_exceed_part_quantity($stock_transfer_advice_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }

        $cost = $this->stockTransferAdviceModel->getStockTransferAdviceCartTotal($stock_transfer_advice_id, 'cost')['total'] ?? 0;

        if($customer_type == 'Internal' && $customer_id == '958' && $issuance_for == 'Tools' && $cost < 5000){
            echo json_encode(['success' => false, 'tools' => true]);
            exit;
        }
    
        $this->stockTransferAdviceModel->updateStockTransferAdviceStatus($stock_transfer_advice_id, 'For Validation', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $check_exceed_part_quantity = $this->stockTransferAdviceModel->check_exceed_part_quantity($stock_transfer_advice_id)['total'] ?? 0;

        if($check_exceed_part_quantity > 0){
            echo json_encode(['success' => false, 'partQuantityExceed' => true]);
            exit;
        }

        $quantityCheck = $this->stockTransferAdviceModel->get_exceeded_part_quantity_count($stock_transfer_advice_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

        $stockTransferAdviceDetails = $this->stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
        $company_id = $stockTransferAdviceDetails['company_id'] ?? '';
        $customer_type = $stockTransferAdviceDetails['customer_type'] ?? '';
        $customer_id = $stockTransferAdviceDetails['customer_id'] ?? '';
        $remarks = $stockTransferAdviceDetails['remarks'] ?? '';
       
        if($customer_type == 'Internal' && $customer_id == 958){
            $issuance_for = $stockTransferAdviceDetails['issuance_for'] ?? '';
        }
        else{
            $issuance_for = null;
        }
        
        if($company_id == '2' || $company_id == '1'){
            $p_reference_number = $stockTransferAdviceDetails['issuance_no'] ?? '';
        }
        else{
            $p_reference_number = $stockTransferAdviceDetails['reference_number'] ?? '';
        }

        $cost = $this->stockTransferAdviceModel->getStockTransferAdviceCartTotal($stock_transfer_advice_id, 'cost')['total'] ?? 0;

        $this->stockTransferAdviceModel->updateStockTransferAdviceStatus($stock_transfer_advice_id, 'Released', '', $userID);
       
        if($customer_type == 'Internal'){
            $productDetails = $this->productModel->getProduct($customer_id);
            $is_service = $productDetails['is_service'] ?? 'No';
            $product_status = $productDetails['product_status'] ?? 'Draft';

            if($is_service == 'Yes'){
                $overallTotal = $this->stockTransferAdviceModel->getStockTransferAdviceCartTotal($stock_transfer_advice_id, 'gasoline cost')['total'] ?? 0;

                $this->stockTransferAdviceModel->createStockTransferAdviceProductExpense($customer_id, 'Issuance Slip', $stock_transfer_advice_id, 0, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, $userID); 
            }
            else{
                $overallTotal = $this->stockTransferAdviceModel->getStockTransferAdviceCartTotal($stock_transfer_advice_id, 'overall total')['total'] ?? 0;

                $this->stockTransferAdviceModel->createStockTransferAdviceProductExpense($customer_id, 'Issuance Slip', $stock_transfer_advice_id, $overallTotal, 'Parts & ACC', 'Issuance No.: ' . $p_reference_number . ' - '.  $remarks, $userID); 
            }
        }
        else{
            $overallTotal = $this->stockTransferAdviceModel->getStockTransferAdviceCartTotal($stock_transfer_advice_id, 'overall total')['total'] ?? 0;
            $is_service = 'No';
            $product_status = 'Draft';
        }

        if($company_id == '2' || $company_id == '3'){         
            $this->stockTransferAdviceModel->createStockTransferAdviceEntry($stock_transfer_advice_id, $company_id, $p_reference_number, $cost, $overallTotal, $customer_type, $is_service, $product_status, $issuance_for, $userID);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsChecked() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->stockTransferAdviceModel->updateStockTransferAdviceStatus($stock_transfer_advice_id, 'Checked', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->stockTransferAdviceModel->updateStockTransferAdviceStatusCancel($stock_transfer_advice_id, $cancellation_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        $draft_reason = htmlspecialchars($_POST['draft_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->stockTransferAdviceModel->updateStockTransferAdviceStatusDraft($stock_transfer_advice_id, $draft_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function savePartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_cart_id = htmlspecialchars($_POST['stock_transfer_advice_cart_id'], ENT_QUOTES, 'UTF-8');
        $parts_id = $_POST['part_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['part_price'];
        $remarks = $_POST['item_remarks'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkStockTransferAdviceCartExist = $this->stockTransferAdviceModel->checkStockTransferAdviceCartExist($stock_transfer_advice_cart_id);
        $total = $checkStockTransferAdviceCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->stockTransferAdviceModel->updateStockTransferAdviceCart($stock_transfer_advice_cart_id, $quantity, $price, $remarks, $userID);
            
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
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
        
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

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $stock_transfer_advice_id  . '/part_transaction/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $stock_transfer_advice_id . '/part_transaction/' . $fileNew;
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

        $this->stockTransferAdviceModel->insertStockTransferAdviceDocument($stock_transfer_advice_id, $document_name, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteStockTransferAdvice
    # Description: 
    # Delete the stock transfer advice if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteStockTransferAdvice() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stockTransferAdviceID = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkStockTransferAdviceExist = $this->stockTransferAdviceModel->checkStockTransferAdviceExist($stockTransferAdviceID);
        $total = $checkStockTransferAdviceExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->stockTransferAdviceModel->deleteStockTransferAdvice($stockTransferAdviceID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteStockTransferAdviceCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_cart_id = htmlspecialchars($_POST['stock_transfer_advice_cart_id'], ENT_QUOTES, 'UTF-8');
        $stock_transfer_advice_id = htmlspecialchars($_POST['stock_transfer_advice_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->stockTransferAdviceModel->deleteStockTransferAdviceCart($stock_transfer_advice_cart_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_job_order_id = htmlspecialchars($_POST['stock_transfer_advice_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->stockTransferAdviceModel->deleteStockTransferAdviceJobOrder($stock_transfer_advice_job_order_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    public function deleteAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stock_transfer_advice_additional_job_order_id = htmlspecialchars($_POST['stock_transfer_advice_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->stockTransferAdviceModel->deleteStockTransferAdviceAdditionalJobOrder($stock_transfer_advice_additional_job_order_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleStockTransferAdvice
    # Description: 
    # Delete the selected stock transfer advices if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleStockTransferAdvice() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $stockTransferAdviceIDs = $_POST['stock_transfer_advice_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($stockTransferAdviceIDs as $stockTransferAdviceID){
            $this->stockTransferAdviceModel->deleteStockTransferAdvice($stockTransferAdviceID);
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
    # Function: getStockTransferAdviceDetails
    # Description: 
    # Handles the retrieval of stock transfer advice details such as stock transfer advice name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getStockTransferAdviceDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['stock_transfer_advice_id']) && !empty($_POST['stock_transfer_advice_id'])) {
            $userID = $_SESSION['user_id'];
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $stockTransferAdviceDetails = $this->stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);

            $response = [
                'success' => true,
                'reference_no' => $stockTransferAdviceDetails['reference_no'],
                'transferred_from' => $stockTransferAdviceDetails['transferred_from'],
                'transferred_to' => $stockTransferAdviceDetails['transferred_to'],
                'sta_type' => $stockTransferAdviceDetails['sta_type'],
                'remarks' => $stockTransferAdviceDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getStockTransferAdviceCartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['stock_transfer_advice_cart_id']) && !empty($_POST['stock_transfer_advice_cart_id'])) {
            $userID = $_SESSION['user_id'];
            $stock_transfer_advice_cart_id = $_POST['stock_transfer_advice_cart_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $stockTransferAdviceCartDetails = $this->stockTransferAdviceModel->getStockTransferAdviceCart($stock_transfer_advice_cart_id);
            $part_id = $stockTransferAdviceCartDetails['part_id'];

            $partsDetails = $this->partsModel->getParts($part_id);
            $description = $partsDetails['description'] ?? '';


            $response = [
                'success' => true,
                'part_id' => $part_id,
                'part_name' => $description,
                'price' => $stockTransferAdviceCartDetails['price'] ?? 0,
                'quantity' => $stockTransferAdviceCartDetails['quantity'],
                'remarks' => $stockTransferAdviceCartDetails['remarks']
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getStockTransferAdviceCartTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['stock_transfer_advice_id']) && !empty($_POST['stock_transfer_advice_id'])) {
            $userID = $_SESSION['user_id'];
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $fromTotal = $this->stockTransferAdviceModel->getStockTransferAdviceCartTotal($stock_transfer_advice_id, 'From')['total'] ?? 0;
            $toTotal = $this->stockTransferAdviceModel->getStockTransferAdviceCartTotal($stock_transfer_advice_id, 'To')['total'] ?? 0;

            $response = [
                'success' => true,
                'total_summary_from' => number_format($fromTotal, 2),
                'total_summary_to' => number_format($toTotal, 2)
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
require_once '../model/stock-transfer-advice-model.php';
require_once '../model/parts-model.php';
require_once '../model/product-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new StockTransferAdviceController(new StockTransferAdviceModel(new DatabaseModel), new PartsModel(new DatabaseModel), new ProductModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>