<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/bank-model.php';
require_once '../model/currency-model.php';
require_once '../model/bank-account-type-model.php';
require_once '../model/brand-model.php';
require_once '../model/color-model.php';
require_once '../model/loan-type-model.php';
require_once '../model/cmap-report-type-model.php';
require_once '../model/asset-type-model.php';
require_once '../model/bank-adb-model.php';
require_once '../model/ci-report-model.php';
require_once '../model/ci-file-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$cityModel = new CityModel($databaseModel);
$stateModel = new StateModel($databaseModel);
$countryModel = new CountryModel($databaseModel);
$bankModel = new BankModel($databaseModel);
$currencyModel = new CurrencyModel($databaseModel);
$bankAccountTypeModel = new BankAccountTypeModel($databaseModel);
$brandModel = new BrandModel($databaseModel);
$colorModel = new ColorModel($databaseModel);
$loanTypeModel = new LoanTypeModel($databaseModel);
$cmapReportTypeModel = new CMAPReportTypeModel($databaseModel);
$assetTypeModel = new AssetTypeModel($databaseModel);
$ciReportModel = new CIReportModel($databaseModel);
$bankADBModel = new BankADBModel($databaseModel);
$ciFileTypeModel = new CIFileTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'ci report table':
                $filter_ci_report_status = $_POST['filter_ci_report_status'];
                $filter_created_date_start_date = $systemModel->checkDate('empty', $_POST['filter_created_date_start_date'], '', 'Y-m-d', '');
                $filter_created_date_end_date = $systemModel->checkDate('empty', $_POST['filter_created_date_end_date'], '', 'Y-m-d', '');
                $filter_completed_date_start_date = $systemModel->checkDate('empty', $_POST['filter_completed_date_start_date'], '', 'Y-m-d', '');
                $filter_completed_date_end_date = $systemModel->checkDate('empty', $_POST['filter_completed_date_end_date'], '', 'Y-m-d', '');

                if (!empty($filter_ci_report_status)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_ci_report_status)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_ci_report_status = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_ci_report_status = null;
                }

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportTable(:filter_ci_report_status, :filter_created_date_start_date, :filter_created_date_end_date, :filter_completed_date_start_date, :filter_completed_date_end_date)');              
                $sql->bindValue(':filter_ci_report_status', $filter_ci_report_status, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_start_date',  $filter_created_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_end_date',  $filter_created_date_end_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_completed_date_start_date',  $filter_completed_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_completed_date_end_date',  $filter_completed_date_end_date, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_id = $row['ci_report_id'];
                    $sales_proposal_id = $row['sales_proposal_id'];
                    $contact_id = $row['contact_id'];
                    $appraiser = $row['appraiser'];
                    $investigator = $row['investigator'];
                    $ci_status = $row['ci_status'];
                    $created_date = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');
                    $completed_date = $systemModel->checkDate('summary', $row['completed_date'], '', 'm/d/Y h:i:s A', '');

                    $ci_report_id_encrypted = $securityModel->encryptData($ci_report_id);

                    $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                    $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                    $customerDetails = $customerModel->getPersonalInformation($contact_id);
                    $customerName = $customerDetails['file_as'] ?? null;
                    $corporateName = $customerDetails['corporate_name'] ?? null;

                    $investigatorDetails = $userModel->getUserByID($investigator);
                    $investigator = $investigatorDetails['file_as'] ?? null;

                    $appraiserDetails = $userModel->getUserByID($appraiser);
                    $appraiser = $appraiserDetails['file_as'] ?? null;

                    if($ci_status === 'Draft'){
                        $ci_status = '<span class="badge bg-secondary">' . $ci_status . '</span>';
                    }
                    else if($ci_status === 'For Completion'){
                        $ci_status = '<span class="badge bg-info">' . $ci_status . '</span>';
                    }
                    else{
                        $ci_status = '<span class="badge bg-success">' . $ci_status . '</span>';
                    }

                    $response[] = [
                        'CUSTOMER' => '<a href="ci-report.php?id='. $ci_report_id_encrypted .'" target="_blank"><div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div>
                                        </a>',
                        'SALES_PROPOSAL' => '<a href="ci-report.php?id='. $ci_report_id_encrypted .'" target="_blank">
                                                        '. $sales_proposal_number .'
                                                    </a>',
                        'APPRAISER' => $appraiser,
                        'INVESTIGATOR' => $investigator,
                        'STATUS' => $ci_status,
                        'DATE_STARTED' => $created_date,
                        'RELEASED_DATE' => $completed_date,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="ci-report.php?id='. $ci_report_id_encrypted .'" class="btn btn-icon btn-primary" target="_blank" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'sales proposal ci report table':
                $sales_proposal_id = $_POST['sales_proposal_id'];
                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);


                $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report WHERE sales_proposal_id = :sales_proposal_id');              
                $sql->bindValue(':sales_proposal_id', $sales_proposal_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_id = $row['ci_report_id'];
                    $sales_proposal_id = $row['sales_proposal_id'];
                    $contact_id = $row['contact_id'];
                    $appraiser = $row['appraiser'];
                    $investigator = $row['investigator'];
                    $ci_status = $row['ci_status'];
                    $created_date = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');
                    $completed_date = $systemModel->checkDate('summary', $row['completed_date'], '', 'm/d/Y h:i:s A', '');

                    $ci_report_id_encrypted = $securityModel->encryptData($ci_report_id);

                    $customerDetails = $customerModel->getPersonalInformation($contact_id);
                    $customerName = $customerDetails['file_as'] ?? null;
                    $corporateName = $customerDetails['corporate_name'] ?? null;

                    $investigatorDetails = $userModel->getUserByID($investigator);
                    $investigator = $investigatorDetails['file_as'] ?? null;

                    $appraiserDetails = $userModel->getUserByID($appraiser);
                    $appraiser = $appraiserDetails['file_as'] ?? null;

                    if($ci_status === 'Draft'){
                        $ci_status_badge = '<span class="badge bg-secondary">' . $ci_status . '</span>';
                    }
                    else if($ci_status === 'For Completion'){
                        $ci_status_badge = '<span class="badge bg-info">' . $ci_status . '</span>';
                    }
                    else{
                        $ci_status_badge = '<span class="badge bg-success">' . $ci_status . '</span>';
                    }

                    $loan_proposal = '';
                    if($ci_status == 'Completed'){
                        $loan_proposal = '<a href="loan-proposal.php?id='. $ci_report_id .'" class="btn btn-icon btn-success" target="_blank" title="View Loan Proposal">
                                            <i class="ti ti-file"></i>
                                        </a>';
                    }

                    $response[] = [
                        'CUSTOMER' => '<a href="ci-report.php?id='. $ci_report_id_encrypted .'" target="_blank"><div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div>
                                        </a>',
                        'APPRAISER' => $appraiser,
                        'INVESTIGATOR' => $investigator,
                        'STATUS' => $ci_status_badge,
                        'DATE_STARTED' => $created_date,
                        'RELEASED_DATE' => $completed_date,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="ci-report.php?id='. $ci_report_id_encrypted .'" class="btn btn-icon btn-primary" target="_blank" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        '. $loan_proposal .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report residence table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportResidenceTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_residence_id = $row['ci_report_residence_id'];
                    $address = $row['address'];
                    $city_id = $row['city_id'];

                    $cityDetails = $cityModel->getCity($city_id);
                    $cityName = $cityDetails['city_name'];
                    $stateID = $cityDetails['state_id'];
    
                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'];
                    $countryID = $stateDetails['country_id'];
    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];

                    $contactAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-residence" data-ci-report-residence-id="'. $ci_report_residence_id .'" title="Delete Residence">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $response[] = [
                        'ADDRESS' => $contactAddress,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-residence" data-bs-toggle="modal" data-bs-target="#ci-residence-modal" data-ci-report-residence-id="'. $ci_report_residence_id .'" title="Update Residence">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report dependents table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportDependentsTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_dependents_id = $row['ci_report_dependents_id'];
                    $name = $row['name'];
                    $age = $row['age'];
                    $school = $row['school'];
                    $employment = $row['employment'];
                    $remarks = $row['remarks'];

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-dependents" data-ci-report-dependents-id="'. $ci_report_dependents_id .'" title="Delete Residence">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'NAME' => $name,
                        'AGE' => $age,
                        'SCHOOL' => $school,
                        'EMPLOYMENT' => $employment,
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-dependents" data-bs-toggle="modal" data-bs-target="#ci-dependents-modal" data-ci-report-dependents-id="'. $ci_report_dependents_id .'" title="Update Dependent">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report business table':
                $ci_report_id = $_POST['ci_report_id'];
                
                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';
                
                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportBusinessTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_business_id = $row['ci_report_business_id'];
                    $business_name = $row['business_name'];
                    $description = $row['description'];
                    $address = $row['address'];
                    $city_id = $row['city_id'];

                    $cityDetails = $cityModel->getCity($city_id);
                    $cityName = $cityDetails['city_name'];
                    $stateID = $cityDetails['state_id'];
    
                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'];
                    $countryID = $stateDetails['country_id'];
    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];

                    $contactAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-business" data-ci-report-business-id="'. $ci_report_business_id .'" title="Delete Residence">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'BUSINESS_NAME' => '<div class="col">
                                            <h6 class="mb-0">'. $business_name .'</h6>
                                            <p class="f-12 mb-0">'. $description .'</p>
                                        </div>',
                        'ADDRESS' => $contactAddress,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-business" data-bs-toggle="modal" data-bs-target="#ci-business-modal" data-ci-report-business-id="'. $ci_report_business_id .'" title="Update Business">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report employment table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportEmploymentTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_employment_id = $row['ci_report_employment_id'];
                    $employment_name = $row['employment_name'];
                    $description = $row['description'];
                    $address = $row['address'];
                    $city_id = $row['city_id'];
                    $department = $row['department'];
                    $rank = $row['rank'];
                    $position = $row['position'];

                    $cityDetails = $cityModel->getCity($city_id);
                    $cityName = $cityDetails['city_name'];
                    $stateID = $cityDetails['state_id'];
    
                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'];
                    $countryID = $stateDetails['country_id'];
    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];

                    $contactAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-employment" data-ci-report-employment-id="'. $ci_report_employment_id .'" title="Delete Residence">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'EMPLOYMENT_NAME' => '<div class="col">
                                            <h6 class="mb-0">'. $employment_name .'</h6>
                                            <p class="f-12 mb-0">'. $description .'</p>
                                        </div>',
                        'ADDRESS' => $contactAddress,
                        'DEPARTMENT' => $department,
                        'RANK' => $rank,
                        'POSITION' => $position,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-employment" data-bs-toggle="modal" data-bs-target="#ci-employment-modal" data-ci-report-employment-id="'. $ci_report_employment_id .'" title="Update Employment">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report bank table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportBankTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_bank_id = $row['ci_report_bank_id'];
                    $bank_id = $row['bank_id'];
                    $bank_account_type_id = $row['bank_account_type_id'];
                    $account_name = $row['account_name'];
                    $account_number = $row['account_number'];
                    $currency_id = $row['currency_id'];                    
                    $date_open = $systemModel->checkDate('summary', $row['date_open'], '', 'm/d/Y', '');
                    $bank_adb_id = $row['bank_adb_id'];

                    $bankAccountTypeName = $bankAccountTypeModel->getBankAccountType($bank_account_type_id)['bank_account_type_name'] ?? null;
                    $currencyName = $currencyModel->getCurrency($currency_id)['currency_name'] ?? null;

                    $bankADBName = $bankADBModel->getBankADB($bank_adb_id)['bank_adb_name'] ?? null;

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-bank" data-ci-report-bank-id="'. $ci_report_bank_id .'" title="Delete Bank">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $average = $ciReportModel->getBankDepositAverage($ci_report_bank_id)['total'] ?? 0;

                    $response[] = [
                        'BANK' => $bank_id,
                        'BANK_ACCOUNT_TYPE' => $bankAccountTypeName,
                        'ACCOUNT_NAME' => $account_name,
                        'ACCOUNT_NUMBER' => $account_number,
                        'CURRENCY' => $currencyName,
                        'DATE_OPEN' => $date_open,
                        'ADB' => $bankADBName,
                        'AVERAGE' => number_format($average, 2) . ' PHP',
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-bank" data-bs-toggle="modal" data-bs-target="#ci-bank-modal" data-ci-report-bank-id="'. $ci_report_bank_id .'" title="Update Bank">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report bank deposits table':
                $ci_report_bank_id = $_POST['ci_report_bank_id'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportBankDepositTable(:ci_report_bank_id)');
                $sql->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_bank_deposits_id = $row['ci_report_bank_deposits_id'];         
                    $ci_report_id = $row['ci_report_id'];         
                    
                    $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                    $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';
                    
                    $deposit_month = $systemModel->checkDate('summary', $row['deposit_month'], '', 'm/d/Y', '');
                    $amount = $row['amount'];
                    $remarks = $row['remarks'];

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-bank-deposits" data-ci-report-bank-deposits-id="'. $ci_report_bank_deposits_id .'" title="Delete Deposit">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'DEPOSIT_MONTH' => $deposit_month,
                        'AMOUNT' => number_format($amount, 2) . ' PHP',
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-bank-deposits" data-bs-toggle="modal" data-bs-target="#ci-bank-deposits-modal" data-ci-report-bank-deposits-id="'. $ci_report_bank_deposits_id .'" title="Update Deposit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report appraisal source table':
                $ci_report_collateral_id = $_POST['ci_report_collateral_id'];
                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportAppraisalSourceTable(:ci_report_collateral_id)');
                $sql->bindValue(':ci_report_collateral_id', $ci_report_collateral_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_appraisal_source_id = $row['ci_report_appraisal_source_id'];
                    $source = $row['source'];
                    $amount = $row['amount'];
                    $remarks = $row['remarks'];

                    $response[] = [
                        'SOURCE' => $source,
                        'AMOUNT' => number_format($amount, 2) . ' PHP',
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-appraisal-source" data-bs-toggle="modal" data-bs-target="#ci-appraisal-source-modal" data-ci-report-appraisal-source-id="'. $ci_report_appraisal_source_id .'" title="Update Deposit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-danger delete-ci-report-appraisal-source" data-ci-report-appraisal-source-id="'. $ci_report_appraisal_source_id .'" title="Delete Deposit">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report loan table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportLoanTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_loan_id = $row['ci_report_loan_id'];
                    $company = $row['company'];
                    $loan_source = $row['loan_source'];
                    $informant = $row['informant'];
                    $account_name = $row['account_name'];
                    $loan_type_id = $row['loan_type_id'];             
                    $availed_date = $systemModel->checkDate('summary', $row['availed_date'], '', 'm/d/Y', '');
                    $maturity_date = $systemModel->checkDate('summary', $row['maturity_date'], '', 'm/d/Y', '');
                    $term = $row['term'];
                    $pn_amount = $row['pn_amount'];
                    $outstanding_balance = $row['outstanding_balance'];
                    $repayment = $row['repayment'];
                    $handling = $row['handling'];
                    $remarks = $row['remarks'];

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-loan" data-ci-report-loan-id="'. $ci_report_loan_id .'" title="Delete Loan">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $loan_type_name = $loanTypeModel->getLoanType($loan_type_id)['loan_type_name'] ?? null;

                    $response[] = [
                        'COMPANY' => $company,
                        'LOAN_SOURCE' => $loan_source,
                        'INFORMANT' => $informant,
                        'ACCOUNT_NAME' => $account_name,
                        'LOAN_TYPE' => $loan_type_name,
                        'AVAILED_DATE' => $availed_date,
                        'MATURITY_DATE' => $maturity_date,
                        'TERM' => $term,
                        'PN_AMOUNT' => number_format($pn_amount, 2) . ' PHP',
                        'OUTSTANDING_BALANCE' => number_format($outstanding_balance, 2) . ' PHP',
                        'REPAYMENT' => number_format($repayment, 2) . ' PHP',
                        'HANDLING' => $handling,
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-loan" data-bs-toggle="modal" data-bs-target="#ci-loan-modal" data-ci-report-loan-id="'. $ci_report_loan_id .'" title="Update Loan">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report asset table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportAssetTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_asset_id = $row['ci_report_asset_id'];
                    $asset_type_id = $row['asset_type_id'];
                    $description = $row['description'];
                    $value = $row['value'];
                    $remarks = $row['remarks'];

                    $asset_type_name = $assetTypeModel->getAssetType($asset_type_id)['asset_type_name'] ?? null;

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-asset" data-ci-report-asset-id="'. $ci_report_asset_id .'" title="Delete Asset">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'ASSET_TYPE' => $asset_type_name,
                        'DESCRIPTION' => $description,
                        'VALUE' => number_format($value, 2) . ' PHP',
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-asset" data-bs-toggle="modal" data-bs-target="#ci-asset-modal" data-ci-report-asset-id="'. $ci_report_asset_id .'" title="Update Asset">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report trade reference table':
                $ci_report_id = $_POST['ci_report_id'];
                $ci_report_business_id = $_POST['ci_report_business_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportTradeReferenceTable(:ci_report_business_id)');
                $sql->bindValue(':ci_report_business_id', $ci_report_business_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_trade_reference_id = $row['ci_report_trade_reference_id'];
                    $supplier = $row['supplier'];
                    $contact_person = $row['contact_person'];
                    $years_of_transaction = $row['years_of_transaction'];
                    $remarks = $row['remarks'];

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-trade-reference" data-ci-report-trade-reference-id="'. $ci_report_trade_reference_id .'" title="Delete Trade Reference">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'SUPPLIER' => $supplier,
                        'CONTACT_PERSON' => $contact_person,
                        'YEARS_OF_TRANSACTION' => $years_of_transaction,
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-trade-reference" data-bs-toggle="modal" data-bs-target="#ci-trade-reference-modal" data-ci-report-trade-reference-id="'. $ci_report_trade_reference_id .'" title="Update Trade Reference">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report cmap table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';
                
                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportCMAPTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_cmap_id = $row['ci_report_cmap_id'];
                    $cmap_report_type_id = $row['cmap_report_type_id'];
                    $defendants = $row['defendants'];
                    $plaintiff = $row['plaintiff'];
                    $nature_of_case = $row['nature_of_case'];
                    $trial_court = $row['trial_court'];
                    $sala_no = $row['sala_no'];
                    $case_no = $row['case_no'];
                    $reported_date = $systemModel->checkDate('summary', $row['reported_date'], '', 'm/d/Y', '');
                    $remarks = $row['remarks'];

                    $cmap_report_type_name = $cmapReportTypeModel->getCMAPReportType($cmap_report_type_id)['cmap_report_type_name'] ?? null;

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-cmap" data-ci-report-cmap-id="'. $ci_report_cmap_id .'" title="Delete CMAP">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'REPORT_TYPE' => $cmap_report_type_name,
                        'DEFENDANTS' => $defendants,
                        'PLAINTIFF' => $plaintiff,
                        'NATURE_OF_CASE' => $nature_of_case,
                        'TRIAL_COURT' => $trial_court,
                        'SALA_NO' => $sala_no,
                        'CASE_NO' => $case_no,
                        'REPORT_DATE' => $reported_date,
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-cmap" data-bs-toggle="modal" data-bs-target="#ci-cmap-modal" data-ci-report-cmap-id="'. $ci_report_cmap_id .'" title="Update CMAP">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report collateral table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportCollateralTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_collateral_id = $row['ci_report_collateral_id'];
                    $appraisal_date = $systemModel->checkDate('summary', $row['appraisal_date'], '', 'm/d/Y', '');
                    $brand_id = $row['brand_id'];
                    $description = $row['description'];
                    $color_id = $row['color_id'];
                    $year_model = $row['year_model'];
                    $plate_no = $row['plate_no'];
                    $motor_no = $row['motor_no'];
                    $serial_no = $row['serial_no'];
                    $mvr_file_no = $row['mvr_file_no'];
                    $cr_no = $row['cr_no'];
                    $or_no = $row['or_no'];
                    $registered_owner = $row['registered_owner'];
                    $appraised_value = $row['appraised_value'];
                    $loannable_value = $row['loannable_value'];
                    $remarks = $row['remarks'];

                    $brand_name = $brandModel->getBrand($brand_id)['brand_id'] ?? null;
                    $color_name = $colorModel->getColor($color_id)['color_name'] ?? null;

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-collateral" data-ci-report-collateral-id="'. $ci_report_collateral_id .'" title="Delete Collateral">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'APPRAISAL_DATE' => $appraisal_date,
                        'BRAND' => $brand_name,
                        'LOANNABLE_VALUE' => number_format($loannable_value, 2) . ' PHP',
                        'APPRAISED_VALUE' => number_format($appraised_value, 2) . ' PHP',
                        'COLOR' => $color_name,
                        'YEAR_MODEL' => $year_model,
                        'PLATE_NO' => $plate_no,
                        'MOTOR_NO' => $motor_no,
                        'SERIAL_NO' => $serial_no,
                        'MVR_FILE_NO' => $mvr_file_no,
                        'CR_NO' => $cr_no,
                        'OR_NO' => $or_no,
                        'REGISTERED_OWNER' => $registered_owner,
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-ci-report-collateral" data-bs-toggle="modal" data-bs-target="#ci-collateral-modal" data-ci-report-collateral-id="'. $ci_report_collateral_id .'" title="Update Collateral">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'ci report files table':
                $ci_report_id = $_POST['ci_report_id'];

                $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
                $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';

                $sql = $databaseModel->getConnection()->prepare('CALL generateCIReportFileTable(:ci_report_id)');
                $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $ci_report_files_id = $row['ci_report_files_id'];
                    $file_name = $row['file_name'];
                    $ci_file_type_id = $row['ci_file_type_id'];
                    $remarks = $row['remarks'];

                    $ci_file_type = $ciFileTypeModel->getCIFileType($ci_file_type_id)['ci_file_type_name'] ?? null;

                    
                    $file = '<a href="'. $systemModel->checkImage($row['file_path'], 'default') .'" target="_blank">'. $ci_file_type .'</a>';

                    $delete = '';
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-ci-report-file" data-ci-report-files-id="'. $ci_report_files_id .'" title="Delete File">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CI_FILE_TYPE' => $file,
                        'REMARKS' => $remarks,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;

        case 'customer ci report summary':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactCIReportSummary(:customerID)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $ci_report_id = $row['ci_report_id'];
                    $sales_proposal_id = $row['sales_proposal_id'];
                    $ci_status = $row['ci_status'];
                    $created_date = $systemModel->checkDate('empty', $row['created_date'], '', 'F d, Y', '');
                    $completed_date = $systemModel->checkDate('empty', $row['completed_date'], '', 'F d, Y', '');

                    $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                    $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                    $ci_report_id_encrypted = $securityModel->encryptData($ci_report_id);

                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    if($ci_status === 'Draft'){
                        $ci_status = '<span class="badge bg-secondary">' . $ci_status . '</span>';
                    }
                    else if($ci_status === 'For Completion'){
                        $ci_status = '<span class="badge bg-info">' . $ci_status . '</span>';
                    }
                    else{
                        $ci_status = '<span class="badge bg-success">' . $ci_status . '</span>';
                    }

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <a href="ci-report.php?&id='. $ci_report_id_encrypted .'" target="_blank">
                                                <p class="mb-1">Sales Proposal Number: '. $sales_proposal_number .'</p>
                                                <p class="mb-1">Created Date: ' . $created_date . '</p>
                                                <p class="mb-3">Completed Date: ' . $completed_date . '</p>
                                                <p class="mb-0">' . $ci_status . '</p>
                                            </a>
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No CI report found.';
                }

                $response[] = [
                    'ciReportSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>