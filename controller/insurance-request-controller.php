<?php
session_start();

class InsuranceRequestController {
    private $insuranceRequestModel;
    private $insuranceProviderModel;
    private $salesProposalModel;
    private $productModel;
    private $makeModel;
    private $colorModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    public function __construct(InsuranceRequestModel $insuranceRequestModel, InsuranceProviderModel $insuranceProviderModel, SalesProposalModel $salesProposalModel, ProductModel $productModel, MakeModel $makeModel, ColorModel $colorModel, UserModel $userModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->insuranceRequestModel = $insuranceRequestModel;
        $this->insuranceProviderModel = $insuranceProviderModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->productModel = $productModel;
        $this->makeModel = $makeModel;
        $this->colorModel = $colorModel;
        $this->userModel = $userModel;
        $this->systemModel = $systemModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save insurance request':
                    $this->saveInsuranceRequest();
                    break;
                case 'save insurance request computation':
                    $this->saveInsuranceRequestComputation();
                    break;
                case 'tag request draft':
                    $this->saveInsuranceRequestAsDraft();
                    break;
                case 'tag request received':
                    $this->saveInsuranceRequestAsReceived();
                    break;
                case 'tag request cancelled':
                    $this->saveInsuranceRequestAsCancelled();
                    break;
                case 'tag request submitted':
                    $this->saveInsuranceRequestAsSubmitted();
                    break;
                case 'tag request for submission':
                    $this->saveInsuranceRequestForSubmission();
                    break;
                case 'get insurance request details':
                    $this->getInsuranceRequestDetails();
                    break;
                case 'delete insurance request':
                    $this->deleteInsuranceRequest();
                    break;
                case 'delete multiple insurance request':
                    $this->deleteMultipleInsuranceRequest();
                    break;
                case 'get category rates':
                    $provider_id = $_POST['insurance_provider_id'] ?? null;
                    $category_id = $_POST['insurance_category_id'] ?? null;

                    if (!$provider_id || !$category_id) {
                        echo json_encode(['success' => false, 'message' => 'Provider and Category IDs are required.']);
                        exit;
                    }

                    $rates = $this->insuranceProviderModel->getCategoryRate($provider_id, $category_id);

                    echo json_encode([
                        'success' => true,
                        'od_theft_rate' => $rates['insurance_rate'] ?? 0,
                        'commission_rate' => $rates['commission_rate'] ?? 0
                    ]);
                    exit;
                    break;
                case 'get tax rates':
                    $provider_id = $_POST['insurance_provider_id'] ?? null;

                    if (!$provider_id) {
                        echo json_encode(['success' => false, 'message' => 'Provider is required.']);
                        exit;
                    }

                    $rates = $this->insuranceProviderModel->getTaxRate($provider_id);

                    echo json_encode([
                        'success' => true,
                        'local_government_tax_rate' => $rates['local_government_tax_rate'] ?? 0,
                    ]);
                    exit;
                    break;

                case 'get coverage rates':
                    $provider_id = $_POST['insurance_provider_id'] ?? null;
                    $coverage_amount = $_POST['coverage_amount'] ?? 0;

                    if (!$provider_id) {
                        echo json_encode(['success' => false, 'message' => 'Provider ID is required.']);
                        exit;
                    }

                    $coverage = $this->insuranceProviderModel->getCoverageRateByAmount($provider_id, $coverage_amount);

                    echo json_encode([
                        'success' => true,
                        'bi_rate' => $coverage['bi_rate'] ?? 0,
                        'pd_amount' => $coverage['pd_amount'] ?? 0
                    ]);
                    exit;
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveInsuranceRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = isset($_POST['insurance_request_id']) ? htmlspecialchars($_POST['insurance_request_id'], ENT_QUOTES, 'UTF-8') : null;
        $requestType = htmlspecialchars($_POST['request_type'], ENT_QUOTES, 'UTF-8');
        $inceptionDate = $this->systemModel->checkDate('empty', $_POST['inception_date'], '', 'Y-m-d', '');
        $insuranceProviderId = htmlspecialchars($_POST['insurance_provider_id'], ENT_QUOTES, 'UTF-8');
        $insuranceTypeId = htmlspecialchars($_POST['insurance_type_id'], ENT_QUOTES, 'UTF-8');
        $customerType = htmlspecialchars($_POST['customer_type'], ENT_QUOTES, 'UTF-8');
        $miscId = htmlspecialchars($_POST['misc_id'], ENT_QUOTES, 'UTF-8');
        $customerId = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalId = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $productId = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $yearModel = htmlspecialchars($_POST['year_model'], ENT_QUOTES, 'UTF-8');
        $color = htmlspecialchars($_POST['color'], ENT_QUOTES, 'UTF-8');
        $make = htmlspecialchars($_POST['make'], ENT_QUOTES, 'UTF-8');
        $plateNumber = htmlspecialchars($_POST['plate_number'], ENT_QUOTES, 'UTF-8');
        $chassisNumber = htmlspecialchars($_POST['chassis_number'], ENT_QUOTES, 'UTF-8');
        $engineNumber = htmlspecialchars($_POST['engine_number'], ENT_QUOTES, 'UTF-8');
        $mvFileNumber = htmlspecialchars($_POST['mv_file_number'], ENT_QUOTES, 'UTF-8');
        $mvFileNumber = htmlspecialchars($_POST['mv_file_number'], ENT_QUOTES, 'UTF-8');
        $insurance_policy_id = $_POST['insurance_policy_id'];
        $paid_by = $_POST['paid_by'];
        $collection_amount = $_POST['collection_amount'] ?? 0;

        if($customerType == 'Customer'){
            $salesProposalId = '';
            $productId = '';
            $assured = '';
            $delivery_price = 0;
        }
        else if($customerType == 'Miscellaneous') {
            $customerId = $miscId;
            $salesProposalId = '';
            $productId = '';
            $assured = '';
            $delivery_price = 0;
        }
        else if($customerType == 'Product') {
            $customerId = $miscId;
            $salesProposalId = '';
            $assured = '';
            $delivery_price = 0;
        }
        else{
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalId);    
            $pricingComputationDetails = $this->salesProposalModel->getSalesProposalPricingComputation($salesProposalId);
            $salesProposalOtherChargesDetails = $this->salesProposalModel->getSalesProposalOtherProductDetails($salesProposalId);
            
            $assured = $salesProposalOtherChargesDetails['assured'] ?? null;

            $delivery_price = $pricingComputationDetails['total_delivery_price'] ?? 0;    
            $customerId = $salesProposalDetails['customer_id'] ?? null;

            $productId = '';
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            // Included new vehicle parameter block to Update method
            $this->insuranceRequestModel->updateInsuranceRequest(
                $insuranceRequestID, $requestType, $inceptionDate, $insuranceProviderId, 
                $insuranceTypeId, $customerType, $customerId, $salesProposalId, $productId,
                $yearModel, $color, $make, $plateNumber, $chassisNumber, $engineNumber, $mvFileNumber, $insurance_policy_id, $paid_by, $assured, $collection_amount,
                $userID
            );
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'insuranceRequestID' => $this->securityModel->encryptData($insuranceRequestID)]);
            exit;
        } 
        else {
            // Included new vehicle parameter block to Insert method
            $insuranceRequestID = $this->insuranceRequestModel->insertInsuranceRequest(
                $requestType, $inceptionDate, $insuranceProviderId, $insuranceTypeId, 
                $customerType, $customerId, $salesProposalId, $productId,
                $yearModel, $color, $make, $plateNumber, $chassisNumber, $engineNumber, $mvFileNumber, $insurance_policy_id, $delivery_price, $delivery_price, $paid_by, $assured, $collection_amount,
                $userID
            );

            echo json_encode(['success' => true, 'insertRecord' => true, 'insuranceRequestID' => $this->securityModel->encryptData($insuranceRequestID)]);
            exit;
        }
    }

    public function saveInsuranceRequestAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'Draft', $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestAsReceived() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $policy_number = $_POST['policy_number'];
        $gross = $_POST['gross'];
        $net_premium = $_POST['net_premium'];
        $coverage_amount = $_POST['coverage_amount'];
        $remarks = $_POST['remarks'];
        $inception_date = $this->systemModel->checkDate('empty', $_POST['inception_date2'], '', 'Y-m-d', '');
        $expiration_date = $this->systemModel->checkDate('empty', $_POST['expiration_date'], '', 'Y-m-d', '');
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'Received', $userID);
            $this->insuranceRequestModel->insertInsurancePolicy($insuranceRequestID, $policy_number, $gross, $net_premium, $coverage_amount, $inception_date, $expiration_date, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $cancellation_reason = $_POST['cancellation_reason'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestCancelled($insuranceRequestID, $cancellation_reason, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestAsSubmitted() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'Submitted', $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestForSubmission() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = $_POST['insurance_request_id'];
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;
    
        if ($total > 0) {
            $insuranceRequestDetails = $this->insuranceRequestModel->getInsuranceRequest($insuranceRequestID);
            $request_type = $insuranceRequestDetails['request_type'] ?? 'New Policy';
            $insurance_policy_id = $insuranceRequestDetails['insurance_policy_id'] ?? null;

            if($request_type == 'Renewal' && empty($insurance_policy_id)){
                echo json_encode(['success' => false, 'message' => 'Please choose the insurance policy.']);
                exit;
            }
                                
            $this->insuranceRequestModel->updateInsuranceRequestStatus($insuranceRequestID, 'For Submission', $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function saveInsuranceRequestComputation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'] ?? null;
        $insuranceRequestID = $_POST['insurance_request_id'] ?? null;

        if (!$userID || !$insuranceRequestID) {
            echo json_encode(['success' => false, 'message' => 'Missing required IDs.']);
            exit;
        }

        $user = $this->userModel->getUserByID($userID);
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;

        if ($total > 0) {
            // Extract raw numerical values directly without letting 0 override valid data
            $odTheftCoverage  = (float)($_POST['odTheftCoverage'] ?? 0);
            $allRiskCoverage  = (float)($_POST['allRiskCoverage'] ?? 0);
            $odTheftRate      = (float)($_POST['odTheftRate'] ?? 0);
            $allRiskRate      = (float)($_POST['allRiskRate'] ?? 0);
            $odTheftPremium   = (float)($_POST['odTheftPremium'] ?? 0);
            $allRiskPremium   = (float)($_POST['allRiskPremium'] ?? 0);

            $computationData = [
                'insurance_category'        => (int)($_POST['insurance_category'] ?? 0),
                'encoding_mode'             => $_POST['encoding_mode'] ?? 'Manual',
                
                // Coverages
                'ctpl_coverage'             => (float)($_POST['ctplCoverage'] ?? 0),
                'all_risk_coverage'         => $allRiskCoverage,
                'od_theft_coverage'         => $odTheftCoverage,
                'aon_coverage'              => (float)($_POST['aonCoverage'] ?? 0),
                'tpbi_coverage'             => (float)($_POST['tpbiCoverage'] ?? 0),
                'tppd_coverage'             => (float)($_POST['tppdCoverage'] ?? 0),
                'par_coverage'              => (float)($_POST['parCoverage'] ?? 0),
                
                // Rates
                'ctpl_rate'                 => (float)($_POST['ctplRate'] ?? 0),
                'all_risk_rate'             => $allRiskRate,
                'od_theft_rate'             => $odTheftRate,
                'aon_rate'                  => (float)($_POST['aonRate'] ?? 0),
                'tpbi_rate'                 => (float)($_POST['tpbiRate'] ?? 0),
                'tppd_rate'                 => (float)($_POST['tppdRate'] ?? 0),
                'par_rate'                  => (float)($_POST['parRate'] ?? 0),
                
                // Premiums
                'ctpl_premium'              => (float)($_POST['ctplPremium'] ?? 0),
                'all_risk_premium'          => $allRiskPremium,
                'od_theft_premium'          => $odTheftPremium,
                'aon_premium'               => (float)($_POST['aonPremium'] ?? 0),
                'tpbi_premium'              => (float)($_POST['tpbiPremium'] ?? 0),
                'tppd_premium'              => (float)($_POST['tppdPremium'] ?? 0),
                'par_premium'               => (float)($_POST['parPremium'] ?? 0),
                
                // Totals & Taxes
                'total_premium'             => (float)($_POST['totalPremium'] ?? 0),
                'vat_premium_tax'           => (float)($_POST['vatPremiumTax'] ?? 0),
                'docstamp'                  => (float)($_POST['docstamp'] ?? 0),
                'local_tax'                 => (float)($_POST['localTax'] ?? 0),
                'gross'                     => (float)($_POST['gross'] ?? 0),
                
                // Commission Rates (Note: payload uses odTheftCommissionRate, etc. Adjust if needed)
                'premium_commission_rate'   => (float)($_POST['premiumCommissionRate'] ?? 0),
                'aon_commission_rate'       => (float)($_POST['aonCommissionRate'] ?? 0),
                'tpbi_commission_rate'      => (float)($_POST['tpbiCommissionRate'] ?? 0),
                'tppd_commission_rate'      => (float)($_POST['tppdCommissionRate'] ?? 0),
                'par_commission_rate'       => (float)($_POST['parCommissionRate'] ?? 0),
                
                // Commissions
                'premium_commission'        => (float)($_POST['premiumCommission'] ?? 0),
                'aon_commission'            => (float)($_POST['aonCommission'] ?? 0),
                'tpbi_commission'           => (float)($_POST['tpbiCommission'] ?? 0),
                'tppd_commission'           => (float)($_POST['tppdCommission'] ?? 0),
                'par_commission'            => (float)($_POST['parCommission'] ?? 0),
                
                // Subtotals & Discounts
                'commission_subtotal'       => (float)($_POST['commissionSubtotal'] ?? 0),
                'commission_discount_rate'  => (float)($_POST['discountRate'] ?? 0), // Payload sends 'discountRate'
                'commission_discount'       => (float)($_POST['commissionDiscount'] ?? 0),
                'net_commission'            => (float)($_POST['netCommission'] ?? 0),
                'net_premium'               => (float)($_POST['netPremium'] ?? 0),
                'last_log_by'               => (int)$userID
            ];

            $updated = $this->insuranceRequestModel->updateInsuranceRequestComputation($insuranceRequestID, $computationData);
            
            echo json_encode(['success' => (bool)$updated]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found.']);
            exit;
        }
    }

    public function deleteInsuranceRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestID = htmlspecialchars($_POST['insurance_request_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceRequestExist = $this->insuranceRequestModel->checkInsuranceRequestExist($insuranceRequestID);
        $total = $checkInsuranceRequestExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->insuranceRequestModel->deleteInsuranceRequest($insuranceRequestID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleInsuranceRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceRequestIDs = $_POST['insurance_request_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($insuranceRequestIDs as $insuranceRequestID){
            $this->insuranceRequestModel->deleteInsuranceRequest($insuranceRequestID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getInsuranceRequestDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['insurance_request_id']) && !empty($_POST['insurance_request_id'])) {
            $userID = $_SESSION['user_id'] ?? null;
            $insuranceRequestID = $_POST['insurance_request_id'];

            if (!$userID) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }

            $user = $this->userModel->getUserByID($userID);

            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }

            $insuranceRequestDetails = $this->insuranceRequestModel->getInsuranceRequest($insuranceRequestID);

            if (!$insuranceRequestDetails) {
                echo json_encode(['success' => false, 'message' => 'Insurance request details not found.']);
                exit;
            }

            // Check stored numeric values
            $rawOdTheftCoverage = (float)($insuranceRequestDetails['od_theft_coverage'] ?? 0);
            $rawAllRiskCoverage = (float)($insuranceRequestDetails['all_risk_coverage'] ?? 0);

            // Determine coverage type based on whichever field actually holds the value
            if ($rawOdTheftCoverage > 0) {
                $coverageType = 'OD/Theft';
                $mainCoverage = $rawOdTheftCoverage;
                $mainRate     = (float)($insuranceRequestDetails['od_theft_rate'] ?? 0);
                $mainPremium  = (float)($insuranceRequestDetails['od_theft_premium'] ?? 0);
            } else if ($rawAllRiskCoverage > 0) {
                $coverageType = 'All Risk';
                $mainCoverage = $rawAllRiskCoverage;
                $mainRate     = (float)($insuranceRequestDetails['all_risk_rate'] ?? 0);
                $mainPremium  = (float)($insuranceRequestDetails['all_risk_premium'] ?? 0);
            } else {
                // Default fallback if neither coverage is set
                $coverageType = 'OD/Theft';
                $mainCoverage = 0;
                $mainRate     = 0;
                $mainPremium  = 0;
            }

            $response = [
                'success' => true,
                'requestType' => $insuranceRequestDetails['request_type'] ?? '',
                'customerType' => $insuranceRequestDetails['customer_type'] ?? '',
                'insuranceProvider' => $insuranceRequestDetails['insurance_provider'] ?? '',
                'insurancePolicyId' => $insuranceRequestDetails['insurance_policy_id'] ?? '',
                'insuranceTypeId' => $insuranceRequestDetails['insurance_type_id'] ?? '',
                'customerId' => $insuranceRequestDetails['customer_id'] ?? '',
                'salesProposalId' => $insuranceRequestDetails['sales_proposal_id'] ?? '',
                'productId' => $insuranceRequestDetails['product_id'] ?? '',
                'insuranceCategory' => $insuranceRequestDetails['insurance_category'] ?? '',
                'encodingMode' => $insuranceRequestDetails['encoding_mode'] ?? 'Manual',
                'coverageType' => $coverageType,
                
                // Coverages
                'ctplCoverage'    => (float)($insuranceRequestDetails['ctpl_coverage'] ?? 0),
                'odTheftCoverage' => $mainCoverage,
                'allRiskCoverage' => $rawAllRiskCoverage,
                'aonCoverage'     => (float)($insuranceRequestDetails['aon_coverage'] ?? 0),
                'tpbiCoverage'    => (float)($insuranceRequestDetails['tpbi_coverage'] ?? 0),
                'tppdCoverage'    => (float)($insuranceRequestDetails['tppd_coverage'] ?? 0),
                'parCoverage'     => (float)($insuranceRequestDetails['par_coverage'] ?? 0),

                // Rates
                'ctplRate'    => (float)($insuranceRequestDetails['ctpl_rate'] ?? 0),
                'odTheftRate' => $mainRate,
                'allRiskRate' => (float)($insuranceRequestDetails['all_risk_rate'] ?? 0),
                'aonRate'     => (float)($insuranceRequestDetails['aon_rate'] ?? 0),
                'tpbiRate'    => (float)($insuranceRequestDetails['tpbi_rate'] ?? 0),
                'tppdRate'    => (float)($insuranceRequestDetails['tppd_rate'] ?? 0),
                'parRate'     => (float)($insuranceRequestDetails['par_rate'] ?? 0),

                // Premiums
                'ctplPremium'    => (float)($insuranceRequestDetails['ctpl_premium'] ?? 0),
                'odTheftPremium' => $mainPremium,
                'allRiskPremium' => (float)($insuranceRequestDetails['all_risk_premium'] ?? 0),
                'aonPremium'     => (float)($insuranceRequestDetails['aon_premium'] ?? 0),
                'tpbiPremium'    => (float)($insuranceRequestDetails['tpbi_premium'] ?? 0),
                'tppdPremium'    => (float)($insuranceRequestDetails['tppd_premium'] ?? 0),
                'parPremium'     => (float)($insuranceRequestDetails['par_premium'] ?? 0),

                // Totals & Taxes
                'totalPremium'  => (float)($insuranceRequestDetails['total_premium'] ?? 0),
                'vatPremiumTax' => (float)($insuranceRequestDetails['vat_premium_tax'] ?? 0),
                'docstamp'      => (float)($insuranceRequestDetails['docstamp'] ?? 0),
                'localTax'      => (float)($insuranceRequestDetails['local_tax'] ?? 0),
                'gross'         => (float)($insuranceRequestDetails['gross'] ?? 0),

                // Commission Rates
                'premiumComissionRate' => (float)($insuranceRequestDetails['premium_commission_rate'] ?? 0),
                'aonComissionRate'     => (float)($insuranceRequestDetails['aon_commission_rate'] ?? 0),
                'tpbiComissionRate'    => (float)($insuranceRequestDetails['tpbi_commission_rate'] ?? 0),
                'tppdComissionRate'    => (float)($insuranceRequestDetails['tppd_commission_rate'] ?? 0),
                'parComissionRate'     => (float)($insuranceRequestDetails['par_commission_rate'] ?? 0),

                // Commissions
                'premiumCommission'  => (float)($insuranceRequestDetails['premium_commission'] ?? 0),
                'aonCommission'      => (float)($insuranceRequestDetails['aon_commission'] ?? 0),
                'tpbiCommission'     => (float)($insuranceRequestDetails['tpbi_commission'] ?? 0),
                'tppdCommission'     => (float)($insuranceRequestDetails['tppd_commission'] ?? 0),
                'parCommission'      => (float)($insuranceRequestDetails['par_commission'] ?? 0),

                // Subtotals & Summaries
                'commissionSubtotal'     => (float)($insuranceRequestDetails['commission_subtotal'] ?? 0),
                'commissionDiscountRate' => (float)($insuranceRequestDetails['commission_discount_rate'] ?? 0),
                'commissionDiscount'     => (float)($insuranceRequestDetails['commission_discount'] ?? 0),
                'netCommission'          => (float)($insuranceRequestDetails['net_commission'] ?? 0),
                'netPremium'             => (float)($insuranceRequestDetails['net_premium'] ?? 0),

                // Form metadata & Car Details
                'paidBy'           => $insuranceRequestDetails['paid_by'] ?? '',
                'collectionAmount' => (float)($insuranceRequestDetails['collection_amount'] ?? 0),
                'assured'          => $insuranceRequestDetails['assured'] ?? '',
                'inceptionDate'    => $this->systemModel->checkDate('empty', $insuranceRequestDetails['inception_date'] ?? '', '', 'm/d/Y', ''),
                'yearModel'        => $insuranceRequestDetails['year_model'] ?? '',
                'color'            => $insuranceRequestDetails['color'] ?? '',
                'make'             => $insuranceRequestDetails['make'] ?? '',
                'plateNumber'      => $insuranceRequestDetails['plate_number'] ?? '',
                'chassisNumber'    => $insuranceRequestDetails['chassis_number'] ?? '',
                'engineNumber'     => $insuranceRequestDetails['engine_number'] ?? '',
                'mvFileNumber'     => $insuranceRequestDetails['mv_file_number'] ?? ''
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/insurance-request-model.php';
require_once '../model/insurance-provider-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/product-model.php';
require_once '../model/make-model.php';
require_once '../model/color-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InsuranceRequestController(new InsuranceRequestModel(new DatabaseModel), new InsuranceProviderModel(new DatabaseModel()), new SalesProposalModel(new DatabaseModel), new ProductModel(new DatabaseModel), new MakeModel(new DatabaseModel), new ColorModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
