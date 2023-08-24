<?php
session_start();

# -------------------------------------------------------------
#
# Function: CurrencyController
# Description: 
# The CurrencyController class handles currency related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CurrencyController {
    private $currencyModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CurrencyModel, UserModel and SecurityModel instances.
    # These instances are used for currency related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CurrencyModel $currencyModel     The CurrencyModel instance for currency related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CurrencyModel $currencyModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel) {
        $this->currencyModel = $currencyModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
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
                case 'save currency':
                    $this->saveCurrency();
                    break;
                case 'get currency details':
                    $this->getCurrencyDetails();
                    break;
                case 'delete currency':
                    $this->deleteCurrency();
                    break;
                case 'delete multiple currency':
                    $this->deleteMultipleCurrency();
                    break;
                case 'duplicate currency':
                    $this->duplicateCurrency();
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
    # Function: saveCurrency
    # Description: 
    # Updates the existing currency if it exists; otherwise, inserts a new currency.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCurrency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $currencyID = isset($_POST['currency_id']) ? htmlspecialchars($_POST['currency_id'], ENT_QUOTES, 'UTF-8') : null;
        $currencyName = htmlspecialchars($_POST['currency_name'], ENT_QUOTES, 'UTF-8');
        $symbol = htmlspecialchars($_POST['symbol'], ENT_QUOTES, 'UTF-8');
        $shorthand = htmlspecialchars($_POST['shorthand'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCurrencyExist = $this->currencyModel->checkCurrencyExist($currencyID);
        $total = $checkCurrencyExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->currencyModel->updateCurrency($currencyID, $currencyName, $symbol, $shorthand, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'currencyID' => $this->securityModel->encryptData($currencyID)]);
            exit;
        } 
        else {
            $currencyID = $this->currencyModel->insertCurrency($currencyName, $symbol, $shorthand, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'currencyID' => $this->securityModel->encryptData($currencyID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCurrency
    # Description: 
    # Delete the currency if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCurrency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $currencyID = htmlspecialchars($_POST['currency_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCurrencyExist = $this->currencyModel->checkCurrencyExist($currencyID);
        $total = $checkCurrencyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->currencyModel->deleteCurrency($currencyID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCurrency
    # Description: 
    # Delete the selected currencys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCurrency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $currencyIDs = $_POST['currency_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($currencyIDs as $currencyID){
            $this->currencyModel->deleteCurrency($currencyID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateCurrency
    # Description: 
    # Duplicates the currency if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateCurrency() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $currencyID = htmlspecialchars($_POST['currency_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCurrencyExist = $this->currencyModel->checkCurrencyExist($currencyID);
        $total = $checkCurrencyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $currencyID = $this->currencyModel->duplicateCurrency($currencyID, $userID);

        echo json_encode(['success' => true, 'currencyID' =>  $this->securityModel->encryptData($currencyID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCurrencyDetails
    # Description: 
    # Handles the retrieval of currency details such as currency name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCurrencyDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['currency_id']) && !empty($_POST['currency_id'])) {
            $userID = $_SESSION['user_id'];
            $currencyID = $_POST['currency_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $currencyDetails = $this->currencyModel->getCurrency($currencyID);

            $response = [
                'success' => true,
                'currencyName' => $currencyDetails['currency_name'],
                'symbol' => $currencyDetails['symbol'],
                'shorthand' => $currencyDetails['shorthand']
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
require_once '../model/system-setting-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CurrencyController(new CurrencyModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>