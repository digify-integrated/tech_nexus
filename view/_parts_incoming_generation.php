<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-model.php';
require_once '../model/parts-incoming-model.php';
require_once '../model/parts-subclass-model.php';
require_once '../model/parts-class-model.php';
require_once '../model/product-model.php';
require_once '../model/unit-model.php';
require_once '../model/supplier-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsModel = new PartsModel($databaseModel);
$partsIncomingModel = new PartsIncomingModel($databaseModel);
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
            $parts_incoming_id = $_POST['parts_incoming_id'];
            $company_id = $_POST['company_id'];
            $sql = $databaseModel->getConnection()->prepare('CALL generateAllPartOptions(:parts_incoming_id, :company_id)');
            $sql->bindValue(':parts_incoming_id', $parts_incoming_id, PDO::PARAM_STR);
            $sql->bindValue(':company_id', $company_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_id = $row['part_id'];
                $description = $row['description'];
                $bar_code = $row['bar_code'];
                $part_price = $row['part_price'];
                $quantity = $row['quantity'];
                $partsImage = $systemModel->checkImage($row['part_image'], 'default');

                $response[] = [
                    'PART' => ' <div class="d-flex align-items-center"><img src="'. $partsImage .'" alt="image" loading="lazy" class="bg-light wid-50 rounded">
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="mb-1">'. $description .'</h5>
                                        <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                    </div>
                                </div>',
                    'PRICE' => number_format($part_price, 2) . ' PHP',
                    'STOCK' => $quantity,
                    'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input assign-part" type="checkbox" value="'. $part_id.'"></div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        case 'part item table':
            $parts_incoming_id = $_POST['parts_incoming_id'];

            $partIncomingDetails = $partsIncomingModel->getPartsIncoming($parts_incoming_id);
            $part_incoming_status = $partIncomingDetails['part_incoming_status'] ?? 'Draft';
            $updatePartCost = $userModel->checkSystemActionAccessRights($user_id, 204);
            $updatePartIncomingCompletedCost = $userModel->checkSystemActionAccessRights($user_id, 213);

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartIncomingItemTable(:parts_incoming_id)');
            $sql->bindValue(':parts_incoming_id', $parts_incoming_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $order = 0;
            foreach ($options as $row) {
                $part_incoming_cart_id = $row['part_incoming_cart_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $received_quantity = $row['received_quantity'];
                $remaining_quantity = $row['remaining_quantity'];
                $remarks = $row['remarks'];
                $total_cost = $row['total_cost'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $bar_code = $partDetails['bar_code'];
                $part_price = $partDetails['part_price'];

                $partsImage = $systemModel->checkImage($partDetails['part_image'], 'default');

                $action = '';
                #if($part_incoming_status == 'Draft' || ($part_incoming_status == 'On-Process' && $updatePartCost['total'] > 0 && $received_quantity == 0 && $remaining_quantity != 0) || ($part_incoming_status == 'Completed' && $updatePartIncomingCompletedCost['total'] > 0)){
                if($part_incoming_status == 'Draft' || ($part_incoming_status == 'On-Process' && $updatePartCost['total'] > 0 && $received_quantity == 0 && $remaining_quantity != 0)){
                    $action .= '
                    <button type="button" class="btn btn-icon btn-success update-part-cart" data-bs-toggle="offcanvas" data-bs-target="#part-cart-offcanvas" aria-controls="part-cart-offcanvas" data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Update Cost">
                                        <i class="ti ti-edit"></i>
                                    </button>';
                }
               
                if($part_incoming_status == 'On-Process' && $remaining_quantity > 0){
                    $action .= ' <button type="button" class="btn btn-icon btn-info receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#receive-item-offcanvas" aria-controls="receive-item-offcanvas" data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Receive Item">
                                            <i class="ti ti-arrow-bar-to-down"></i>
                                        </button>';

                    if($received_quantity > 0){
                        $action .= ' <button type="button" class="btn btn-icon btn-warning cancel-receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#cancel-receive-item-offcanvas" aria-controls="cancel-receive-item-offcanvas" data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Cancel Remaining Quantity">
                                            <i class="ti ti-arrow-forward"></i>
                                        </button>';
                    }
                    
                }
               
                if($part_incoming_status == 'On-Process' && $received_quantity == 0 && $remaining_quantity != 0){
                    $action .= ' <button type="button" class="btn btn-icon btn-danger cancel-item-quantity"  data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Cancel Remaining Quantity">
                                            <i class="ti ti-x"></i>
                                        </button>';
                }
               
                
                if($part_incoming_status == 'Draft'){
                    $action .= '<button type="button" class="btn btn-icon btn-danger delete-part-cart" data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Delete Part Item">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }
                
                $partDetails = $partsModel->getParts($part_id);
                $partQuantity = $partDetails['quantity'] ?? 0;
                $unitSale = $partDetails['unit_sale'] ?? null;

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;
                $order += 1;

                $response[] = [
                    'PART' => '<div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">'. $description .'</h5>
                                        <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                    </div>
                                </div>',
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'RECEIVED_QUANTITY' => number_format($received_quantity, 2) . ' ' . $short_name,
                    'REMAINING_QUANTITY' => number_format($remaining_quantity, 2) . ' ' . $short_name,
                    'TOTAL_COST' => number_format($total_cost, 2) . ' PHP',
                    'AVAILABLE_STOCK' => number_format($partQuantity, 0, '', ',') . ' ' . $short_name,
                    'REMARKS' => $remarks,
                    'ORDER' => $order,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'part item table 2':
            $parts_id = $_POST['parts_id'];

            $parts_incoming_start_date = $systemModel->checkDate('empty', $_POST['parts_incoming_start_date'], '', 'Y-m-d', '');
            $parts_incoming_end_date = $systemModel->checkDate('empty', $_POST['parts_incoming_end_date'], '', 'Y-m-d', '');

            $partDetails = $partsModel->getParts($parts_id);
            $unitSale = $partDetails['unit_sale'] ?? null;

            $unitCode = $unitModel->getUnit($unitSale);
            $short_name = $unitCode['short_name'] ?? null;

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartIncomingItemTable2(:parts_id, :parts_incoming_start_date, :parts_incoming_end_date)');
            $sql->bindValue(':parts_id', $parts_id, PDO::PARAM_STR);
            $sql->bindValue(':parts_incoming_start_date', $parts_incoming_start_date, PDO::PARAM_STR);
            $sql->bindValue(':parts_incoming_end_date', $parts_incoming_end_date, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_incoming_cart_id = $row['part_incoming_cart_id'];
                $parts_incoming_id = $row['part_incoming_id'];
                $quantity = $row['quantity'];
                $received_quantity = $row['received_quantity'];
                $remaining_quantity = $row['remaining_quantity'];
                $remarks = $row['remarks'];
                $cost = $row['cost'];

                $partIncomingDetails = $partsIncomingModel->getPartsIncoming($parts_incoming_id);
                $reference_number = $partIncomingDetails['reference_number'] ?? '';
                $company_id = $partIncomingDetails['company_id'] ?? '';
                $product_id = $partIncomingDetails['product_id'] ?? null;

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'] ?? '--';

                $part_incoming_id_encrypted = $securityModel->encryptData($parts_incoming_id);

                if($company_id == '1'){
                    $link = 'supplies-incoming';
                }
                else if($company_id == '2'){
                    $link = 'netruck-parts-incoming';
                }
                else{
                    $link = 'parts-incoming';
                }


                $response[] = [
                    'REFERENCE_NUMBER' => '<a href="'.$link.'.php?id='. $part_incoming_id_encrypted .'" target="_blank">
                                        '. $reference_number .'
                                    </a>',
                    'PRODUCT' => $stock_number,
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'RECEIVED_QUANTITY' => number_format($received_quantity, 2) . ' ' . $short_name,
                    'COST' => number_format($cost, 2) . ' PHP',
                    'TOTAL_COST' => number_format($cost * $quantity, 2) . ' PHP',
                    'REMARKS' => $remarks
                ];
            }

            echo json_encode($response);
        break;
        case 'part incoming document table':
            $parts_incoming_id = $_POST['parts_incoming_id'];
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsIncomingDocument(:parts_incoming_id)');
            $sql->bindValue(':parts_incoming_id', $parts_incoming_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_incoming_document_id = $row['part_incoming_document_id'];
                $document_name = $row['document_name'];
                $document_file_path = $row['document_file_path'];
                $incoming_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');

                $documentType = '<a href="'. $document_file_path .'" target="_blank">' . $document_name . "</a>";

                $response[] = [
                    'DOCUMENT' => $documentType,
                    'UPLOAD_DATE' => $incoming_date,
                    'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-danger delete-parts-document" data-parts-incoming-document-id="'. $part_incoming_document_id .'" title="Delete Incoming Document">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts incoming table':
            $filterIncomingDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterIncomingDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $filter_released_date_start_date = $systemModel->checkDate('empty', $_POST['filter_released_date_start_date'], '', 'Y-m-d', '');
            $filter_released_date_end_date = $systemModel->checkDate('empty', $_POST['filter_released_date_end_date'], '', 'Y-m-d', '');
            $filter_purchased_date_start_date = $systemModel->checkDate('empty', $_POST['filter_purchased_date_start_date'], '', 'Y-m-d', '');
            $filter_purchased_date_end_date = $systemModel->checkDate('empty', $_POST['filter_purchased_date_end_date'], '', 'Y-m-d', '');
            $incoming_status_filter = $_POST['filter_incoming_status'];
            $company = $_POST['company'];

            if (!empty($incoming_status_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $incoming_status_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_incoming_status = implode(', ', $quoted_values_array);
            } else {
                $filter_incoming_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsIncomingTable(:company, :filterIncomingDateStartDate, :filterIncomingDateEndDate, :filter_released_date_start_date, :filter_released_date_end_date, :filter_purchased_date_start_date, :filter_purchased_date_end_date, :filter_incoming_status)');
            $sql->bindValue(':company', $company, PDO::PARAM_STR);
            $sql->bindValue(':filterIncomingDateStartDate', $filterIncomingDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterIncomingDateEndDate', $filterIncomingDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_released_date_start_date', $filter_released_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_released_date_end_date', $filter_released_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_purchased_date_start_date', $filter_purchased_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_purchased_date_end_date', $filter_purchased_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_incoming_status', $filter_incoming_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_incoming_id = $row['part_incoming_id'];
                $reference_number = $row['reference_number'];
                $part_incoming_status = $row['part_incoming_status'];
                $product_id = $row['product_id'];
                $supplier_id = $row['supplier_id'];
                $incoming_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $completion_date = $systemModel->checkDate('empty', $row['completion_date'], '', 'm/d/Y', '');
                $purchase_date = $systemModel->checkDate('empty', $row['purchase_date'], '', 'm/d/Y', '');
                $delivery_date = $systemModel->checkDate('empty', $row['delivery_date'], '', 'm/d/Y', '');
                $posted_date = $systemModel->checkDate('empty', $row['posted_date'], '', 'm/d/Y h:i:s A', '');
                
                $supplierDetails = $supplierModel->getSupplier($supplier_id);
                $supplier_name = $supplierDetails['supplier_name'] ?? 'N/A';

                $cost = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'total cost')['total'];
                $lines = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'lines')['total'];
                $quantity = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'quantity')['total'];
                $received = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'received')['total'];
                $remaining = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'remaining')['total'];

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'] ?? '';
                $description = $productDetails['description'] ?? '';

                if($part_incoming_status === 'Draft'){
                    $part_incoming_status = '<span class="badge bg-secondary">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'Cancelled'){
                    $part_incoming_status = '<span class="badge bg-warning">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'On-Process'){
                    $part_incoming_status = '<span class="badge bg-info">' . $part_incoming_status . '</span>';
                }
                else{
                    $part_incoming_status = '<span class="badge bg-success">' . $part_incoming_status . '</span>';
                }

                $part_incoming_id_encrypted = $securityModel->encryptData($part_incoming_id);
                if($company == '1'){
                    $link = 'supplies-incoming';
                }
                else if($company == '2'){
                    $link = 'netruck-parts-incoming';
                }
                else{
                    $link = 'parts-incoming';
                }

                $response[] = [
                    'TRANSACTION_ID' => $reference_number,
                    'PRODUCT' => '<div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'LINES' => number_format($lines, 0),
                    'QUANTITY' => number_format($quantity, 2),
                    'RECEIVED' => number_format($received, 2),
                    'REMAINING' => $supplier_name,
                    'COST' => number_format($cost, 2) . ' PHP',
                    'COMPLETION_DATE' => $completion_date,
                    'PURCHASE_DATE' => $delivery_date,
                    'TRANSACTION_DATE' => $incoming_date,
                    'POSTED_DATE' => $posted_date,
                    'STATUS' => $part_incoming_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $part_incoming_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts incoming posting table':
            $filterIncomingDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterIncomingDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $filter_released_date_start_date = $systemModel->checkDate('empty', $_POST['filter_released_date_start_date'], '', 'Y-m-d', '');
            $filter_released_date_end_date = $systemModel->checkDate('empty', $_POST['filter_released_date_end_date'], '', 'Y-m-d', '');
            $filter_purchased_date_start_date = $systemModel->checkDate('empty', $_POST['filter_purchased_date_start_date'], '', 'Y-m-d', '');
            $filter_purchased_date_end_date = $systemModel->checkDate('empty', $_POST['filter_purchased_date_end_date'], '', 'Y-m-d', '');
            $incoming_status_filter = $_POST['filter_incoming_status'];
            $company = null;

            if (!empty($incoming_status_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $incoming_status_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_incoming_status = implode(', ', $quoted_values_array);
            } else {
                $filter_incoming_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsIncomingTable(:company, :filterIncomingDateStartDate, :filterIncomingDateEndDate, :filter_released_date_start_date, :filter_released_date_end_date, :filter_purchased_date_start_date, :filter_purchased_date_end_date, :filter_incoming_status)');
            $sql->bindValue(':company', $company, PDO::PARAM_STR);
            $sql->bindValue(':filterIncomingDateStartDate', $filterIncomingDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterIncomingDateEndDate', $filterIncomingDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_released_date_start_date', $filter_released_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_released_date_end_date', $filter_released_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_purchased_date_start_date', $filter_purchased_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_purchased_date_end_date', $filter_purchased_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_incoming_status', $filter_incoming_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_incoming_id = $row['part_incoming_id'];
                $reference_number = $row['reference_number'];
                $part_incoming_status = $row['part_incoming_status'];
                $product_id = $row['product_id'];
                $invoice_number = $row['invoice_number'];
                $invoice_price = $row['invoice_price'];
                $supplier_id = $row['supplier_id'];

                $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? '';

                $incoming_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $completion_date = $systemModel->checkDate('empty', $row['completion_date'], '', 'm/d/Y', '');
                $purchase_date = $systemModel->checkDate('empty', $row['purchase_date'], '', 'm/d/Y', '');
                $delivery_date = $systemModel->checkDate('empty', $row['delivery_date'], '', 'm/d/Y', '');
                $invoice_date = $systemModel->checkDate('empty', $row['invoice_date'], '', 'm/d/Y', '');

                $cost = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'total cost')['total'];
                $lines = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'lines')['total'];
                $quantity = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'quantity')['total'];
                $received = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'received')['total'];
                $remaining = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'remaining')['total'];

                if($part_incoming_status === 'Draft'){
                    $part_incoming_status = '<span class="badge bg-secondary">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'Cancelled'){
                    $part_incoming_status = '<span class="badge bg-warning">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'On-Process'){
                    $part_incoming_status = '<span class="badge bg-info">' . $part_incoming_status . '</span>';
                }
                else{
                    $part_incoming_status = '<span class="badge bg-success">' . $part_incoming_status . '</span>';
                }
                
                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'] ?? '';
                $description = $productDetails['description'] ?? '';

                $part_incoming_id_encrypted = $securityModel->encryptData($part_incoming_id);
                if($company == '1'){
                    $link = 'supplies-incoming';
                }
                else if($company == '2'){
                    $link = 'netruck-parts-incoming';
                }
                else{
                    $link = 'parts-incoming';
                }

                $response[] = [
                    'TRANSACTION_ID' => $reference_number,
                    'PRODUCT' => '<div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'LINES' => number_format($lines, 0),
                    'QUANTITY' => number_format($quantity, 2),
                    'RECEIVED' => number_format($received, 2),
                    'REMAINING' => number_format($remaining, 2),
                    'COST' => number_format($cost, 2) . ' PHP',
                    'SUPPLIER' => $supplierName,
                    'COMPLETION_DATE' => $completion_date,
                    'DELIVERY_DATE' => $delivery_date,
                    'TRANSACTION_DATE' => $incoming_date,
                    'STATUS' => $part_incoming_status,
                    'INVOICE_NUMBER' => $invoice_number,
                    'INVOICE_DATE' => $invoice_date,
                    'INVOICE_PRICE' => number_format($invoice_price, 2) . ' PHP',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $part_incoming_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts incoming dashboard table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsIncomingDashboardTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_incoming_id = $row['part_incoming_id'];
                $reference_number = $row['reference_number'];
                $company_id = $row['company_id'];
                $product_id = $row['product_id'];
                $part_incoming_status = $row['part_incoming_status'];
                $incoming_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $completion_date = $systemModel->checkDate('empty', $row['completion_date'], '', 'm/d/Y', '');
                $purchase_date = $systemModel->checkDate('empty', $row['purchase_date'], '', 'm/d/Y', '');
                $delivery_date = $systemModel->checkDate('empty', $row['delivery_date'], '', 'm/d/Y', '');

                $cost = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'total cost')['total'];
                $lines = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'lines')['total'];
                $quantity = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'quantity')['total'];
                $received = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'received')['total'];
                $remaining = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'remaining')['total'];

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'];
                $description = $productDetails['description'];

                if($part_incoming_status === 'Draft'){
                    $part_incoming_status = '<span class="badge bg-secondary">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'Cancelled'){
                    $part_incoming_status = '<span class="badge bg-warning">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'On-Process'){
                    $part_incoming_status = '<span class="badge bg-info">' . $part_incoming_status . '</span>';
                }
                else{
                    $part_incoming_status = '<span class="badge bg-success">' . $part_incoming_status . '</span>';
                }

                $part_incoming_id_encrypted = $securityModel->encryptData($part_incoming_id);
                if($company_id == '1'){
                    $link = 'supplies-incoming';
                }
                else if($company_id == '2'){
                    $link = 'netruck-parts-incoming';
                }
                else{
                    $link = 'parts-incoming';
                }

                $response[] = [
                    'TRANSACTION_ID' =>'<a href="'. $link .'.php?id='. $part_incoming_id_encrypted .'" title="View Details" target="_blank">
                                        '. $reference_number .'
                                    </a>', 
                    'PRODUCT' => '<div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'LINES' => number_format($lines, 0),
                    'QUANTITY' => number_format($quantity, 2),
                    'COST' => number_format($cost, 2) . ' PHP',
                    'TRANSACTION_DATE' => $incoming_date,
                    'STATUS' => $part_incoming_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $part_incoming_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts incoming dashboard list':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsIncomingDashboardTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $list = '';
            foreach ($options as $row) {
                $part_incoming_id = $row['part_incoming_id'];
                $reference_number = $row['reference_number'];
                $company_id = $row['company_id'];
                $product_id = $row['product_id'];
                $part_incoming_status = $row['part_incoming_status'];
                $incoming_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $completion_date = $systemModel->checkDate('empty', $row['completion_date'], '', 'm/d/Y', '');
                $purchase_date = $systemModel->checkDate('empty', $row['purchase_date'], '', 'm/d/Y', '');
                $delivery_date = $systemModel->checkDate('empty', $row['delivery_date'], '', 'm/d/Y', '');

                $cost = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'total cost')['total'];
                $lines = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'lines')['total'];
                $quantity = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'quantity')['total'];
                $received = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'received')['total'];
                $remaining = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'remaining')['total'];

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'] ?? '';
                $description = $productDetails['description'] ?? '';

                if($part_incoming_status === 'Draft'){
                    $part_incoming_status = '<span class="badge bg-secondary">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'Cancelled'){
                    $part_incoming_status = '<span class="badge bg-warning">' . $part_incoming_status . '</span>';
                }
                else if($part_incoming_status === 'On-Process'){
                    $part_incoming_status = '<span class="badge bg-info">' . $part_incoming_status . '</span>';
                }
                else{
                    $part_incoming_status = '<span class="badge bg-success">' . $part_incoming_status . '</span>';
                }

                $part_incoming_id_encrypted = $securityModel->encryptData($part_incoming_id);
                if($company_id == '1'){
                    $link = 'supplies-incoming';
                }
                else if($company_id == '2'){
                    $link = 'netruck-parts-incoming';
                }
                else{
                    $link = 'parts-incoming';
                }

                 $list .= ' <li class="list-group-item">
                          <div class="d-flex align-items-center">
                              <div class="flex-grow-1 ms-3">
                                  <div class="row g-1">
                                        <div class="col-9">
                                            <a href="'. $link .'.php?id='. $part_incoming_id_encrypted .'" title="View Details" target="_blank">
                                                <p class="mb-0"><b>'. strtoupper($description) .'</b></p>
                                                <p class="text-muted mb-0"><small>Stock Number: '. strtoupper($stock_number) .'</small></p>
                                                <p class="text-muted mb-0"><small>Reference Number: '. $reference_number .'</small></p>
                                                <p class="text-muted mb-0"><small>Quantity: '. number_format($quantity, 2) .'</small></p>
                                                <p class="text-muted mb-0"><small>Cost: '. number_format($cost, 2) .' PHP</small></p>
                                                <p class="text-muted mb-0"><small>Transaction Date: '. $incoming_date .'</small></p>
                                            </a>
                                      </div>
                                      <div class="col-3 text-end">
                                          '. $part_incoming_status .'
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