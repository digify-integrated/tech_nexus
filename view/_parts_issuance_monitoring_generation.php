<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/product-model.php';
require_once '../model/parts-model.php';
require_once '../model/parts-incoming-model.php';
require_once '../model/unit-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$productModel = new ProductModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
$partsModel = new PartsModel($databaseModel);
$partsIncomingModel = new PartsIncomingModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {        
        # -------------------------------------------------------------
        #
        # Type: parts issuance monitoring table
        # Description:
        # Generates the parts issuance monitoring table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts issuance monitoring table':
            $sql = $databaseModel->getConnection()->prepare('SELECT DISTINCT(customer_id) AS product_id FROM part_transaction WHERE customer_type = "Internal" AND part_transaction_status IN ("Released", "Checked")');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $product_id = $row['product_id'];

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = $productDetails['stock_number'];
                $description = $productDetails['description'];

                $productIDEncrypted = $securityModel->encryptData($product_id);

                $response[] = [
                    'PRODUCT' => ' <div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-issuance-monitoring.php?id='. $productIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts issuance monitoring item table':
            $product_id = $_POST['product_id'];

            $productDetails = $productModel->getProduct($product_id);
            $stock_number = $productDetails['stock_number'];
            $description = $productDetails['description'];

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_cart WHERE part_transaction_id IN (SELECT part_transaction_id FROM part_transaction WHERE customer_type = "Internal" AND customer_id = :product_id AND part_transaction_status IN ("Released", "Checked"));');
            $sql->bindValue(':p_product_id', $product_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_issuance_monitoring_item_id = $row['part_issuance_monitoring_item_id'];
                $part_issuance_monitoring_id = $row['part_issuance_monitoring_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $quantity_issued = $row['quantity_issued'];
                $not_issued_quantity = $row['not_issued_quantity'];
                $part_issuance_item_status = $row['part_issuance_item_status'];
                $remarks = $row['remarks'];
                $reference_number = $row['reference_number'];
                $issuance_date = $systemModel->checkDate('empty', $row['issuance_date'], '', 'm/d/Y', '');

                $checkPartsIssuanceMonitoring = $partsIssuanceMonitoringModel->checkPartsIssuanceMonitoring($part_issuance_monitoring_id);
                $part_incoming_id = $checkPartsIssuanceMonitoring['part_incoming_id'];

                $partIncomingDetails = $partsIncomingModel->getPartsIncoming($part_incoming_id);
                $part_incoming_reference_number = $partIncomingDetails['reference_number'] ?? '';

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $bar_code = $partDetails['bar_code'];
                $unitSale = $partDetails['unit_sale'] ?? null;
                
                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;      

                if($part_issuance_item_status == 'Issued'){
                    $status = '<span class="badge bg-success">Issued</span>';
                }
                else if($part_issuance_item_status == 'Cancelled'){
                    $status = '<span class="badge bg-warning">Cancelled</span>';
                }
                else if($part_issuance_item_status == 'For Issuance' && $quantity_issued > 0 && $not_issued_quantity > 0){
                    $status = '<span class="badge bg-info">Partially Issued</span>';
                }
                else{
                    $status = '<span class="badge bg-info">For Issuance</span>';
                }

                $action = '';
                if($part_issuance_item_status == 'For Issuance'){
                    $action .= ' <button type="button" class="btn btn-icon btn-info issue-part-issuance" data-bs-toggle="offcanvas" data-bs-target="#issue-part-issuance-offcanvas" aria-controls="issue-part-issuance-offcanvas" data-parts-purchase-monitoring-item-id="'. $part_issuance_monitoring_item_id .'" title="Update Issue Part Issuance Item">
                                        <i class="ti ti-arrow-bar-to-down"></i>
                                    </button>';

                    if(!empty($reference_number) && $quantity_issued > 0){
                        $action .= ' <button type="button" class="btn btn-icon btn-success tag-part-issuance-issue" data-parts-purchase-monitoring-item-id="'. $part_issuance_monitoring_item_id .'" title="Issue Part Issuance Item">
                                            <i class="ti ti-check"></i>
                                        </button>';
                    }
                    
                    if($quantity_issued == 0){
                        $action .= ' <button type="button" class="btn btn-icon btn-warning cancel-part-issuance" data-bs-toggle="offcanvas" data-bs-target="#cancel-part-issuance-offcanvas" aria-controls="cancel-part-issuance-offcanvas" data-parts-purchase-monitoring-item-id="'. $part_issuance_monitoring_item_id .'" title="Cancel Part Issuance Item">
                                        <i class="ti ti-arrow-forward"></i>
                                    </button>';
                    }
                }

                $response[] = [
                    'PART' => ' <div class="col">
                                        <h6 class="mb-0">'. $description .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $bar_code .'</p>
                                        </div>',
                    'PRODUCT' => ' <div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'PART_INCOMING' => $part_incoming_reference_number,
                    'REFERENCE_NUMBER' => $reference_number,
                    'ISSUANCE_DATE' => $issuance_date,
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'QUANTITY_ISSUED' => number_format($quantity_issued, 2) . ' ' . $short_name,
                    'QUANTITY_NOT_ISSUED' => number_format($not_issued_quantity, 2) . ' ' . $short_name,
                    'STATUS' => $status,
                    'REMARKS' => $remarks
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>