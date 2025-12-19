<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-model.php';
require_once '../model/purchase-order-model.php';
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
$purchaseOrderModel = new PurchaseOrderModel($databaseModel);
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

            $sql = $databaseModel->getConnection()->prepare('CALL generatePurchaseOrderTable(:filter_transaction_date_start_date, :filter_transaction_date_end_date, :filter_approval_date_start_date, :filter_approval_date_end_date, :filter_request_status)');
            $sql->bindValue(':filter_transaction_date_start_date', $filter_transaction_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_transaction_date_end_date', $filter_transaction_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_start_date', $filter_approval_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_end_date', $filter_approval_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_request_status', $filter_request_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $purchase_order_id = $row['purchase_order_id'];
                $reference_no = $row['reference_no'];
                $purchase_order_status = $row['purchase_order_status'];
                $purchase_order_type = $row['purchase_order_type'];
                $company_id = $row['company_id'];

                $companyName = $companyModel->getCompany($company_id)['company_name'] ?? null;

                if($purchase_order_status === 'Draft'){
                    $purchase_order_status = '<span class="badge bg-secondary">' . $purchase_order_status . '</span>';
                }
                else if($purchase_order_status === 'Cancelled'){
                    $purchase_order_status = '<span class="badge bg-warning">' . $purchase_order_status . '</span>';
                }
                else if($purchase_order_status === 'For Approval'){
                    $purchase_order_status = '<span class="badge bg-info">For Approval</span>';
                }
                else if($purchase_order_status === 'Approved'){
                    $purchase_order_status = '<span class="badge bg-success">Approved</span>';
                }
                else{
                    $purchase_order_status = '<span class="badge bg-success">' . $purchase_order_status . '</span>';
                }

                $purchase_order_id_encrypted = $securityModel->encryptData($purchase_order_id);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $purchase_order_id .'">',
                    'REFERENCE_NO' => $reference_no,
                    'PURCHASE_REQUEST_TYPE' => $purchase_order_type,
                    'COMPANY' => $companyName,
                    'STATUS' => $purchase_order_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="purchase-order.php?id='. $purchase_order_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'purchase request item table':
            $purchase_order_id = $_POST['purchase_order_id'];

            $purchaseOrderDetails = $purchaseOrderModel->getPurchaseOrder($purchase_order_id);
            $purchase_order_status = $purchaseOrderDetails['purchase_order_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM purchase_order_cart WHERE purchase_order_id = :purchase_order_id');
            $sql->bindValue(':purchase_order_id', $purchase_order_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $purchase_order_cart_id = $row['purchase_order_cart_id'];
                $description = $row['description'];
                $quantity = $row['quantity'];
                $unit_id = $row['unit_id'];
                $short_name = $row['short_name'];
                $remarks = $row['remarks'];

                $action = '';
                if($purchase_order_status == 'Draft'){
                    $action = ' <button type="button" class="btn btn-icon btn-success update-part-cart" data-bs-toggle="offcanvas" data-bs-target="#add-item-offcanvas" aria-controls="add-item-offcanvas" data-purchase-order-cart-id="'. $purchase_order_cart_id .'" title="Update Part Item">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-danger delete-part-cart" data-purchase-order-cart-id="'. $purchase_order_cart_id .'" title="Delete Item">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $purchase_order_id_encrypted = $securityModel->encryptData($purchase_order_id);

                $response[] = [
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'DESCRIPTION' => $description,
                    'REMARKS' => $remarks,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>