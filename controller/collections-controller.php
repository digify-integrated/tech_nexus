<?php
session_start();

# -------------------------------------------------------------
#
# Function: CollectionsController
# Description: 
# The CollectionsController class handles collections related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CollectionsController {
    private $collectionsModel;
    private $salesProposalModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemSettingModel;
    private $companyModel;
    private $leasingApplicationModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CollectionsModel, UserModel and SecurityModel instances.
    # These instances are used for collections related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CollectionsModel $collectionsModel     The CollectionsModel instance for collections related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CollectionsModel $collectionsModel, SalesProposalModel $salesProposalModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, CompanyModel $companyModel, LeasingApplicationModel $leasingApplicationModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->collectionsModel = $collectionsModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
        $this->leasingApplicationModel = $leasingApplicationModel;
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
                case 'save collections':
                    $this->saveCollections();
                    break;
                case 'get collections details':
                    $this->getCollectionsDetails();
                    break;
                case 'delete collections':
                    $this->deleteCollections();
                    break;
                case 'delete multiple collections':
                    $this->deleteMultipleCollections();
                    break;
                case 'tag collection as cleared':
                    $this->tagCollectionAsCleared();
                    break;
                case 'tag multiple collection as cleared':
                    $this->tagMultiplePDCAsCleared();
                    break;
                case 'tag collection as cancelled':
                    $this->tagCollectionAsCancel();
                    break;
                case 'tag multiple pdc as cancelled':
                    $this->tagMultiplePDCAsCancel();
                    break;
                case 'tag collection as reversed':
                    $this->tagCollectionAsReversed();
                    break;
                case 'tag multiple pdc as reversed':
                    $this->tagMultiplePDCAsReversed();
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
    # Function: tagCollectionAsCleared
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagCollectionAsCleared() {
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
    
        $checkLoanCollectionExist = $this->collectionsModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->collectionsModel->updateCollectionStatus($loanCollectionID, 'Cleared', '', '', '', $userID);
            
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
            $this->collectionsModel->updateCollectionStatus($loanCollectionID, 'Cleared', '', '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagCollectionAsCancel
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagCollectionAsCancel() {
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
    
        $checkLoanCollectionExist = $this->collectionsModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->collectionsModel->updateCollectionStatus($loanCollectionID, 'Cancelled', $cancellationReason, '', '', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsCancel
    # Description: 
    # Delete the selected collectionss if it exists; otherwise, skip it.
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
            $this->collectionsModel->updateCollectionStatus($loanCollectionID, 'Cancelled', $cancellationReason, '', '', $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagCollectionAsReversed
    # Description: 
    # Tag the pdc as deposited if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagCollectionAsReversed() {
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
    
        $checkLoanCollectionExist = $this->collectionsModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $referenceNumber = $this->systemSettingModel->getSystemSetting(10)['value'] + 1;

        $collectionsDetails = $this->collectionsModel->getCollections($loanCollectionID);
        $paymentAmount = $collectionsDetails['payment_amount'];
        $companyID = $collectionsDetails['company_id'];
        $payment_advice = $collectionsDetails['payment_advice'];
        $pdc_type = $collectionsDetails['pdc_type'];
        $leasing_collections_id = $collectionsDetails['leasing_collections_id'] ?? null;

        if($companyID == 1){
            $systemSettingID = 11;
        }
        else if($companyID == 2){
            $systemSettingID = 12;
        }
        else{
            $systemSettingID = 13;
        }

        if(($pdc_type == 'Leasing' || $pdc_type == 'Leasing Other') && !empty($leasing_collections_id)){
            $leasingCollectionDetails = $this->leasingApplicationModel->getLeasingCollections($leasing_collections_id);
            $leasingApplicationRepaymentID = $leasingCollectionDetails['leasing_application_repayment_id'];
            $paymentFor = $leasingCollectionDetails['payment_for'];
            $paymentID = $leasingCollectionDetails['payment_id'];
            $paymentAmount = $leasingCollectionDetails['payment_amount'];
    
        
            $this->leasingApplicationModel->deleteLeasingCollections($leasing_collections_id, $leasingApplicationRepaymentID, $paymentFor, $paymentID, $paymentAmount);
            $this->leasingApplicationModel->updateLeasingOtherChargesStatus();
            $this->leasingApplicationModel->updateLeasingApplicationRepaymentStatus();
        }

        $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $paymentAmount;

        $this->collectionsModel->updateCollectionStatus($loanCollectionID, 'Reversed', $reversalReason, $reversalRemarks, $referenceNumber, $userID);
                    
        $this->systemSettingModel->updateSystemSettingValue(10, $referenceNumber, $userID);
        
        if($payment_advice == 'No'){
            $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagMultiplePDCAsReversed
    # Description: 
    # Delete the selected collectionss if it exists; otherwise, skip it.
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
            $checkLoanCollectionExist = $this->collectionsModel->checkLoanCollectionExist($loanCollectionID);
            $total = $checkLoanCollectionExist['total'] ?? 0;

            if($total > 0){
                $referenceNumber = $this->systemSettingModel->getSystemSetting(10)['value'] + 1;

                $collectionsDetails = $this->collectionsModel->getCollections($loanCollectionID);
                $paymentAmount = $collectionsDetails['payment_amount'];
                $companyID = $collectionsDetails['company_id'];
                $payment_advice = $collectionsDetails['payment_advice'];
                $pdc_type = $collectionsDetails['pdc_type'];
                $leasing_collections_id = $collectionsDetails['leasing_collections_id'] ?? null;

                if($companyID == 1){
                    $systemSettingID = 11;
                }
                else if($companyID == 2){
                    $systemSettingID = 12;
                }
                else{
                    $systemSettingID = 13;
                }

                if(($pdc_type == 'Leasing' || $pdc_type == 'Leasing Other') && !empty($leasing_collections_id)){
                    $leasingCollectionDetails = $this->leasingApplicationModel->getLeasingCollections($leasing_collections_id);
                    $leasingApplicationRepaymentID = $leasingCollectionDetails['leasing_application_repayment_id'];
                    $paymentFor = $leasingCollectionDetails['payment_for'];
                    $paymentID = $leasingCollectionDetails['payment_id'];
                    $paymentAmount = $leasingCollectionDetails['payment_amount'];            
                
                    $this->leasingApplicationModel->deleteLeasingCollections($leasing_collections_id, $leasingApplicationRepaymentID, $paymentFor, $paymentID, $paymentAmount);
                    $this->leasingApplicationModel->updateLeasingOtherChargesStatus();
                    $this->leasingApplicationModel->updateLeasingApplicationRepaymentStatus();
                }
        
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $paymentAmount;
        
                $this->collectionsModel->updateCollectionStatus($loanCollectionID, 'Reversed', $reversalReason, $reversalRemarks, $referenceNumber, $userID);
                            
                $this->systemSettingModel->updateSystemSettingValue(10, $referenceNumber, $userID);

                if($payment_advice == 'No'){
                    $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
                }
            }
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
    # Function: saveCollections
    # Description: 
    # Updates the existing collections if it exists; otherwise, inserts a new collections.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCollections() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $loanCollectionID = isset($_POST['loan_collection_id']) ? htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8') : null;
        $modeOfPayment = htmlspecialchars($_POST['mode_of_payment'], ENT_QUOTES, 'UTF-8');
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $pdcType = htmlspecialchars($_POST['pdc_type'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $leasing_repayment_id = htmlspecialchars($_POST['leasing_repayment_id'], ENT_QUOTES, 'UTF-8');
        $leasing_other_charges_id = htmlspecialchars($_POST['leasing_other_charges_id'], ENT_QUOTES, 'UTF-8');
        $payment_for = htmlspecialchars($_POST['payment_for'], ENT_QUOTES, 'UTF-8');
        $paymentDetails = $_POST['payment_details'];
        
        $paymentAmount = $_POST['payment_amount'];
        $referenceNumber = $_POST['reference_number'];
        $remarks = $_POST['remarks'];
        $depositedTo = $_POST['deposited_to'];
        $miscellaneousClientID = $_POST['miscellaneous_client_id'];
        $payment_advice = $_POST['payment_advice'];
        $orDate = $this->systemModel->checkDate('empty', $_POST['or_date'], '', 'Y-m-d', '');
        $paymentDate = $this->systemModel->checkDate('empty', $_POST['payment_date'], '', 'Y-m-d', '');

        if($payment_advice == 'No'){
            $orNumber = $_POST['or_number'];
        }
        else{
            $orNumber = $this->systemSettingModel->getSystemSetting(26)['value'] + 1;
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->collectionsModel->checkLoanCollectionExist($loanCollectionID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($pdcType == 'Loan'){
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $loanNumber = $salesProposalDetails['loan_number'];
            $customerID = $salesProposalDetails['customer_id'];
        }
        else{
            $loanNumber = '';
        }

        if($pdcType == 'Leasing' || $pdcType == 'Leasing Other'){
            if($pdcType == 'Leasing'){
                $leasingApplicationRepaymentDetails = $this->leasingApplicationModel->getLeasingApplicationRepayment($leasing_repayment_id);
                $leasing_application_id = $leasingApplicationRepaymentDetails['leasing_application_id'];
            }
            else{
                $leasingApplicationRepaymentDetails = $this->leasingApplicationModel->getLeasingOtherCharges($leasing_other_charges_id);
                $leasing_application_id = $leasingApplicationRepaymentDetails['leasing_application_id'];
                $leasing_repayment_id = $leasingApplicationRepaymentDetails['leasing_application_repayment_id'];
            }
        }
        else{
            $leasing_application_id = '';
        }
    
        if ($total > 0) {
            $collectionsDetails = $this->collectionsModel->getCollections($loanCollectionID);
            $paymentAmount2 = $collectionsDetails['payment_amount'];
            $companyID2 = $collectionsDetails['company_id'];
    
            if($companyID2 == '1'){
                $systemSettingID = 11;
            }
            else if($companyID2 == '2'){
                $systemSettingID = 12;
            }
            else{
                $systemSettingID = 13;
            }

            $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] - $paymentAmount2;

            if($payment_advice == 'No'){
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }

            if($companyID == '1'){
                $systemSettingID = 11;
            }
            else if($companyID == '2'){
                $systemSettingID = 12;
            }
            else{
                $systemSettingID = 13;
            }
            
            $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $paymentAmount;

            $this->collectionsModel->updateCollection( $loanCollectionID, $salesProposalID, $loanNumber, $productID, $customerID, $leasing_application_id, $leasing_repayment_id, $leasing_other_charges_id, $payment_for, $pdcType, $modeOfPayment, $orNumber, $orDate, $paymentDate, $paymentAmount, $referenceNumber, $paymentDetails, $companyID, $depositedTo, $remarks, $miscellaneousClientID, $payment_advice, $userID);
                    
            if($payment_advice == 'No'){
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'loanCollectionID' => $this->securityModel->encryptData($loanCollectionID)]);
            exit;
        } 
        else {
            if($modeOfPayment === 'GCash' || $modeOfPayment === 'Online Deposit'){
                $checkLoanCollectionReferenceExist = $this->collectionsModel->checkLoanCollectionReferenceExist($referenceNumber);
                $total = $checkLoanCollectionReferenceExist['total'] ?? 0;

                if($total > 0){
                    echo json_encode(['success' => false, 'referenceExist' => true]);
                    exit;
                }
            }
            
            if($companyID == '1'){
                $systemSettingID = 11;
            }
            else if($companyID == '2'){
                $systemSettingID = 12;
            }
            else{
                $systemSettingID = 13;
            }
            
            if($pdcType === 'Leasing'){
                $leasingApplicationRepaymentDetails = $this->leasingApplicationModel->getLeasingApplicationRepayment($leasing_repayment_id);
                $unpaidRental = $leasingApplicationRepaymentDetails['unpaid_rental'] ?? 0;
    
                if($paymentAmount <= $unpaidRental){
                    $leasing_collections_id = $this->leasingApplicationModel->insertLeasingRentalPayment($leasing_repayment_id, $leasing_application_id, 'Rent', '', $orNumber, $modeOfPayment, $paymentDate, $paymentAmount, $userID);
                    $this->leasingApplicationModel->updateLeasingOtherChargesStatus();
                    $this->leasingApplicationModel->updateLeasingApplicationRepaymentStatus();
                }
                else{
                    echo json_encode(['success' => false, 'overPayment' => true]);
                    exit;
                }               
            }
            else if($pdcType === 'Leasing Other'){
                $leasingApplicationOtherChargesDetails = $this->leasingApplicationModel->getLeasingOtherCharges($leasing_other_charges_id);
                    $dueAmount = $leasingApplicationOtherChargesDetails['due_amount'] ?? 0;
            
                    if($paymentAmount <= $dueAmount){
                        $leasing_collections_id = $this->leasingApplicationModel->insertLeasingOtherChargesPayment($leasing_repayment_id, $leasing_application_id, $payment_for, $leasing_other_charges_id, $orNumber, $modeOfPayment, $paymentDate, $paymentAmount, $userID);
                        $this->leasingApplicationModel->updateLeasingOtherChargesStatus();
                        $this->leasingApplicationModel->updateLeasingApplicationRepaymentStatus();
                    }
                    else{
                        echo json_encode(['success' => false, 'overPayment' => true]);
                        exit;
                    }
            }
            else{
                $leasing_collections_id = '';
            }

            $loanCollectionID = $this->collectionsModel->insertCollection($salesProposalID, $loanNumber, $productID, $customerID, $leasing_application_id, $leasing_repayment_id, $leasing_other_charges_id, $leasing_collections_id, $payment_for, $pdcType, $modeOfPayment, $orNumber, $orDate, $paymentDate, $paymentAmount, $referenceNumber, $paymentDetails, $companyID, $depositedTo, $remarks, $miscellaneousClientID, $payment_advice, $userID);

            if($pdcType != 'Leasing Other' && $pdcType != 'Leasing'){
                $onhandBalance = $this->systemSettingModel->getSystemSetting($systemSettingID)['value'] + $paymentAmount;

                if($payment_advice === 'No'){
                    $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
                }
                        
                if($payment_advice === 'Yes'){
                    $this->systemSettingModel->updateSystemSettingValue(26, $orNumber, $userID);
                }
            }            

            echo json_encode(['success' => true, 'insertRecord' => true, 'loanCollectionID' => $this->securityModel->encryptData($loanCollectionID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCollections
    # Description: 
    # Delete the collections if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCollections() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $collectionsID = htmlspecialchars($_POST['loan_collection_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLoanCollectionExist = $this->collectionsModel->checkLoanCollectionExist($collectionsID);
        $total = $checkLoanCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $collectionsDetails = $this->collectionsModel->getCollections($collectionsID);
        $paymentAmount = $collectionsDetails['payment_amount'];
        $companyID = $collectionsDetails['company_id'];
        $payment_advice = $collectionsDetails['payment_advice'];

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

        $this->collectionsModel->deleteCollections($collectionsID);
                    
        if($payment_advice == 'No'){
            $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
        }

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCollections
    # Description: 
    # Delete the selected collectionss if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCollections() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $collectionsIDs = $_POST['loan_collection_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($collectionsIDs as $collectionsID){
            $collectionsDetails = $this->collectionsModel->getCollections($collectionsID);
            $paymentAmount = $collectionsDetails['payment_amount'];
            $companyID = $collectionsDetails['company_id'];
            $payment_advice = $collectionsDetails['payment_advice'];

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
    
            $this->collectionsModel->deleteCollections($collectionsID);
                        
            if($payment_advice == 'No'){
                $this->systemSettingModel->updateSystemSettingValue($systemSettingID, $onhandBalance, $userID);
            }
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
    # Function: getCollectionsDetails
    # Description: 
    # Handles the retrieval of collections details such as collections name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCollectionsDetails() {
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
    
            $collectionsDetails = $this->collectionsModel->getCollections($loanCollectionID);

            $response = [
                'success' => true,
                'salesProposalID' => $collectionsDetails['sales_proposal_id'],
                'productID' => $collectionsDetails['product_id'],
                'customerID' => $collectionsDetails['customer_id'],
                'pdcType' => $collectionsDetails['pdc_type'],
                'paymentDetails' => $collectionsDetails['payment_details'],
                'paymentAmount' => $collectionsDetails['payment_amount'],
                'orNumber' => $collectionsDetails['or_number'],
                'modeOfPayment' => $collectionsDetails['mode_of_payment'],
                'remarks' => $collectionsDetails['remarks'],
                'companyID' => $collectionsDetails['company_id'],
                'leasingRepaymentID' => $collectionsDetails['leasing_application_repayment_id'],
                'leasingOtherChargesID' => $collectionsDetails['leasing_other_charges_id'],
                'paymentFor' => $collectionsDetails['payment_for'],
                'referenceNumber' => $collectionsDetails['reference_number'],
                'depositedTo' => $collectionsDetails['deposited_to'],
                'miscellaneousClientID' => $collectionsDetails['collected_from'],
                'orDate' =>  $this->systemModel->checkDate('empty', $collectionsDetails['or_date'], '', 'm/d/Y', ''),
                'paymentDate' =>  $this->systemModel->checkDate('empty', $collectionsDetails['payment_date'], '', 'm/d/Y', ''),
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
require_once '../model/collections-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/company-model.php';
require_once '../model/leasing-application-model.php';
require_once '../model/system-model.php';

$controller = new CollectionsController(new CollectionsModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new CompanyModel(new DatabaseModel), new LeasingApplicationModel(new DatabaseModel()), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>