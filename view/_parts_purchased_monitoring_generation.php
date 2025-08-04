<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-purchased-monitoring-model.php';
require_once '../model/product-model.php';
require_once '../model/parts-model.php';
require_once '../model/unit-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$productModel = new ProductModel($databaseModel);
$partsPurchasedMonitoringModel = new PartsPurchasedMonitoringModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
$partsModel = new PartsModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {        
        # -------------------------------------------------------------
        #
        # Type: parts purchased monitoring table
        # Description:
        # Generates the parts purchased monitoring table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts purchased monitoring table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsPurchasedMonitoringTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $partPurchasedMonitoringID = $row['part_purchased_monitoring_id'];
                $product_id = $row['product_id'];

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'];
                $description = $productDetails['description'];

                $partsPurchasedMonitoringIDEncrypted = $securityModel->encryptData($partPurchasedMonitoringID);

                $totalComplete = $partsPurchasedMonitoringModel->getPartsPurchasedMonitoringProgress($partPurchasedMonitoringID, 'issued')['total'] ?? 0;
                $totalInProgress = $partsPurchasedMonitoringModel->getPartsPurchasedMonitoringProgress($partPurchasedMonitoringID, 'for issuance')['total'] ?? 0;

                $totalItems = $totalComplete + $totalInProgress;

                $percentageComplete = $totalItems > 0 ? ($totalComplete / $totalItems) * 100 : 0;

                if($percentageComplete === 100){
                    $status = '<span class="badge bg-success">Completed</span>';
                }
                else if($percentageComplete === 0){
                    $status = '<span class="badge bg-info">For Issuance</span>';
                }
                else{
                    $status = '<span class="badge bg-warning">In Progress</span>';
                }

                $response[] = [
                    'PRODUCT' => ' <div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'STATUS' => $status,
                    'PROGRESS' => number_format($percentageComplete, 2) . "%",
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-purchased-monitoring.php?id='. $partsPurchasedMonitoringIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts purchased monitoring item table':
            $parts_purchased_monitoring_id = $_POST['parts_purchased_monitoring_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsPurchasedMonitoringItemTable(:parts_purchased_monitoring_id)');
            $sql->bindValue(':parts_purchased_monitoring_id', $parts_purchased_monitoring_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_purchased_monitoring_item_id = $row['part_purchased_monitoring_item_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $quantity_issued = $row['quantity_issued'];
                $not_issued_quantity = $row['not_issued_quantity'];
                $part_purchased_item_status = $row['part_purchased_item_status'];
                $remarks = $row['remarks'];
                $reference_number = $row['reference_number'];
                $issuance_date = $systemModel->checkDate('empty', $row['issuance_date'], '', 'm/d/Y', '');

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $bar_code = $partDetails['bar_code'];
                $unitSale = $partDetails['unit_sale'] ?? null;
                
                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;      

                if($part_purchased_item_status == 'Issued'){
                    $status = '<span class="badge bg-success">Issued</span>';
                }
                else if($part_purchased_item_status == 'Cancelled'){
                    $status = '<span class="badge bg-warning">Cancelled</span>';
                }
                else if($part_purchased_item_status == 'For Issuance' && $quantity_issued > 0 && $not_issued_quantity > 0){
                    $status = '<span class="badge bg-info">Partially Issued</span>';
                }
                else{
                    $status = '<span class="badge bg-info">For Issuance</span>';
                }

                $action = '';
                if($part_purchased_item_status == 'For Issuance'){
                    $action .= ' <button type="button" class="btn btn-icon btn-info issue-part-purchased" data-bs-toggle="offcanvas" data-bs-target="#issue-part-purchased-offcanvas" aria-controls="issue-part-purchased-offcanvas" data-parts-purchase-monitoring-item-id="'. $part_purchased_monitoring_item_id .'" title="Update Issue Part Purchased Item">
                                        <i class="ti ti-arrow-bar-to-down"></i>
                                    </button>';
                    $action .= ' <button type="button" class="btn btn-icon btn-success tag-part-purchased-issue" data-parts-purchase-monitoring-item-id="'. $part_purchased_monitoring_item_id .'" title="Issue Part Purchased Item">
                                        <i class="ti ti-check"></i>
                                    </button>';
                    if($quantity_issued == 0){
                        $action .= ' <button type="button" class="btn btn-icon btn-warning cancel-part-purchased" data-bs-toggle="offcanvas" data-bs-target="#cancel-part-purchased-offcanvas" aria-controls="cancel-part-purchased-offcanvas" data-parts-purchase-monitoring-item-id="'. $part_purchased_monitoring_item_id .'" title="Cancel Part Purchased Item">
                                        <i class="ti ti-arrow-forward"></i>
                                    </button>';
                    }
                }

                $response[] = [
                    'PART' => ' <div class="col">
                                        <h6 class="mb-0">'. $description .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $bar_code .'</p>
                                        </div>',
                    'REFERENCE_NUMBER' => $reference_number,
                    'ISSUANCE_DATE' => $issuance_date,
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'QUANTITY_ISSUED' => number_format($quantity_issued, 2) . ' ' . $short_name,
                    'QUANTITY_NOT_ISSUED' => number_format($not_issued_quantity, 2) . ' ' . $short_name,
                    'STATUS' => $status,
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