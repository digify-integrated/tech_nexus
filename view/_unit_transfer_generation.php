<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/product-model.php';
require_once '../model/warehouse-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$productModel = new ProductModel($databaseModel);
$warehouseModel = new WarehouseModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: unit transfer table
        # Description:
        # Generates the unit transfer table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'unit transfer table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateUnitTransferTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $unit_transfer_id = $row['unit_transfer_id'];
                $product_id = $row['product_id'];
                $transferred_from = $row['transferred_from'];
                $transferred_to = $row['transferred_to'];
                $transferred_by = $row['transferred_by'];
                $received_by = $row['received_by'];
                $transfer_status = $row['transfer_status'];
                $transfer_remarks = $row['transfer_remarks'];
                $received_remarks = $row['received_remarks'];
                $transfer_date = $systemModel->checkDate('summary', $row['transfer_date'], '', 'm/d/Y h:i:s A', '');
                $received_date = $systemModel->checkDate('summary', $row['received_date'], '', 'm/d/Y h:i:s A', '');

                $productDetails = $productModel->getProduct($product_id);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;
                $warehouse_id = $productDetails['warehouse_id'] ?? null;

                $warehouseDetails1 = $warehouseModel->getWarehouse($transferred_from);
                $warehouse_name1 = $warehouseDetails1['warehouse_name'] ?? null;

                $warehouseDetails2 = $warehouseModel->getWarehouse($transferred_to);
                $warehouse_name2 = $warehouseDetails2['warehouse_name'] ?? null;

                $scannedBy1 = $userModel->getUserByID($transferred_by);
                $scannedByName1 = $scannedBy1['file_as'] ?? null;

                $scannedBy2 = $userModel->getUserByID($received_by);
                $scannedByName2 = $scannedBy2['file_as'] ?? null;

                $statusClasses = [
                    'To Receive' => 'info',
                    'Received' => 'success',
                    'Cancelled' => 'warning',
                ];

                $defaultClass = 'dark';
                $action = '';

                $class = $statusClasses[$transfer_status] ?? $defaultClass;

                $badge = '<span class="badge bg-' . $class . '">' . $transfer_status . '</span>';

                if($transfer_status == 'To Receive'){
                    $action = '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-success update-unit-transfer" data-bs-toggle="offcanvas" data-bs-target="#unit-transfer-offcanvas" aria-controls="unit-transfer-offcanvas" data-unit-transfer-id="'. $unit_transfer_id .'" title="Update Unit Transfer">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-warning cancel-unit-transfer" data-unit-transfer-id="'. $unit_transfer_id .'" title="Cancel Unit Transfer">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>';
                }

                $response[] = [
                    'PRODUCT' => '<div class="col">
                                        <h6 class="mb-0">'. $stockNumber .'</h6>
                                        <p class="f-12 mb-0">'. $productName .'</p>
                                    </div>',
                    'TRANSFER_DETAILS' => '<div class="col">
                                        <h6 class="mb-0">'. $warehouse_name1 .' -> '. $warehouse_name2.'</h6>
                                    </div>',
                    'TRANSFERRED_DETAILS' => '<div class="col">
                                        <p class="f-12 mb-2">Transferred Date: '. $transfer_date .'</p>
                                        <p class="f-12 mb-2">Transferred By: '. $scannedByName1 .'</p>
                                        <p class="f-12 mb-0">Remarks: '. $transfer_remarks .'</p>
                                    </div>',
                    'RECEIVE_DETAILS' => '<div class="col">
                                        <p class="f-12 mb-2">Received Date: '. $received_date .'</p>
                                        <p class="f-12 mb-2">Received By: '. $scannedByName2 .'</p>
                                        <p class="f-12 mb-0">Remarks: '. $received_remarks .'</p>
                                    </div>',
                    'STATUS' => $badge,
                    'ACTION' => $action
    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>