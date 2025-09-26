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
    public function __construct(PartsReturnModel $partsReturnModel, PartsModel $partsModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->partsReturnModel = $partsReturnModel;
        $this->partsModel = $partsModel;
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
                case 'save part item':
                    $this->savePartsItem();
                    break;
                case 'save received part item':
                    $this->saveReceivedPartsItem();
                    break;
                case 'save cancel received part item':
                    $this->saveCancelReceivedPartsItem();
                    break;
                case 'add parts return item':
                    $this->addPartsReturnItem();
                    break;
                case 'get parts return cart details':
                    $this->getPartsReturnCartDetails();
                    break;
                case 'get receive parts return cart details':
                    $this->getPartsRemainingReturnCartDetails();
                    break;
                case 'delete parts return':
                    $this->deletePartsReturn();
                    break;
                case 'delete part item':
                    $this->deletePartsReturnCart();
                    break;
                case 'cancel part item':
                    $this->cancelPartsReturnCart();
                    break;
                case 'add parts return document':
                    $this->addPartsDocument();
                    break;
                case 'delete multiple parts return':
                    $this->deleteMultiplePartsReturn();
                    break;
                case 'tag return as released':
                    $this->tagAsReleased();
                    break;
                case 'tag return as on-process':
                    $this->tagAsOnProcess();
                    break;
                case 'tag return as posted':
                    $this->tagAsPosted();
                    break;
                case 'tag return as for approval':
                    $this->tagForApproval();
                    break;
                case 'tag return as cancelled':
                    $this->tagAsCancelled();
                    break;
                case 'tag return as draft':
                    $this->tagAsDraft();
                    break;
                case 'get parts return details':
                    $this->getPartsReturnDetails();
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
        $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $request_by = htmlspecialchars($_POST['request_by'], ENT_QUOTES, 'UTF-8');
        $purchase_date = $this->systemModel->checkDate('empty', $_POST['purchase_date'], '', 'Y-m-d', '');
        $supplier_id = htmlspecialchars($_POST['supplier_id'], ENT_QUOTES, 'UTF-8');
        $customer_ref_id = htmlspecialchars($_POST['customer_ref_id'], ENT_QUOTES, 'UTF-8');
        $delivery_date = null;
        $rr_no = null;
        $rr_date = null;

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsReturnExist = $this->partsReturnModel->checkPartsReturnExist($parts_return_id);
        $total = $checkPartsReturnExist['total'] ?? 0;
    
        if ($total > 0) {
            $reference_number = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');

            $this->partsReturnModel->updatePartsReturn($parts_return_id, $reference_number, $supplier_id, $rr_no, $rr_date, $delivery_date, $purchase_date, $request_by, $product_id, $customer_ref_id, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'partsReturnID' => $this->securityModel->encryptData($parts_return_id)]);
            exit;
        } 
        else {
            if($company_id == '1'){
                $reference_number = ((int)($this->systemSettingModel->getSystemSetting(38)['value'] ?? 0)) + 1;
            }
            else if($company_id == '2'){
                $reference_number = ((int)($this->systemSettingModel->getSystemSetting(31)['value'] ?? 0)) + 1;
            }
            else{
                $reference_number = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
            }

            $parts_return_id = $this->partsReturnModel->insertPartsReturn($reference_number, $supplier_id, $rr_no, $rr_date, $delivery_date, $purchase_date, $company_id, $request_by, $product_id, $customer_ref_id, $userID);

            if($company_id == '1'){
                $this->systemSettingModel->updateSystemSettingValue(38, $reference_number, $userID);
            }
            else if($company_id == '2'){
                $this->systemSettingModel->updateSystemSettingValue(31, $reference_number, $userID);
            }
            
            echo json_encode(['success' => true, 'insertRecord' => true, 'partsReturnID' => $this->securityModel->encryptData($parts_return_id)]);
            exit;
        }
    }
    public function addPartsReturnItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        $part_ids = explode(',', $_POST['part_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($part_ids as $part_id) {
            if(!empty($part_id)){
                $this->partsReturnModel->insertPartReturnItem($parts_return_id, $part_id, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsPosted() {
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

        $lines = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'lines')['total'];

        if($lines == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

        $partsReturnDetails = $this->partsReturnModel->getPartsReturn($parts_return_id);
        $company_id = $partsReturnDetails['company_id'] ?? '';
        $reference_number = $partsReturnDetails['reference_number'] ?? '';

        $cost = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'total cost')['total'] ?? 0;

        if($company_id == '1'){
            $rr_no = (int)$this->systemSettingModel->getSystemSetting(39)['value'] + 1;
        }
        else if($company_id == '2'){
            $rr_no = (int)$this->systemSettingModel->getSystemSetting(35)['value'] + 1;
        }
        else{
            $rr_no = (int)$this->systemSettingModel->getSystemSetting(36)['value'] + 1;
        }

        $this->partsReturnModel->updatePartsReturnPosted($parts_return_id, $rr_no, $userID);

        if($company_id == '1'){
            $this->systemSettingModel->updateSystemSettingValue(39, $rr_no, $userID);
        }
        else if($company_id == '2'){
            $this->systemSettingModel->updateSystemSettingValue(35, $rr_no, $userID);
        }
        else{
            $this->systemSettingModel->updateSystemSettingValue(36, $rr_no, $userID);
        }

        if($company_id == '3' || $company_id == '2'){
            $this->partsReturnModel->createPartsReturnEntry($parts_return_id, $company_id, $reference_number, $cost, $userID);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsOnProcess() {
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

         $lines = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'lines')['total'];

        if($lines == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

        $lines = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'without total cost')['total'];

        if($lines > 0){
            echo json_encode(['success' => false, 'withoutCost' => true]);
            exit;
        }

        $this->partsReturnModel->updatePartsReturnStatus($parts_return_id, 'On-Process', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagForApproval() {
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

        $lines = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'lines')['total'];

        if($lines == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

        $lines = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'without total cost')['total'];

        if($lines > 0){
            echo json_encode(['success' => false, 'withoutCost' => true]);
            exit;
        }

        $this->partsReturnModel->updatePartsReturnStatus($parts_return_id, 'For Approval', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        $invoice_number = htmlspecialchars($_POST['invoice_number'], ENT_QUOTES, 'UTF-8');
        $invoice_price = htmlspecialchars($_POST['invoice_price'], ENT_QUOTES, 'UTF-8');
        $invoice_date = $this->systemModel->checkDate('empty', $_POST['invoice_date'], '', 'Y-m-d', '');
        $delivery_date = $this->systemModel->checkDate('empty', $_POST['delivery_date'], '', 'Y-m-d', '');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $remaining = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'remaining')['total'];

        if($remaining > 0){
            echo json_encode(['success' => false, 'remaining' => true]);
            exit;
        }

        $cost = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'total cost')['total'] ?? 0;

        if(number_format($cost, 2, ".", "") != $invoice_price){
            echo json_encode(['success' => false, 'invoicePrice' => true]);
            exit;
        }

        
        $partsReturnDetails = $this->partsReturnModel->getPartsReturn($parts_return_id);
        $product_id = $partsReturnDetails['product_id'] ?? '';
        $company_id = $partsReturnDetails['company_id'] ?? '';

        $this->partsReturnModel->updatePartsReturnReleased($parts_return_id, 'Completed', $invoice_number, $invoice_price, $invoice_date, $delivery_date, $userID);

        $getPartsReturnCartByID = $this->partsReturnModel->getPartsReturnCartByID($parts_return_id);

        foreach ($getPartsReturnCartByID as $row) {
            $received_quantity = $row['received_quantity'];
            $part_id = $row['part_id'];
            $total_cost = $row['total_cost'] / $received_quantity;

            $this->partsReturnModel->updatePartsAverageCostAndSRP( $part_id, $company_id, $received_quantity, $total_cost, $userID);
        }

        if(!empty($product_id) && $product_id != '958'){
            $this->partsReturnModel->generatePartsIssuanceMonitoring($parts_return_id, $userID);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsReturnModel->updatePartsReturnStatus($parts_return_id, 'Cancelled', $cancellation_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        $set_to_draft_reason = htmlspecialchars($_POST['set_to_draft_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsReturnModel->updatePartsReturnStatus($parts_return_id, 'Draft', $set_to_draft_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function savePartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $part_return_cart_id = htmlspecialchars($_POST['part_return_cart_id'], ENT_QUOTES, 'UTF-8');
        $parts_id = $_POST['part_id'];
        $quantity = $_POST['quantity'];
        $total_cost = $_POST['total_cost'];
        $remarks = $_POST['remarks'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsReturnCartExist = $this->partsReturnModel->checkPartsReturnCartExist($part_return_cart_id);
        $total = $checkPartsReturnCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsReturnModel->updatePartsReturnCart($part_return_cart_id, $quantity, $total_cost, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function saveReceivedPartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $part_return_cart_id = htmlspecialchars($_POST['part_return_cart_id'], ENT_QUOTES, 'UTF-8');
        $received_quantity = $_POST['received_quantity'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsReturnCartExist = $this->partsReturnModel->checkPartsReturnCartExist($part_return_cart_id);
        $total = $checkPartsReturnCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $partsReturnCartDetails = $this->partsReturnModel->getPartsReturnCart($part_return_cart_id);
            $part_id = $partsReturnCartDetails['part_id'];
            $remaining_quantity = $partsReturnCartDetails['remaining_quantity'] ?? 0;
            $quantity = $partsReturnCartDetails['quantity'] ?? 0;
            
            if($received_quantity > $remaining_quantity || $received_quantity > $quantity){
                echo json_encode(['success' => false, 'remainingQuantity' => true]);
                exit;
            }

            $this->partsReturnModel->updatePartsReceivedReturnCart($part_return_cart_id, $part_id, $received_quantity, $userID);
        } 
        
        echo json_encode(['success' => $received_quantity]);
        exit;
    }
    
    public function saveCancelReceivedPartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $part_return_cart_id = htmlspecialchars($_POST['part_return_cart_id'], ENT_QUOTES, 'UTF-8');
        $cancel_received_quantity = $_POST['cancel_received_quantity'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsReturnCartExist = $this->partsReturnModel->checkPartsReturnCartExist($part_return_cart_id);
        $total = $checkPartsReturnCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsReturnModel->updatePartsReceivedCancelReturnCart($part_return_cart_id, $cancel_received_quantity, $userID);
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
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $returnDocumentFileName = $_FILES['return_document']['name'];
        $returnDocumentFileSize = $_FILES['return_document']['size'];
        $returnDocumentFileError = $_FILES['return_document']['error'];
        $returnDocumentTempName = $_FILES['return_document']['tmp_name'];
        $returnDocumentFileExtension = explode('.', $returnDocumentFileName);
        $returnDocumentActualFileExtension = strtolower(end($returnDocumentFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(6);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(6);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($returnDocumentActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($returnDocumentTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the return document.']);
            exit;
        }
        
        if($returnDocumentFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($returnDocumentFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The return document exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $returnDocumentActualFileExtension;

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $parts_return_id  . '/part_return/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $parts_return_id . '/part_return/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($returnDocumentTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->partsReturnModel->insertPartsReturnDocument($parts_return_id, $document_name, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsReturn
    # Description: 
    # Delete the parts return if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsReturn() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsReturnID = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsReturnExist = $this->partsReturnModel->checkPartsReturnExist($partsReturnID);
        $total = $checkPartsReturnExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->partsReturnModel->deletePartsReturn($partsReturnID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function cancelPartsReturnCart() {
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
    
        $this->partsReturnModel->cancelPartsReturnCart($parts_return_cart_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePartsReturnCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_return_cart_id = htmlspecialchars($_POST['parts_return_cart_id'], ENT_QUOTES, 'UTF-8');
        $parts_return_id = htmlspecialchars($_POST['parts_return_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsReturnModel->deletePartsReturnCart($parts_return_cart_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePartsReturn
    # Description: 
    # Delete the selected parts returns if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePartsReturn() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsReturnIDs = $_POST['parts_return_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($partsReturnIDs as $partsReturnID){
            $this->partsReturnModel->deletePartsReturn($partsReturnID);
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
    # Function: getPartsReturnDetails
    # Description: 
    # Handles the retrieval of parts return details such as parts return name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
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

            $response = [
                'success' => true,
                'part_id' => $partsReturnCartDetails['part_id'],
                'quantity' => $partsReturnCartDetails['quantity'],
                'total_cost' => $partsReturnCartDetails['total_cost'],
                'remarks' => $partsReturnCartDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsRemainingReturnCartDetails() {
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

            $response = [
                'success' => true,
                'remaining_quantity' => $partsReturnCartDetails['remaining_quantity']
            ];

            echo json_encode($response);
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
                'reference_number' => $partsReturnDetails['reference_number'],
                'request_by' => $partsReturnDetails['request_by'],
                'product_id' => $partsReturnDetails['product_id'],
                'customer_ref_id' => $partsReturnDetails['customer_ref_id'],
                'purchase_date' =>  $this->systemModel->checkDate('empty', $partsReturnDetails['purchase_date'], '', 'm/d/Y', ''),
                'supplier_id' => $partsReturnDetails['supplier_id'],
                'delivery_date' =>  $this->systemModel->checkDate('empty', $partsReturnDetails['delivery_date'], '', 'm/d/Y', ''),
                'rr_no' => $partsReturnDetails['rr_no'],
                'rr_date' =>  $this->systemModel->checkDate('empty', $partsReturnDetails['rr_date'], '', 'm/d/Y', ''),
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
            $received = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'received')['total'];
            $remaining = $this->partsReturnModel->getPartsReturnCartTotal($parts_return_id, 'remaining')['total'];

            $response = [
                'success' => true,
                'cost' => number_format($cost, 2) . ' PHP',
                'quantity' => number_format($quantity, 2),
                'received' => number_format($received, 2),
                'total_received' => $received,
                'remaining' => number_format($remaining, 2),
                'lines' => number_format($lines, 0, '', ','),
            ];

            echo json_encode($response);
            exit;
        }
    }
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/parts-return-model.php';
require_once '../model/parts-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsReturnController(new PartsReturnModel(new DatabaseModel), new PartsModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>