<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/property-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/product-model.php';
require_once '../model/product-inventory-report-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$propertyModel = new PropertyModel($databaseModel);
$cityModel = new CityModel($databaseModel);
$stateModel = new StateModel($databaseModel);
$countryModel = new CountryModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$productInventoryReportModel = new ProductInventoryReportModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: product inventory table
        # Description:
        # Generates the property table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'product inventory report table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateProductInventoryReport()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $product_inventory_id = $row['product_inventory_id'];
                $product_inventory_batch  = $row['product_inventory_batch'];
                $open_date = $systemModel->checkDate('summary', $row['open_date'], '', 'm/d/Y h:i:s A', '');
                $close_date = $systemModel->checkDate('summary', $row['close_date'], '', 'm/d/Y h:i:s A', '');

                $product_inventory_id_enc = $securityModel->encryptData($product_inventory_id);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $product_inventory_id .'">',
                    'BATCH_NUMBER' => $product_inventory_batch,
                    'OPEN_DATE' => $open_date,
                    'CLOSE_DATE' => $close_date,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="product-inventory-report.php?id='. $product_inventory_id_enc .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'product inventory report batch table':
            $product_inventory_id = htmlspecialchars($_POST['product_inventory_id'], ENT_QUOTES, 'UTF-8');

            $getProductInventory = $productInventoryReportModel->getProductInventory($product_inventory_id);
            $close_date = $systemModel->checkDate('empty', $getProductInventory['close_date'], '', 'm/d/Y h:i:s A', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generateProductInventoryReportBatch(:product_inventory_id)');
            $sql->bindValue(':product_inventory_id', $product_inventory_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $product_inventory_batch_id  = $row['product_inventory_batch_id'];
                $product_id  = $row['product_id'];
                $product_inventory_status  = $row['product_inventory_status'];
                $scanned_by  = $row['scanned_by'];
                $remarks  = $row['remarks'];
                $scanned_date = $systemModel->checkDate('summary', $row['scanned_date'], '', 'm/d/Y h:i:s A', '');

                $scanned_by_details = $userModel->getUserByID($scanned_by);
                $scanned_by_name = $scanned_by_details['file_as'] ?? '';

                $productDetails = $productModel->getProduct($product_id);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                if($product_inventory_status === 'For Scanning'){
                    $product_inventory_status_badge = '<span class="badge bg-info">' . $product_inventory_status . '</span>';
                }
                else if($product_inventory_status === 'Scanned'){
                    $product_inventory_status_badge = '<span class="badge bg-success">' . $product_inventory_status . '</span>';
                }
                else if($product_inventory_status === 'Missing'){
                    $product_inventory_status_badge = '<span class="badge bg-danger">' . $product_inventory_status . '</span>';
                }
                else if($product_inventory_status === 'Unscanned'){
                    $product_inventory_status_badge = '<span class="badge bg-danger">' . $product_inventory_status . '</span>';
                }
                else{
                    $product_inventory_status_badge = '<span class="badge bg-secondary">' . $product_inventory_status . '</span>';
                }

                $product_inventory_id_enc = $securityModel->encryptData($product_inventory_id);

                $action = '';
                if(empty($close_date) && $product_inventory_status === 'For Scanning'){
                    $action .= '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-danger update-product-inventory-batch" data-bs-toggle="offcanvas" data-bs-target="#product-inventory-batch-offcanvas" aria-controls="product-inventory-batch-offcanvas" data-product-inventory-batch-id="'. $product_inventory_batch_id .'" title="Tag As Missing">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>';
                }

                if(empty($close_date)){
                    $action .= '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-info update-product-inventory-batch2" data-bs-toggle="offcanvas" data-bs-target="#product-inventory-batch-offcanvas" aria-controls="product-inventory-batch-offcanvas" data-product-inventory-batch-id="'. $product_inventory_batch_id .'" title="Add Remarks">
                                        <i class="ti ti-file-text"></i>
                                    </button>
                                </div>';
                }

                $response[] = [
                    'PRODUCT' => $stockNumber,
                    'DESCRIPTION' => $productName,
                    'SCAN_STATUS' => $product_inventory_status_badge,
                    'SCAN_DATE' => $scanned_date,
                    'SCAN_BY' => $scanned_by_name,
                    'REMARKS' => $remarks,
                    'ACTION' => $action
                ];
            }

            echo json_encode($response);
        break;
        case 'product inventory report scan history table':
            $product_inventory_id = htmlspecialchars($_POST['product_inventory_id'], ENT_QUOTES, 'UTF-8');
            $sql = $databaseModel->getConnection()->prepare('CALL generateProductInventoryReportScanHistory(:product_inventory_id)');
            $sql->bindValue(':product_inventory_id', $product_inventory_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $product_id  = $row['product_id'];
                $scanned_by  = $row['scanned_by'];
                $scanned_date = $systemModel->checkDate('summary', $row['scanned_date'], '', 'm/d/Y h:i:s A', '');

                $scanned_by_details = $userModel->getUserByID($scanned_by);
                $scanned_by_name = $scanned_by_details['file_as'] ?? '';

                $productDetails = $productModel->getProduct($product_id);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $product_inventory_id_enc = $securityModel->encryptData($product_inventory_id);

                $response[] = [
                    'PRODUCT' => '<div class="col">
                                                    <h6 class="mb-0">'. $stockNumber .'</h6>
                                                    <p class="f-12 mb-0">'. $productName .'</p>
                                                </div>',
                    'SCAN_DATE' => $scanned_date,
                    'SCAN_BY' => $scanned_by_name
                ];
            }

            echo json_encode($response);
        break;
        case 'product inventory report scan excess table':
            $product_inventory_id = htmlspecialchars($_POST['product_inventory_id'], ENT_QUOTES, 'UTF-8');
            $sql = $databaseModel->getConnection()->prepare('CALL generateProductInventoryReportScanExcess(:product_inventory_id)');
            $sql->bindValue(':product_inventory_id', $product_inventory_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $product_id  = $row['product_id'];
                $scanned_by  = $row['scanned_by'];
                $scanned_date = $systemModel->checkDate('summary', $row['scanned_date'], '', 'm/d/Y h:i:s A', '');

                $scanned_by_details = $userModel->getUserByID($scanned_by);
                $scanned_by_name = $scanned_by_details['file_as'] ?? '';

                $productDetails = $productModel->getProduct($product_id);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $product_inventory_id_enc = $securityModel->encryptData($product_inventory_id);

                $response[] = [
                    'PRODUCT' => '<div class="col">
                                                    <h6 class="mb-0">'. $stockNumber .'</h6>
                                                    <p class="f-12 mb-0">'. $productName .'</p>
                                                </div>',
                    'SCAN_DATE' => $scanned_date,
                    'SCAN_BY' => $scanned_by_name
                ];
            }

            echo json_encode($response);
        break;
        case 'product inventory report scan additional table':
            $product_inventory_id = htmlspecialchars($_POST['product_inventory_id'], ENT_QUOTES, 'UTF-8');
            $getProductInventory = $productInventoryReportModel->getProductInventory($product_inventory_id);
            $close_date = $systemModel->checkDate('empty', $getProductInventory['close_date'], '', 'm/d/Y h:i:s A', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generateProductInventoryReportScanAdditional(:product_inventory_id)');
            $sql->bindValue(':product_inventory_id', $product_inventory_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $product_inventory_scan_additional_id  = $row['product_inventory_scan_additional_id'];
                $stock_number  = $row['stock_number'];
                $scanned_by  = $row['added_by'];
                $remarks  = $row['remarks'];
                $created_date = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');

                $scanned_by_details = $userModel->getUserByID($scanned_by);
                $scanned_by_name = $scanned_by_details['file_as'] ?? '';

                $action = '';
                    if(empty($close_date)){
                        $action = '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-product-inventory-additional" data-bs-toggle="offcanvas" data-bs-target="#product-inventory-additional-offcanvas" aria-controls="product-inventory-additional-offcanvas" data-product-inventory-additional-id="'. $product_inventory_scan_additional_id .'" title="Update Additional">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-danger delete-product-inventory-additional" data-product-inventory-additional-id="'. $product_inventory_scan_additional_id .'" title="Delete Additional">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>';
                    }

                $response[] = [
                    'STOCK_NUMBER' => $stock_number,
                    'ADDED_BY' => $scanned_by_name,
                    'CREATED_DATE' => $created_date,
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