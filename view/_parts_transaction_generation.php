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

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsModel = new PartsModel($databaseModel);
$partsTransactionModel = new PartsTransactionModel($databaseModel);
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
            $parts_transaction_id = $_POST['parts_transaction_id'];
            $sql = $databaseModel->getConnection()->prepare('CALL generateInStockPartOptions(:parts_transaction_id)');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
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
            $parts_transaction_id = $_POST['parts_transaction_id'];

            $partTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartItemTable(:parts_transaction_id)');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_cart_id = $row['part_transaction_cart_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $discount = $row['discount'];
                $discount_type = $row['discount_type'];
                $discount_total = $row['discount_total'];
                $sub_total = $row['sub_total'];
                $total = $row['total'];
                $add_on = $row['add_on'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $bar_code = $partDetails['bar_code'];
                $part_price = $partDetails['part_price'];

                $partsImage = $systemModel->checkImage($partDetails['part_image'], 'default');

                if($discount_type === 'Amount'){
                    $discount = number_format($discount, 2) . ' PHP';
                }
                else{
                    $discount = number_format($discount, decimals: 2) . '%';
                }

                $action = '';
                if($part_transaction_status == 'Draft' || $part_transaction_status == 'On-Process'){
                    $action = ' <button type="button" class="btn btn-icon btn-success update-part-cart" data-bs-toggle="offcanvas" data-bs-target="#part-cart-offcanvas" aria-controls="part-cart-offcanvas" data-parts-transaction-cart-id="'. $part_transaction_cart_id .'" title="Update Part Item">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-danger delete-part-cart" data-parts-transaction-cart-id="'. $part_transaction_cart_id .'" title="Delete Part Item">
                                        <i class="ti ti-trash"></i>
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
                    'PRICE' => number_format($part_price, 2) .' PHP',
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'AVAILABLE_STOCK' => number_format($partQuantity, 0, '', ',') . ' ' . $short_name,
                    'ADD_ON' => number_format($add_on, 2) .' PHP',
                    'DISCOUNT' => $discount,
                    'DISCOUNT_TOTAL' => number_format($discount_total, 2) .' PHP',
                    'SUBTOTAL' => number_format($sub_total, 2) .' PHP',
                    'TOTAL' => number_format($total, 2) .' PHP',
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'part transaction document table':
            $parts_transaction_id = $_POST['parts_transaction_id'];
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsTransactionDocument(:parts_transaction_id)');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_document_id = $row['part_transaction_document_id'];
                $document_name = $row['document_name'];
                $document_file_path = $row['document_file_path'];
                $transaction_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');

                $documentType = '<a href="'. $document_file_path .'" target="_blank">' . $document_name . "</a>";

                $response[] = [
                    'DOCUMENT' => $documentType,
                    'UPLOAD_DATE' => $transaction_date,
                    'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-danger delete-parts-document" data-parts-transaction-document-id="'. $part_transaction_document_id .'" title="Delete Transaction Document">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'parts transaction table':
            $filterTransactionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_start_date'], '', 'Y-m-d', '');
            $filterTransactionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transaction_date_end_date'], '', 'Y-m-d', '');
            $filter_approval_date_start_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_start_date'], '', 'Y-m-d', '');
            $filter_approval_date_end_date = $systemModel->checkDate('empty', $_POST['filter_approval_date_end_date'], '', 'Y-m-d', '');
            $transaction_status_filter = $_POST['filter_transaction_status'];

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

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsTransactionTable(:filterTransactionDateStartDate, :filterTransactionDateEndDate, :filter_approval_date_start_date, :filter_approval_date_end_date, :filter_transaction_status)');
            $sql->bindValue(':filterTransactionDateStartDate', $filterTransactionDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterTransactionDateEndDate', $filterTransactionDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_start_date', $filter_approval_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_approval_date_end_date', $filter_approval_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_transaction_status', $filter_transaction_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_id = $row['part_transaction_id'];
                $part_transaction_status = $row['part_transaction_status'];
                $number_of_items = $row['number_of_items'];
                $add_on = $row['add_on'];
                $sub_total = $row['sub_total'];
                $total_discount = $row['total_discount'];
                $total_amount = $row['total_amount'];
                $transaction_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');

                if($part_transaction_status === 'Draft'){
                    $part_transaction_status = '<span class="badge bg-secondary">' . $part_transaction_status . '</span>';
                }
                else if($part_transaction_status === 'Cancelled'){
                    $part_transaction_status = '<span class="badge bg-warning">' . $part_transaction_status . '</span>';
                }
                else if($part_transaction_status === 'For Approval'){
                    $part_transaction_status = '<span class="badge bg-info">' . $part_transaction_status . '</span>';
                }
                else{
                    $part_transaction_status = '<span class="badge bg-success">' . $part_transaction_status . '</span>';
                }

                $part_transaction_id_encrypted = $securityModel->encryptData($part_transaction_id);

                $response[] = [
                    'TRANSACTION_ID' => $part_transaction_id,
                    'NUMBER_OF_ITEMS' => number_format($number_of_items, 0),
                    'ADD_ON' => number_format($add_on, 2) . ' PHP',
                    'DISCOUNT' => number_format($total_discount, 2) . ' PHP',
                    'SUB_TOTAL' => number_format($sub_total, 2) . ' PHP',
                    'TOTAL_AMOUNT' => number_format($total_amount, 2) . ' PHP',
                    'TRANSACTION_DATE' => $transaction_date,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-transaction.php?id='. $part_transaction_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details">
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