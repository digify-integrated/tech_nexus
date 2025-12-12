<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsIncomingController
# Description: 
# The PartsIncomingController class handles parts incoming related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsIncomingController {
    private $partsIncomingModel;
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
    # The constructor initializes the object with the provided PartsIncomingModel, UserModel and SecurityModel instances.
    # These instances are used for parts incoming related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsIncomingModel $partsIncomingModel     The PartsIncomingModel instance for parts incoming related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsIncomingModel $partsIncomingModel, PartsModel $partsModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->partsIncomingModel = $partsIncomingModel;
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
    # This method checks the request method and dispatches the corresponding incoming based on the provided incoming parameter.
    # The incoming determines which action should be performed.
    #
    # Parameters:
    # - $incoming (string): The type of incoming.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save parts incoming':
                    $this->savePartsIncoming();
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
                case 'add parts incoming item':
                    $this->addPartsIncomingItem();
                    break;
                case 'get parts incoming cart details':
                    $this->getPartsIncomingCartDetails();
                    break;
                case 'get receive parts incoming cart details':
                    $this->getPartsRemainingIncomingCartDetails();
                    break;
                case 'delete parts incoming':
                    $this->deletePartsIncoming();
                    break;
                case 'delete part item':
                    $this->deletePartsIncomingCart();
                    break;
                case 'cancel part item':
                    $this->cancelPartsIncomingCart();
                    break;
                case 'add parts incoming document':
                    $this->addPartsDocument();
                    break;
                case 'delete multiple parts incoming':
                    $this->deleteMultiplePartsIncoming();
                    break;
                case 'tag incoming as released':
                    $this->tagAsReleased();
                    break;
                case 'tag incoming as on-process':
                    $this->tagAsOnProcess();
                    break;
                case 'tag incoming as posted':
                    $this->tagAsPosted();
                    break;
                case 'tag incoming as for approval':
                    $this->tagForApproval();
                    break;
                case 'tag incoming as cancelled':
                    $this->tagAsCancelled();
                    break;
                case 'tag incoming as draft':
                    $this->tagAsDraft();
                    break;
                case 'get parts incoming details':
                    $this->getPartsIncomingDetails();
                    break;
                case 'get parts incoming cart total':
                    $this->getPartsIncomingCartTotal();
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
    # Function: savePartsIncoming
    # Description: 
    # Updates the existing parts incoming if it exists; otherwise, inserts a new parts incoming.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsIncoming() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_incoming_id = isset($_POST['parts_incoming_id']) ? htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8') : null;
        $company_id = trim(htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8'));
        $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $request_by = htmlspecialchars($_POST['request_by'], ENT_QUOTES, 'UTF-8');
        $purchase_date = $this->systemModel->checkDate('empty', $_POST['purchase_date'], '', 'Y-m-d', '');
        $supplier_id = htmlspecialchars($_POST['supplier_id'], ENT_QUOTES, 'UTF-8');
        $customer_ref_id = htmlspecialchars($_POST['customer_ref_id'], ENT_QUOTES, 'UTF-8');
        $incoming_for = 'Parts';
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
        $delivery_date = null;
        $rr_no = null;
        $rr_date = null;

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsIncomingExist = $this->partsIncomingModel->checkPartsIncomingExist($parts_incoming_id);
        $total = $checkPartsIncomingExist['total'] ?? 0;
    
        if ($total > 0) {
            $reference_number = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');

            $this->partsIncomingModel->updatePartsIncoming($parts_incoming_id, $reference_number, $supplier_id, $rr_no, $rr_date, $delivery_date, $purchase_date, $request_by, $product_id, $customer_ref_id, $remarks, $incoming_for, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'partsIncomingID' => $this->securityModel->encryptData($parts_incoming_id)]);
            exit;
        } 
        else {
            if($company_id == '1'){
                $reference_number = ((int)($this->systemSettingModel->getSystemSetting(38)['value'] ?? 0)) + 1;
            }
            else if($company_id == '2'){
                $reference_number = ((int)($this->systemSettingModel->getSystemSetting(31)['value'] ?? 0)) + 1;
            }
            else if($company_id == '3'){
                $reference_number = ((int)($this->systemSettingModel->getSystemSetting(p_system_setting_id: 40)['value'] ?? 0)) + 1;
            }

            $parts_incoming_id = $this->partsIncomingModel->insertPartsIncoming($reference_number, $supplier_id, $rr_no, $rr_date, $delivery_date, $purchase_date, $company_id, $request_by, $product_id, $customer_ref_id, $remarks, $incoming_for, $userID);

            if($company_id == '1'){
                $this->systemSettingModel->updateSystemSettingValue(38, $reference_number, $userID);
            }
            else if($company_id == '2'){
                $this->systemSettingModel->updateSystemSettingValue(31, $reference_number, $userID);
            }
            else if($company_id == '3'){
                $this->systemSettingModel->updateSystemSettingValue(40, $reference_number, $userID);
            }
            
            echo json_encode(['success' => true, 'insertRecord' => true, 'partsIncomingID' => $this->securityModel->encryptData($parts_incoming_id)]);
            exit;
        }
    }
    public function addPartsIncomingItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        $part_ids = explode(',', $_POST['part_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($part_ids as $part_id) {
            if(!empty($part_id)){
                $this->partsIncomingModel->insertPartIncomingItem($parts_incoming_id, $part_id, $userID);
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
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $lines = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'lines')['total'];

        if($lines == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

        $partsIncomingDetails = $this->partsIncomingModel->getPartsIncoming($parts_incoming_id);
        $company_id = $partsIncomingDetails['company_id'] ?? '';
        $reference_number = $partsIncomingDetails['reference_number'] ?? '';

        $cost = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'total cost')['total'] ?? 0;

        if($company_id == '1'){
            $rr_no = (int)$this->systemSettingModel->getSystemSetting(39)['value'] + 1;
        }
        else if($company_id == '2'){
            $rr_no = (int)$this->systemSettingModel->getSystemSetting(35)['value'] + 1;
        }
        else{
            $rr_no = (int)$this->systemSettingModel->getSystemSetting(36)['value'] + 1;
        }

        $this->partsIncomingModel->updatePartsIncomingPosted($parts_incoming_id, $rr_no, $userID);

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
            $this->partsIncomingModel->createPartsIncomingEntry($parts_incoming_id, $company_id, $reference_number, $cost, $userID);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsOnProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

         $lines = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'lines')['total'];

        if($lines == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

        $lines = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'without total cost')['total'];

        if($lines > 0){
            echo json_encode(['success' => false, 'withoutCost' => true]);
            exit;
        }

        $this->partsIncomingModel->updatePartsIncomingStatus($parts_incoming_id, 'On-Process', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $lines = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'lines')['total'];

        if($lines == 0){
            echo json_encode(['success' => false, 'noItem' => true]);
            exit;
        }

        $lines = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'without total cost')['total'];

        if($lines > 0){
            echo json_encode(['success' => false, 'withoutCost' => true]);
            exit;
        }

        $this->partsIncomingModel->updatePartsIncomingStatus($parts_incoming_id, 'For Approval', '', $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        $invoice_number = htmlspecialchars($_POST['invoice_number'], ENT_QUOTES, 'UTF-8');
        $invoice_price = htmlspecialchars($_POST['invoice_price'], ENT_QUOTES, 'UTF-8');
        $invoice_date = $this->systemModel->checkDate('empty', $_POST['invoice_date'], '', 'Y-m-d', '');
        $delivery_date = $this->systemModel->checkDate('empty', $_POST['delivery_date'], '', 'Y-m-d', '');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $remaining = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'remaining')['total'];

        if($remaining > 0){
            echo json_encode(['success' => false, 'remaining' => true]);
            exit;
        }

        $cost = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'total cost')['total'] ?? 0;

        if(number_format($cost, 2, ".", "") != $invoice_price){
            echo json_encode(['success' => false, 'invoicePrice' => true]);
            exit;
        }
        

        $partsIncomingDetails = $this->partsIncomingModel->getPartsIncoming($parts_incoming_id);
        $product_id = $partsIncomingDetails['product_id'] ?? '';
        $company_id = $partsIncomingDetails['company_id'] ?? '';


        $getPartsIncomingCartByID = $this->partsIncomingModel->getPartsIncomingCartByID($parts_incoming_id);

        foreach ($getPartsIncomingCartByID as $row) {
            $part_id = $row['part_id'];
            $received_quantity = $row['received_quantity'];
            $total_cost = $row['total_cost'] / $received_quantity;

            $this->partsIncomingModel->updatePartsAverageCostAndSRP(
                $part_id,
                $company_id,
                $received_quantity,
                $total_cost,
                $userID
            );
        }

        if(!empty($product_id) && $product_id != '958'){
            $this->partsIncomingModel->generatePartsIssuanceMonitoring($parts_incoming_id, $userID);
        }

        $this->partsIncomingModel->updatePartsIncomingReleased($parts_incoming_id, 'Completed', $invoice_number, $invoice_price, $invoice_date, $delivery_date, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsIncomingModel->updatePartsIncomingStatus($parts_incoming_id, 'Cancelled', $cancellation_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        $set_to_draft_reason = htmlspecialchars($_POST['set_to_draft_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsIncomingModel->updatePartsIncomingStatus($parts_incoming_id, 'Draft', $set_to_draft_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function savePartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $part_incoming_cart_id = htmlspecialchars($_POST['part_incoming_cart_id'], ENT_QUOTES, 'UTF-8');
        $parts_id = $_POST['part_id'];
        $quantity = $_POST['quantity'];
        $total_cost = $_POST['total_cost'];
        $remarks = $_POST['cart_remarks'];
        $part_for = $_POST['part_for'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsIncomingCartExist = $this->partsIncomingModel->checkPartsIncomingCartExist($part_incoming_cart_id);
        $total = $checkPartsIncomingCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsIncomingModel->updatePartsIncomingCart($part_incoming_cart_id, $quantity, $total_cost, $remarks, $part_for, $userID);
            
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
        $part_incoming_cart_id = htmlspecialchars($_POST['part_incoming_cart_id'], ENT_QUOTES, 'UTF-8');
        $received_quantity = $_POST['received_quantity'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsIncomingCartExist = $this->partsIncomingModel->checkPartsIncomingCartExist($part_incoming_cart_id);
        $total = $checkPartsIncomingCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $partsIncomingCartDetails = $this->partsIncomingModel->getPartsIncomingCart($part_incoming_cart_id);
            $part_id = $partsIncomingCartDetails['part_id'];
            $remaining_quantity = $partsIncomingCartDetails['remaining_quantity'] ?? 0;
            $quantity = $partsIncomingCartDetails['quantity'] ?? 0;
            
            if($received_quantity > $remaining_quantity || $received_quantity > $quantity){
                echo json_encode(['success' => false, 'remainingQuantity' => true]);
                exit;
            }

            $this->partsIncomingModel->updatePartsReceivedIncomingCart($part_incoming_cart_id, $part_id, $received_quantity, $userID);
        } 
        
        echo json_encode(['success' => $received_quantity]);
        exit;
    }
    
    public function saveCancelReceivedPartsItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $part_incoming_cart_id = htmlspecialchars($_POST['part_incoming_cart_id'], ENT_QUOTES, 'UTF-8');
        $cancel_received_quantity = $_POST['cancel_received_quantity'];
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsIncomingCartExist = $this->partsIncomingModel->checkPartsIncomingCartExist($part_incoming_cart_id);
        $total = $checkPartsIncomingCartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsIncomingModel->updatePartsReceivedCancelIncomingCart($part_incoming_cart_id, $cancel_received_quantity, $userID);
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
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $incomingDocumentFileName = $_FILES['incoming_document']['name'];
        $incomingDocumentFileSize = $_FILES['incoming_document']['size'];
        $incomingDocumentFileError = $_FILES['incoming_document']['error'];
        $incomingDocumentTempName = $_FILES['incoming_document']['tmp_name'];
        $incomingDocumentFileExtension = explode('.', $incomingDocumentFileName);
        $incomingDocumentActualFileExtension = strtolower(end($incomingDocumentFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(6);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(6);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($incomingDocumentActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($incomingDocumentTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the incoming document.']);
            exit;
        }
        
        if($incomingDocumentFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($incomingDocumentFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The incoming document exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $incomingDocumentActualFileExtension;

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $parts_incoming_id  . '/part_incoming/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $parts_incoming_id . '/part_incoming/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($incomingDocumentTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->partsIncomingModel->insertPartsIncomingDocument($parts_incoming_id, $document_name, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsIncoming
    # Description: 
    # Delete the parts incoming if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsIncoming() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsIncomingID = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsIncomingExist = $this->partsIncomingModel->checkPartsIncomingExist($partsIncomingID);
        $total = $checkPartsIncomingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->partsIncomingModel->deletePartsIncoming($partsIncomingID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function cancelPartsIncomingCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_incoming_cart_id = htmlspecialchars($_POST['parts_incoming_cart_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsIncomingModel->cancelPartsIncomingCart($parts_incoming_cart_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePartsIncomingCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_incoming_cart_id = htmlspecialchars($_POST['parts_incoming_cart_id'], ENT_QUOTES, 'UTF-8');
        $parts_incoming_id = htmlspecialchars($_POST['parts_incoming_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsIncomingModel->deletePartsIncomingCart($parts_incoming_cart_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePartsIncoming
    # Description: 
    # Delete the selected parts incomings if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePartsIncoming() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsIncomingIDs = $_POST['parts_incoming_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($partsIncomingIDs as $partsIncomingID){
            $this->partsIncomingModel->deletePartsIncoming($partsIncomingID);
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
    # Function: getPartsIncomingDetails
    # Description: 
    # Handles the retrieval of parts incoming details such as parts incoming name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsIncomingCartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['part_incoming_cart_id']) && !empty($_POST['part_incoming_cart_id'])) {
            $userID = $_SESSION['user_id'];
            $part_incoming_cart_id = $_POST['part_incoming_cart_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsIncomingCartDetails = $this->partsIncomingModel->getPartsIncomingCart($part_incoming_cart_id);

            $response = [
                'success' => true,
                'part_id' => $partsIncomingCartDetails['part_id'],
                'quantity' => $partsIncomingCartDetails['quantity'],
                'total_cost' => $partsIncomingCartDetails['total_cost'],
                'part_for' => $partsIncomingCartDetails['part_for'],
                'remarks' => $partsIncomingCartDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsRemainingIncomingCartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['part_incoming_cart_id']) && !empty($_POST['part_incoming_cart_id'])) {
            $userID = $_SESSION['user_id'];
            $part_incoming_cart_id = $_POST['part_incoming_cart_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsIncomingCartDetails = $this->partsIncomingModel->getPartsIncomingCart($part_incoming_cart_id);

            $response = [
                'success' => true,
                'remaining_quantity' => $partsIncomingCartDetails['remaining_quantity']
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsIncomingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_incoming_id']) && !empty($_POST['parts_incoming_id'])) {
            $userID = $_SESSION['user_id'];
            $parts_incoming_id = $_POST['parts_incoming_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsIncomingDetails = $this->partsIncomingModel->getPartsIncoming($parts_incoming_id);

            $response = [
                'success' => true,
                'reference_number' => $partsIncomingDetails['reference_number'],
                'request_by' => $partsIncomingDetails['request_by'],
                'product_id' => $partsIncomingDetails['product_id'],
                'remarks' => $partsIncomingDetails['remarks'],
                'customer_ref_id' => $partsIncomingDetails['customer_ref_id'],
                'purchase_date' =>  $this->systemModel->checkDate('empty', $partsIncomingDetails['purchase_date'], '', 'm/d/Y', ''),
                'supplier_id' => $partsIncomingDetails['supplier_id'],
                'delivery_date' =>  $this->systemModel->checkDate('empty', $partsIncomingDetails['delivery_date'], '', 'm/d/Y', ''),
                'rr_no' => $partsIncomingDetails['rr_no'],
                'rr_date' =>  $this->systemModel->checkDate('empty', $partsIncomingDetails['rr_date'], '', 'm/d/Y', ''),
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPartsIncomingCartTotal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_incoming_id']) && !empty($_POST['parts_incoming_id'])) {
            $userID = $_SESSION['user_id'];
            $parts_incoming_id = $_POST['parts_incoming_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $cost = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'total cost')['total'];
            $lines = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'lines')['total'];
            $quantity = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'quantity')['total'];
            $received = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'received')['total'];
            $remaining = $this->partsIncomingModel->getPartsIncomingCartTotal($parts_incoming_id, 'remaining')['total'];

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
require_once '../model/parts-incoming-model.php';
require_once '../model/parts-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsIncomingController(new PartsIncomingModel(new DatabaseModel), new PartsModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>