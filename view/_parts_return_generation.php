<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-model.php';
require_once '../model/parts-return-model.php';
require_once '../model/parts-subclass-model.php';
require_once '../model/parts-transaction-model.php';
require_once '../model/parts-class-model.php';
require_once '../model/product-model.php';
require_once '../model/unit-model.php';
require_once '../model/supplier-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsModel = new PartsModel($databaseModel);
$partsReturnModel = new PartsReturnModel($databaseModel);
$partTransactionModel = new PartsTransactionModel($databaseModel);
$partsSubclassModel = new PartsSubclassModel($databaseModel);
$partsClassModel = new PartsClassModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
$supplierModel = new SupplierModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: add part table
        # Description:
        # Generates the add part table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add part table':
            $parts_return_id = $_POST['parts_return_id'];
            $company_id = $_POST['company_id'];
            $return_type = $_POST['return_type'];

            if($return_type != 'Stock to Supplier'){
                $query = 'SELECT * FROM part_transaction_cart WHERE part_transaction_id IN (SELECT part_transaction_id FROM part_transaction WHERE company_id = :company_id AND part_transaction_status IN ("Checked", "Released") AND part_transaction_id NOT IN (SELECT part_transaction_id FROM part_return_cart WHERE part_return_id = :parts_return_id)) AND return_quantity > 0';
            }
            else{
                $query = 'SELECT * FROM part WHERE company_id = :company_id AND part_id NOT IN (SELECT part_id FROM part_return_cart WHERE part_return_id = :parts_return_id) AND quantity > 0 AND part_status = "For Sale"';
            }

            $sql = $databaseModel->getConnection()->prepare($query);
            $sql->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_STR);
            $sql->bindValue(':company_id', $company_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                if($return_type != 'Stock to Supplier'){
                    $part_transaction_cart_id = $row['part_transaction_cart_id'];
                    $part_transaction_id = $row['part_transaction_id'];
                    $part_id = $row['part_id'];
                    $cost = $row['cost'];
                    $return_quantity = $row['return_quantity'];

                    $getPartsTransaction = $partTransactionModel->getPartsTransaction($part_transaction_id);
                    $slip_reference_no = $getPartsTransaction['issuance_no'] ?? '';

                    $partDetails = $partsModel->getParts($part_id);
                    $description = $partDetails['description'];
                    $bar_code = $partDetails['bar_code'];
                    $unitSale = $partDetails['unit_sale'] ?? null;

                    $unitCode = $unitModel->getUnit($unitSale);
                    $short_name = $unitCode['short_name'] ?? null;

                    $response[] = [
                        'ISSUANCE_NO' => $slip_reference_no,
                        'PART' => ' <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-1">'. $description .'</h5>
                                            <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                        </div>
                                    </div>',
                        'COST' => number_format($cost, 2) . ' PHP',
                        'AVAILABLE_QUANTITY' => number_format($return_quantity, 2) . ' ' . $short_name,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input assign-part" type="checkbox" value="'. $part_transaction_cart_id.'"></div>'
                    ];
                }
                else{
                    $part_id = $row['part_id'];
                    $description = $row['description'];
                    $bar_code = $row['bar_code'];
                    $unitSale = $row['unit_sale'] ?? null;
                    $cost = $row['part_cost'];
                    $quantity = $row['quantity'];

                    $unitCode = $unitModel->getUnit($unitSale);
                    $short_name = $unitCode['short_name'] ?? null;


                    $response[] = [
                        'ISSUANCE_NO' => 'N/A',
                        'PART' => ' <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-1">'. $description .'</h5>
                                            <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                        </div>
                                    </div>',
                        'COST' => number_format($cost, 2) . ' PHP',
                        'AVAILABLE_QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input assign-part" type="checkbox" value="'. $part_id.'"></div>'
                    ];
                }

                
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        case 'part return item table':
            $parts_return_id = $_POST['parts_return_id'];

            $partReturnDetails = $partsReturnModel->getPartsReturn($parts_return_id);
            $part_return_status = $partReturnDetails['part_return_status'] ?? 'Draft';
            $return_type = $partReturnDetails['return_type'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_return_cart WHERE part_return_id = :parts_return_id');
            $sql->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $order = 0;
            foreach ($options as $row) {
                $part_return_cart_id = $row['part_return_cart_id'];                
                $part_transaction_id = $row['part_transaction_id'];
                $part_transaction_cart_id = $row['part_transaction_cart_id'];
                $return_quantity = $row['return_quantity'];
                $remarks = $row['remarks'];
                $part_id = $row['part_id'];

                
                if($return_type == 'Issuance'){
                    $getPartsTransaction = $partTransactionModel->getPartsTransaction( $part_transaction_id);
                    $slip_reference_no = $getPartsTransaction['issuance_no'] ?? '';
                    
                    $getPartsTransactionCart = $partTransactionModel->getPartsTransactionCart($part_transaction_cart_id);
                    $available_return_quantity = $getPartsTransactionCart['return_quantity'] ?? 0;
                    $cost = $getPartsTransactionCart['cost'] ?? 0;

                    $partDetails = $partsModel->getParts($part_id);
                    $description = $partDetails['description'];
                    $bar_code = $partDetails['bar_code'];
                    $unitSale = $partDetails['unit_sale'] ?? null;
                }
                else{
                     $slip_reference_no = 'N/A';
                    $partDetails = $partsModel->getParts($part_id);
                    $description = $partDetails['description'] ?? null;
                    $bar_code = $partDetails['bar_code'] ?? null;
                    $cost = $partDetails['part_cost'] ?? null;
                    $available_return_quantity = $partDetails['quantity'] ?? null;
                    $unitSale = $partDetails['unit_sale'] ?? null;
                }

                

                $action = '';
                if($part_return_status == 'Draft'){
                    $action .= '
                    <button type="button" class="btn btn-icon btn-success update-part-cart" data-bs-toggle="offcanvas" data-bs-target="#part-cart-offcanvas" aria-controls="part-cart-offcanvas" data-parts-return-cart-id="'. $part_return_cart_id .'" title="Update Cost">
                                        <i class="ti ti-edit"></i>
                                    </button>';
                }               
                
                if($part_return_status == 'Draft'){
                    $action .= '<button type="button" class="btn btn-icon btn-danger delete-part-cart" data-parts-return-cart-id="'. $part_return_cart_id .'" title="Delete Part Item">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }
                

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;
                $order += 1;

                $response[] = [
                    'ISSUANCE_NO' => $slip_reference_no,
                    'PART' => '<div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">'. $description .'</h5>
                                        <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                    </div>
                                </div>',
                    'COST' => number_format($cost, 2),
                    'RETURN_QUANTITY' => number_format($return_quantity, 2) . ' ' . $short_name,
                    'AVAILABLE_QUANTITY' => number_format($available_return_quantity, 2) . ' ' . $short_name,
                    'REMARKS' => $remarks,
                    'ORDER' => $order,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts return table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $filter_approval_date_start_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_start_date'], '', 'Y-m-d', '');
            $filter_approval_date_end_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_end_date'], '', 'Y-m-d', '');
            $transaction_status_filter = $_POST['filter_return_status'];
            $company = $_POST['company'];

            if (!empty($transaction_status_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $transaction_status_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_transaction_status = implode(', ', $quoted_values_array);
            } else {
                $filter_transaction_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsReturnTable(:company, :filterTransactionDateStartDate, :filterTransactionDateEndDate, :filter_approval_date_start_date, :filter_approval_date_end_date, :filter_transaction_status)');
            $sql->bindValue(':company', $company, PDO::PARAM_INT);
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_start_date', $filter_approval_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_end_date', $filter_approval_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_transaction_status', $filter_transaction_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_return_id = $row['part_return_id'];
                $reference_number = $row['reference_number'];
                $supplier_id = $row['supplier_id'];
                $ref_invoice_number = $row['ref_invoice_number'];
                $ref_po_number = $row['ref_po_number'];
                $part_return_status = $row['part_return_status'];

                $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? '';

                if($part_return_status === 'Draft'){
                    $part_return_status = '<span class="badge bg-secondary">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'Cancelled'){
                    $part_return_status = '<span class="badge bg-warning">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'On-Process'){
                    $part_return_status = '<span class="badge bg-info">' . $part_return_status . '</span>';
                }
                else{
                    $part_return_status = '<span class="badge bg-success">' . $part_return_status . '</span>';
                }

                $part_return_id_encrypted = $securityModel->encryptData($part_return_id);

                if($company == '1'){
                    $link = 'supplies-return';
                }
                else if($company == '2'){
                    $link = 'netruck-parts-return';
                }
                else{
                    $link = 'parts-return';
                }

                $response[] = [
                    'REF_NO' => $reference_number,
                    'SUPPLIER' => $supplierName,
                    'REF_INVOICE_NUMBER' => $ref_invoice_number,
                    'REF_PO_NUMBER' => $ref_po_number,
                    'STATUS' => $part_return_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $part_return_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts return posting table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $filter_released_date_start_date = $systemModel->checkDate('empty', $_POST['filter_released_date_start_date'], '', 'Y-m-d', '');
            $filter_released_date_end_date = $systemModel->checkDate('empty', $_POST['filter_released_date_end_date'], '', 'Y-m-d', '');
            $filter_purchased_date_start_date = $systemModel->checkDate('empty', $_POST['filter_purchased_date_start_date'], '', 'Y-m-d', '');
            $filter_purchased_date_end_date = $systemModel->checkDate('empty', $_POST['filter_purchased_date_end_date'], '', 'Y-m-d', '');
            $return_status_filter = $_POST['filter_return_status'];
            $company = null;

            if (!empty($return_status_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $return_status_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_return_status = implode(', ', $quoted_values_array);
            } else {
                $filter_return_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsReturnTable(:company, :filterTransactionDateStartDate, :filterTransactionDateEndDate, :filter_released_date_start_date, :filter_released_date_end_date, :filter_purchased_date_start_date, :filter_purchased_date_end_date, :filter_return_status)');
            $sql->bindValue(':company', $company, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_released_date_start_date', $filter_released_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_released_date_end_date', $filter_released_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_purchased_date_start_date', $filter_purchased_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_purchased_date_end_date', $filter_purchased_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_return_status', $filter_return_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_return_id = $row['part_return_id'];
                $reference_number = $row['reference_number'];
                $supplier_id = $row['supplier_id'];
                $ref_invoice_number = $row['ref_invoice_number'];
                $ref_po_number = $row['ref_po_number'];
                $part_return_status = $row['part_return_status'];

                $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? '';

                if($part_return_status === 'Draft'){
                    $part_return_status = '<span class="badge bg-secondary">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'Cancelled'){
                    $part_return_status = '<span class="badge bg-warning">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'On-Process'){
                    $part_return_status = '<span class="badge bg-info">' . $part_return_status . '</span>';
                }
                else{
                    $part_return_status = '<span class="badge bg-success">' . $part_return_status . '</span>';
                }

                $part_return_id_encrypted = $securityModel->encryptData($part_return_id);

                if($company == '1'){
                    $link = 'supplies-return';
                }
                else if($company == '2'){
                    $link = 'netruck-parts-return';
                }
                else{
                    $link = 'parts-return';
                }

                $response[] = [
                    'REF_NO' => $reference_number,
                    'SUPPLIER' => $supplierName,
                    'REF_INVOICE_NUMBER' => $ref_invoice_number,
                    'REF_PO_NUMBER' => $ref_po_number,
                    'STATUS' => $part_return_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $part_return_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts return dashboard table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsReturnDashboardTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_return_id = $row['part_return_id'];
                $reference_number = $row['reference_number'];
                $company_id = $row['company_id'];
                $product_id = $row['product_id'];
                $part_return_status = $row['part_return_status'];
                $return_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $completion_date = $systemModel->checkDate('empty', $row['completion_date'], '', 'm/d/Y', '');
                $purchase_date = $systemModel->checkDate('empty', $row['purchase_date'], '', 'm/d/Y', '');
                $delivery_date = $systemModel->checkDate('empty', $row['delivery_date'], '', 'm/d/Y', '');

                $cost = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'total cost')['total'];
                $lines = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'lines')['total'];
                $quantity = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'quantity')['total'];
                $received = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'received')['total'];
                $remaining = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'remaining')['total'];

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'];
                $description = $productDetails['description'];

                if($part_return_status === 'Draft'){
                    $part_return_status = '<span class="badge bg-secondary">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'Cancelled'){
                    $part_return_status = '<span class="badge bg-warning">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'On-Process'){
                    $part_return_status = '<span class="badge bg-info">' . $part_return_status . '</span>';
                }
                else{
                    $part_return_status = '<span class="badge bg-success">' . $part_return_status . '</span>';
                }

                $part_return_id_encrypted = $securityModel->encryptData($part_return_id);
                if($company_id == '1'){
                    $link = 'supplies-return';
                }
                else if($company_id == '2'){
                    $link = 'netruck-parts-return';
                }
                else{
                    $link = 'parts-return';
                }

                $response[] = [
                    'TRANSACTION_ID' =>'<a href="'. $link .'.php?id='. $part_return_id_encrypted .'" title="View Details" target="_blank">
                                        '. $reference_number .'
                                    </a>', 
                    'PRODUCT' => '<div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'LINES' => number_format($lines, 0),
                    'QUANTITY' => number_format($quantity, 2),
                    'COST' => number_format($cost, 2) . ' PHP',
                    'TRANSACTION_DATE' => $return_date,
                    'STATUS' => $part_return_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $part_return_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts return dashboard list':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsReturnDashboardTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $list = '';
            foreach ($options as $row) {
                $part_return_id = $row['part_return_id'];
                $reference_number = $row['reference_number'];
                $company_id = $row['company_id'];
                $product_id = $row['product_id'];
                $part_return_status = $row['part_return_status'];
                $return_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $completion_date = $systemModel->checkDate('empty', $row['completion_date'], '', 'm/d/Y', '');
                $purchase_date = $systemModel->checkDate('empty', $row['purchase_date'], '', 'm/d/Y', '');
                $delivery_date = $systemModel->checkDate('empty', $row['delivery_date'], '', 'm/d/Y', '');

                $cost = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'total cost')['total'];
                $lines = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'lines')['total'];
                $quantity = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'quantity')['total'];
                $received = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'received')['total'];
                $remaining = $partsReturnModel->getPartsReturnCartTotal($part_return_id, 'remaining')['total'];

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'] ?? '';
                $description = $productDetails['description'] ?? '';

                if($part_return_status === 'Draft'){
                    $part_return_status = '<span class="badge bg-secondary">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'Cancelled'){
                    $part_return_status = '<span class="badge bg-warning">' . $part_return_status . '</span>';
                }
                else if($part_return_status === 'On-Process'){
                    $part_return_status = '<span class="badge bg-info">' . $part_return_status . '</span>';
                }
                else{
                    $part_return_status = '<span class="badge bg-success">' . $part_return_status . '</span>';
                }

                $part_return_id_encrypted = $securityModel->encryptData($part_return_id);
                if($company_id == '1'){
                    $link = 'supplies-return';
                }
                else if($company_id == '2'){
                    $link = 'netruck-parts-return';
                }
                else{
                    $link = 'parts-return';
                }

                 $list .= ' <li class="list-group-item">
                          <div class="d-flex align-items-center">
                              <div class="flex-grow-1 ms-3">
                                  <div class="row g-1">
                                        <div class="col-9">
                                            <a href="'. $link .'.php?id='. $part_return_id_encrypted .'" title="View Details" target="_blank">
                                                <p class="mb-0"><b>'. strtoupper($description) .'</b></p>
                                                <p class="text-muted mb-0"><small>Stock Number: '. strtoupper($stock_number) .'</small></p>
                                                <p class="text-muted mb-0"><small>Reference Number: '. $reference_number .'</small></p>
                                                <p class="text-muted mb-0"><small>Quantity: '. number_format($quantity, 2) .'</small></p>
                                                <p class="text-muted mb-0"><small>Cost: '. number_format($cost, 2) .' PHP</small></p>
                                                <p class="text-muted mb-0"><small>Transaction Date: '. $return_date .'</small></p>
                                            </a>
                                      </div>
                                      <div class="col-3 text-end">
                                          '. $part_return_status .'
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </li>';
            }

            if(empty($list)){
                $list = ' <li class="list-group-item text-center"><b>No Parts Purchase For Approval Found</b></li>';
            }

            echo json_encode(['LIST' => $list]);
        break;
        # -------------------------------------------------------------
    }
}

?>