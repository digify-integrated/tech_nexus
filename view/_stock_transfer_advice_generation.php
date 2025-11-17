<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-model.php';
require_once '../model/stock-transfer-advice-model.php';
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

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsModel = new PartsModel($databaseModel);
$stockTransferAdviceModel = new StockTransferAdviceModel($databaseModel);
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
        case 'add part table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part
            WHERE part_id NOT IN (SELECT part_id FROM stock_transfer_advice_cart WHERE stock_transfer_advice_id = :stock_transfer_advice_id)
            ORDER BY description');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
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
                    'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input assign-part" type="checkbox" value="'. $part_id.'"></div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];

            $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_job_order WHERE part_transaction_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_job_order_id  = $row['part_transaction_job_order_id'];
                $job_order = $row['job_order_id'];
                
                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalJobOrder($job_order);
                $sales_proposal_id = $salesProposalJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $salesProposalJobOrderDetails['job_order'] ?? null;
                $contractor_id = $salesProposalJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $salesProposalJobOrderDetails['work_center_id'] ?? null;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($part_transaction_status == 'Draft' || $part_transaction_status == 'For Validation' || $part_transaction_status == 'For Approval'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-job-order" data-stock-transfer-advice-job-order-id="'. $part_transaction_job_order_id  .'" title="Delete Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'internal job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];

            $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_job_order WHERE part_transaction_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'internal job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_job_order_id  = $row['part_transaction_job_order_id'];
                $job_order = $row['job_order_id'];
                
                $backJobMonitoringJobOrderDetails = $backjobMonitoringModel->getBackJobMonitoringJobOrder($job_order);
                $sales_proposal_id = $backJobMonitoringJobOrderDetails['sales_proposal_id'] ?? null;
                $backjob_monitoring_id = $backJobMonitoringJobOrderDetails['backjob_monitoring_id'] ?? null;
                $job_order = $backJobMonitoringJobOrderDetails['job_order'] ?? null;
                $contractor_id = $backJobMonitoringJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $backJobMonitoringJobOrderDetails['work_center_id'] ?? null;

                $backJobMonitoringDetails = $backjobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
                $backJobMonitoringType = $backJobMonitoringDetails['type'] ?? null;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($part_transaction_status == 'Draft' || $part_transaction_status == 'For Validation' || $part_transaction_status == 'For Approval'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-internal-job-order" data-stock-transfer-advice-job-order-id="'. $part_transaction_job_order_id  .'" title="Delete Internal Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'TYPE' => $backJobMonitoringType,
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'additional job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];

            $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_additional_job_order WHERE part_transaction_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'additional job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_additional_job_order_id   = $row['part_transaction_additional_job_order_id'];
                $job_order = $row['additional_job_order_id'];
                
                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalAdditionalJobOrder($job_order);
                $sales_proposal_id = $salesProposalJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $salesProposalJobOrderDetails['particulars'] ?? null;
                $contractor_id = $salesProposalJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $salesProposalJobOrderDetails['work_center_id'] ?? null;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($part_transaction_status == 'Draft' || $part_transaction_status == 'For Validation' || $part_transaction_status == 'For Approval'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-additional-job-order" data-stock-transfer-advice-additional-job-order-id="'. $part_transaction_additional_job_order_id   .'" title="Delete Additional Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'internal additional job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];

            $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_additional_job_order WHERE part_transaction_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'internal additional job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_additional_job_order_id   = $row['part_transaction_additional_job_order_id '];
                $job_order = $row['additional_job_order_id'];
                
                $backJobMonitoringJobOrderDetails = $backjobMonitoringModel->getBackJobMonitoringAdditionalJobOrder($job_order);
                $sales_proposal_id = $backJobMonitoringJobOrderDetails['sales_proposal_id'] ?? null;
                $backjob_monitoring_id = $backJobMonitoringJobOrderDetails['backjob_monitoring_id'] ?? null;
                $job_order = $backJobMonitoringJobOrderDetails['particulars'] ?? null;
                $contractor_id = $backJobMonitoringJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $backJobMonitoringJobOrderDetails['work_center_id'] ?? null;

                $backJobMonitoringDetails = $backjobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
                $backJobMonitoringType = $backJobMonitoringDetails['type'] ?? null;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($part_transaction_status == 'Draft' || $part_transaction_status == 'For Validation' || $part_transaction_status == 'For Approval'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-internal-additional-job-order" data-stock-transfer-advice-additional-job-order-id="'. $part_transaction_additional_job_order_id  .'" title="Delete Internal Additional Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'TYPE' => $backJobMonitoringType,
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'add additional job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
            $generate_job_order = $_POST['generate_job_order'];

            $stockTransferAdviceDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $customer_id = $stockTransferAdviceDetails['customer_id'] ?? null;

            #$company = $_POST['company'];
            $sql = $databaseModel->getConnection()->prepare('CALL generateStockTransferAdviceAdditionalJobOrderOptions(:stock_transfer_advice_id, :customer_id, :generate_job_order)');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':customer_id', $customer_id, PDO::PARAM_STR);
            $sql->bindValue(':generate_job_order', $generate_job_order, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                if($generate_job_order === 'additional job order'){
                    $job_order_id = $row['sales_proposal_additional_job_order_id'];
                    $sales_proposal_id = $row['sales_proposal_id'];
                }
                else{
                    $job_order_id = $row['backjob_monitoring_additional_job_order_id'];
                    $sales_proposal_id = $row['sales_proposal_id'];
                }

                $job_order = $row['particulars'];

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $reference_id = $salesProposalDetails['sales_proposal_number'] ?? '--';
                $customer_id = $salesProposalDetails['customer_id'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customer_id);
                $customerName = $customerDetails['file_as'] ?? null;

                $response[] = [
                    'CUSTOMER_NAME' => $customerName,
                    'REFERENCE_ID' => $reference_id,
                    'JOB_ORDER' => strtoupper($job_order),
                    'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input assign-additional-job-order" type="checkbox" value="'. $job_order_id.'"></div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        case 'part item table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];

            $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartItemTable(:stock_transfer_advice_id)');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $order = 0;
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
                $price = $row['price'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $bar_code = $partDetails['bar_code'];

                $partsImage = $systemModel->checkImage($partDetails['part_image'], 'default');

                if($discount_type === 'Amount'){
                    $discount = number_format($discount, 2) . ' PHP';
                }
                else{
                    $discount = number_format($discount, decimals: 2) . '%';
                }

                $action = '';
                if($part_transaction_status == 'Draft'){
                    $action = ' <button type="button" class="btn btn-icon btn-success update-part-cart" data-bs-toggle="offcanvas" data-bs-target="#part-cart-offcanvas" aria-controls="part-cart-offcanvas" data-stock-transfer-advice-cart-id="'. $part_transaction_cart_id .'" title="Update Part Item">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-danger delete-part-cart" data-stock-transfer-advice-cart-id="'. $part_transaction_cart_id .'" title="Delete Part Item">
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
                    'PART' => '<div class="d-flex align-items-center"><img src="'. $partsImage .'" alt="image" class="bg-light wid-50 rounded">
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">'. $description .'</h5>
                                        <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                    </div>
                                </div>',
                    'PRICE' => number_format($price, 2) .' PHP',
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'AVAILABLE_STOCK' => number_format($partQuantity, 0, '', ',') . ' ' . $short_name,
                    'ADD_ON' => number_format($add_on, 2) .' PHP',
                    'DISCOUNT' => $discount,
                    'ORDER' => $order,
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
        case 'part item table 2':
            $parts_id = $_POST['parts_id'];

            $stock_transfer_advice_start_date = $systemModel->checkDate('empty', $_POST['stock_transfer_advice_start_date'], '', 'Y-m-d', '');
            $stock_transfer_advice_end_date = $systemModel->checkDate('empty', $_POST['stock_transfer_advice_end_date'], '', 'Y-m-d', '');

            $partDetails = $partsModel->getParts($parts_id);
            $unitSale = $partDetails['unit_sale'] ?? null;

            $unitCode = $unitModel->getUnit($unitSale);
            $short_name = $unitCode['short_name'] ?? null;            

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartItemTable2(:parts_id, :stock_transfer_advice_start_date, :stock_transfer_advice_end_date)');
            $sql->bindValue(':parts_id', $parts_id, PDO::PARAM_STR);
            $sql->bindValue(':stock_transfer_advice_start_date', $stock_transfer_advice_start_date, PDO::PARAM_STR);
            $sql->bindValue(':stock_transfer_advice_end_date', $stock_transfer_advice_end_date, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_cart_id = $row['part_transaction_cart_id'];
                $part_transaction_id = $row['part_transaction_id'];
                $quantity = $row['quantity'];
                $discount = $row['discount'];
                $discount_type = $row['discount_type'];
                $discount_total = $row['discount_total'];
                $sub_total = $row['sub_total'];
                $total = $row['total'];
                $add_on = $row['add_on'];
                $price = $row['price'];
                


                $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($part_transaction_id);
                $company_id = $partTransactionDetails['company_id'];
                 $customer_type = $partTransactionDetails['customer_type'];
                $customer_id = $partTransactionDetails['customer_id'];
                $request_by = $partTransactionDetails['request_by'];
                $customer_ref_id = $partTransactionDetails['customer_ref_id'];
                $released_date = $systemModel->checkDate('empty', $partTransactionDetails['released_date'], '', 'm/d/Y h:i:s', '');

                
                $customerDetails = $customerModel->getPersonalInformation($customer_ref_id);
                $customerName = $customerDetails['file_as'] ?? null;               

                if($customer_type == 'Internal'){
                    $productDetails = $productModel->getProduct($customer_id);
                    $stock_number = $productDetails['stock_number'];
                }
                else{
                    $stock_number = '--';
                }

                $partsImage = $systemModel->checkImage($partDetails['part_image'], 'default');

                if($discount_type === 'Amount'){
                    $discount = number_format($discount, 2) . ' PHP';
                }
                else{
                    $discount = number_format($discount, decimals: 2) . '%';
                }

                $part_transaction_id_encrypted = $securityModel->encryptData($part_transaction_id);

                if($company_id == '1'){
                    $link = 'supplies-transaction';
                }
                else if($company_id == '2'){
                    $link = 'netruck-stock-transfer-advice';
                }
                else{
                    $link = 'stock-transfer-advice';
                }
                
                $response[] = [
                    'PART_TRANSACTION_NO' => '<a href="'. $link .'.php?id='. $part_transaction_id_encrypted .'" target="_blank">
                                        '. $part_transaction_id .'
                                    </a>',
                    'PRODUCT' => $stock_number,
                    'CUSTOMER' => $customerName,
                    'REQUESTED_BY' => $request_by,
                    'RELEASED_DATE' => $released_date,
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'ADD_ON' => number_format($add_on, 2) .' PHP',
                    'DISCOUNT' => $discount,
                    'DISCOUNT_TOTAL' => number_format($discount_total, 2) .' PHP',
                    'SUBTOTAL' => number_format($sub_total, 2) .' PHP',
                    'TOTAL' => number_format($total, 2) .' PHP',
                ];
            }

            echo json_encode($response);
        break;
        case 'part item table 3':
            $product_id = $_POST['product_id'];

            $stock_transfer_advice_start_date = $systemModel->checkDate('empty', $_POST['stock_transfer_advice_start_date'], '', 'Y-m-d', '');
            $stock_transfer_advice_end_date = $systemModel->checkDate('empty', $_POST['stock_transfer_advice_end_date'], '', 'Y-m-d', '');                   

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartItemTable3(:product_id, :stock_transfer_advice_start_date, :stock_transfer_advice_end_date)');
            $sql->bindValue(':product_id', $product_id, PDO::PARAM_STR);
            $sql->bindValue(':stock_transfer_advice_start_date', $stock_transfer_advice_start_date, PDO::PARAM_STR);
            $sql->bindValue(':stock_transfer_advice_end_date', $stock_transfer_advice_end_date, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_cart_id = $row['part_transaction_cart_id'];
                $part_transaction_id = $row['part_transaction_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $discount = $row['discount'];
                $discount_type = $row['discount_type'];
                $discount_total = $row['discount_total'];
                $sub_total = $row['sub_total'];
                $total = $row['total'];
                $add_on = $row['add_on'];
                $price = $row['price'];
               
                $partDetails = $partsModel->getParts($part_id);
                 $description = $partDetails['description'];
                $unitSale = $partDetails['unit_sale'] ?? null;

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;   

                $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($part_transaction_id);
                $company_id = $partTransactionDetails['company_id'];
                $customer_type = $partTransactionDetails['customer_type'];
                $customer_ref_id = $partTransactionDetails['customer_ref_id'];
                $customer_id = $partTransactionDetails['customer_id'];

                $customerDetails = $customerModel->getPersonalInformation($customer_ref_id);
                $customer_ref_name = $customerDetails['file_as'] ?? null;

                if($customer_type == 'Internal'){
                    $productDetails = $productModel->getProduct($customer_id);
                    $stock_number = $productDetails['stock_number'];
                }
                else{
                    $stock_number = '--';
                }

                $partsImage = $systemModel->checkImage($partDetails['part_image'], 'default');

                if($discount_type === 'Amount'){
                    $discount = number_format($discount, 2) . ' PHP';
                }
                else{
                    $discount = number_format($discount, decimals: 2) . '%';
                }

                $part_transaction_id_encrypted = $securityModel->encryptData($part_transaction_id);

                if($company_id == '1'){
                    $link = 'supplies-transaction';
                }
                else if($company_id == '2'){
                    $link = 'netruck-stock-transfer-advice';
                }
                else{
                    $link = 'stock-transfer-advice';
                }
                
                $response[] = [
                    'PART_TRANSACTION_NO' => '<a href="'. $link .'.php?id='. $part_transaction_id_encrypted .'" target="_blank">
                                        '. $part_transaction_id .'
                                    </a>',
                    'CUSTOMER' => $customer_ref_name,
                    'PRODUCT' => $stock_number,
                    'PART' => $description,
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'ADD_ON' => number_format($add_on, 2) .' PHP',
                    'DISCOUNT' => $discount,
                    'DISCOUNT_TOTAL' => number_format($discount_total, 2) .' PHP',
                    'SUBTOTAL' => number_format($sub_total, 2) .' PHP',
                    'TOTAL' => number_format($total, 2) .' PHP',
                ];
            }

            echo json_encode($response);
        break;
        case 'part transaction document table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
            $sql = $databaseModel->getConnection()->prepare('CALL generateStockTransferAdviceDocument(:stock_transfer_advice_id)');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
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
                                        <button type="button" class="btn btn-icon btn-danger delete-parts-document" data-stock-transfer-advice-document-id="'. $part_transaction_document_id .'" title="Delete Transaction Document">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'stock transfer advice table':
            $filter_created_date_start_date = $systemModel->checkDate('empty', $_POST['filter_created_date_start_date'], '', 'Y-m-d', '');
            $filter_created_date_end_date = $systemModel->checkDate('empty', $_POST['filter_created_date_end_date'], '', 'Y-m-d', '');
            $filter_on_process_date_start_date = $systemModel->checkDate('empty', $_POST['filter_on_process_date_start_date'], '', 'Y-m-d', '');
            $filter_on_process_date_end_date = $systemModel->checkDate('empty', $_POST['filter_on_process_date_end_date'], '', 'Y-m-d', '');
            $filter_completed_date_start_date = $systemModel->checkDate('empty', $_POST['filter_completed_date_start_date'], '', 'Y-m-d', '');
            $filter_completed_date_end_date = $systemModel->checkDate('empty', $_POST['filter_completed_date_end_date'], '', 'Y-m-d', '');
            $filter_sta_status = $_POST['filter_sta_status'];

            if (!empty($transaction_filter_sta_statusstatus_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_sta_status)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_sta_status = implode(', ', $quoted_values_array);
            } else {
                $filter_sta_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateStockTransferAdviceTable(:filter_created_date_start_date, :filter_created_date_end_date, :filter_on_process_date_start_date, :filter_on_process_date_end_date, :filter_completed_date_start_date, :filter_completed_date_end_date, :filter_sta_status)');
            $sql->bindValue(':filter_created_date_start_date', $filter_created_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_created_date_end_date', $filter_created_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_on_process_date_start_date', $filter_on_process_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_on_process_date_end_date', $filter_on_process_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_completed_date_start_date', $filter_completed_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_completed_date_end_date', $filter_completed_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_sta_status', $filter_sta_status, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $stock_transfer_advice_id = $row['stock_transfer_advice_id'];
                $reference_no = $row['reference_no'];
                $transferred_from = $row['transferred_from'];
                $transferred_to = $row['transferred_to'];
                $sta_status = $row['sta_status'];

                if($sta_status === 'Draft'){
                    $sta_status = '<span class="badge bg-secondary">' . $sta_status . '</span>';
                }
                else if($sta_status === 'Cancelled'){
                    $sta_status = '<span class="badge bg-warning">' . $sta_status . '</span>';
                }
                else if($sta_status === 'On-Process'){
                    $sta_status = '<span class="badge bg-info">'. $sta_status .'</span>';
                }
                else{
                    $sta_status = '<span class="badge bg-success">' . $sta_status . '</span>';
                }

                $productDetails1 = $productModel->getProduct($transferred_from);
                $productName1 = $productDetails1['description'] ?? null;
                $stockNumber1 = $productDetails1['stock_number'] ?? null;

                $productDetails2 = $productModel->getProduct($transferred_to);
                $productName2 = $productDetails2['description'] ?? null;
                $stockNumber2 = $productDetails2['stock_number'] ?? null;

                $stock_transfer_advice_id_encrypted = $securityModel->encryptData($stock_transfer_advice_id);

                $response[] = [
                    'REFERENCE_NO' => $reference_no,
                    'FROM' => '<div class="col">
                                        <h6 class="mb-0">'. $productName1 .'</h6>
                                        <p class="f-12 mb-0">'. $stockNumber1 .'</p>
                                    </div>',
                    'TO' => '<div class="col">
                                        <h6 class="mb-0">'. $productName2 .'</h6>
                                        <p class="f-12 mb-0">'. $stockNumber2 .'</p>
                                    </div>',
                    'STATUS' => $sta_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="stock-transfer-advice.php?id='. $stock_transfer_advice_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'stock transfer advice dashboard table':

            $sql = $databaseModel->getConnection()->prepare('CALL generateStockTransferAdviceDashboardTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $part_transaction_id = $row['part_transaction_id'];
                $part_transaction_status = $row['part_transaction_status'];
                $customer_type = $row['customer_type'];
                $customer_id = $row['customer_id'];
                $number_of_items = $row['number_of_items'];
                $add_on = $row['add_on'];
                $sub_total = $row['sub_total'];
                $total_discount = $row['total_discount'];
                $total_amount = $row['total_amount'];
                $issuance_no = $row['issuance_no'];
                $company_id = $row['company_id'];
                $reference_number = $row['reference_number'];
                $transaction_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $issuance_date = $systemModel->checkDate('empty', $row['issuance_date'], '', 'm/d/Y', '');

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

                if($company_id == '1'){
                    $link = 'supplies-transaction';
                    $number = $issuance_no;
                }
                else if($company_id == '2'){
                    $link = 'netruck-stock-transfer-advice';
                    $number = $issuance_no;
                }
                else{
                    $link = 'stock-transfer-advice';
                    $number = $reference_number;
                }

                if($customer_type === 'Miscellaneous'){
                    $customerDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
                    $transaction_reference = $customerDetails['client_name'] ?? 'N/A';
                }
                else if($customer_type === 'Customer'){
                    $customerDetails = $customerModel->getPersonalInformation($customer_id);
                    $transaction_reference = $customerDetails['file_as'] ?? null;
                }
                else{
                    $productDetails = $productModel->getProduct($customer_id);
                    $stock_number = $productDetails['stock_number'];
                    $description = $productDetails['description'];
                    $transaction_reference = '<div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>';
                }

                $response[] = [
                    'TRANSACTION_ID' => '<a href="'. $link .'.php?id='. $part_transaction_id_encrypted .'" title="View Details" target="_blank">
                                       '. $number .'
                                    </a>',
                    'CUSTOMER_TYPE' => $customer_type,
                    'REFERENCE' => $transaction_reference,
                    'NUMBER_OF_ITEMS' => number_format($number_of_items, 0),
                    'TOTAL_AMOUNT' => number_format($total_amount, 2) . ' PHP',
                    'TRANSACTION_DATE' => $transaction_date,
                    'ISSUANCE_DATE' => $issuance_date,
                    'STATUS' => $part_transaction_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $part_transaction_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'stock transfer advice dashboard list':
            $sql = $databaseModel->getConnection()->prepare('CALL generateStockTransferAdviceDashboardTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $list = '';
            foreach ($options as $row) {
               $part_transaction_id = $row['part_transaction_id'];
                $part_transaction_status = $row['part_transaction_status'];
                $customer_type = $row['customer_type'];
                $customer_id = $row['customer_id'];
                $number_of_items = $row['number_of_items'];
                $add_on = $row['add_on'];
                $sub_total = $row['sub_total'];
                $total_discount = $row['total_discount'];
                $total_amount = $row['total_amount'];
                $issuance_no = $row['issuance_no'];
                $company_id = $row['company_id'];
                $reference_number = $row['reference_number'];
                $transaction_date = $systemModel->checkDate('empty', $row['created_date'], '', 'm/d/Y', '');
                $issuance_date = $systemModel->checkDate('empty', $row['issuance_date'], '', 'm/d/Y', '');

                if($part_transaction_status === 'Draft'){
                    $part_transaction_status = '<span class="badge bg-secondary">' . $part_transaction_status . '</span>';
                }
                else if($part_transaction_status === 'Cancelled'){
                    $part_transaction_status = '<span class="badge bg-warning">' . $part_transaction_status . '</span>';
                }
                else if($part_transaction_status === 'For Approval' || $part_transaction_status === 'For Validation'){
                    $part_transaction_status = '<span class="badge bg-info">For Validation</span>';
                }
                else if($part_transaction_status === 'Approved' || $part_transaction_status === 'Validated'){
                    $part_transaction_status = '<span class="badge bg-success">Validated</span>';
                }
                else{
                    $part_transaction_status = '<span class="badge bg-success">' . $part_transaction_status . '</span>';
                }


                $part_transaction_id_encrypted = $securityModel->encryptData($part_transaction_id);

                if($company_id == '1'){
                    $link = 'supplies-transaction';
                    $number = $issuance_no;
                }
                else if($company_id == '2'){
                    $link = 'netruck-stock-transfer-advice';
                    $number = $issuance_no;
                }
                else{
                    $link = 'stock-transfer-advice';
                    $number = $reference_number;
                }

                if($customer_type === 'Miscellaneous'){
                    $customerDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
                    $transaction_reference = $customerDetails['client_name'] ?? 'N/A';
                }
                else if($customer_type === 'Customer'){
                    $customerDetails = $customerModel->getPersonalInformation($customer_id);
                    $transaction_reference = $customerDetails['file_as'] ?? null;
                }
               else if($customer_type === 'Department'){
                    $departmentDetails = $departmentModel->getDepartment($customer_id);
                    $transaction_reference = $departmentDetails['department_name'] ?? null;
                }
                else{
                    $productDetails = $productModel->getProduct($customer_id);
                    $stock_number = $productDetails['stock_number'];
                    $description = $productDetails['description'];
                    $transaction_reference = '<div class="col">
                                        <h6 class="mb-0">'. $stock_number .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>';
                }

                 $list .= ' <li class="list-group-item">
                          <div class="d-flex align-items-center">
                              <div class="flex-grow-1 ms-3">
                                  <div class="row g-1">
                                        <div class="col-9">
                                            <a href="'. $link .'.php?id='. $part_transaction_id_encrypted .'" title="View Details" target="_blank">
                                                <p class="mb-0"><b>'. strtoupper($transaction_reference) .'</b></p>
                                                <p class="text-muted mb-0"><small>Issuance Number: '. $number .'</small></p>
                                                <p class="text-muted mb-0"><small>Transaction Type: '. strtoupper($customer_type) .'</small></p>
                                                <p class="text-muted mb-0"><small>No. Items: '. number_format($number_of_items, 0) .'</small></p>
                                                <p class="text-muted mb-0"><small>Net Amount: '. number_format($total_amount, 2) .' PHP</small></p>
                                                <p class="text-muted mb-0"><small>Transaction Date: '. $transaction_date .'</small></p>
                                            </a>
                                      </div>
                                      <div class="col-3 text-end">
                                          '. $part_transaction_status .'
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </li>';
            }

            if(empty($list)){
                $list = ' <li class="list-group-item text-center"><b>No Parts Issuance For Approval Found</b></li>';
            }

            echo json_encode(['LIST' => $list]);
        break;
        # -------------------------------------------------------------
    }
}

?>