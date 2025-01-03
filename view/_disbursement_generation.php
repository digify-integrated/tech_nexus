<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/security-model.php';
require_once '../model/miscellaneous-client-model.php';
require_once '../model/system-model.php';
require_once '../model/department-model.php';
require_once '../model/company-model.php';
require_once '../model/chart-of-account-model.php';
require_once '../model/disbursement-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$chartOfAccountModel = new ChartOfAccountModel($databaseModel);
$disbursementModel = new DisbursementModel($databaseModel);
$securityModel = new SecurityModel();
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: disbursement table
        # Description:
        # Generates the disbursement table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'disbursement table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementTable(:filterTransactionDateStartDate, :filterTransactionDateEndDate)');
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $disbursementID = $row['disbursement_id'];
                $transaction_date = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');
                $transaction_number = $row['transaction_number'];
                $transaction_type = $row['transaction_type'];
                $fund_source = $row['fund_source'];
                $particulars = $row['particulars'];
                $customer_id = $row['customer_id'];
                $department_id = $row['department_id'];
                $company_id = $row['company_id'];
                $disburse_status = $row['disburse_status'];
                $payable_type = $row['payable_type'];

                if($disburse_status === 'Draft'){
                    $disburse_status = '<span class="badge bg-secondary">' . $disburse_status . '</span>';
                }
                else if($disburse_status === 'Cancelled'){
                    $disburse_status = '<span class="badge bg-warning">' . $disburse_status . '</span>';
                }
                else if($disburse_status === 'Reversed'){
                    $disburse_status = '<span class="badge bg-danger">' . $disburse_status . '</span>';
                }
                else{
                    $disburse_status = '<span class="badge bg-success">' . $disburse_status . '</span>';
                }

                if($payable_type === 'Customer'){
                    $customerDetails = $customerModel->getPersonalInformation($customer_id);
                    $customerName = $customerDetails['file_as'] ?? null;
                }
                else{
                    $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
                    $customerName = $miscellaneousClientDetails['client_name'] ?? null;
                }

                $disbursementIDEncrypted = $securityModel->encryptData($disbursementID);

                $departmentDetails = $departmentModel->getDepartment($department_id);
                $departmentName = $departmentDetails['department_name'] ?? null;

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? null;

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children pdc-id" type="checkbox" value="'. $disbursementID .'">',
                    'TRANSACTION_DATE' => $transaction_date,
                    'CUSTOMER_NAME' => '<a href="disbursement.php?id='. $disbursementIDEncrypted .'" title="View Details">
                                        '. $customerName .'
                                    </a>',
                    'DEPARTMENT_NAME' => $departmentName,
                    'COMPANY_NAME' => $companyName,
                    'TRANSACTION_NUMBER' => $transaction_number,
                    'TRANSACTION_TYPE' => $transaction_type,
                    'FUND_SOURCE' => $fund_source,
                    'PARTICULARS' => $particulars,
                    'STATUS' => $disburse_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="disbursement.php?id='. $disbursementIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'particulars table':
            $disbursement_id = $_POST['disbursement_id'];

            $disbursementDetails = $disbursementModel->getDisbursement($disbursement_id);
            $disburse_status = $disbursementDetails['disburse_status'] ?? '';

            $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementParticularsTable(:disbursement_id)');
            $sql->bindValue(':disbursement_id', $disbursement_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $disbursement_particulars_id = $row['disbursement_particulars_id'];
                $chart_of_account_id = $row['chart_of_account_id'];
                $remarks = $row['remarks'];
                $particulars_amount = $row['particulars_amount'];

                $chartOfAccountDetails = $chartOfAccountModel->getChartOfAccount($chart_of_account_id);
                $chartOfAccountName = $chartOfAccountDetails['name'] ?? null;

                $action = '';
                if($disburse_status == 'Draft'){
                    $action = '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-success update-disbursement-particulars" data-bs-toggle="offcanvas" data-bs-target="#particulars-offcanvas" aria-controls="particulars-offcanvas" data-disbursement-particulars-id="'. $disbursement_particulars_id .'" title="Update Particular">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-danger delete-disbursement-particulars" data-disbursement-particulars-id="'. $disbursement_particulars_id .'" title="Delete Particular">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>';
                }

                $disbursement_particulars_id_enc = $securityModel->encryptData($disbursement_particulars_id);

                $response[] = [
                    'PARTICULARS' => $chartOfAccountName,
                    'PARTICULAR_AMOUNT' => number_format($particulars_amount, 2),
                    'REMARKS' => $remarks,
                    'ACTION' => $action
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>