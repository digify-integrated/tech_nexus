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
    private $securityModel;

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
    public function __construct(PartsTransactionModel $partsTransactionModel, PartsModel $partsModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->partsTransactionModel = $partsTransactionModel;
        $this->partsModel = $partsModel;
        $this->userModel = $userModel;
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
                case 'delete multiple parts transaction':
                    $this->deleteMultiplePartsTransaction();
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
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsTransactionController(new PartsTransactionModel(new DatabaseModel), new PartsModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>