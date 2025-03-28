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
            $filter_replenishment_date_start_date = $systemModel->checkDate('empty', $_POST['filter_replenishment_date_start_date'], '', 'Y-m-d', '');
            $filter_replenishment_date_end_date = $systemModel->checkDate('empty', $_POST['filter_replenishment_date_end_date'], '', 'Y-m-d', '');
            $fund_source_filter = $_POST['fund_source_filter'];
            $disbursement_status_filter = $_POST['disbursement_status_filter'];
            $transaction_type_filter = $_POST['transaction_type_filter'];

            if(empty($_POST['fund_source_filter'])){
                $fund_source_filter = null;
            }

            if(empty($_POST['disbursement_status_filter'])){
                $disbursement_status_filter = null;
            }

            if(empty($_POST['transaction_type_filter'])){
                $transaction_type_filter = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementTable(:filterTransactionDateStartDate, :filterTransactionDateEndDate, :filter_replenishment_date_start_date, :filter_replenishment_date_end_date, :fund_source_filter, :disbursement_status_filter, :transaction_type_filter)');
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_replenishment_date_start_date', $filter_replenishment_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_replenishment_date_end_date', $filter_replenishment_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':fund_source_filter', $fund_source_filter, PDO::PARAM_STR);
            $sql->bindValue(':disbursement_status_filter', $disbursement_status_filter, PDO::PARAM_STR);
            $sql->bindValue(':transaction_type_filter', $transaction_type_filter, PDO::PARAM_STR);
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

                $disbursementDetails = $disbursementModel->getDisbursementTotal($disbursementID);
                $disbursementTotal = $disbursementDetails['total'] ?? 0;

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
                    'TOTAL_AMOUNT' => number_format($disbursementTotal, 2),
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="disbursement.php?id='. $disbursementIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'check disbursement table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $fund_source_filter = $_POST['fund_source_filter'];
            $disbursement_status_filter = $_POST['disbursement_status_filter'];
            $transaction_type_filter = $_POST['transaction_type_filter'];

            if(empty($_POST['fund_source_filter'])){
                $fund_source_filter = null;
            }

            if(empty($_POST['disbursement_status_filter'])){
                $disbursement_status_filter = null;
            }

            if(empty($_POST['transaction_type_filter'])){
                $transaction_type_filter = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateCheckDisbursementTable(:filterTransactionDateStartDate, :filterTransactionDateEndDate, :fund_source_filter, :disbursement_status_filter, :transaction_type_filter)');
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':fund_source_filter', $fund_source_filter, PDO::PARAM_STR);
            $sql->bindValue(':disbursement_status_filter', $disbursement_status_filter, PDO::PARAM_STR);
            $sql->bindValue(':transaction_type_filter', $transaction_type_filter, PDO::PARAM_STR);
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

                $disbursementDetails = $disbursementModel->getDisbursementTotal($disbursementID);
                $disbursementTotal = $disbursementDetails['total'] ?? 0;

                $departmentDetails = $departmentModel->getDepartment($department_id);
                $departmentName = $departmentDetails['department_name'] ?? null;

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? null;

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children pdc-id" type="checkbox" value="'. $disbursementID .'">',
                    'TRANSACTION_DATE' => $transaction_date,
                    'CUSTOMER_NAME' => '<a href="check-disbursement.php?id='. $disbursementIDEncrypted .'" title="View Details">
                                        '. $customerName .'
                                    </a>',
                    'DEPARTMENT_NAME' => $departmentName,
                    'COMPANY_NAME' => $companyName,
                    'TRANSACTION_NUMBER' => $transaction_number,
                    'TRANSACTION_TYPE' => $transaction_type,
                    'FUND_SOURCE' => $fund_source,
                    'PARTICULARS' => $particulars,
                    'STATUS' => $disburse_status,
                    'TOTAL_AMOUNT' => number_format($disbursementTotal, 2),
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="check-disbursement.php?id='. $disbursementIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'disbursement check monitoring table':
            $filterCheckDateStartDate = $systemModel->checkDate('empty', $_POST['filter_check_date_start_date'], '', 'Y-m-d', '');
            $filterCheckDateEndDate = $systemModel->checkDate('empty', $_POST['filter_check_date_end_date'], '', 'Y-m-d', '');
            $filter_transmitted_date_start_date = $systemModel->checkDate('empty', $_POST['filter_transmitted_date_start_date'], '', 'Y-m-d', '');
            $filter_transmitted_date_end_date = $systemModel->checkDate('empty', $_POST['filter_transmitted_date_end_date'], '', 'Y-m-d', '');
            $filter_outstanding_date_start_date = $systemModel->checkDate('empty', $_POST['filter_outstanding_date_start_date'], '', 'Y-m-d', '');
            $filter_outstanding_date_end_date = $systemModel->checkDate('empty', $_POST['filter_outstanding_date_end_date'], '', 'Y-m-d', '');
            $filter_negotiated_date_start_date = $systemModel->checkDate('empty', $_POST['filter_negotiated_date_start_date'], '', 'Y-m-d', '');
            $filter_negotiated_date_end_date = $systemModel->checkDate('empty', $_POST['filter_negotiated_date_end_date'], '', 'Y-m-d', '');
            $filter_check_status = $_POST['filter_check_status'];

            if(empty($_POST['filter_check_status'])){
                $filter_check_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementCheckMonitoringTable(:filterCheckDateStartDate, :filterCheckDateEndDate, :filter_transmitted_date_start_date, :filter_transmitted_date_end_date, :filter_outstanding_date_start_date, :filter_outstanding_date_end_date, :filter_negotiated_date_start_date, :filter_negotiated_date_end_date, :filter_check_status)');
            $sql->bindValue(':filterCheckDateStartDate', $filterCheckDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterCheckDateEndDate', $filterCheckDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_transmitted_date_start_date', $filter_transmitted_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_transmitted_date_end_date', $filter_transmitted_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_outstanding_date_start_date', $filter_outstanding_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_outstanding_date_end_date', $filter_outstanding_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_negotiated_date_start_date', $filter_negotiated_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_negotiated_date_end_date', $filter_negotiated_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_check_status', $filter_check_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $disbursement_check_id  = $row['disbursement_check_id'];
                $disbursementID = $row['disbursement_id'];
                $transaction_date = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');
                $transaction_number = $row['transaction_number'];
                $transaction_type = $row['transaction_type'];
                $fund_source = $row['fund_source'];
                $particulars = $row['particulars'];
                $customer_id = $row['customer_id'];
                $department_id = $row['department_id'];
                $company_id = $row['company_id'];
                $payable_type = $row['payable_type'];
                $check_number = $row['check_number'];
                $check_name = $row['check_name'];
                $check_date = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');
                $reversal_date = $systemModel->checkDate('empty', $row['reversal_date'], '', 'm/d/Y', '');
                $transmitted_date = $systemModel->checkDate('empty', $row['transmitted_date'], '', 'm/d/Y', '');
                $outstanding_date = $systemModel->checkDate('empty', $row['outstanding_date'], '', 'm/d/Y', '');
                $negotiated_date = $systemModel->checkDate('empty', $row['negotiated_date'], '', 'm/d/Y', '');
                $check_amount = $row['check_amount'];
                $check_status = $row['check_status'];

                if($check_status === 'Draft'){
                    $check_status = '<span class="badge bg-secondary">' . $check_status . '</span>';
                }
                else if($check_status === 'Transmitted'){
                    $check_status = '<span class="badge bg-warning">Unreleased</span>';
                }
                else if($check_status === 'Outstanding'){
                    $check_status = '<span class="badge bg-info">Outstanding Check</span>';
                }
                else if($check_status === 'Outstanding PDC'){
                    $check_status = '<span class="badge bg-info">' . $check_status . '</span>';
                }
                else if($check_status === 'Negotiated'){
                    $check_status = '<span class="badge bg-success">' . $check_status . '</span>';
                }
                else{
                    $check_status = '<span class="badge bg-danger">' . $check_status . '</span>';
                }
                
                if(!empty($check_name)){
                    $customerName = $check_name;
                }
                else{
                    if($payable_type === 'Customer'){
                        $customerDetails = $customerModel->getPersonalInformation($customer_id);
                        $customerName = $customerDetails['file_as'] ?? null;
                    }
                    else{
                        $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
                        $customerName = $miscellaneousClientDetails['client_name'] ?? null;
                    }
                }

                $disbursementIDEncrypted = $securityModel->encryptData($disbursementID);

                $departmentDetails = $departmentModel->getDepartment($department_id);
                $departmentName = $departmentDetails['department_name'] ?? null;

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? null;

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children pdc-id" type="checkbox" value="'. $disbursement_check_id .'">',
                    'TRANSACTION_DATE' => $transaction_date,
                    'CUSTOMER_NAME' => '<a href="check-disbursement.php?id='. $disbursementIDEncrypted .'" title="View Details">
                                        '. $customerName .'
                                    </a>',
                    'DEPARTMENT_NAME' => $departmentName,
                    'COMPANY_NAME' => $companyName,
                    'TRANSACTION_NUMBER' => $transaction_number,
                    'CHECK_DATE' => $check_date,
                    'CHECK_NUMBER' => $check_number,
                    'CHECK_AMOUNT' => '<p class="text-end m-0">'. number_format($check_amount, 2) . '</p>',
                    'REVERSAL_DATE' => $reversal_date,
                    'TRANSMITTED_DATE' => $transmitted_date,
                    'OUTSTANDING_DATE' => $outstanding_date,
                    'NEGOTIATED_DATE' => $negotiated_date,
                    'CHECK_STATUS' => $check_status
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
            
            $response = []; 

            foreach ($options as $row) {
                $disbursement_particulars_id = $row['disbursement_particulars_id'];
                $chart_of_account_id = $row['chart_of_account_id'];
                $company_id = $row['company_id'];
                $remarks = $row['remarks'];
                $particulars_amount = $row['particulars_amount'];
                $with_vat = $row['with_vat'];
                $with_withholding = $row['with_withholding'];
                $vat_amount = $row['vat_amount'];
                $withholding_amount = $row['withholding_amount'];
                $total_amount = $row['total_amount'];

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? null;

                $chartOfAccountDetails = $chartOfAccountModel->getChartOfAccount($chart_of_account_id);
                $chartOfAccountName = $chartOfAccountDetails['name'] ?? null;

                $action = '';
                if($disburse_status === 'Draft'){
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
                    'COMPANY' => $companyName,
                    'PARTICULAR_AMOUNT' => number_format($particulars_amount, 2),
                    'REMARKS' => $remarks,
                    'ACTION' => $action
                ];

                if($with_vat === 'Yes'){
                    $response[] = [
                        'PARTICULARS' => 'Input Tax',
                        'COMPANY' => $companyName,
                        'PARTICULAR_AMOUNT' => number_format($vat_amount, 2),
                        'REMARKS' => 'Particulars of ' . $chartOfAccountName . ' with an amount of ' . number_format($particulars_amount, 2),
                        'ACTION' => ''
                    ];
                }

                if($with_withholding != 'No'){
                    $response[] = [
                        'PARTICULARS' => 'Withholding Tax Payable Other',
                        'COMPANY' => $companyName,
                        'PARTICULAR_AMOUNT' => number_format($withholding_amount * -1, 2),
                        'REMARKS' => 'Particulars of ' . $chartOfAccountName . ' with an amount of ' . number_format($particulars_amount, 2),
                        'ACTION' => ''
                    ];
                }
            }

            echo json_encode($response);
        break;
        case 'check table':
            $disbursement_id = $_POST['disbursement_id'];

            $disbursementDetails = $disbursementModel->getDisbursement($disbursement_id);
            $disburse_status = $disbursementDetails['disburse_status'] ?? '';

            $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementCheckTable(:disbursement_id)');
            $sql->bindValue(':disbursement_id', $disbursement_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $disbursement_check_id = $row['disbursement_check_id'];
                $bank_branch = $row['bank_branch'];
                $check_number = $row['check_number'];
                $check_name = $row['check_name'];
                $check_date = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');
                $reversal_date = $systemModel->checkDate('empty', $row['reversal_date'], '', 'm/d/Y', '');
                $transmitted_date = $systemModel->checkDate('empty', $row['transmitted_date'], '', 'm/d/Y', '');
                $outstanding_date = $systemModel->checkDate('empty', $row['outstanding_date'], '', 'm/d/Y', '');
                $negotiated_date = $systemModel->checkDate('empty', $row['negotiated_date'], '', 'm/d/Y', '');
                $check_amount = $row['check_amount'];
                $check_status = $row['check_status'];

                $action = '';
                if($check_status === 'Draft'){
                    $action .= '<button type="button" class="btn btn-icon btn-success transmit-disbursement-check" data-disbursement-check-id="'. $disbursement_check_id .'" title="Transmit Check">
                                        <i class="ti ti-check"></i>
                                    </button>';
                }

                if($check_status === 'Transmitted'){
                    $action .= '<button type="button" class="btn btn-icon btn-success outstanding-disbursement-check" data-disbursement-check-id="'. $disbursement_check_id .'" title="Outstanding Check">
                                        <i class="ti ti-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-success outstanding-disbursement-pdc" data-disbursement-check-id="'. $disbursement_check_id .'" title="Outstanding PDC">
                                        <i class="ti ti-check"></i>
                                    </button>';
                }

                if($check_status === 'Outstanding'){
                    $action .= '<button type="button" class="btn btn-icon btn-success" data-bs-toggle="offcanvas" data-bs-target="#negotiated-disbursement-check-offcanvas" aria-controls="negotiated-disbursement-check-offcanvas" data-disbursement-check-id="'. $disbursement_check_id .'" title="Negotiated Check">
                                        <i class="ti ti-check"></i>
                                    </button>';
                }

                if($check_status === 'Outstanding' || $check_status === 'Draft' || $check_status === 'Transmitted'){
                    $action .= '<button type="button" class="btn btn-icon btn-danger cancel-disbursement-check" data-bs-toggle="offcanvas" data-bs-target="#cancel-disbursement-check-offcanvas" aria-controls="cancel-disbursement-check-offcanvas" data-disbursement-check-id="'. $disbursement_check_id .'" title="Cancel Check">
                                        <i class="ti ti-x"></i>
                                    </button>';
                }

                if($disburse_status === 'Posted' && $check_status === 'Draft'){
                    $action .= '<a href="print-disbursement-check.php?id='. $disbursement_check_id .'" class="btn btn-icon btn-warning" title="Print Check" target="_blank">
                                        <i class="ti ti-printer"></i>
                                    </a>';
                }

                if($disburse_status === 'Draft' && $check_status === 'Draft'){
                    $action .= '<button type="button" class="btn btn-icon btn-success update-disbursement-check" data-bs-toggle="offcanvas" data-bs-target="#check-offcanvas" aria-controls="check-offcanvas" data-disbursement-check-id="'. $disbursement_check_id .'" title="Update Check">
                                        <i class="ti ti-edit"></i>
                                    </button>';
                }

                if($disburse_status === 'Draft' && $check_status === 'Draft'){
                    $action .= '<button type="button" class="btn btn-icon btn-danger delete-disbursement-check" data-disbursement-check-id="'. $disbursement_check_id .'" title="Delete Check">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                if($check_status === 'Draft'){
                    $check_status = '<span class="badge bg-secondary">' . $check_status . '</span>';
                }
                else if($check_status === 'Transmitted'){
                    $check_status = '<span class="badge bg-warning">Unreleased</span>';
                }
                else if($check_status === 'Outstanding'){
                    $check_status = '<span class="badge bg-info">Outstanding Check</span>';
                }
                else if($check_status === 'Outstanding PDC'){
                    $check_status = '<span class="badge bg-info">' . $check_status . '</span>';
                }
                else if($check_status === 'Negotiated'){
                    $check_status = '<span class="badge bg-success">' . $check_status . '</span>';
                }
                else{
                    $check_status = '<span class="badge bg-danger">' . $check_status . '</span>';
                }

                $disbursement_check_id_enc = $securityModel->encryptData($disbursement_check_id);

                $response[] = [
                    'BANK_BRANCH' => $bank_branch,
                    'CHECK_NAME' => $check_name,
                    'CHECK_DATE' => $check_date,
                    'CHECK_NUMBER' => $check_number,
                    'CHECK_AMOUNT' => number_format($check_amount, 2),
                    'CHECK_STATUS' => $check_status,
                    'REVERSAL_DATE' => $reversal_date,
                    'TRANSMITTED_DATE' => $transmitted_date,
                    'OUTSTANDING_DATE' => $outstanding_date,
                    'NEGOTIATED_DATE' => $negotiated_date,
                    'ACTION' => '<div class="d-flex gap-2">'. 
                                    $action . 
                                '</div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>