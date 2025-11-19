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
require_once '../model/product-subcategory-model.php';

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
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'add part from table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part
            WHERE part_id NOT IN (SELECT part_id FROM stock_transfer_advice_cart WHERE stock_transfer_advice_id = :stock_transfer_advice_id AND part_from = :part_from)
            ORDER BY description');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':part_from', 'From', PDO::PARAM_STR);
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
        case 'add part to table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part
            WHERE part_id NOT IN (SELECT part_id FROM stock_transfer_advice_cart WHERE stock_transfer_advice_id = :stock_transfer_advice_id AND part_from = :part_from)
            ORDER BY description');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':part_from', 'To', PDO::PARAM_STR);
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
        case 'add job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
            $generate_job_order = $_POST['generate_job_order'];

            $partsTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $transferred_from = $partsTransactionDetails['transferred_from'] ?? null;
            $transferred_to = $partsTransactionDetails['transferred_to'] ?? null;

            $query = '';

            if($generate_job_order == 'job order'){
                $query = 'SELECT * FROM sales_proposal_job_order
                            WHERE sales_proposal_id IN (select sales_proposal_id FROM sales_proposal where product_id IN ('. $transferred_from .', '. $transferred_to .') AND sales_proposal_status IN ("On-Process"))
                            AND sales_proposal_job_order_id NOT IN (select job_order_id from stock_transfer_advice_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id)
                            AND progress < 100
                            ORDER BY job_order';
            }
            else{
                $query = ' SELECT * FROM backjob_monitoring_job_order
                            WHERE backjob_monitoring_id IN (select backjob_monitoring_id FROM backjob_monitoring where product_id IN ('. $transferred_from .', '. $transferred_to .') AND status NOT IN ("Draft", "Cancelled", "For Approval"))
                            AND backjob_monitoring_job_order_id NOT IN (select job_order_id from stock_transfer_advice_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id)
                            AND progress < 100
                            ORDER BY job_order';
            }

            $sql = $databaseModel->getConnection()->prepare($query);
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                if($generate_job_order === 'job order'){
                    $job_order_id = $row['sales_proposal_job_order_id'];
                    $sales_proposal_id = $row['sales_proposal_id'];
                }
                else{
                    $job_order_id = $row['backjob_monitoring_job_order_id'];
                    $sales_proposal_id = $row['sales_proposal_id'];
                }

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $reference_id = $salesProposalDetails['sales_proposal_number'] ?? '--';
                $customer_id = $salesProposalDetails['customer_id'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customer_id);
                $customerName = $customerDetails['file_as'] ?? null;

                $job_order = $row['job_order'];

                $response[] = [
                    'CUSTOMER_NAME' => $customerName,
                    'REFERENCE_ID' => $reference_id,
                    'JOB_ORDER' => strtoupper($job_order),
                    'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input assign-job-order" type="checkbox" value="'. $job_order_id.'"></div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'add additional job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];
            $generate_job_order = $_POST['generate_job_order'];

            $partsTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $transferred_from = $partsTransactionDetails['transferred_from'] ?? null;
            $transferred_to = $partsTransactionDetails['transferred_to'] ?? null;

           $query = '';

            if($generate_job_order == 'job order'){
                $query = ' SELECT * FROM sales_proposal_additional_job_order
                            WHERE sales_proposal_id IN (select sales_proposal_id FROM sales_proposal where product_id IN ('. $transferred_from .', '. $transferred_to .') AND sales_proposal_status IN ("On-Process"))
                            AND sales_proposal_additional_job_order_id NOT IN (select additional_job_order_id from stock_transfer_advice_additional_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id)
                            AND progress < 100
                            ORDER BY particulars;';
            }
            else{
                $query = 'SELECT * FROM backjob_monitoring_additional_job_order
                        WHERE backjob_monitoring_id IN (select backjob_monitoring_id FROM backjob_monitoring where product_id IN ('. $transferred_from .', '. $transferred_to .') AND status NOT IN ("Draft", "Cancelled", "For Approval"))
                        AND backjob_monitoring_additional_job_order_id NOT IN (select additional_job_order_id from stock_transfer_advice_additional_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id)
                        AND progress < 100
                        ORDER BY particulars;';
            }

            $sql = $databaseModel->getConnection()->prepare($query);
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
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
        case 'job order table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];

            $stockTransferAdviceDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $sta_status = $stockTransferAdviceDetails['sta_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $stock_transfer_advice_job_order_id = $row['stock_transfer_advice_job_order_id'];
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
                if($sta_status == 'Draft'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-job-order" data-stock-transfer-advice-job-order-id="'. $stock_transfer_advice_job_order_id  .'" title="Delete Job Order">
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

            $stockTransferAdviceDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $sta_status = $stockTransferAdviceDetails['sta_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'internal job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $stock_transfer_advice_job_order_id  = $row['stock_transfer_advice_job_order_id'];
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
                if($sta_status == 'Draft'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-internal-job-order" data-stock-transfer-advice-job-order-id="'. $stock_transfer_advice_job_order_id  .'" title="Delete Internal Job Order">
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

            $stockTransferAdviceDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $sta_status = $stockTransferAdviceDetails['sta_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_additional_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'additional job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $stock_transfer_advice_additional_job_order_id   = $row['stock_transfer_advice_additional_job_order_id'];
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
                if($sta_status == 'Draft'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-additional-job-order" data-stock-transfer-advice-additional-job-order-id="'. $stock_transfer_advice_additional_job_order_id   .'" title="Delete Additional Job Order">
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

            $stockTransferAdviceDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $sta_status = $stockTransferAdviceDetails['sta_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_additional_job_order WHERE stock_transfer_advice_id = :stock_transfer_advice_id AND type = :type');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->bindValue(':type', 'internal additional job order', PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $stock_transfer_advice_additional_job_order_id   = $row['stock_transfer_advice_additional_job_order_id '];
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
                if($sta_status == 'Draft'){
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-internal-additional-job-order" data-stock-transfer-advice-additional-job-order-id="'. $stock_transfer_advice_additional_job_order_id  .'" title="Delete Internal Additional Job Order">
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
        
        # -------------------------------------------------------------

        case 'part item table':
            $stock_transfer_advice_id = $_POST['stock_transfer_advice_id'];

            $stockTransferAdviceDetails = $stockTransferAdviceModel->getStockTransferAdvice($stock_transfer_advice_id);
            $sta_status = $stockTransferAdviceDetails['sta_status'] ?? null;
            $transferred_from = $stockTransferAdviceDetails['transferred_from'] ?? null;
            $transferred_to = $stockTransferAdviceDetails['transferred_to']  ?? null;

            $productDetails1 = $productModel->getProduct($transferred_from);
            $productName1 = $productDetails1['description'] ?? null;
            $productSubategoryID1 = $productDetails1['product_subcategory_id'] ?? '';

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID1);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;

            $stockNumber1 = str_replace($productSubcategoryCode, '', $productDetails1['stock_number'] ?? '');
            $fullStockNumber1 = $productSubcategoryCode . $stockNumber1;

            $productDetails2 = $productModel->getProduct($transferred_to);
            $productName2 = $productDetails2['description'] ?? null;   
            $productSubategoryID2 = $productDetails2['product_subcategory_id'] ?? '';

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID2);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;

            $stockNumber2 = str_replace($productSubcategoryCode, '', $productDetails2['stock_number'] ?? '');
            $fullStockNumber2 = $productSubcategoryCode . $stockNumber2;

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_cart
            WHERE stock_transfer_advice_id = :stock_transfer_advice_id
            ORDER BY stock_transfer_advice_id');
            $sql->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $stock_transfer_advice_cart_id = $row['stock_transfer_advice_cart_id'];
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $price = $row['price'];
                $part_from = $row['part_from'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $unitSale = $partDetails['unit_sale'] ?? null;
                $bar_code = $partDetails['bar_code'];

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;            

                $partsImage = $systemModel->checkImage($partDetails['part_image'], 'default');

                $action = '';
                if($sta_status == 'Draft'){
                    $action = ' <button type="button" class="btn btn-icon btn-success update-part-cart" data-bs-toggle="offcanvas" data-bs-target="#part-cart-offcanvas" aria-controls="part-cart-offcanvas" data-stock-transfer-advice-cart-id="'. $stock_transfer_advice_cart_id .'" title="Update Part Item">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-danger delete-part-cart" data-stock-transfer-advice-cart-id="'. $stock_transfer_advice_cart_id .'" title="Delete Part Item">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                if($part_from == 'From'){
                    $transferred_from = $fullStockNumber1;
                    $transferred_to = $fullStockNumber2;
                }
                else{
                    $transferred_from = $fullStockNumber2; 
                    $transferred_to = $fullStockNumber1; 
                }

                $response[] = [
                    'TRANSFERRED_FROM' => $transferred_from,
                    'TRANSFERRED_TO' => $transferred_to,
                    'PART' => '<div class="d-flex align-items-center"><img src="'. $partsImage .'" alt="image" class="bg-light wid-50 rounded">
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">'. $description .'</h5>
                                        <p class="text-sm text-muted mb-0">'. $bar_code .'</p>
                                    </div>
                                </div>',
                    'PRICE' => number_format($price, 2) .' PHP',
                    'QUANTITY' => number_format($quantity, 2) . ' ' . $short_name,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
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
                $stock_transfer_advice_id = $row['stock_transfer_advice_id'];
                $sta_status = $row['sta_status'];
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

                if($sta_status === 'Draft'){
                    $sta_status = '<span class="badge bg-secondary">' . $sta_status . '</span>';
                }
                else if($sta_status === 'Cancelled'){
                    $sta_status = '<span class="badge bg-warning">' . $sta_status . '</span>';
                }
                else if($sta_status === 'For Approval'){
                    $sta_status = '<span class="badge bg-info">' . $sta_status . '</span>';
                }
                else{
                    $sta_status = '<span class="badge bg-success">' . $sta_status . '</span>';
                }

                $stock_transfer_advice_id_encrypted = $securityModel->encryptData($stock_transfer_advice_id);

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
                    'TRANSACTION_ID' => '<a href="'. $link .'.php?id='. $stock_transfer_advice_id_encrypted .'" title="View Details" target="_blank">
                                       '. $number .'
                                    </a>',
                    'CUSTOMER_TYPE' => $customer_type,
                    'REFERENCE' => $transaction_reference,
                    'NUMBER_OF_ITEMS' => number_format($number_of_items, 0),
                    'TOTAL_AMOUNT' => number_format($total_amount, 2) . ' PHP',
                    'TRANSACTION_DATE' => $transaction_date,
                    'ISSUANCE_DATE' => $issuance_date,
                    'STATUS' => $sta_status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="'. $link .'.php?id='. $stock_transfer_advice_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
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
               $stock_transfer_advice_id = $row['stock_transfer_advice_id'];
                $sta_status = $row['sta_status'];
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

                if($sta_status === 'Draft'){
                    $sta_status = '<span class="badge bg-secondary">' . $sta_status . '</span>';
                }
                else if($sta_status === 'Cancelled'){
                    $sta_status = '<span class="badge bg-warning">' . $sta_status . '</span>';
                }
                else if($sta_status === 'For Approval' || $sta_status === 'For Validation'){
                    $sta_status = '<span class="badge bg-info">For Validation</span>';
                }
                else if($sta_status === 'Approved' || $sta_status === 'Validated'){
                    $sta_status = '<span class="badge bg-success">Validated</span>';
                }
                else{
                    $sta_status = '<span class="badge bg-success">' . $sta_status . '</span>';
                }


                $stock_transfer_advice_id_encrypted = $securityModel->encryptData($stock_transfer_advice_id);

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
                                            <a href="'. $link .'.php?id='. $stock_transfer_advice_id_encrypted .'" title="View Details" target="_blank">
                                                <p class="mb-0"><b>'. strtoupper($transaction_reference) .'</b></p>
                                                <p class="text-muted mb-0"><small>Issuance Number: '. $number .'</small></p>
                                                <p class="text-muted mb-0"><small>Transaction Type: '. strtoupper($customer_type) .'</small></p>
                                                <p class="text-muted mb-0"><small>No. Items: '. number_format($number_of_items, 0) .'</small></p>
                                                <p class="text-muted mb-0"><small>Net Amount: '. number_format($total_amount, 2) .' PHP</small></p>
                                                <p class="text-muted mb-0"><small>Transaction Date: '. $transaction_date .'</small></p>
                                            </a>
                                      </div>
                                      <div class="col-3 text-end">
                                          '. $sta_status .'
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