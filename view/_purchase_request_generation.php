<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-model.php';
require_once '../model/parts-transaction-model.php';
require_once '../model/parts-subclass-model.php';
require_once '../model/parts-class-model.php';
require_once '../model/unit-model.php';
require_once '../model/product-model.php';
require_once '../model/miscellaneous-client-model.php';
require_once '../model/customer-model.php';
require_once '../model/back-job-monitoring-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/contractor-model.php';
require_once '../model/work-center-model.php';
require_once '../model/department-model.php';
require_once '../model/customer-model.php';
require_once '../model/company-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$companyModel = new CompanyModel($databaseModel);
$partsModel = new PartsModel($databaseModel);
$partsTransactionModel = new PartsTransactionModel($databaseModel);
$partsSubclassModel = new PartsSubclassModel($databaseModel);
$partsClassModel = new PartsClassModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$backjobMonitoringModel = new BackJobMonitoringModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$contractorModel = new ContractorModel($databaseModel);
$workCenterModel = new WorkCenterModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        
        case 'purchase request table':
            $filter_transaction_date_start_date = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filter_transaction_date_end_date = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $filter_approval_date_start_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_start_date'], '', 'Y-m-d', '');
            $filter_approval_date_end_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_end_date'], '', 'Y-m-d', '');
            $filter_request_status = $_POST['filter_request_status'];

            if (!empty($filter_request_status)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_request_status)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_request_status = implode(', ', $quoted_values_array);
            } else {
                $filter_request_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generatePurchaseRequestTable(:filter_transaction_date_start_date, :filter_transaction_date_end_date, :filter_approval_date_start_date, :filter_approval_date_end_date, :filter_request_status)');
            $sql->bindValue(':filter_transaction_date_start_date', $filter_transaction_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_transaction_date_end_date', $filter_transaction_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_start_date', $filter_approval_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_end_date', $filter_approval_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_request_status', $filter_request_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $purchase_request_id = $row['purchase_request_id'];
                $reference_no = $row['reference_no'];
                $purchase_request_status = $row['purchase_request_status'];
                $purchase_request_type = $row['purchase_request_type'];
                $company_id = $row['company_id'];

                $companyName = $companyModel->getCompany($company_id)['company_name'] ?? null;

                if($purchase_request_status === 'Draft'){
                    $purchase_request_status = '<span class="badge bg-secondary">' . $purchase_request_status . '</span>';
                }
                else if($purchase_request_status === 'Cancelled'){
                    $purchase_request_status = '<span class="badge bg-warning">' . $purchase_request_status . '</span>';
                }
                else if($purchase_request_status === 'For Approval'){
                    $purchase_request_status = '<span class="badge bg-info">For Approval</span>';
                }
                else if($purchase_request_status === 'Approved'){
                    $purchase_request_status = '<span class="badge bg-success">Approved</span>';
                }
                else{
                    $purchase_request_status = '<span class="badge bg-success">' . $purchase_request_status . '</span>';
                }

                $purchase_request_id_encrypted = $securityModel->encryptData($purchase_request_id);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $purchase_request_id .'">',
                    'REFERENCE_NO' => $reference_no,
                    'PURCHASE_REQUEST_TYPE' => $purchase_request_type,
                    'COMPANY' => $companyName,
                    'STATUS' => $purchase_request_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="purchase-request.php?id='. $purchase_request_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
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