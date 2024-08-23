<?php
session_start();

# -------------------------------------------------------------
#
# Function: PDCManagementController
# Description: 
# The PDCManagementController class handles pdc management related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PDCManagementController {
    private $pdcManagementModel;
    private $salesProposalModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemSettingModel;
    private $companyModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PDCManagementModel, UserModel and SecurityModel instances.
    # These instances are used for pdc management related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PDCManagementModel $pdcManagementModel     The PDCManagementModel instance for pdc management related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PDCManagementModel $pdcManagementModel, SalesProposalModel $salesProposalModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, CompanyModel $companyModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->pdcManagementModel = $pdcManagementModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
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
                case 'save pdc management':
                    $this->savePDCManagement();
                    break;
                case 'get pdc management details':
                    $this->getPDCManagementDetails();
                    break;
                case 'delete pdc management':
                    $this->deletePDCManagement();
                    break;
                case 'delete multiple pdc management':
                    $this->deleteMultiplePDCManagement();
                    break;
                case 'tag pdc as deposited':
                    $this->tagPDCAsDeposited();
                    break;
                case 'tag multiple pdc as deposited':
                    $this->tagMultiplePDCAsDeposited();
                    break;
                case 'tag pdc as for deposit':
                    $this->tagPDCAsForDeposit();
                    break;
                case 'tag multiple pdc as for deposit':
                    $this->tagMultiplePDCAsForDeposit();
                    break;
                case 'tag pdc as cleared':
                    $this->tagPDCAsCleared();
                    break;
                case 'tag multiple pdc as cleared':
                    $this->tagMultiplePDCAsCleared();
                    break;
                case 'tag pdc as on-hold':
                    $this->tagPDCAsOnHold();
                    break;
                case 'tag pdc as cancelled':
                    $this->tagPDCAsCancel();
                    break;
                case 'tag multiple pdc as cancelled':
                    $this->tagMultiplePDCAsCancel();
                    break;
                case 'tag pdc as pulled-out':
                    $this->tagPDCAsPulledOut();
                    break;
                case 'tag pdc as redeposit':
                    $this->tagPDCAsRedeposit();
                    break;
                case 'tag pdc as reversed':
                    $this->tagPDCAsReversed();
                    break;
                case 'tag multiple pdc as reversed':
                    $this->tagMultiplePDCAsReversed();
                    break;
                case 'duplicate multiple cancelled pdc':
                    $this->duplicateMultipleCancelledPDC();
                    break;
                case 'save pdc import':
                    $this->saveImportPDC();
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
    # Function: tagPDCAsDeposited
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsDeposited() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $referenceNumber = $this->systemSettingModel->getSystemSetting(9)['value'] + 1;

        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Deposited', '', '', '', $referenceNumber, $userID);

        $this->systemSettingModel->updateSystemSettingValue(9, $referenceNumber, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsDeposited
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsDeposited() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionIDs as $loanCollectionID){
            $referenceNumber = $this->systemSettingModel->getSystemSetting(9)['value'] + 1;

            $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Deposited', '', '', '', $referenceNumber, $userID);

            $this->systemSettingModel->updateSystemSettingValue(9, $referenceNumber, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateMultipleCancelledPDC
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateMultipleCancelledPDC() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionIDs as $loanCollectionID){
            $this->pdcManagementModel->duplicateCancelledPDC($loanCollectionID, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsForDeposit
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsForDeposit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'For Deposit', '', '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsForDeposit
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsForDeposit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionIDs as $loanCollectionID){
            $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'For Deposit', '', '', '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsCleared
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsCleared() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Cleared', '', '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsOnHold
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsOnHold() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
        $onHoldReason = $_POST['on_hold_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $onHoldAttachmentFileName = $_FILES['onhold_attachment']['name'];
        $onHoldAttachmentFileSize = $_FILES['onhold_attachment']['size'];
        $onHoldAttachmentFileError = $_FILES['onhold_attachment']['error'];
        $onHoldAttachmentTempName = $_FILES['onhold_attachment']['tmp_name'];
        $onHoldAttachmentFileExtension = explode('.', $onHoldAttachmentFileName);
        $onHoldAttachmentActualFileExtension = strtolower(end($onHoldAttachmentFileExtension));

        
        $pdcManagementDetails = $this->pdcManagementModel->getPDCManagement($loanCollectionID);
        $onHoldAttachment = !empty($pdcManagementDetails['onhold_attachment']) ? '.' . $pdcManagementDetails['onhold_attachment'] : null;

        if(file_exists($onHoldAttachment)){
            if (!unlink($onHoldAttachment)) {
                echo json_encode(['success' => false, 'message' => 'On-hold attachment file cannot be deleted due to an error.']);
                exit;
            }
        }

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(1);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(1);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($onHoldAttachmentActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($onHoldAttachmentTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the sales proposal form.']);
            exit;
        }
        
        if($onHoldAttachmentFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($onHoldAttachmentFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The on-hold attachment file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $onHoldAttachmentActualFileExtension;

        $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/onhold_attachment/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/onhold_attachment/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($onHoldAttachmentTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        
        $this->pdcManagementModel->updateLoanCollectionOnHoldAttachment($loanCollectionID, $filePath, $userID);
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'On-Hold', $onHoldReason, '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsCancel
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = $_POST['cancellation_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Cancelled', $cancellationReason, '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsCancel
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = $_POST['loan_collection_id']; 
        $loanCollectionIDs = explode(',', $loanCollectionID);
        $cancellationReason = $_POST['cancellation_reason'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionIDs as $loanCollectionID){
            $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Cancelled', $cancellationReason, '', '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsPulledOut
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsPulledOut() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
        $pulledOutReason = $_POST['pulled_out_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Pulled-Out', $pulledOutReason, '', '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsRedeposit
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsRedeposit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
        $redepositDate = $this->systemModel->checkDate('empty', $_POST['redeposit_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Redeposit', '', '', $redepositDate, '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagPDCAsReversed
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagPDCAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
        $reversalReason = $_POST['reversal_reason'];
        $reversalRemarks = $_POST['reversal_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $referenceNumber = $this->systemSettingModel->getSystemSetting(10)['value'] + 1;

        $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Reversed', $reversalReason, $reversalRemarks, '', $referenceNumber, $userID);

        if($reversalReason == 'Account Closed'){
            $pdcManagementDetails = $this->pdcManagementModel->getPDCManagement($loanCollectionID);
            $salesProposalID = $pdcManagementDetails['sales_proposal_id'];
            $checkDate = $pdcManagementDetails['check_date'];
            $accountNumber = $pdcManagementDetails['account_number'];

            $this->pdcManagementModel->cancelLoanCollectionClosed($salesProposalID, $checkDate, $accountNumber, $userID);
        }

        $this->systemSettingModel->updateSystemSettingValue(10, $referenceNumber, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsReversed
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsReversed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = $_POST['loan_collection_id']; 
        $loanCollectionIDs = explode(',', $loanCollectionID);
        $reversalReason = $_POST['reversal_reason'];
        $reversalRemarks = $_POST['reversal_remarks'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionIDs as $loanCollectionID){
            $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
            $total = $checkLoanCollectionExist['total'] ?? 0;

            if($total > 0){
                $referenceNumber = $this->systemSettingModel->getSystemSetting(10)['value'] + 1;

                $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Reversed', $reversalReason, $reversalRemarks, '', $referenceNumber, $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsCleared
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagMultiplePDCAsCleared() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($loanCollectionIDs as $loanCollectionID){
            $this->pdcManagementModel->updateLoanCollectionStatus($loanCollectionID, 'Cleared', '', '', '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: savePDCManagement
    # Description: 
    # Updates the existing pdc management if it exists; otherwise, inserts a new pdc management.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = isset($_POST['loan_collection_id']) ? htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8') : null;
        $pdcType = htmlspecialchars($_POST['pdc_type'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $paymentDetails = $_POST['payment_details'];
        $checkNumber = $_POST['check_number'];
        $paymentAmount = $_POST['payment_amount'];
        $bankBranch = $_POST['bank_branch'];
        $remarks = $_POST['remarks'];
        $accountNumber = $_POST['account_number'];
        $companyID = $_POST['company_id'];
        $checkDate = $this->systemModel->checkDate('empty', $_POST['check_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($pdcType == 'Loan'){
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $loanNumber = $salesProposalDetails['loan_number'];
            $customerID = $salesProposalDetails['customer_id'];
        }
        else{
            $loanNumber = '';
        }
    
        if ($total > 0) {
            if($pdcType == 'Loan'){
                $checkLoanCollectionConflict = $this->pdcManagementModel->checkLoanCollectionConflict($loanCollectionID, $salesProposalID, $checkNumber);
                $total = $checkLoanCollectionConflict['total'] ?? 0;
            }
            else{
                $total = 0;
            }

            if ($total == 0) {
                $this->pdcManagementModel->updatePDCManagement($loanCollectionID, $salesProposalID, $loanNumber, $productID, $customerID, $pdcType, $checkNumber, $checkDate, $paymentAmount, $paymentDetails, $bankBranch, $remarks, $accountNumber, $companyID, $userID);
            
                echo json_encode(['success' => true, 'insertRecord' => false, 'loanCollectionID' => $this->securityModel->encryptData($loanCollectionID)]);
                exit;
            }
            else{
                echo json_encode(['success' => false, 'checkConflict' => true]);
                exit;
            }
        } 
        else {
            if($pdcType == 'Loan'){
                $checkLoanCollectionConflict = $this->pdcManagementModel->checkLoanCollectionConflict('', $salesProposalID, $checkNumber);
                $total = $checkLoanCollectionConflict['total'] ?? 0;
            }
            else{
                $total = 0;
            }

            if ($total == 0) {
                $loanCollectionID = $this->pdcManagementModel->insertPDCManagement($salesProposalID, $loanNumber, $productID, $customerID, $pdcType, $checkNumber, $checkDate, $paymentAmount, $paymentDetails, $bankBranch, $remarks, $accountNumber, $companyID, $userID);

                echo json_encode(['success' => true, 'insertRecord' => true, 'loanCollectionID' => $this->securityModel->encryptData($loanCollectionID)]);
                exit;
            }
            else{
                echo json_encode(['success' => false, 'checkConflict' => true]);
                exit;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePDCManagement
    # Description: 
    # Delete the pdc management if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pdcManagementID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPDCManagementExist = $this->pdcManagementModel->checkPDCManagementExist($pdcManagementID);
        $total = $checkPDCManagementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pdcManagementModel->deletePDCManagement($pdcManagementID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePDCManagement
    # Description: 
    # Delete the selected pdc managements if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pdcManagementIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($pdcManagementIDs as $pdcManagementID){
            $this->pdcManagementModel->deletePDCManagement($pdcManagementID);
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
    # Function: duplicatePDCManagement
    # Description: 
    # Duplicates the pdc management if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicatePDCManagement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pdcManagementID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPDCManagementExist = $this->pdcManagementModel->checkPDCManagementExist($pdcManagementID);
        $total = $checkPDCManagementExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $pdcManagementID = $this->pdcManagementModel->duplicatePDCManagement($pdcManagementID, $userID);

        echo json_encode(['success' => true, 'pdcManagementID' =>  $this->securityModel->encryptData($pdcManagementID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPDCManagementDetails
    # Description: 
    # Handles the retrieval of pdc management details such as pdc management name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPDCManagementDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['loan_collection_id']) && !empty($_POST['loan_collection_id'])) {
            $userID = $_SESSION['user_id'];
            $loanCollectionID = $_POST['loan_collection_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $pdcManagementDetails = $this->pdcManagementModel->getPDCManagement($loanCollectionID);

            $response = [
                'success' => true,
                'salesProposalID' => $pdcManagementDetails['sales_proposal_id'],
                'productID' => $pdcManagementDetails['product_id'],
                'customerID' => $pdcManagementDetails['customer_id'],
                'pdcType' => $pdcManagementDetails['pdc_type'],
                'paymentDetails' => $pdcManagementDetails['payment_details'],
                'paymentAmount' => $pdcManagementDetails['payment_amount'],
                'checkNumber' => $pdcManagementDetails['check_number'],
                'bankBranch' => $pdcManagementDetails['bank_branch'],
                'remarks' => $pdcManagementDetails['remarks'],
                'accountNumber' => $pdcManagementDetails['account_number'],
                'companyID' => $pdcManagementDetails['company_id'],
                'cancellationReason' => $pdcManagementDetails['cancellation_reason'],
                'pulledOutReason' => $pdcManagementDetails['pulled_out_reason'],
                'reversalReason' => $pdcManagementDetails['reversal_reason'],
                'onholdReason' => $pdcManagementDetails['onhold_reason'],
                'onholdAttachment' => $this->systemModel->checkImage($pdcManagementDetails['onhold_attachment'], 'default'),
                'checkDate' =>  $this->systemModel->checkDate('empty', $pdcManagementDetails['check_date'], '', 'm/d/Y', '')
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveImportPDC
    # Description: 
    # Save the imported product for loading.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveImportPDC() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $importFileFileName = $_FILES['import_file']['name'];
        $importFileFileSize = $_FILES['import_file']['size'];
        $importFileFileError = $_FILES['import_file']['error'];
        $importFileTempName = $_FILES['import_file']['tmp_name'];
        $importFileFileExtension = explode('.', $importFileFileName);
        $importFileActualFileExtension = strtolower(end($importFileFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(8);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(8);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($importFileActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($importFileTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the import file.']);
            exit;
        }
        
        if($importFileFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($importFileFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The import file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $importData = array_map('str_getcsv', file($importFileTempName));

        array_shift($importData);

        foreach ($importData as $row) {
            $loan_collection_id = htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8');
            $sales_proposal_id = htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8');
            $loan_number = htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8');
            $product_id = htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8');
            $customer_id = htmlspecialchars($row[4], ENT_QUOTES, 'UTF-8');
            $pdc_type = htmlspecialchars($row[5], ENT_QUOTES, 'UTF-8');
            $payment_details = htmlspecialchars($row[6], ENT_QUOTES, 'UTF-8');
            $payment_amount = htmlspecialchars($row[7], ENT_QUOTES, 'UTF-8');
            $collection_status = htmlspecialchars($row[8], ENT_QUOTES, 'UTF-8');
            $check_date = $this->systemModel->checkDate('empty', $row[9], '', 'Y-m-d', '');
            $check_number = htmlspecialchars($row[10], ENT_QUOTES, 'UTF-8');
            $bank_branch = $row[11];
            $payment_date = $this->systemModel->checkDate('empty', $row[12], '', 'Y-m-d', '');
            $transaction_date = $this->systemModel->checkDate('empty', $row[13], '', 'Y-m-d', '');
            $onhold_date = $this->systemModel->checkDate('empty', $row[14], '', 'Y-m-d', '');
            $onhold_reason = $row[15];
            $deposit_date = $this->systemModel->checkDate('empty', $row[16], '', 'Y-m-d', '');
            $for_deposit_date = $this->systemModel->checkDate('empty', $row[17], '', 'Y-m-d', '');
            $redeposit_date = $this->systemModel->checkDate('empty', $row[18], '', 'Y-m-d', '');
            $new_deposit_date = $this->systemModel->checkDate('empty', $row[19], '', 'Y-m-d', '');
            $clear_date = $this->systemModel->checkDate('empty', $row[20], '', 'Y-m-d', '');
            $cancellation_date = $this->systemModel->checkDate('empty', $row[21], '', 'Y-m-d', '');
            $cancellation_reason = $row[22];
            $reversal_date = $this->systemModel->checkDate('empty', $row[23], '', 'Y-m-d', '');
            $pulled_out_date = $this->systemModel->checkDate('empty', $row[24], '', 'Y-m-d', '');
            $pulled_out_reason = $row[25];
            $reversal_reason = $row[26];
            $reversal_remarks = $row[27];

            if(!empty($loan_collection_id)){
                $checkLoanCollectionExist = $this->pdcManagementModel->checkLoanCollectionExist($loan_collection_id);
                $total = $checkLoanCollectionExist['total'] ?? 0;

                if($total > 0){
                    $this->pdcManagementModel->updateImportPDC($loan_collection_id, $sales_proposal_id, $loan_number, $product_id, $customer_id, $pdc_type, $payment_details, $payment_amount, $collection_status, $check_date, $check_number, $bank_branch, $payment_date, $transaction_date, $onhold_date, $onhold_reason, $deposit_date, $for_deposit_date, $redeposit_date, $new_deposit_date, $clear_date, $cancellation_date, $cancellation_reason, $reversal_date, $pulled_out_date, $pulled_out_reason, $reversal_reason, $reversal_remarks, $userID);
                }
                else{
                    if($pdc_type == 'Loan'){
                        $checkLoanCollectionConflict = $this->pdcManagementModel->checkLoanCollectionConflict($loan_collection_id, $sales_proposal_id, $check_number);
                        $total = $checkLoanCollectionConflict['total'] ?? 0;
                    }
                    else{
                        $total = 0;
                    }
    
                    if($total == 0){
                        $this->pdcManagementModel->insertImportPDC($sales_proposal_id, $loan_number, $product_id, $customer_id, $pdc_type, $payment_details, $payment_amount, $collection_status, $check_date, $check_number, $bank_branch, $payment_date, $transaction_date, $onhold_date, $onhold_reason, $deposit_date, $for_deposit_date, $redeposit_date, $new_deposit_date, $clear_date, $cancellation_date, $cancellation_reason, $reversal_date, $pulled_out_date, $pulled_out_reason, $reversal_reason, $reversal_remarks, $userID);
                    }
                }
            }
            else{
                if($pdc_type == 'Loan'){
                    $checkLoanCollectionConflict = $this->pdcManagementModel->checkLoanCollectionConflict('', $sales_proposal_id, $check_number);
                    $total = $checkLoanCollectionConflict['total'] ?? 0;
                }
                else{
                    $total = 0;
                }

                if($total == 0){
                    $this->pdcManagementModel->insertImportPDC($sales_proposal_id, $loan_number, $product_id, $customer_id, $pdc_type, $payment_details, $payment_amount, $collection_status, $check_date, $check_number, $bank_branch, $payment_date, $transaction_date, $onhold_date, $onhold_reason, $deposit_date, $for_deposit_date, $redeposit_date, $new_deposit_date, $clear_date, $cancellation_date, $cancellation_reason, $reversal_date, $pulled_out_date, $pulled_out_reason, $reversal_reason, $reversal_remarks, $userID);
                }
            }
        }

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/pdc-management-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/company-model.php';
require_once '../model/system-model.php';

$controller = new PDCManagementController(new PDCManagementModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new CompanyModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>