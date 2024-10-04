<?php
session_start();

# -------------------------------------------------------------
#
# Function: ChartOfAccountController
# Description: 
# The ChartOfAccountController class handles chart of account related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ChartOfAccountController {
    private $chartOfAccountModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ChartOfAccountModel, UserModel and SecurityModel instances.
    # These instances are used for chart of account related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ChartOfAccountModel $chartOfAccountModel     The ChartOfAccountModel instance for chart of account related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ChartOfAccountModel $chartOfAccountModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->chartOfAccountModel = $chartOfAccountModel;
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
                case 'save chart of account':
                    $this->saveChartOfAccount();
                    break;
                case 'get chart of account details':
                    $this->getChartOfAccountDetails();
                    break;
                case 'delete chart of account':
                    $this->deleteChartOfAccount();
                    break;
                case 'delete multiple chart of account':
                    $this->deleteMultipleChartOfAccount();
                    break;
                case 'duplicate chart of account':
                    $this->duplicateChartOfAccount();
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
    # Function: saveChartOfAccount
    # Description: 
    # Updates the existing chart of account if it exists; otherwise, inserts a new chart of account.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveChartOfAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $chartOfAccountID = isset($_POST['chart_of_account_id']) ? htmlspecialchars($_POST['chart_of_account_id'], ENT_QUOTES, 'UTF-8') : null;
        $code = htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8');
        $codeName = htmlspecialchars($_POST['code_name'], ENT_QUOTES, 'UTF-8');
        $accountType = $_POST['account_type'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkChartOfAccountExist = $this->chartOfAccountModel->checkChartOfAccountExist($chartOfAccountID);
        $total = $checkChartOfAccountExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->chartOfAccountModel->updateChartOfAccount($chartOfAccountID, $code, $codeName, $accountType, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'chartOfAccountID' => $this->securityModel->encryptData($chartOfAccountID)]);
            exit;
        } 
        else {
            $chartOfAccountID = $this->chartOfAccountModel->insertChartOfAccount($code, $codeName, $accountType, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'chartOfAccountID' => $this->securityModel->encryptData($chartOfAccountID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteChartOfAccount
    # Description: 
    # Delete the chart of account if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteChartOfAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $chartOfAccountID = htmlspecialchars($_POST['chart_of_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkChartOfAccountExist = $this->chartOfAccountModel->checkChartOfAccountExist($chartOfAccountID);
        $total = $checkChartOfAccountExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->chartOfAccountModel->deleteChartOfAccount($chartOfAccountID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleChartOfAccount
    # Description: 
    # Delete the selected chart of accounts if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleChartOfAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $chartOfAccountIDs = $_POST['chart_of_account_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($chartOfAccountIDs as $chartOfAccountID){
            $this->chartOfAccountModel->deleteChartOfAccount($chartOfAccountID);
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
    # Function: duplicateChartOfAccount
    # Description: 
    # Duplicates the chart of account if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateChartOfAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $chartOfAccountID = htmlspecialchars($_POST['chart_of_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkChartOfAccountExist = $this->chartOfAccountModel->checkChartOfAccountExist($chartOfAccountID);
        $total = $checkChartOfAccountExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $chartOfAccountID = $this->chartOfAccountModel->duplicateChartOfAccount($chartOfAccountID, $userID);

        echo json_encode(['success' => true, 'chartOfAccountID' =>  $this->securityModel->encryptData($chartOfAccountID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getChartOfAccountDetails
    # Description: 
    # Handles the retrieval of chart of account details such as chart of account name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getChartOfAccountDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['chart_of_account_id']) && !empty($_POST['chart_of_account_id'])) {
            $userID = $_SESSION['user_id'];
            $chartOfAccountID = $_POST['chart_of_account_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $chartOfAccountDetails = $this->chartOfAccountModel->getChartOfAccount($chartOfAccountID);

            $response = [
                'success' => true,
                'code' => $chartOfAccountDetails['code'],
                'name' => $chartOfAccountDetails['name'],
                'accountType' => $chartOfAccountDetails['account_type'],
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
require_once '../model/chart-of-account-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ChartOfAccountController(new ChartOfAccountModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>