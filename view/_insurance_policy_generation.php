<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/insurance-request-model.php';
require_once '../model/insurance-policy-model.php';
require_once '../model/insurance-provider-model.php';
require_once '../model/insurance-type-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insurancePolicyModel = new InsurancePolicyModel($databaseModel);
$insuranceRequestModel = new InsuranceRequestModel($databaseModel);
$insuranceProviderModel = new InsuranceProviderModel($databaseModel);
$insuranceTypeModel = new InsuranceTypeModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'insurance policy table':

            $status_filter = $_POST['status_filter'];
            $remittance_status_filter = $_POST['remittance_status_filter'];
            $payment_status_filter = $_POST['payment_status_filter'];

            $filter_inception_start_date = $systemModel->checkDate('empty', $_POST['filter_inception_start_date'], '', 'Y-m-d', '');
            $filter_inception_end_date = $systemModel->checkDate('empty', $_POST['filter_inception_end_date'], '', 'Y-m-d', '');
            $filter_expiry_start_date = $systemModel->checkDate('empty', $_POST['filter_expiry_start_date'], '', 'Y-m-d', '');
            $filter_expiry_end_date = $systemModel->checkDate('empty', $_POST['filter_expiry_end_date'], '', 'Y-m-d', '');

            $query = "SELECT * FROM insurance_policy WHERE 1=1";
            $params = [];

            // Status
            if (!empty($status_filter)) {
                if ($status_filter == 'Expired') {
                    $query .= " AND status = 'Active' AND expiry_date < CURDATE()";
                }
                elseif ($status_filter == 'Active') {
                    $query .= " AND status = 'Active' AND expiry_date >= CURDATE()";
                }
                else {
                    $query .= " AND status = :status";
                    $params[':status'] = $status_filter;
                }
            }

            // Remittance Status
            if (!empty($remittance_status_filter)) {
                $query .= " AND remittance_status = :remittance_status";
                $params[':remittance_status'] = $remittance_status_filter;
            }

            // Payment Status
            if (!empty($payment_status_filter)) {
                $query .= " AND payment_status = :payment_status";
                $params[':payment_status'] = $payment_status_filter;
            }

            // Inception Date Range
            if (!empty($filter_inception_start_date)) {
                $query .= " AND inception_date >= :inception_start";
                $params[':inception_start'] = $filter_inception_start_date;
            }

            if (!empty($filter_inception_end_date)) {
                $query .= " AND inception_date <= :inception_end";
                $params[':inception_end'] = $filter_inception_end_date;
            }

            // Expiry Date Range
            if (!empty($filter_expiry_start_date)) {
                $query .= " AND expiry_date >= :expiry_start";
                $params[':expiry_start'] = $filter_expiry_start_date;
            }

            if (!empty($filter_expiry_end_date)) {
                $query .= " AND expiry_date <= :expiry_end";
                $params[':expiry_end'] = $filter_expiry_end_date;
            }

            $query .= " ORDER BY insurance_policy_id DESC";

            $sql = $databaseModel->getConnection()->prepare($query);
            $sql->execute($params);

            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $insurancePolicyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 200, 'delete');

            $today = new DateTime();

            foreach ($options as $row) {

                $insurancePolicyID = $row['insurance_policy_id'];
                $insurance_request_id = $row['insurance_request_id'];
                $policy_number = $row['policy_number'];
                $status = $row['status'];
                $gross = $row['gross'] ?? 0;
                $net_premium = $row['net_premium'] ?? 0;
                $coverage_amount = $row['coverage_amount'] ?? 0;
                $plate_number = $row['plate_number'] ?? '';
                $chassis_number = $row['chassis_number'] ?? '';
                $engine_number = $row['engine_number'] ?? '';

                $raw_inception_date = $row['inception_date'];
                $raw_expiry_date = $row['expiry_date'];
                $raw_remittance_date = $row['remittance_date'];
                $raw_payment_date = $row['payment_date'];

                $customer_type = $row['customer_type'] ?? '--';
                $customer_id = $row['customer_id'] ?? '';
                $insurance_type_id = $row['insurance_type_id'] ?? '';
                $insurance_provider = $row['insurance_provider'] ?? '';

                $inception_date = $systemModel->checkDate('summary', $raw_inception_date, '', 'm/d/Y', '');
                $expiry_date = $systemModel->checkDate('summary', $raw_expiry_date, '', 'm/d/Y', '');
                $remittance_date = $systemModel->checkDate('summary', $raw_remittance_date, '', 'm/d/Y', '');
                $payment_date = $systemModel->checkDate('summary', $raw_payment_date, '', 'm/d/Y', '');

                // =========================
                // EXPIRY CALCULATION
                // =========================
                $daysRemaining = null;
                $expiryLabel = '';
                $expiryBadgeClass = '';

                if (!empty($raw_expiry_date)) {
                    try {
                        $expiry = new DateTime($raw_expiry_date);
                        $daysRemaining = (int)$today->diff($expiry)->format('%r%a');

                        if ($daysRemaining < 0) {
                            $expiryLabel = abs($daysRemaining) . ' day(s) expired';
                            $expiryBadgeClass = 'text-danger';

                        } elseif ($daysRemaining == 0) {
                            $expiryLabel = 'Expires today';
                            $expiryBadgeClass = 'text-warning fw-bold';

                        } elseif ($daysRemaining <= 3) {
                            $expiryLabel = 'Expiring in ' . $daysRemaining . ' day(s)';
                            $expiryBadgeClass = 'text-warning fw-bold';

                        } else {
                            $expiryLabel = $daysRemaining . ' day(s) remaining';
                            $expiryBadgeClass = 'text-muted';
                        }

                    } catch (Exception $e) {
                        $daysRemaining = null;
                        $expiryLabel = 'Invalid date';
                        $expiryBadgeClass = 'text-muted';
                    }
                }

                // =========================
                // STATUS LOGIC
                // =========================
                if ($status === 'Cancelled') {
                    $displayStatus = 'Cancelled';
                    $statusBadge = 'warning';

                } elseif ($daysRemaining !== null && $daysRemaining < 0) {
                    $displayStatus = 'Expired';
                    $statusBadge = 'danger';

                } elseif ($daysRemaining !== null && $daysRemaining <= 3) {
                    $displayStatus = 'Expiring Soon';
                    $statusBadge = 'warning';

                } else {
                    $displayStatus = 'Active';
                    $statusBadge = 'success';
                }

                // =========================
                // CUSTOMER DETAILS
                // =========================

                if ($customer_type == 'Miscellaneous') {
                    $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
                    $customerName = $miscellaneousClientDetails['client_name'] ?? null;
                }
                else {
                    $customerDetails = $customerModel->getPersonalInformation($customer_id);
                    $customerName = $customerDetails['file_as'] ?? null;
                }

                $insuranceTypeDetails = $insuranceTypeModel->getInsuranceType($insurance_type_id);
                $insrance_type_name = $insuranceTypeDetails['insurance_type_name'] ?? null;

                $providerDetails = $insuranceProviderModel->getInsuranceProvider($insurance_provider);
                $provider_name = $providerDetails['provider_name'] ?? null;

                $insuranceRequestDetails = $insuranceRequestModel->getInsuranceRequest($insurance_request_id);
                $assured = $insuranceRequestDetails['assured'] ?? null;
                $sales_proposal_id = $insuranceRequestDetails['sales_proposal_id'] ?? null;

                $lastInsuranceRequestDetails = $insuranceRequestModel->getLastInsuranceRequest($insurancePolicyID);
                $last_coverage_amount = $lastInsuranceRequestDetails['coverage_amount'] ?? 0;

                if(!empty($sales_proposal_id)){
                    $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                    $product_id = $salesProposalDetails['product_id'] ?? null;

                    $termLength = $salesProposalDetails['term_length'];
                    $termType = $salesProposalDetails['term_type'];

                    // Compute maturity date
                    $maturityRaw = date('F d, Y', strtotime("+{$termLength} {$termType}", strtotime($salesProposalDetails['actual_start_date'])));

                    $productDetails = $productModel->getProduct($product_id);
                    $stockNumber = $productDetails['stock_number'] ?? null;
                }
                else{
                    $maturityRaw = '';
                    $stockNumber = '';
                }                

                $insurancePolicyIDEncrypted = $securityModel->encryptData($insurancePolicyID);

                // =========================
                // DELETE BUTTON
                // =========================
                $delete = '';
                if ($insurancePolicyDeleteAccess['total'] > 0) {
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-policy"
                                    data-insurance-policy-id="'. $insurancePolicyID .'"
                                    title="Delete Insurance Policy">
                                    <i class="ti ti-trash"></i>
                            </button>';
                }

                // =========================
                // RESPONSE
                // =========================
                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children"
                                        type="checkbox"
                                        value="'. $insurancePolicyID .'">',

                    'POLICY_NUMBER' => $policy_number,
                    'CUSTOMER_NAME' => $customerName,
                    'INSURANCE_TYPE' => $insrance_type_name,
                    'PROVIDER_NAME' => $provider_name,
                    'STOCK_NUMBER' => $stockNumber,
                    'PLATE_NUMBER' => $plate_number,
                    'CHASSIS_NUMBER' => $chassis_number,
                    'ENGINE_NUMBER' => $engine_number,
                    'ASSURED' => $assured,
                    'STATUS' => '',
                    'LAST_YEAR_COVERAGE' => number_format($last_coverage_amount, 2),
                    'COVERAGE' => number_format($coverage_amount, 2),
                    'GROSS' => number_format($gross, 2),
                    'MATURITY_DATE' => $maturityRaw,
                    'NET_PREMIUM' => number_format($net_premium, 2),

                    'INCEPTION_DATE' => $inception_date,
                    'REMITTANCE_DATE' => $remittance_date,
                    'PAYMENT_DATE' => $payment_date,

                    'EXPIRY_DATE' => '
                        <div>
                            <h6 class="mb-0">'. $expiry_date .'</h6>
                            <small class="'. $expiryBadgeClass .'">'. $expiryLabel .'</small>
                        </div>
                    ',

                    'INS_STATUS' => '<span class="badge bg-'. $statusBadge .'">
                                    '. $displayStatus .'
                                </span>',

                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="insurance-policy.php?id='. $insurancePolicyIDEncrypted .'"
                                    class="btn btn-icon btn-primary"
                                    title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);

        break;
    }
}

?>