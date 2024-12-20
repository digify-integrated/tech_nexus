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

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
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
                $reference_number = $row['reference_number'];
                $customer_id = $row['customer_id'];
                $department_id = $row['department_id'];
                $company_id = $row['company_id'];
                $transaction_type = $row['transaction_type'];
                $particulars = $row['particulars'];
                $disbursement_amount = $row['disbursement_amount'];

                $customerDetails = $customerModel->getPersonalInformation($customer_id);
                $customerName = $customerDetails['file_as'] ?? null;

                $departmentDetails = $departmentModel->getDepartment($department_id);
                $departmentName = $departmentDetails['department_name'] ?? null;

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? null;

                $disbursementIDEncrypted = $securityModel->encryptData($disbursementID);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children pdc-id" type="checkbox" value="'. $disbursementID .'">',
                    'TRANSACTION_DATE' => $transaction_date,
                    'TRANSACTION_NUMBER' => $transaction_number,
                    'CUSTOMER' => '<a href="disbursement.php?id='. $disbursementIDEncrypted .'" title="View Details"><div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                </div></a>',
                    'TRANSACTION_TYPE' => $transaction_type,
                    'DEPARTMENT' => $departmentName,
                    'COMPANY' => $companyName,
                    'PARTICULARS' => $particulars,
                    'DISBURSMENT_AMOUNT' => number_format($disbursement_amount, 2),
                    'REFERENCE_NUMBER' => $reference_number,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="disbursement.php?id='. $disbursementIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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