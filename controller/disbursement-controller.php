<?php
session_start();

# -------------------------------------------------------------
#
# Function: DisbursementController
# Description: 
# The DisbursementController class handles disbursement related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/disbursement-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/chart-of-account-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/company-model.php';
require_once '../model/system-model.php';

$controller = new DisbursementController(new DisbursementModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new ChartOfAccountModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();

class DisbursementController {
    private $disbursementModel;
    private $salesProposalModel;
    private $chartOfAccountModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemSettingModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided DisbursementModel, UserModel and SecurityModel instances.
    # These instances are used for disbursement related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DisbursementModel $disbursementModel     The DisbursementModel instance for disbursement related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DisbursementModel $disbursementModel, SalesProposalModel $salesProposalModel, ChartOfAccountModel $chartOfAccountModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->disbursementModel = $disbursementModel;
        $this->chartOfAccountModel = $chartOfAccountModel;
        $this->salesProposalModel = $salesProposalModel;
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
                case 'save disbursement':
                    $this->saveDisbursement();
                    break;
                case 'save particulars':
                    $this->saveParticulars();
                    break;
                case 'save check':
                    $this->saveCheck();
                    break;
                case 'save liquidation particulars':
                    $this->saveLiquidationParticulars();
                    break;
                case 'get disbursement details':
                    $this->getDisbursementDetails();
                    break;
                case 'get liquidation details':
                    $this->getLiquidationDetails();
                    break;
                case 'get disbursement particulars details':
                    $this->getDisbursementParticularsDetails();
                    break;
                case 'get disbursement check details':
                    $this->getDisbursementCheckDetails();
                    break;
                case 'get liquidation particulars details':
                    $this->getLiquidationParticularsDetails();
                    break;
                case 'get disbursement total':
                    $this->getDisbursementTotal();
                    break;
                case 'get disbursement particulars total':
                    $this->getDisbursementParticularsTotal();
                    break;
                case 'get disbursement check total':
                    $this->getDisbursementCheckTotal();
                    break;
                case 'delete disbursement particulars':
                    $this->deleteDisbursementParticulars();
                    break;
                case 'delete disbursement check':
                    $this->deleteDisbursementCheck();
                    break;
                case 'delete liquidation particulars':
                    $this->deleteLiquidationParticulars();
                    break;
                case 'delete disbursement':
                    $this->deleteDisbursement();
                    break;
                case 'delete multiple disbursement':
                    $this->deleteMultipleDisbursement();
                    break;
                case 'replenish disbursement':
                    $this->replenishDisbursement();
                    break;
                case 'replenish multiple disbursement':
                    $this->replenishMultipleDisbursement();
                    break;
                case 'post disbursement':
                    $this->tagDisbursementAsPosted();
                    break;
                case 'post multiple disbursement':
                    $this->postMultipleDisbursement();
                    break;
                case 'post liquidation particulars':
                    $this->tagLiquidationParticularsAsPosted();
                    break;
                case 'post liquidation particulars addon':
                    $this->tagLiquidationParticularsAddOnAsPosted();
                    break;
                case 'reverse liquidation particulars':
                    $this->tagLiquidationParticularsAsReversed();
                    break;
                case 'reverse liquidation particulars addon':
                    $this->tagLiquidationParticularsAddOnAsReversed();
                    break;
                case 'tag disbursement as cancelled':
                    $this->tagDisbursementAsCancel();
                    break;
                case 'tag disbursement check as cancelled':
                    $this->tagDisbursementCheckAsCancel();
                    break;
                case 'tag disbursement as reversed':
                    $this->tagDisbursementAsReversed();
                    break;
                case 'transmit disbursement check':
                    $this->tagDisbursementCheckAsTransmitted();
                    break;
                case 'transmit multiple disbursement check':
                    $this->tagMultipleDisbursementCheckAsTransmitted();
                    break;
                case 'outstanding disbursement check':
                    $this->tagDisbursementCheckAsOutstanding();
                    break;
                case 'outstanding multiple disbursement check':
                    $this->tagMultipleDisbursementCheckAsOutstanding();
                    break;
                case 'negotiated disbursement check':
                    $this->tagDisbursementCheckAsNegotiated();
                    break;
                case 'negotiated multiple disbursement check':
                    $this->tagMultipleDisbursementCheckAsNegotiated();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDisbursementAsPosted
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDisbursementAsPosted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
        $fund_source = $disbursementDetails['fund_source'];
        $transaction_type = $disbursementDetails['transaction_type'];
        $transaction_number = $disbursementDetails['transaction_number'];
        $transaction_date = $disbursementDetails['transaction_date'];

        $disbursementTotal = number_format($this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0, 2, '.', '');
        $disbursementCheckTotal = number_format($this->disbursementModel->getDisbursementCheckTotal($disbursementID)['total'] ?? 0, 2, '.', '');

        //if( $transaction_type == 'Disbursement' && $fund_source != 'Check' && $disbursementTotal > 5000){
        if( $transaction_type == 'Disbursement' && $fund_source != 'Check'){
            echo json_encode(['success' => false, 'disbursementGreater' => true]);
            exit;
        }
        
        if($disbursementTotal === 0){
            echo json_encode(['success' => false, 'disbursementZero' => true]);
            exit;
        }

        if($fund_source === 'Check' && $disbursementCheckTotal == 0){
            echo json_encode(['success' => false, 'checkZero' => true]);
            exit;
        }

        if($fund_source === 'Check' && $disbursementCheckTotal != $disbursementTotal){
            echo json_encode(['success' => false, 'checkNotEqual' => true]);
            exit;
        }

        if($transaction_type === 'Disbursement'){
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
            }
        }
        else{
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
            }
        }

        $this->disbursementModel->createDisbursementEntry($disbursementID, $transaction_number, $fund_source, 'posted', $transaction_date, $userID);
        $this->disbursementModel->createLiquidation($disbursementID, $transaction_date, $userID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagLiquidationParticularsAsPosted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $liquidation_particulars_id = htmlspecialchars($_POST['liquidation_particulars_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLiquidationParticularsExist = $this->disbursementModel->checkLiquidationParticularsExist($liquidation_particulars_id);
        $total = $checkLiquidationParticularsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $liquidationParticularsDetails = $this->disbursementModel->getLiquidationParticulars($liquidation_particulars_id);
        $liquidation_id = $liquidationParticularsDetails['liquidation_id'] ?? '';
        $particulars_amount = $liquidationParticularsDetails['particulars_amount'] ?? '';

        $liquidationDetails = $this->disbursementModel->getLiquidation($liquidation_id);
        $disbursement_particulars_id = $liquidationDetails['disbursement_particulars_id'] ?? '';
        $disbursement_id = $liquidationDetails['disbursement_id'] ?? '';

        $disbursementDetails = $this->disbursementModel->getDisbursement($disbursement_id);
        $company_id = $disbursementDetails['company_id'] ?? '';
        $transaction_number = $disbursementDetails['transaction_number'] ?? '';
                
        $disbursementParticulars = $this->disbursementModel->getDisbursementParticulars($disbursement_particulars_id);
        $chart_of_account_id = $disbursementParticulars['chart_of_account_id'] ?? '';

        $this->disbursementModel->createLiquidationParticularsEntry($liquidation_particulars_id, $transaction_number, $company_id, $chart_of_account_id, 'posted', $userID);

        $this->disbursementModel->updateLiquidationBalance($liquidation_id, $particulars_amount, $userID);

        $this->disbursementModel->updateLiquidationParticularsStatus($liquidation_particulars_id, 'Posted', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagLiquidationParticularsAddOnAsPosted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $liquidation_particulars_id = htmlspecialchars($_POST['liquidation_particulars_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLiquidationParticularsExist = $this->disbursementModel->checkLiquidationParticularsExist($liquidation_particulars_id);
        $total = $checkLiquidationParticularsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $liquidationParticularsDetails = $this->disbursementModel->getLiquidationParticulars($liquidation_particulars_id);
        $liquidation_id = $liquidationParticularsDetails['liquidation_id'] ?? '';
        $particulars_amount = $liquidationParticularsDetails['particulars_amount'] ?? '';

        $this->disbursementModel->updateLiquidationBalance($liquidation_id, $particulars_amount, $userID);

        $this->disbursementModel->updateLiquidationParticularsStatus($liquidation_particulars_id, 'Posted', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function replenishDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $replenishmentBatch = 'RPLNSH-';
    
        $uniqueId = time() . '-' . rand(1000, 9999);
        $batchID = $replenishmentBatch . $uniqueId;
    
        $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Replenished', $batchID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function replenishMultipleDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementIDs = $_POST['disbursement_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($disbursementIDs as $disbursementID){
            $replenishmentBatch = 'RPLNSH-';
    
            $uniqueId = time() . '-' . rand(1000, 9999);
            $batchID = $replenishmentBatch . $uniqueId;
        
            $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Replenished', $batchID, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function postMultipleDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementIDs = $_POST['disbursement_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($disbursementIDs as $disbursementID) {
            $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
            $fund_source = $disbursementDetails['fund_source'];
            $transaction_type = $disbursementDetails['transaction_type'];
            $transaction_number = $disbursementDetails['transaction_number'];
            $transaction_date = $disbursementDetails['transaction_date'];
        
            $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
            $disbursementCheckTotal = $this->disbursementModel->getDisbursementCheckTotal($disbursementID)['total'] ?? 0;
        
            if ($disbursementTotal === 0) {
                continue;
            }
        
            if ($fund_source === 'Check' && $disbursementCheckTotal == 0) {
                continue;
            }
        
            if ($fund_source === 'Check' && $disbursementCheckTotal != $disbursementTotal) {
                continue; 
            }
        
            if ($transaction_type === 'Disbursement') {
                if ($fund_source == 'Petty Cash') {
                    $systemSettingID = 20;
                    $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
                    $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                    $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
                } else if ($fund_source == 'Revolving Fund') {
                    $systemSettingID = 21;
                    $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
                    $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                    $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
                } else {
                    $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                }
            } else {
                if ($fund_source == 'Petty Cash') {
                    $systemSettingID = 20;
                    $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
                    $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                    $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
                } else if ($fund_source == 'Revolving Fund') {
                    $systemSettingID = 21;
                    $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
                    $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                    $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
                } else {
                    $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Posted', '', $userID);
                }
            }
        
            $this->disbursementModel->createDisbursementEntry($disbursementID, $transaction_number, $fund_source, 'posted', $transaction_date, $userID);
            $this->disbursementModel->createLiquidation($disbursementID, $transaction_date, $userID, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDisbursementAsCancel
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDisbursementAsCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = $_POST['cancellation_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
        $fund_source = $disbursementDetails['fund_source'];

        $disbursementNegotiatedCheckTotal = $this->disbursementModel->getDisbursementNegotiatedCheckTotal($disbursementID)['total'] ?? 0;
        
        if($disbursementNegotiatedCheckTotal > 0 && $fund_source == 'Check'){
            echo json_encode(['success' => false, 'hasNegotiatedCheck' => true]);
            exit;
        }
    
        $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Cancelled', $cancellationReason, $userID);
        $this->disbursementModel->cancelAllDisbursementCheck($disbursementID,$cancellationReason, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagDisbursementCheckAsCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_id = htmlspecialchars($_POST['disbursement_check_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = $_POST['check_cancellation_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->updateDisbursementCheckStatus($disbursement_check_id, 'Cancelled', $cancellationReason, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagDisbursementAsReversed
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagDisbursementAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars( $_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
        $reversalRemarks = $_POST['reversal_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
        $fund_source = $disbursementDetails['fund_source'];
        $transaction_type = $disbursementDetails['transaction_type'];
        $transaction_number = $disbursementDetails['transaction_number'];

        $disbursementNegotiatedCheckTotal = $this->disbursementModel->getDisbursementNegotiatedCheckTotal($disbursementID)['total'] ?? 0;
        
        if($disbursementNegotiatedCheckTotal > 0 && $fund_source == 'Check'){
            echo json_encode(['success' => false, 'hasNegotiatedCheck' => true]);
            exit;
        }

        if ($transaction_type === 'Disbursement') {
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
            }    
        }
        else{
            if($fund_source == 'Petty Cash'){
                $systemSettingID = 20;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else if($fund_source == 'Revolving Fund'){
                $systemSettingID = 21;
                $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;
    
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $disbursementTotal;
            
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            else{
                $this->disbursementModel->updateDisbursementStatus($disbursementID, 'Reversed', $reversalRemarks, $userID);
            }    
        }

        $this->disbursementModel->createDisbursementEntry($disbursementID, $transaction_number, $fund_source, 'reversed', date('Y-m-d'), $userID);
        
        $this->disbursementModel->cancelAllDisbursementCheck($disbursementID,$reversalRemarks, $userID);
                
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagDisbursementCheckAsTransmitted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_id = htmlspecialchars( $_POST['disbursement_check_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->updateDisbursementCheckStatus($disbursement_check_id, 'Transmitted', '', $userID);
                
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagMultipleDisbursementCheckAsTransmitted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_ids = $_POST['disbursement_check_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($disbursement_check_ids as $disbursement_check_id) {
            $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
            $total = $checkDisbursementExist['total'] ?? 0;

            if($total > 0){
                $this->disbursementModel->updateDisbursementCheckStatus($disbursement_check_id, 'Transmitted', '', $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagDisbursementCheckAsOutstanding() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_id = htmlspecialchars( $_POST['disbursement_check_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->updateDisbursementCheckStatus($disbursement_check_id, 'Outstanding', '', $userID);
                
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagMultipleDisbursementCheckAsOutstanding() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_ids = $_POST['disbursement_check_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($disbursement_check_ids as $disbursement_check_id) {
            $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
            $total = $checkDisbursementExist['total'] ?? 0;

            if($total > 0){
                $this->disbursementModel->updateDisbursementCheckStatus($disbursement_check_id, 'Outstanding', '', $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagDisbursementCheckAsNegotiated() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_id = htmlspecialchars( $_POST['disbursement_check_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->updateDisbursementCheckStatus($disbursement_check_id, 'Negotiated', '', $userID);
                
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagMultipleDisbursementCheckAsNegotiated() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_ids = $_POST['disbursement_check_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($disbursement_check_ids as $disbursement_check_id) {
            $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
            $total = $checkDisbursementExist['total'] ?? 0;

            if($total > 0){
                $this->disbursementModel->updateDisbursementCheckStatus($disbursement_check_id, 'Negotiated', '', $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagLiquidationParticularsAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $liquidation_particulars_id = htmlspecialchars($_POST['liquidation_particulars_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLiquidationParticularsExist = $this->disbursementModel->checkLiquidationParticularsExist($liquidation_particulars_id);
        $total = $checkLiquidationParticularsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $liquidationParticularsDetails = $this->disbursementModel->getLiquidationParticulars($liquidation_particulars_id);
        $liquidation_id = $liquidationParticularsDetails['liquidation_id'] ?? '';
        $particulars_amount = $liquidationParticularsDetails['particulars_amount'] ?? '';

        $liquidationDetails = $this->disbursementModel->getLiquidation($liquidation_id);
        $disbursement_particulars_id = $liquidationDetails['disbursement_particulars_id'] ?? '';
        $disbursement_id = $liquidationDetails['disbursement_id'] ?? '';

        $disbursementDetails = $this->disbursementModel->getDisbursement($disbursement_id);
        $company_id = $disbursementDetails['company_id'] ?? '';
        $transaction_number = $disbursementDetails['transaction_number'] ?? '';
                
        $disbursementParticulars = $this->disbursementModel->getDisbursementParticulars($disbursement_particulars_id);
        $chart_of_account_id = $disbursementParticulars['chart_of_account_id'] ?? '';

        $this->disbursementModel->createLiquidationParticularsEntry($liquidation_particulars_id, $transaction_number, $company_id, $chart_of_account_id, 'reversed', $userID);

        $this->disbursementModel->updateLiquidationBalance($liquidation_id, '-' . $particulars_amount, $userID);

        $this->disbursementModel->updateLiquidationParticularsStatus($liquidation_particulars_id, 'Reversed', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagLiquidationParticularsAddOnAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $liquidation_particulars_id = htmlspecialchars($_POST['liquidation_particulars_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLiquidationParticularsExist = $this->disbursementModel->checkLiquidationParticularsExist($liquidation_particulars_id);
        $total = $checkLiquidationParticularsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $liquidationParticularsDetails = $this->disbursementModel->getLiquidationParticulars($liquidation_particulars_id);
        $liquidation_id = $liquidationParticularsDetails['liquidation_id'] ?? '';
        $particulars_amount = $liquidationParticularsDetails['particulars_amount'] ?? '';

        $this->disbursementModel->updateLiquidationBalance($liquidation_id, '-' . $particulars_amount, $userID);

        $this->disbursementModel->updateLiquidationParticularsStatus($liquidation_particulars_id, 'Reversed', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveDisbursement
    # Description: 
    # Updates the existing disbursement if it exists; otherwise, inserts a new disbursement.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = isset($_POST['disbursement_id']) ? htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8') : null;
        $payable_type = $_POST['payable_type'];
        $transaction_type = $_POST['transaction_type'];
        $fund_source = $_POST['fund_source'];
        $particulars = $_POST['particulars'];
        $department_id = $_POST['department_id'];
        $company_id = $_POST['company_id'];
        $transaction_date = $this->systemModel->checkDate('empty', $_POST['transaction_date'], '', 'Y-m-d', '');

        if($payable_type === 'Customer'){
            $customer_id = $_POST['customer_id'];
        }
        else{
            $customer_id = $_POST['misc_id'];
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;
    
        if ($total > 0) {
            $transaction_number = $_POST['transaction_number'];

            $this->disbursementModel->updateDisbursement($disbursementID, $payable_type, $customer_id, $department_id, $company_id, $transaction_number, $transaction_type, $fund_source, $particulars, $transaction_date, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'disbursementID' => $this->securityModel->encryptData($disbursementID)]);
            exit;
        } 
        else {

            if($fund_source == 'Check'){
                $transaction_number = $this->systemSettingModel->getSystemSetting(24)['value'] + 1;
            }
            else if($fund_source == 'Revolving Fund'){
                $transaction_number = 'R-'.$this->systemSettingModel->getSystemSetting(29)['value'] + 1;
            }
            else{
                if($transaction_type == 'Disbursement'){
                    $transaction_number = $this->systemSettingModel->getSystemSetting(19)['value'] + 1;
                }
                else{
                    $transaction_number = $this->systemSettingModel->getSystemSetting(27)['value'] + 1;
                }
            }
            

            $disbursementID = $this->disbursementModel->insertDisbursement($payable_type, $customer_id, $department_id, $company_id, $transaction_number, $transaction_type, $fund_source, $particulars, $transaction_date, $userID);

            if($fund_source === 'Check'){
                $this->systemSettingModel->updateSystemSettingValue(24, $transaction_number, $userID);
            }
            else if($fund_source === 'Revolving Fund'){
                $this->systemSettingModel->updateSystemSettingValue(29, $transaction_number, $userID);
            }
            else{
                
                if($transaction_type === 'Disbursement'){
                    $this->systemSettingModel->updateSystemSettingValue(19, $transaction_number, $userID);
                }
                else{
                    $this->systemSettingModel->updateSystemSettingValue(27, $transaction_number, $userID);
                }
            }

            echo json_encode(['success' => true, 'insertRecord' => true, 'disbursementID' => $this->securityModel->encryptData($disbursementID)]);
            exit;
        }
    }
    
    public function saveParticulars() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_particulars_id = isset($_POST['disbursement_particulars_id']) ? htmlspecialchars($_POST['disbursement_particulars_id'], ENT_QUOTES, 'UTF-8') : null;
        $disbursement_id = $_POST['disbursement_id'];
        $chart_of_account_id = $_POST['chart_of_account_id'];
        $particulars_amount = $_POST['particulars_amount'];
        $company_id = $_POST['particulars_company_id'];
        $remarks = $_POST['remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementParticularsExist($disbursement_particulars_id);
        $total = $checkDisbursementExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->disbursementModel->updateDisbursementParticulars($disbursement_particulars_id, $disbursement_id, $chart_of_account_id, $company_id, $remarks, $particulars_amount, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->disbursementModel->insertDisbursementParticulars($disbursement_id, $chart_of_account_id, $company_id, $remarks, $particulars_amount, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    
    public function saveCheck() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_id = isset($_POST['disbursement_check_id']) ? htmlspecialchars($_POST['disbursement_check_id'], ENT_QUOTES, 'UTF-8') : null;
        $disbursement_id = $_POST['disbursement_id'];
        $bank_branch = $_POST['bank_branch'];
        $check_amount = $_POST['check_amount'];
        $check_name = $_POST['check_name'];
        $check_date = $this->systemModel->checkDate('empty', $_POST['check_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
        $total = $checkDisbursementExist['total'] ?? 0;
    
        if ($total > 0) {
            $disbursementDetails = $this->disbursementModel->getDisbursementCheck($disbursement_check_id);
            $check_number = $disbursementDetails['check_number'] ?? null;

            $this->disbursementModel->updateDisbursementCheck($disbursement_check_id, $disbursement_id, $bank_branch, $check_name, $check_number, $check_date, $check_amount, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $check_number = $this->systemSettingModel->getSystemSetting(25)['value'] + 1;

            $this->disbursementModel->insertDisbursementCheck($disbursement_id, $bank_branch, $check_name, $check_number, $check_date, $check_amount, $userID);

            $this->systemSettingModel->updateSystemSettingValue(25, $check_number, $userID);
            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function saveLiquidationParticulars() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $liquidation_particulars_id = isset($_POST['liquidation_particulars_id']) ? htmlspecialchars($_POST['liquidation_particulars_id'], ENT_QUOTES, 'UTF-8') : null;
        $liquidation_id = $_POST['liquidation_id'];
        $chart_of_account_id = $_POST['chart_of_account_id'];
        $particulars_amount = $_POST['particulars_amount'];
        $reference_type = $_POST['reference_type'];
        $reference_number = $_POST['reference_number'];
        $remarks = $_POST['remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkLiquidationParticularsExist($liquidation_particulars_id);
        $total = $checkDisbursementExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->disbursementModel->updateLiquidationParticulars($liquidation_particulars_id, $liquidation_id, $chart_of_account_id, $particulars_amount, $reference_type, $reference_number, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->disbursementModel->insertLiquidationParticulars($liquidation_id, $chart_of_account_id, $particulars_amount, $reference_type, $reference_number, $remarks, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDisbursement
    # Description: 
    # Delete the disbursement if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementID = htmlspecialchars($_POST['disbursement_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementExist = $this->disbursementModel->checkDisbursementExist($disbursementID);
        $total = $checkDisbursementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->deleteDisbursement($disbursementID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteDisbursementParticulars() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_particulars_id = htmlspecialchars($_POST['disbursement_particulars_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementParticularsExist = $this->disbursementModel->checkDisbursementParticularsExist($disbursement_particulars_id);
        $total = $checkDisbursementParticularsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->deleteDisbursementParticulars($disbursement_particulars_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteDisbursementCheck() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursement_check_id = htmlspecialchars($_POST['disbursement_check_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementCheckExist = $this->disbursementModel->checkDisbursementCheckExist($disbursement_check_id);
        $total = $checkDisbursementCheckExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->disbursementModel->deleteDisbursementCheck($disbursement_check_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteLiquidationParticulars() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $liquidation_particulars_id = htmlspecialchars($_POST['liquidation_particulars_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDisbursementParticularsExist = $this->disbursementModel->checkLiquidationParticularsExist($liquidation_particulars_id);
        $total = $checkDisbursementParticularsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $liquidationParticularsDetails = $this->disbursementModel->getLiquidationParticulars($liquidation_particulars_id);
        $liquidation_id = $liquidationParticularsDetails['liquidation_id'];
        $particulars_amount = $liquidationParticularsDetails['particulars_amount'];
        
        $this->disbursementModel->deleteLiquidationBalance($liquidation_id, $particulars_amount, $userID);

        $this->disbursementModel->deleteLiquidationParticulars($liquidation_particulars_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDisbursement
    # Description: 
    # Delete the selected disbursements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDisbursement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $disbursementIDs = $_POST['disbursement_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($disbursementIDs as $disbursementID){
            $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);
            $paymentAmount = $disbursementDetails['payment_amount'];
            $companyID = $disbursementDetails['company_id'];

            if($companyID == 1){
                $systemSettingID = 11;
            }
            else if($companyID == 2){
                $systemSettingID = 12;
            }
            else{
                $systemSettingID = 13;
            }
    
            $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $paymentAmount;
    
            $this->disbursementModel->deleteDisbursement($disbursementID);
                        
            $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
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
    # Function: getDisbursementDetails
    # Description: 
    # Handles the retrieval of disbursement details such as disbursement name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDisbursementDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['disbursement_id']) && !empty($_POST['disbursement_id'])) {
            $userID = $_SESSION['user_id'];
            $disbursementID = $_POST['disbursement_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);

            $response = [
                'success' => true,
                'payableType' => $disbursementDetails['payable_type'],
                'transactionNumber' => $disbursementDetails['transaction_number'],
                'particulars' => $disbursementDetails['particulars'],
                'transactionType' => $disbursementDetails['transaction_type'],
                'customerID' => $disbursementDetails['customer_id'],
                'departmentID' => $disbursementDetails['department_id'],
                'companyID' => $disbursementDetails['company_id'],
                'fundSource' => $disbursementDetails['fund_source'],
                'transactionDate' =>  $this->systemModel->checkDate('empty', $disbursementDetails['transaction_date'], '', 'm/d/Y', '')
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getLiquidationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['liquidation_id']) && !empty($_POST['liquidation_id'])) {
            $userID = $_SESSION['user_id'];
            $liquidation_id = $_POST['liquidation_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $liquidationDetails = $this->disbursementModel->getLiquidation($liquidation_id);
            $disbursementID = $liquidationDetails['disbursement_id'];
            $disbursement_particulars_id = $liquidationDetails['disbursement_particulars_id'];

            $disbursementParticularsDetails = $this->disbursementModel->getDisbursementParticulars($disbursement_particulars_id);
            $chart_of_account_id = $disbursementParticularsDetails['chart_of_account_id'];

            $chartOfAccountDetails = $this->chartOfAccountModel->getChartOfAccount($chart_of_account_id);
            $chartOfAccountName = $chartOfAccountDetails['name'] ?? null;

            $disbursementDetails = $this->disbursementModel->getDisbursement($disbursementID);

            $response = [
                'success' => true,
                'remainingBalance' => $liquidationDetails['remaining_balance'],
                'payableType' => $disbursementDetails['payable_type'],
                'transactionNumber' => $disbursementDetails['transaction_number'],
                'particulars' => $disbursementDetails['particulars'],
                'transactionType' => $disbursementDetails['transaction_type'],
                'customerID' => $disbursementDetails['customer_id'],
                'departmentID' => $disbursementDetails['department_id'],
                'companyID' => $disbursementDetails['company_id'],
                'fundSource' => $disbursementDetails['fund_source']
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getDisbursementParticularsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['disbursement_particulars_id']) && !empty($_POST['disbursement_particulars_id'])) {
            $userID = $_SESSION['user_id'];
            $disbursement_particulars_id = $_POST['disbursement_particulars_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $disbursementDetails = $this->disbursementModel->getDisbursementParticulars($disbursement_particulars_id);

            $response = [
                'success' => true,
                'chartOfAccountID' => $disbursementDetails['chart_of_account_id'],
                'remarks' => $disbursementDetails['remarks'],
                'particularsAmount' => $disbursementDetails['particulars_amount']
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getDisbursementCheckDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['disbursement_check_id']) && !empty($_POST['disbursement_check_id'])) {
            $userID = $_SESSION['user_id'];
            $disbursement_check_id = $_POST['disbursement_check_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $disbursementDetails = $this->disbursementModel->getDisbursementCheck($disbursement_check_id);

            $response = [
                'success' => true,
                'bankBranch' => $disbursementDetails['bank_branch'],
                'checkNumber' => $disbursementDetails['check_number'],
                'checkAmount' => $disbursementDetails['check_amount'],
                'checkName' => $disbursementDetails['check_name'],
                'checkDate' =>  $this->systemModel->checkDate('empty', $disbursementDetails['check_date'], '', 'm/d/Y', '')
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getLiquidationParticularsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['liquidation_particulars_id']) && !empty($_POST['liquidation_particulars_id'])) {
            $userID = $_SESSION['user_id'];
            $liquidation_particulars_id = $_POST['liquidation_particulars_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $disbursementDetails = $this->disbursementModel->getLiquidationParticulars($liquidation_particulars_id);

            $response = [
                'success' => true,
                'chart_of_account_id' => $disbursementDetails['chart_of_account_id'],
                'remarks' => $disbursementDetails['remarks'],
                'reference_type' => $disbursementDetails['reference_type'],
                'reference_number' => $disbursementDetails['reference_number'],
                'particulars_amount' => $disbursementDetails['particulars_amount']
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getDisbursementTotal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $filterTransactionDateStartDate = $this->systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
        $filterTransactionDateEndDate = $this->systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
        $fund_source_filter = $_POST['fund_source_filter'];
        $disbursement_status_filter = $_POST['disbursement_status_filter'];
        $transaction_type_filter = $_POST['transaction_type_filter'];
        $disbursement_category = $_POST['disbursement_category'];

        if(empty($_POST['fund_source_filter'])){
            $fund_source_filter = null;
        }

        if(empty($_POST['disbursement_status_filter'])){
            $disbursement_status_filter = null;
        }

        if(empty($_POST['transaction_type_filter'])){
            $transaction_type_filter = null;
        }

        $disbursementDetails = $this->disbursementModel->getDisbursementTableTotal($filterTransactionDateStartDate, $filterTransactionDateEndDate, $fund_source_filter, $disbursement_status_filter, $transaction_type_filter, $disbursement_category);

        $response = [
            'success' => true,
            'total' => number_format($disbursementDetails['total'] ?? 0, 2) . ' Php'
        ];

        echo json_encode($response);
        exit;
    }

    public function getDisbursementParticularsTotal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $disbursementID = $_POST['disbursement_id'];
        $disbursementTotal = $this->disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;

        $response = [
            'success' => true,
            'total' => number_format($disbursementTotal, 2) . ' Php'
        ];

        echo json_encode($response);
        exit;
    }

    public function getDisbursementCheckTotal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $disbursementID = $_POST['disbursement_id'];
        $disbursementCheckTotal = $this->disbursementModel->getDisbursementCheckTotal($disbursementID)['total'] ?? 0;

        $response = [
            'success' => true,
            'total' => number_format($disbursementCheckTotal, 2) . ' Php'
        ];

        echo json_encode($response);
        exit;
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------


?>