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
require_once '../model/unit-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsModel = new PartsModel($databaseModel);
$partsIncomingModel = new PartsIncomingModel($databaseModel);
$partsSubclassModel = new PartsSubclassModel($databaseModel);
$partsClassModel = new PartsClassModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
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
            $sql = $databaseModel->getConnection()->prepare('CALL generateAllPartOptions(:parts_incoming_id)');
            $sql->bindValue(':parts_incoming_id', $parts_incoming_id, PDO::PARAM_STR);
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

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartIncomingItemTable(:parts_incoming_id)');
            $sql->bindValue(':parts_incoming_id', $parts_incoming_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_incoming_cart_id = $row['part_incoming_cart_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $received_quantity = $row['received_quantity'];
                $remaining_quantity = $row['remaining_quantity'];
                $remarks = $row['remarks'];
                $cost = $row['cost'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $bar_code = $partDetails['bar_code'];
                $part_price = $partDetails['part_price'];

                $partsImage = $systemModel->checkImage($partDetails['part_image'], 'default');

                $action = '';
                if($part_incoming_status == 'Draft'){
                    $action = '
                    <button type="button" class="btn btn-icon btn-success update-part-cart" data-bs-toggle="offcanvas" data-bs-target="#part-cart-offcanvas" aria-controls="part-cart-offcanvas" data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Update Part Item">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-danger delete-part-cart" data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Delete Part Item">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }
                else if($part_incoming_status == 'On-Process' && $remaining_quantity > 0){
                    $action = ' <button type="button" class="btn btn-icon btn-warning receive-quantity" data-bs-toggle="offcanvas" data-bs-target="#receive-item-offcanvas" aria-controls="receive-item-offcanvas" data-parts-incoming-cart-id="'. $part_incoming_cart_id .'" title="Receive Item">
                                        <i class="ti ti-arrow-bar-to-down"></i>
                                    </button>';
                }
                
                $partDetails = $partsModel->getParts($part_id);
                $partQuantity = $partDetails['quantity'] ?? 0;
                $unitSale = $partDetails['unit_sale'] ?? null;

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;

                $response[] = [
                    'PART' => '<div class="d-flex align-items-center"><img src="'. $partsImage .'" alt="image" class="bg-light wid-50 rounded">
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">'. $description .'</h5>
                                        <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                    </div>
                                </div>',
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'RECEIVED_QUANTITY' => number_format($received_quantity, 2) . ' ' . $short_name,
                    'REMAINING_QUANTITY' => number_format($remaining_quantity, 2) . ' ' . $short_name,
                    'COST' => number_format($cost, 2) . ' PHP',
                    'TOTAL_COST' => number_format($cost * $quantity, 2) . ' PHP',
                    'AVAILABLE_STOCK' => number_format($partQuantity, 0, '', ',') . ' ' . $short_name,
                    'REMARKS' => $remarks,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
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

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsIncomingTable(:filterIncomingDateStartDate, :filterIncomingDateEndDate, :filter_released_date_start_date, :filter_released_date_end_date, :filter_purchased_date_start_date, :filter_purchased_date_end_date, :filter_incoming_status)');
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
                $incoming_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $completion_date = $systemModel->checkDate('empty', $row['completion_date'], '', 'm/d/Y', '');
                $purchase_date = $systemModel->checkDate('empty', $row['purchase_date'], '', 'm/d/Y', '');

                $cost = $partsIncomingModel->getPartsIncomingCartTotal($part_incoming_id, 'cost')['total'];
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

                $part_incoming_id_encrypted = $securityModel->encryptData($part_incoming_id);

                $response[] = [
                    'TRANSACTION_ID' => $reference_number,
                    'LINES' => number_format($lines, 0),
                    'QUANTITY' => number_format($quantity, 2),
                    'RECEIVED' => number_format($received, 2),
                    'REMAINING' => number_format($remaining, 2),
                    'COST' => number_format($cost, 2) . ' PHP',
                    'COMPLETION_DATE' => $completion_date,
                    'PURCHASE_DATE' => $purchase_date,
                    'TRANSACTION_DATE' => $incoming_date,
                    'STATUS' => $part_incoming_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-incoming.php?id='. $part_incoming_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details">
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