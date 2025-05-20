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
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;
    private $systemSettingModel;

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
    public function __construct(PartsTransactionModel $partsTransactionModel, PartsModel $partsModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel) {
        $this->partsTransactionModel = $partsTransactionModel;
        $this->partsModel = $partsModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
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
                case 'save parts transaction':
                    $this->savePartsTransaction();
                    break;
                case 'save part item':
                    $this->savePartsItem();
                    break;
                case 'add parts transaction item':
                    $this->addPartsTransactionItem();
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
                case 'tag transaction as cancelled':
                    $this->tagAsCancelled();
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
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $partsTransactionID = $this->generateTransactionID();
        $this->partsTransactionModel->insertPartsTransaction($partsTransactionID, $userID);

        echo json_encode(value: ['success' => true, 'insertRecord' => true, 'partsTransactionID' => $this->securityModel->encryptData($partsTransactionID)]);
        exit;
    }

    private function generateTransactionID($prefix = 'PRT-') {
        // Ensure prefix is alphanumeric and not too long
        $prefix = preg_replace('/[^A-Za-z0-9]/', '', $prefix);
        $maxPrefixLength = 10;
        $prefix = substr($prefix, 0, $maxPrefixLength);

        // Timestamp format: YmdHis = 14 characters
        $timestamp = date('YmdHis');

        // Remaining space for random string: 80 - prefix - timestamp = 80 - (<=10) - 14
        $remainingLength = 20 - strlen($prefix) - strlen($timestamp);

        // Convert bytes to hex (2 characters per byte)
        $numBytes = floor($remainingLength / 2); // each byte = 2 hex chars
        $randomHex = strtoupper(bin2hex(random_bytes($numBytes)));

        // Combine all parts
        $transactionID = $prefix . $timestamp . $randomHex;

        return $transactionID;
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

        $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
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

         $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

    
       $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'For Approval', '', $userID);
        
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

        $quantityCheck = $this->partsTransactionModel->get_exceeded_part_quantity_count($parts_transaction_id)['total'] ?? 0;

        if($quantityCheck > 0){
            echo json_encode(['success' => false, 'cartQuantity' => true]);
            exit;
        }

       $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'Released', '', $userID);
        
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
    
        $this->partsTransactionModel->updatePartsTransactionStatus($parts_transaction_id, 'Approved', $approval_remarks, $userID);
        
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
            $this->partsTransactionModel->updatePartsTransactionCart($part_transaction_cart_id, $quantity, $add_on, $discount, $discount_type, $discount_total, $part_item_subtotal, $part_item_total, $userID);

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
                'discount' => $partsTransactionCartDetails['discount'],
                'add_on' => $partsTransactionCartDetails['add_on'],
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

            $response = [
                'success' => true,
                'subTotal' => number_format($subTotal, 2) . ' PHP',
                'discountAmount' => number_format($discountAmount, 2) . ' PHP',
                'addOn' => number_format($addOn, 2) . ' PHP',
                'total' => number_format($total, 2) . ' PHP'
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
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsTransactionController(new PartsTransactionModel(new DatabaseModel), new PartsModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>