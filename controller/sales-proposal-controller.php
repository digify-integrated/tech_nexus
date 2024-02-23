<?php
session_start();

# -------------------------------------------------------------
#
# Function: CustomerController
# Description: 
# The CustomerController class handles customer related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class SalesProposalController {
    private $salesProposalModel;
    private $customerModel;
    private $userModel;
    private $systemSettingModel;
    private $companyModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CustomerModel, UserModel and SecurityModel instances.
    # These instances are used for customer related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param SalesProposalModel $salesProposalModel     The SalesProposalModel instance for sales proposal related operations.
    # - @param CustomerModel $customerModel     The CustomerModel instance for customer related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(SalesProposalModel $salesProposalModel, CustomerModel $customerModel, UserModel $userModel, CompanyModel $companyModel, SystemSettingModel $systemSettingModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->salesProposalModel = $salesProposalModel;
        $this->customerModel = $customerModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemModel = $systemModel;
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
                case 'save sales proposal':
                    $this->saveSalesProposal();
                    break;
                case 'save sales proposal accessories':
                    $this->saveSalesProposalAccessories();
                    break;
                case 'save sales proposal job order':
                    $this->saveSalesProposalJobOrder();
                    break;
                case 'save sales proposal additional job order':
                    $this->saveSalesProposalAdditionalJobOrder();
                    break;
                case 'save sales proposal pricing computation':
                    $this->saveSalesProposalPricingComputation();
                    break;
                case 'save sales proposal other charges':
                    $this->saveSalesProposalOtherCharges();
                    break;
                case 'save sales proposal renewal amount':
                    $this->saveSalesProposalRenewalAmount();
                    break;
                case 'save sales proposal deposit amount':
                    $this->saveSalesProposalDepositAmount();
                    break;
                case 'tag for initial approval':
                    $this->tagSalesProposalForInitialApproval();
                    break;
                case 'delete sales proposal accessories':
                    $this->deleteSalesProposalAccessories();
                    break;
                case 'delete sales proposal job order':
                    $this->deleteSalesProposalJobOrder();
                    break;
                case 'delete sales proposal additional job order':
                    $this->deleteSalesProposalAdditionalJobOrder();
                    break;
                case 'delete sales proposal deposit amount':
                    $this->deleteSalesProposalDepositAmount();
                    break;
                case 'get sales proposal details':
                    $this->getSalesProposalDetails();
                    break;
                case 'get sales proposal accessories details':
                    $this->getSalesProposalAccessoriesDetails();
                    break;
                case 'get sales proposal accessories total details':
                    $this->getSalesProposalAccessoriesTotalDetails();
                    break;
                case 'get sales proposal job order details':
                    $this->getSalesProposalJobOrderDetails();
                    break;
                case 'get sales proposal job order total details':
                    $this->getSalesProposalJobOrderTotalDetails();
                    break;
                case 'get sales proposal additional job order details':
                    $this->getSalesProposalAdditionalJobOrderDetails();
                    break;
                case 'get sales proposal additional job order total details':
                    $this->getSalesProposalAdditionalJobOrderTotalDetails();
                    break;
                case 'get sales proposal deposit amount details':
                    $this->getSalesProposalDepositAmountDetails();
                    break;
                case 'get sales proposal pricing computation details':
                    $this->getSalesProposalPricingComputationDetails();
                    break;
                case 'get sales proposal other charges details':
                    $this->getSalesProposalOtherChargesDetails();
                    break;
                case 'get sales proposal renewal amount details':
                    $this->getSalesProposalRenewalAmountDetails();
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
    # Function: saveSalesProposal
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = isset($_POST['sales_proposal_id']) ? htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8') : null;
        $salesProposalNumber = isset($_POST['sales_proposal_number']) ? htmlspecialchars($_POST['sales_proposal_number'], ENT_QUOTES, 'UTF-8') : $this->systemSettingModel->getSystemSetting(6)['value'] + 1;
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $comakerID = htmlspecialchars($_POST['comaker_id'], ENT_QUOTES, 'UTF-8');
        $referredBy = htmlspecialchars($_POST['referred_by'], ENT_QUOTES, 'UTF-8');
        $releaseDate = $this->systemModel->checkDate('empty', $_POST['release_date'], '', 'Y-m-d', '');
        $startDate = $this->systemModel->checkDate('empty', $_POST['start_date'], '', 'Y-m-d', '');
        $firstDueDate = $this->systemModel->checkDate('empty', $_POST['first_due_date'], '', 'Y-m-d', '');
        $termLength = htmlspecialchars($_POST['term_length'], ENT_QUOTES, 'UTF-8');
        $termType = htmlspecialchars($_POST['term_type'], ENT_QUOTES, 'UTF-8');
        $numberOfPayments = htmlspecialchars($_POST['number_of_payments'], ENT_QUOTES, 'UTF-8');
        $paymentFrequency = htmlspecialchars($_POST['payment_frequency'], ENT_QUOTES, 'UTF-8');
        $forRegistration = htmlspecialchars($_POST['for_registration'], ENT_QUOTES, 'UTF-8');
        $withCR = htmlspecialchars($_POST['with_cr'], ENT_QUOTES, 'UTF-8');
        $forTransfer = htmlspecialchars($_POST['for_transfer'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
        $initialApprovingOfficer = htmlspecialchars($_POST['initial_approving_officer'], ENT_QUOTES, 'UTF-8');
        $finalApprovingOfficer = htmlspecialchars($_POST['final_approving_officer'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposal($salesProposalID, $salesProposalNumber, $customerID, $comakerID, $productID, $referredBy, $releaseDate, $startDate, $firstDueDate, $termLength, $termType, $numberOfPayments, $paymentFrequency, $forRegistration, $withCR, $forTransfer, $remarks, $initialApprovingOfficer, $finalApprovingOfficer, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'customerID' => $this->securityModel->encryptData($customerID), 'salesProposalID' => $this->securityModel->encryptData($salesProposalID)]);
            exit;
        } 
        else {
            $salesProposalID = $this->salesProposalModel->insertSalesProposal($salesProposalNumber, $customerID, $comakerID, $productID, $referredBy, $releaseDate, $startDate, $firstDueDate, $termLength, $termType, $numberOfPayments, $paymentFrequency, $forRegistration, $withCR, $forTransfer, $remarks, $contactID, $initialApprovingOfficer, $finalApprovingOfficer, $userID);

            $this->systemSettingModel->updateSystemSettingValue(6, $salesProposalNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'customerID' => $this->securityModel->encryptData($customerID), 'salesProposalID' => $this->securityModel->encryptData($salesProposalID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalAccessories
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalAccessories() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAccessoriesID = htmlspecialchars($_POST['sales_proposal_accessories_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $accessories = htmlspecialchars($_POST['accessories'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['accessories_cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAccessoriesExist = $this->salesProposalModel->checkSalesProposalAccessoriesExist($salesProposalAccessoriesID);
        $total = $checkSalesProposalAccessoriesExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalAccessories($salesProposalAccessoriesID, $salesProposalID, $accessories, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalAccessories($salesProposalID, $accessories, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalForInitialApproval
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalForInitialApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $userID, 'For Initial Approval', '', $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalJobOrder
    # Description: 
    # Updates the existing sales proposal job order if it exists; otherwise, inserts a new sales proposal job order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalJobOrderID = htmlspecialchars($_POST['sales_proposal_job_order_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $jobOrder = htmlspecialchars($_POST['job_order'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['job_order_cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalJobOrderExist = $this->salesProposalModel->checkSalesProposalJobOrderExist($salesProposalJobOrderID);
        $total = $checkSalesProposalJobOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalJobOrder($salesProposalJobOrderID, $salesProposalID, $jobOrder, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalJobOrder($salesProposalID, $jobOrder, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalAdditionalJobOrder
    # Description: 
    # Updates the existing sales proposal job order if it exists; otherwise, inserts a new sales proposal job order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAdditionalJobOrderID = htmlspecialchars($_POST['sales_proposal_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $jobOrderNumber = htmlspecialchars($_POST['job_order_number'], ENT_QUOTES, 'UTF-8');
        $jobOrderDate = $this->systemModel->checkDate('empty', $_POST['job_order_date'], '', 'Y-m-d', '');
        $particulars = htmlspecialchars($_POST['particulars'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['additional_job_order_cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAdditionalJobOrderExist = $this->salesProposalModel->checkSalesProposalAdditionalJobOrderExist($salesProposalAdditionalJobOrderID);
        $total = $checkSalesProposalAdditionalJobOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID, $salesProposalID, $jobOrderNumber, $jobOrderDate, $particulars, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalAdditionalJobOrder($salesProposalID, $jobOrderNumber, $jobOrderDate, $particulars, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalPricingComputation
    # Description: 
    # Updates the existing sales proposal pricing computation if it exists; otherwise, inserts a new sales proposal pricing computation.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalPricingComputation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $deliverPrice = htmlspecialchars($_POST['delivery_price'], ENT_QUOTES, 'UTF-8');
        $costOfAccessories = htmlspecialchars($_POST['cost_of_accessories'], ENT_QUOTES, 'UTF-8');
        $reconditioningCost = htmlspecialchars($_POST['reconditioning_cost'], ENT_QUOTES, 'UTF-8');
        $subtotal = htmlspecialchars($_POST['subtotal'], ENT_QUOTES, 'UTF-8');
        $downpayment = htmlspecialchars($_POST['downpayment'], ENT_QUOTES, 'UTF-8');
        $outstandingBalance = htmlspecialchars($_POST['outstanding_balance'], ENT_QUOTES, 'UTF-8');
        $amountFinanced = htmlspecialchars($_POST['amount_financed'], ENT_QUOTES, 'UTF-8');
        $pnAmount = htmlspecialchars($_POST['pn_amount'], ENT_QUOTES, 'UTF-8');
        $repaymentAmount = htmlspecialchars($_POST['repayment_amount'], ENT_QUOTES, 'UTF-8');
        $interestRate = htmlspecialchars($_POST['interest_rate'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalPricingComputationExist = $this->salesProposalModel->checkSalesProposalPricingComputationExist($salesProposalID);
        $total = $checkSalesProposalPricingComputationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalPricingComputation($salesProposalID, $deliverPrice, $costOfAccessories, $reconditioningCost, $subtotal, $downpayment, $outstandingBalance, $amountFinanced, $pnAmount, $repaymentAmount, $interestRate, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalPricingComputation($salesProposalID, $deliverPrice, $costOfAccessories, $reconditioningCost, $subtotal, $downpayment, $outstandingBalance, $amountFinanced, $pnAmount, $repaymentAmount, $interestRate, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalOtherCharges
    # Description: 
    # Updates the existing sales proposal other charges if it exists; otherwise, inserts a new sales proposal other charges.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalOtherCharges() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverage = htmlspecialchars($_POST['insurance_coverage'], ENT_QUOTES, 'UTF-8');
        $insurancePremium = htmlspecialchars($_POST['insurance_premium'], ENT_QUOTES, 'UTF-8');
        $handlingFee = htmlspecialchars($_POST['handling_fee'], ENT_QUOTES, 'UTF-8');
        $transferFee = htmlspecialchars($_POST['transfer_fee'], ENT_QUOTES, 'UTF-8');
        $registrationFee = htmlspecialchars($_POST['registration_fee'], ENT_QUOTES, 'UTF-8');
        $docStampTax = htmlspecialchars($_POST['doc_stamp_tax'], ENT_QUOTES, 'UTF-8');
        $transactionFee = htmlspecialchars($_POST['transaction_fee'], ENT_QUOTES, 'UTF-8');
        $totalOtherCharges = htmlspecialchars($_POST['total_other_charges'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalPricingOtherChargesExist = $this->salesProposalModel->checkSalesProposalPricingOtherChargesExist($salesProposalID);
        $total = $checkSalesProposalPricingOtherChargesExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalOtherCharges($salesProposalID, $insuranceCoverage, $insurancePremium, $handlingFee, $transferFee, $registrationFee, $docStampTax, $transactionFee, $totalOtherCharges, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalOtherCharges($salesProposalID, $insuranceCoverage, $insurancePremium, $handlingFee, $transferFee, $registrationFee, $docStampTax, $transactionFee, $totalOtherCharges, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalRenewalAmount
    # Description: 
    # Updates the existing sales proposal other charges if it exists; otherwise, inserts a new sales proposal other charges.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalRenewalAmount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $registrationSecondYear = htmlspecialchars($_POST['registration_second_year'], ENT_QUOTES, 'UTF-8');
        $registrationThirdYear = htmlspecialchars($_POST['registration_third_year'], ENT_QUOTES, 'UTF-8');
        $registrationFourthYear = htmlspecialchars($_POST['registration_fourth_year'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverageSecondYear = htmlspecialchars($_POST['insurance_coverage_second_year'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverageThirdYear = htmlspecialchars($_POST['insurance_coverage_third_year'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverageFourthYear = htmlspecialchars($_POST['insurance_coverage_fourth_year'], ENT_QUOTES, 'UTF-8');
        $insurancePremiumSecondYear = htmlspecialchars($_POST['insurance_premium_second_year'], ENT_QUOTES, 'UTF-8');
        $insurancePremiumThirdYear = htmlspecialchars($_POST['insurance_premium_third_year'], ENT_QUOTES, 'UTF-8');
        $insurancePremiumFourthYear = htmlspecialchars($_POST['insurance_premium_fourth_year'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalRenewalAmountExist = $this->salesProposalModel->checkSalesProposalRenewalAmountExist($salesProposalID);
        $total = $checkSalesProposalRenewalAmountExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalRenewalAmount($salesProposalID, $registrationSecondYear, $registrationThirdYear, $registrationFourthYear, $insuranceCoverageSecondYear, $insuranceCoverageThirdYear, $insuranceCoverageFourthYear, $insurancePremiumSecondYear, $insurancePremiumThirdYear, $insurancePremiumFourthYear, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalRenewalAmount($salesProposalID, $registrationSecondYear, $registrationThirdYear, $registrationFourthYear, $insuranceCoverageSecondYear, $insuranceCoverageThirdYear, $insuranceCoverageFourthYear, $insurancePremiumSecondYear, $insurancePremiumThirdYear, $insurancePremiumFourthYear, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalDepositAmount
    # Description: 
    # Updates the existing sales proposal deposit amount if it exists; otherwise, inserts a new sales proposal deposit amount.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalDepositAmount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalDepositAmountID = htmlspecialchars($_POST['sales_proposal_deposit_amount_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $depositDate = $this->systemModel->checkDate('empty', $_POST['deposit_date'], '', 'Y-m-d', '');
        $referenceNumber = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
        $depositAmount = htmlspecialchars($_POST['deposit_amount'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalDepositAmountExist = $this->salesProposalModel->checkSalesProposalDepositAmountExist($salesProposalDepositAmountID);
        $total = $checkSalesProposalDepositAmountExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalDepositAmount($salesProposalDepositAmountID, $salesProposalID, $depositDate, $referenceNumber, $depositAmount, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalDepositAmount($salesProposalID, $depositDate, $referenceNumber, $depositAmount, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAccessories
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAccessories() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAccessoriesID = htmlspecialchars($_POST['sales_proposal_accessories_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAccessoriesExist = $this->salesProposalModel->checkSalesProposalAccessoriesExist($salesProposalAccessoriesID);
        $total = $checkSalesProposalAccessoriesExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalAccessories($salesProposalAccessoriesID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalJobOrder
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalJobOrderID = htmlspecialchars($_POST['sales_proposal_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalJobOrderExist = $this->salesProposalModel->checkSalesProposalJobOrderExist($salesProposalJobOrderID);
        $total = $checkSalesProposalJobOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalJobOrder($salesProposalJobOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAdditionalJobOrder
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAdditionalJobOrderID = htmlspecialchars($_POST['sales_proposal_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAdditionalJobOrderExist = $this->salesProposalModel->checkSalesProposalAdditionalJobOrderExist($salesProposalAdditionalJobOrderID);
        $total = $checkSalesProposalAdditionalJobOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalDepositAmount
    # Description: 
    # Delete the sales proposal deposit amount if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalDepositAmount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalDepositAmountID = htmlspecialchars($_POST['sales_proposal_deposit_amount_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalDepositAmountExist = $this->salesProposalModel->checkSalesProposalDepositAmountExist($salesProposalDepositAmountID);
        $total = $checkSalesProposalDepositAmountExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalDepositAmount($salesProposalDepositAmountID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalDetails
    # Description: 
    # Handles the retrieval of product subcategory details such as product subcategory name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $initialApprovingOfficer = $salesProposalDetails['initial_approving_officer'];
            $finalApprovingOfficer = $salesProposalDetails['final_approving_officer'];

            $createdByDetails = $this->customerModel->getPersonalInformation($salesProposalDetails['created_by']);
            $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

            $initialApprovingOfficerDetails = $this->customerModel->getPersonalInformation($initialApprovingOfficer);
            $initialApprovingOfficerName = strtoupper($initialApprovingOfficerDetails['file_as'] ?? null);

            $finalApprovingOfficerDetails = $this->customerModel->getPersonalInformation($finalApprovingOfficer);
            $finalApprovingOfficerName = strtoupper($finalApprovingOfficerDetails['file_as'] ?? null);

            $response = [
                'success' => true,
                'salesProposalNumber' => $salesProposalDetails['sales_proposal_number'],
                'comakerID' => $salesProposalDetails['comaker_id'],
                'productID' => $salesProposalDetails['product_id'],
                'referredBy' => $salesProposalDetails['referred_by'],
                'releaseDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['release_date'], '', 'm/d/Y', ''),
                'startDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['start_date'], '', 'm/d/Y', ''),
                'firstDueDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['first_due_date'], '', 'm/d/Y', ''),
                'termLength' => $salesProposalDetails['term_length'],
                'termType' => $salesProposalDetails['term_type'],
                'numberOfPayments' => $salesProposalDetails['number_of_payments'],
                'paymentFrequency' => $salesProposalDetails['payment_frequency'],
                'forRegistration' => $salesProposalDetails['for_registration'],
                'withCR' => $salesProposalDetails['with_cr'],
                'forTransfer' => $salesProposalDetails['for_transfer'],
                'remarks' => $salesProposalDetails['remarks'],
                'initialApprovingOfficer' => $initialApprovingOfficer,
                'finalApprovingOfficer' => $finalApprovingOfficer,
                'createdByName' => $createdByName,
                'initialApprovingOfficerName' => $initialApprovingOfficerName,
                'finalApprovingOfficerName' => $finalApprovingOfficerName,
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAccessoriesDetails
    # Description: 
    # Handles the retrieval of sales proposal accessories details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAccessoriesDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_accessories_id']) && !empty($_POST['sales_proposal_accessories_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalAccessoriesID = $_POST['sales_proposal_accessories_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAccessoriesDetails = $this->salesProposalModel->getSalesProposalAccessories($salesProposalAccessoriesID);

            $response = [
                'success' => true,
                'accessories' => $salesProposalAccessoriesDetails['accessories'],
                'cost' => $salesProposalAccessoriesDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAccessoriesTotalDetails
    # Description: 
    # Handles the retrieval of sales proposal accessories total details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAccessoriesTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAccessoriesTotalDetails = $this->salesProposalModel->getSalesProposalAccessoriesTotal($salesProposalID);

            $response = [
                'success' => true,
                'total' => number_format($salesProposalAccessoriesTotalDetails['total'] ?? 0, 2)
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalJobOrderDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_job_order_id']) && !empty($_POST['sales_proposal_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalJobOrderID = $_POST['sales_proposal_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalJobOrderDetails = $this->salesProposalModel->getSalesProposalJobOrder($salesProposalJobOrderID);

            $response = [
                'success' => true,
                'jobOrder' => $salesProposalJobOrderDetails['job_order'],
                'cost' => $salesProposalJobOrderDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalJobOrderTotalDetails
    # Description: 
    # Handles the retrieval of sales proposal job order total details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalJobOrderTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalJobOrderTotalDetails = $this->salesProposalModel->getSalesProposalJobOrderTotal($salesProposalID);

            $response = [
                'success' => true,
                'total' => number_format($salesProposalJobOrderTotalDetails['total'] ?? 0, 2)
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAdditionalJobOrderDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAdditionalJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_additional_job_order_id']) && !empty($_POST['sales_proposal_additional_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalAdditionalJobOrderID = $_POST['sales_proposal_additional_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAdditionalJobOrderDetails = $this->salesProposalModel->getSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID);

            $response = [
                'success' => true,
                'jobOrderNumber' => $salesProposalAdditionalJobOrderDetails['job_order_number'],
                'jobOrderDate' =>  $this->systemModel->checkDate('empty', $salesProposalAdditionalJobOrderDetails['job_order_date'], '', 'm/d/Y', ''),
                'particulars' =>  $salesProposalAdditionalJobOrderDetails['particulars'],
                'cost' => $salesProposalAdditionalJobOrderDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAdditionalJobOrderTotalDetails
    # Description: 
    # Handles the retrieval of sales proposal additional job order total details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAdditionalJobOrderTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAdditionalJobOrderTotalDetails = $this->salesProposalModel->getSalesProposalAdditionalJobOrderTotal($salesProposalID);

            $response = [
                'success' => true,
                'total' => number_format($salesProposalAdditionalJobOrderTotalDetails['total'] ?? 0, 2)
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalPricingComputationDetails
    # Description: 
    # Handles the retrieval of sales proposal pricing computation details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalPricingComputationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalPricingComputationDetails = $this->salesProposalModel->getSalesProposalPricingComputation($salesProposalID);

            $response = [
                'success' => true,
                'deliveryPrice' => $salesProposalPricingComputationDetails['delivery_price'] ?? '',
                'costOfAccessories' => $salesProposalPricingComputationDetails['cost_of_accessories'] ?? 0,
                'reconditioningCost' => $salesProposalPricingComputationDetails['reconditioning_cost'] ?? 0,
                'downpayment' => $salesProposalPricingComputationDetails['downpayment'] ?? 0,
                'interestRate' => $salesProposalPricingComputationDetails['interest_rate'] ?? 0,
                'repaymentAmount' => $salesProposalPricingComputationDetails['repayment_amount'] ?? 0,
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalOtherChargesDetails
    # Description: 
    # Handles the retrieval of sales proposal other charges details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalOtherChargesDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalOtherChargesDetails = $this->salesProposalModel->getSalesProposalOtherCharges($salesProposalID);

            $response = [
                'success' => true,
                'insuranceCoverage' => $salesProposalOtherChargesDetails['insurance_coverage'] ?? 0,
                'insurancePremium' => $salesProposalOtherChargesDetails['insurance_premium'] ?? 0,
                'handlingFee' => $salesProposalOtherChargesDetails['handling_fee'] ?? 0,
                'transferFee' => $salesProposalOtherChargesDetails['transfer_fee'] ?? 0,
                'registrationFee' => $salesProposalOtherChargesDetails['registration_fee'] ?? 0,
                'docStampTax' => $salesProposalOtherChargesDetails['doc_stamp_tax'] ?? 0,
                'transactionFee' => $salesProposalOtherChargesDetails['transaction_fee'] ?? 0
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalRenewalAmountDetails
    # Description: 
    # Handles the retrieval of sales proposal renewal amount details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalRenewalAmountDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalRenewalAmountDetails = $this->salesProposalModel->getSalesProposalRenewalAmount($salesProposalID);

            $response = [
                'success' => true,
                'registrationSecondYear' => $salesProposalRenewalAmountDetails['registration_second_year'] ?? 0,
                'registrationThirdYear' => $salesProposalRenewalAmountDetails['registration_third_year'] ?? 0,
                'registrationFourthYear' => $salesProposalRenewalAmountDetails['registration_fourth_year'] ?? 0,
                'insuranceCoverageSecondYear' => $salesProposalRenewalAmountDetails['insurance_coverage_second_year'] ?? 0,
                'insuranceCoverageThirdYear' => $salesProposalRenewalAmountDetails['insurance_coverage_third_year'] ?? 0,
                'insuranceCoverageFourthYear' => $salesProposalRenewalAmountDetails['insurance_coverage_fourth_year'] ?? 0,
                'insurancePremiumSecondYear' => $salesProposalRenewalAmountDetails['insurance_premium_second_year'] ?? 0,
                'insurancePremiumThirdYear' => $salesProposalRenewalAmountDetails['insurance_premium_third_year'] ?? 0,
                'insurancePremiumFourthYear' => $salesProposalRenewalAmountDetails['insurance_premium_fourth_year'] ?? 0
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalDepositAmountDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalDepositAmountDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_deposit_amount_id']) && !empty($_POST['sales_proposal_deposit_amount_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalDepositAmountID = $_POST['sales_proposal_deposit_amount_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalDepositAmountDetails = $this->salesProposalModel->getSalesProposalDepositAmount($salesProposalDepositAmountID);

            $response = [
                'success' => true,
                'depositDate' =>  $this->systemModel->checkDate('empty', $salesProposalDepositAmountDetails['deposit_date'], '', 'm/d/Y', ''),
                'referenceNumber' =>  $salesProposalDepositAmountDetails['reference_number'],
                'depositAmount' =>  $salesProposalDepositAmountDetails['deposit_amount']
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
require_once '../model/customer-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/user-model.php';
require_once '../model/company-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new SalesProposalController(new SalesProposalModel(new DatabaseModel), new CustomerModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CompanyModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>