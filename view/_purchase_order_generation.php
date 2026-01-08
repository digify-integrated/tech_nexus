<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-model.php';
require_once '../model/purchase-order-model.php';
require_once '../model/purchase-request-model.php';
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
require_once '../model/body-type-model.php';
require_once '../model/color-model.php';
require_once '../model/supplier-model.php';
require_once '../model/brand-model.php';
require_once '../model/cabin-model.php';
require_once '../model/model-model.php';
require_once '../model/make-model.php';
require_once '../model/class-model.php';
require_once '../model/product-subcategory-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$companyModel = new CompanyModel($databaseModel);
$partsModel = new PartsModel($databaseModel);
$purchaseOrderModel = new PurchaseOrderModel($databaseModel);
$purchaseRequestModel = new PurchaseRequestModel($databaseModel);
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
$bodyTypeModel = new BodyTypeModel($databaseModel);
$colorModel = new ColorModel($databaseModel);
$supplierModel = new SupplierModel($databaseModel);
$brandModel = new BrandModel($databaseModel);
$cabinModel = new CabinModel($databaseModel);
$modelModel = new ModelModel($databaseModel);
$makeModel = new MakeModel($databaseModel);
$classModel = new ClassModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'purchase order table':
            $filter_transaction_date_start_date = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filter_transaction_date_end_date = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $filter_approval_date_start_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_start_date'], '', 'Y-m-d', '');
            $filter_approval_date_end_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_end_date'], '', 'Y-m-d', '');
            $filter_onprocess_date_start_date = $systemModel->checkDate('empty', $_POST['filter_onprocess_date_start_date'], '', 'Y-m-d', '');
            $filter_onprocess_date_end_date = $systemModel->checkDate('empty', $_POST['filter_onprocess_date_end_date'], '', 'Y-m-d', '');
            $filter_completion_date_start_date = $systemModel->checkDate('empty', $_POST['filter_completion_date_start_date'], '', 'Y-m-d', '');
            $filter_completion_date_end_date = $systemModel->checkDate('empty', $_POST['filter_completion_date_end_date'], '', 'Y-m-d', '');
            $filter_order_status = $_POST['filter_order_status'];

            if (!empty($filter_order_status)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_order_status)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_order_status = implode(', ', $quoted_values_array);
            } else {
                $filter_order_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generatePurchaseOrderTable(:filter_transaction_date_start_date, :filter_transaction_date_end_date, :filter_approval_date_start_date, :filter_approval_date_end_date, :filter_onprocess_date_start_date, :filter_onprocess_date_end_date, :filter_completion_date_start_date, :filter_completion_date_end_date, :filter_order_status)');
            $sql->bindValue(':filter_transaction_date_start_date', $filter_transaction_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_transaction_date_end_date', $filter_transaction_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_start_date', $filter_approval_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_end_date', $filter_approval_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_onprocess_date_start_date', $filter_onprocess_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_onprocess_date_end_date', $filter_onprocess_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_completion_date_start_date', $filter_completion_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_completion_date_end_date', $filter_completion_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_order_status', $filter_order_status, PDO::PARAM_STR);
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
        case 'purchase order item unit table':
            $purchase_order_id = $_POST['purchase_order_id'];

            $purchaseOrderDetails = $purchaseOrderModel->getPurchaseOrder($purchase_order_id);
            $purchase_order_status = $purchaseOrderDetails['purchase_order_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM purchase_order_unit WHERE purchase_order_id = :purchase_order_id');
            $sql->bindValue(':purchase_order_id', $purchase_order_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $purchase_order_unit_id = $row['purchase_order_unit_id'];
                $purchase_request_cart_id = $row['purchase_request_cart_id'];
                $quantity = $row['quantity'];
                $actual_quantity = $row['actual_quantity'];
                $cancelled_quantity = $row['cancelled_quantity'];
                $unit_id = $row['unit_id'];
                $brand_id = $row['brand_id'];
                $model_id = $row['model_id'];
                $body_type_id = $row['body_type_id'];
                $class_id = $row['class_id'];
                $color_id = $row['color_id'];
                $make_id = $row['make_id'];
                $year_model = $row['year_model'];
                $product_category_id = $row['product_category_id'];
                $length = $row['length'];
                $length_unit = $row['length_unit'];
                $cabin_id = $row['cabin_id'];
                $price = $row['price'];
                $remarks = $row['remarks'];
                $remaining_quantity = $quantity - ($actual_quantity + $cancelled_quantity);

                $descriptionRequest = $purchaseRequestModel->getPurchaseRequestCart($purchase_request_cart_id)['description'] ?? '';
                $bodyTypeName = $brandModel->getBrand($body_type_id)['body_type_name'] ?? '';
                $brandName = $brandModel->getBrand($brand_id)['brand_name'] ?? '';
                $modelName = $modelModel->getModel($model_id)['model_name'] ?? '';
                $class_name = $classModel->getClass($class_id)['class_name'] ?? '';
                $colorName = $colorModel->getColor($color_id)['color_name'] ?? '';
                $makeName = $makeModel->getMake($make_id)['make_name'] ?? '';
                $cabinName = $cabinModel->getCabin($cabin_id)['cabin_name'] ?? '';
                $short_name = $unitModel->getUnit($unit_id)['short_name'] ?? '';
                $length_unit_short_name = $unitModel->getUnit($length_unit)['short_name'] ?? '';
                $productSubcategoryName = $productSubcategoryModel->getProductSubcategory($product_category_id)['product_subcategory_name'] ?? '';

                $unit = '';
                if(!empty($productSubcategoryName)){
                    $unit .= 'Product Category: ' . $productSubcategoryName. '<br/>';
                }
                if(!empty($brandName)){
                    $unit .= 'Brand: ' . $brandName . '<br/>';
                }
                if(!empty($year_model)){
                    $unit .= 'Year Model: ' . $year_model . '<br/>';
                }
                if(!empty($modelName)){
                    $unit .= 'Model: ' . $modelName . '<br/>';
                }
                if(!empty($class_name)){
                    $unit .= 'Class: ' . $class_name . '<br/>';
                }
                if(!empty($colorName)){
                    $unit .= 'Color: ' . $colorName . '<br/>';
                }
                if(!empty($bodyTypeName)){
                    $unit .= 'Body Type: ' . $bodyTypeName . '<br/>';
                }
                if(!empty($makeName)){
                    $unit .= 'Make: ' . $makeName . '<br/>';
                }
                if(!empty($cabinName)){
                    $unit .= 'Cabin: ' . $cabinName . '<br/>';
                }
                if(!empty($length)){
                    $unit .= 'Length: ' . $length . ' ' . $length_unit_short_name . '<br/>';
                }

                $action = '';
                if($purchase_order_status == 'Draft'){
                    $action = ' <button type="button" class="btn btn-icon btn-success update-part-cart-unit" data-bs-toggle="offcanvas" data-bs-target="#add-item-unit-offcanvas" aria-controls="add-item-unit-offcanvas" data-purchase-order-unit-id="'. $purchase_order_unit_id .'" title="Update Part Item">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger delete-part-cart-unit" data-purchase-order-unit-id="'. $purchase_order_unit_id .'" title="Delete Item">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                if($purchase_order_status == 'On-Process' && $remaining_quantity > 0){
                    $action .= ' <button type="button" class="btn btn-icon btn-info receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#receive-item-offcanvas" aria-controls="receive-item-offcanvas" data-type="unit" data-purchase-order-cart-id="'. $purchase_order_unit_id .'" title="Receive Item">
                                            <i class="ti ti-arrow-bar-to-down"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-warning cancel-receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#cancel-receive-item-offcanvas" data-type="unit" aria-controls="cancel-receive-item-offcanvas" data-purchase-order-cart-id="'. $purchase_order_unit_id .'" title="Cancel Remaining Quantity">
                                            <i class="ti ti-arrow-forward"></i>
                                        </button>';
                    
                }

                $purchase_order_id_encrypted = $securityModel->encryptData($purchase_order_id);

                $response[] = [
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'ACTUAL_QUANTITY' => number_format($actual_quantity, 2) . ' ' . $short_name,
                    'CANCELLED_QUANTITY' => number_format($cancelled_quantity, 2) . ' ' . $short_name,
                    'PRICE' => number_format($price, 2) . ' PHP',
                    'REQUEST' => $descriptionRequest,
                    'UNIT' => $unit,
                    'REMARKS' => $remarks,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'purchase order item part table':
            $purchase_order_id = $_POST['purchase_order_id'];

            $purchaseOrderDetails = $purchaseOrderModel->getPurchaseOrder($purchase_order_id);
            $purchase_order_status = $purchaseOrderDetails['purchase_order_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM purchase_order_part WHERE purchase_order_id = :purchase_order_id');
            $sql->bindValue(':purchase_order_id', $purchase_order_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $purchase_order_part_id = $row['purchase_order_part_id'];
                $purchase_request_cart_id = $row['purchase_request_cart_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $actual_quantity = $row['actual_quantity'];
                $cancelled_quantity = $row['cancelled_quantity'];
                $unit_id = $row['unit_id'];
                $short_name = $unitModel->getUnit($unit_id)['short_name'] ?? '';
                $remarks = $row['remarks'];
                $price = $row['price'];
                $remaining_quantity = $quantity - ($actual_quantity + $cancelled_quantity);

                $descriptionRequest = $purchaseRequestModel->getPurchaseRequestCart($purchase_request_cart_id)['description'] ?? '';

                $partsDetails = $partsModel->getParts($part_id);
                $description = $partsDetails['description'];

                $action = '';
                if($purchase_order_status == 'Draft'){
                    $action = ' <button type="button" class="btn btn-icon btn-success update-part-cart-part" data-bs-toggle="offcanvas" data-bs-target="#add-item-part-offcanvas" aria-controls="add-item-part-offcanvas" data-purchase-order-part-id="'. $purchase_order_part_id .'" title="Update Part Item">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger delete-part-cart-part" data-purchase-order-part-id="'. $purchase_order_part_id .'" title="Delete Item">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                if($purchase_order_status == 'On-Process' && $remaining_quantity > 0){
                    $action .= ' <button type="button" class="btn btn-icon btn-info receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#receive-item-offcanvas" aria-controls="receive-item-offcanvas" data-type="part" data-purchase-order-cart-id="'. $purchase_order_part_id .'" title="Receive Item">
                                            <i class="ti ti-arrow-bar-to-down"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-warning cancel-receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#cancel-receive-item-offcanvas" data-type="part" aria-controls="cancel-receive-item-offcanvas" data-purchase-order-cart-id="'. $purchase_order_part_id .'" title="Cancel Remaining Quantity">
                                            <i class="ti ti-arrow-forward"></i>
                                        </button>';
                    
                }

                $purchase_order_id_encrypted = $securityModel->encryptData($purchase_order_id);

                $response[] = [
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'ACTUAL_QUANTITY' => number_format($actual_quantity, 2) . ' ' . $short_name,
                    'CANCELLED_QUANTITY' => number_format($cancelled_quantity, 2) . ' ' . $short_name,
                    'PRICE' => number_format($price, 2) . ' PHP',
                    'REQUEST' => $descriptionRequest,
                    'PART' => $description,
                    'REMARKS' => $remarks,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'purchase order item supply table':
            $purchase_order_id = $_POST['purchase_order_id'];

            $purchaseOrderDetails = $purchaseOrderModel->getPurchaseOrder($purchase_order_id);
            $purchase_order_status = $purchaseOrderDetails['purchase_order_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM purchase_order_supply WHERE purchase_order_id = :purchase_order_id');
            $sql->bindValue(':purchase_order_id', $purchase_order_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $purchase_order_supply_id = $row['purchase_order_supply_id'];
                $purchase_request_cart_id = $row['purchase_request_cart_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $actual_quantity = $row['actual_quantity'];
                $cancelled_quantity = $row['cancelled_quantity'];
                $unit_id = $row['unit_id'];
                $short_name = $unitModel->getUnit($unit_id)['short_name'] ?? '';
                $remarks = $row['remarks'];
                $price = $row['price'];

                $descriptionRequest = $purchaseRequestModel->getPurchaseRequestCart($purchase_request_cart_id)['description'] ?? '';

                $partsDetails = $partsModel->getParts($part_id);
                $description = $partsDetails['description'];

                $action = '';
                if($purchase_order_status == 'Draft'){
                    $action = ' <button type="button" class="btn btn-icon btn-success update-part-cart-supply" data-bs-toggle="offcanvas" data-bs-target="#add-item-supply-offcanvas" aria-controls="add-item-supply-offcanvas" data-purchase-order-supply-id="'. $purchase_order_supply_id .'" title="Update Part Item">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger delete-part-cart-supply" data-purchase-order-supply-id="'. $purchase_order_supply_id .'" title="Delete Item">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                if($purchase_order_status == 'On-Process' && $remaining_quantity > 0){
                    $action .= ' <button type="button" class="btn btn-icon btn-info receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#receive-item-offcanvas" aria-controls="receive-item-offcanvas" data-type="supply" data-purchase-order-cart-id="'. $purchase_order_supply_id .'" title="Receive Item">
                                            <i class="ti ti-arrow-bar-to-down"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-warning cancel-receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#cancel-receive-item-offcanvas" data-type="supply" aria-controls="cancel-receive-item-offcanvas" data-purchase-order-cart-id="'. $purchase_order_supply_id .'" title="Cancel Remaining Quantity">
                                            <i class="ti ti-arrow-forward"></i>
                                        </button>';
                    
                }

                $purchase_order_id_encrypted = $securityModel->encryptData($purchase_order_id);

                $response[] = [
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'ACTUAL_QUANTITY' => number_format($actual_quantity, 2) . ' ' . $short_name,
                    'CANCELLED_QUANTITY' => number_format($cancelled_quantity, 2) . ' ' . $short_name,
                    'PRICE' => number_format($price, 2) . ' PHP',
                    'REQUEST' => $descriptionRequest,
                    'SUPPLY' => $description,
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