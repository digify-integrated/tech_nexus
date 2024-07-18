<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: pdc management table
        # Description:
        # Generates the pdc management table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'pdc management table':
            $filterCheckDateStartDate = $systemModel->checkDate('empty', $_POST['filter_check_date_start_date'], '', 'Y-m-d', '');
            $filterCheckDateEndDate = $systemModel->checkDate('empty', $_POST['filter_check_date_end_date'], '', 'Y-m-d', '');
            $filterPDCManagementStatus = $_POST['filter_pdc_management_status'];

            $sql = $databaseModel->getConnection()->prepare('CALL generatePDCManagementTable(:filterPDCManagementStatus, :filterCheckDateStartDate, :filterCheckDateEndDate)');
            $sql->bindValue(':filterPDCManagementStatus', $filterPDCManagementStatus, PDO::PARAM_STR);
            $sql->bindValue(':filterCheckDateStartDate', $filterCheckDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCheckDateEndDate', $filterCheckDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $loanCollectionID = $row['loan_collection_id'];
                $salesProposalID = $row['sales_proposal_id'];
                $loanNumber = $row['loan_number'];
                $paymentDetails = $row['payment_details'];
                $checkNumber = $row['check_number'];
                $paymentAmount = $row['payment_amount'];
                $bankBranch = $row['bank_branch'];
                $collectionStatus = $row['collection_status'];
                $checkDate = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $customerID = $salesProposalDetails['customer_id'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $statusClasses = [
                    'Pending' => 'info',
                    'Cleared' => 'success',
                    'On-Hold' => 'dark',
                    'Cancelled' => 'warning',
                    'Redeposit' => 'info',
                    'For Deposit' => 'warning',
                    'Deposited' => 'success',
                    'Pulled-Out' => 'dark',
                    'Reversed' => 'danger'
                ];
                
                $defaultClass = 'dark';
                
                $class = $statusClasses[$collectionStatus] ?? $defaultClass;
                
                $badge = '<span class="badge bg-' . $class . '">' . $collectionStatus . '</span>';

                $loanCollectionIDEncrypted = $securityModel->encryptData($loanCollectionID);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $loanCollectionID .'">',
                    'LOAN_NUMBER' => ' <a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" title="View Details">
                                        '. $loanNumber .'
                                    </a>',
                    'CUSTOMER' => '<div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div>',
                    'PAYMENT_DETAILS' => $paymentDetails,
                    'CHECK_NUMBER' => $checkNumber,
                    'CHECK_DATE' => $checkDate,
                    'PAYMENT_AMOUNT' => number_format($paymentAmount, 2),
                    'BANK_BRANCH' => $bankBranch,
                    'STATUS' => $badge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="pdc-management.php?id='. $loanCollectionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>