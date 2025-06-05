<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/back-job-monitoring-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/contractor-model.php';
require_once '../model/work-center-model.php';
require_once '../model/product-model.php';
require_once '../model/product-category-model.php';
require_once '../model/product-subcategory-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$backjobMonitoringModel = new BackJobMonitoringModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$contractorModel = new ContractorModel($databaseModel);
$workCenterModel = new WorkCenterModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: internal DR table
        # Description:
        # Generates the internal DR table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'backjob monitoring table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBackJobMonitoringTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $backjobMonitoringDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 133, 'delete');

            foreach ($options as $row) {
                $backjob_monitoring_id = $row['backjob_monitoring_id'];
                $type = $row['type'];
                $product_id = $row['product_id'];
                $sales_proposal_id = $row['sales_proposal_id'];
                $status = $row['status'];
                $created_date = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y', '');

                $backjob_monitoring_id_encrypted = $securityModel->encryptData($backjob_monitoring_id);

                if(!empty($sales_proposal_id)){
                    $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                    $sales_proposal_number = $salesProposalDetails['sales_proposal_number'];
                }
                else{
                    $sales_proposal_number = '--';
                }

                if(!empty($product_id)){
                    $productDetails = $productModel->getProduct($product_id);
                    $description = $productDetails['description'];
                    $productSubategoryID = $productDetails['product_subcategory_id'];

                    $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
                    $productSubcategoryName = $productSubcategoryDetails['product_subcategory_name'] ?? null;
                    $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;


                    $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number']);
                    $fullStockNumber = $productSubcategoryCode . $stockNumber;

                    $product = ' <div class="col">
                                        <h6 class="mb-0">'. $fullStockNumber .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>';
                }
                else{
                    $product = '--';
                }

                if($status === 'Draft'){
                    $status = '<span class="badge bg-secondary">' . $status . '</span>';
                }
                else if($status === 'On-Process'){
                    $status = '<span class="badge bg-warning">' . $status . '</span>';
                }
                else if($status === 'Ready For Release'){
                    $status = '<span class="badge bg-info">' . $status . '</span>';
                }
                else if($status === 'Released' && $type === 'Internal Repair'){
                    $status = '<span class="badge bg-success">Completed</span>';
                }
                else{
                    $status = '<span class="badge bg-success">' . $status . '</span>';
                }

                $delete = '';
                if($backjobMonitoringDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-backjob-monitoring" data-backjob-monitoring-id="'. $backjob_monitoring_id .'" title="Delete Zoom API">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $backjob_monitoring_id .'">',
                    'TYPE' => $type,
                    'SALES_PROPOSAL' => $sales_proposal_number,
                    'PRODUCT' =>$product,
                    'STATUS' => $status,
                    'CREATED_DATE' => $created_date,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="back-job-monitoring.php?id='. $backjob_monitoring_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'backjob monitoring table2':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBackJobMonitoringTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $backjobMonitoringDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 133, 'delete');

            foreach ($options as $row) {
                $backjob_monitoring_id = $row['backjob_monitoring_id'];
                $type = $row['type'];
                $product_id = $row['product_id'];
                $sales_proposal_id = $row['sales_proposal_id'];
                $status = $row['status'];
                $created_date = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y', '');

                $backjob_monitoring_id_encrypted = $securityModel->encryptData($backjob_monitoring_id);

                if(!empty($sales_proposal_id)){
                    $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                    $sales_proposal_number = $salesProposalDetails['sales_proposal_number'];
                }
                else{
                    $sales_proposal_number = '--';
                }

                if(!empty($product_id)){
                    $productDetails = $productModel->getProduct($product_id);
                    $description = $productDetails['description'];
                    $productSubategoryID = $productDetails['product_subcategory_id'];

                    $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
                    $productSubcategoryName = $productSubcategoryDetails['product_subcategory_name'] ?? null;
                    $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;


                    $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number']);
                    $fullStockNumber = $productSubcategoryCode . $stockNumber;

                    $product = ' <div class="col">
                                        <h6 class="mb-0">'. $fullStockNumber .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>';
                }
                else{
                    $product = '--';
                }

                if($status === 'Draft'){
                    $status = '<span class="badge bg-secondary">' . $status . '</span>';
                }
                else if($status === 'On-Process'){
                    $status = '<span class="badge bg-warning">' . $status . '</span>';
                }
                else if($status === 'Ready For Release'){
                    $status = '<span class="badge bg-info">' . $status . '</span>';
                }
                else if($status === 'Released' && $type === 'Internal Repair'){
                    $status = '<span class="badge bg-success">Completed</span>';
                }
                else{
                    $status = '<span class="badge bg-success">' . $status . '</span>';
                }

                $delete = '';
                if($backjobMonitoringDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-backjob-monitoring" data-backjob-monitoring-id="'. $backjob_monitoring_id .'" title="Delete Zoom API">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $backjob_monitoring_id .'">',
                    'TYPE' => $type,
                    'SALES_PROPOSAL' => $sales_proposal_number,
                    'PRODUCT' =>$product,
                    'STATUS' => $status,
                    'CREATED_DATE' => $created_date,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="back-job-monitoring.php?id='. $backjob_monitoring_id_encrypted .'" class="btn btn-icon btn-primary" target="_blank" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'job order monitoring table':
            $backjob_monitoring_id = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
            $backjobMonitoringDetails = $backjobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
            $status = $backjobMonitoringDetails['status'] ?? null;

            $sql = $databaseModel->getConnection()->prepare('CALL generateBackJobMonitoringJobOrderTable(:backjob_monitoring_id)');
            $sql->bindValue(':backjob_monitoring_id',  $backjob_monitoring_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $backjobMonitoringJobOrderID = $row['backjob_monitoring_job_order_id'];
                $job_order_id = $row['job_order_id'];
                $progress = $row['progress'];
                $contractor_id = $row['contractor_id'];
                $work_center_id = $row['work_center_id'];
                $cost = number_format($row['cost'], 2);
                $completionDate = $systemModel->checkDate('summary', $row['completion_date'], '', 'm/d/Y', '');
                $planned_start_date = $systemModel->checkDate('summary', $row['planned_start_date'], '', 'm/d/Y', '');
                $planned_finish_date = $systemModel->checkDate('summary', $row['planned_finish_date'], '', 'm/d/Y', '');
                $date_started = $systemModel->checkDate('summary', $row['date_started'], '', 'm/d/Y', '');

                $jobOrder = $row['job_order'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $delete = '';
                $update = '';
                if($status === 'Draft' || $status === 'On-Process'){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-backjob-monitoring-job-order" data-backjob-monitoring-job-order-id="'. $backjobMonitoringJobOrderID .'" title="Delete Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    $update = '<button type="button" class="btn btn-icon btn-success update-backjob-monitoring-job-order" data-bs-toggle="offcanvas" data-bs-target="#job-order-monitoring-offcanvas" aria-controls="job-order-monitoring-offcanvas" data-backjob-monitoring-job-order-id="'. $backjobMonitoringJobOrderID .'" title="Update Job Order Progress">
                                    <i class="ti ti-edit"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input job-order-checkbox-children" type="checkbox" value="'. $backjobMonitoringJobOrderID .'">',
                    'JOB_ORDER' => $jobOrder,
                    'COST' => $cost,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'COMPLETION_DATE' => $completionDate,
                    'PLANNED_START_DATE' => $planned_start_date,
                    'PLANNED_FINISH_DATE' => $planned_finish_date,
                    'DATE_STARTED' => $date_started,
                    'PROGRESS' => number_format($progress, 2) . '%',
                    'ACTION' => '<div class="d-flex gap-2">'.
                                $update . 
                                $delete . 
                                '</div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        case 'additional job order monitoring table':
            $backjob_monitoring_id = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
            $backjobMonitoringDetails = $backjobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
            $status = $backjobMonitoringDetails['status'] ?? null;

            $sql = $databaseModel->getConnection()->prepare('CALL generateBackJobMonitoringAdditionalJobOrderTable(:backjob_monitoring_id)');
            $sql->bindValue(':backjob_monitoring_id',  $backjob_monitoring_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $backjob_monitoring_additional_job_order_id  = $row['backjob_monitoring_additional_job_order_id'];
                $additional_job_order_id = $row['additional_job_order_id'];
                $progress = $row['progress'];
                $contractor_id = $row['contractor_id'];
                $work_center_id = $row['work_center_id'];
                $cost = number_format($row['cost'], 2);
                $completionDate = $systemModel->checkDate('summary', $row['completion_date'], '', 'm/d/Y', '');
                $planned_start_date = $systemModel->checkDate('summary', $row['planned_start_date'], '', 'm/d/Y', '');
                $planned_finish_date = $systemModel->checkDate('summary', $row['planned_finish_date'], '', 'm/d/Y', '');
                $date_started = $systemModel->checkDate('summary', $row['date_started'], '', 'm/d/Y', '');

                $job_order_number = $row['job_order_number'] ?? null;
                $job_order_date = $systemModel->checkDate('summary', $row['job_order_date'], '', 'F d, Y', '');
                $particulars = $row['particulars'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $delete = '';
                $update = '';
                if($status === 'Draft' || $status === 'On-Process'){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-backjob-monitoring-additional-job-order" data-backjob-monitoring-additional-job-order-id="'. $backjob_monitoring_additional_job_order_id  .'" title="Delete Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    $update = '<button type="button" class="btn btn-icon btn-success update-backjob-monitoring-additional-job-order" data-bs-toggle="offcanvas" data-bs-target="#additional-job-order-monitoring-offcanvas" aria-controls="additional-job-order-monitoring-offcanvas" data-backjob-monitoring-additional-job-order-id="'. $backjob_monitoring_additional_job_order_id  .'" title="Update Additional Job Order Progress">
                                    <i class="ti ti-edit"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input additional-job-order-checkbox-children" type="checkbox" value="'. $backjob_monitoring_additional_job_order_id .'">',
                    'JOB_ORDER_NUMBER' => $job_order_number,
                    'JOB_ORDER_DATE' => $job_order_date,
                    'PARTICULARS' => $particulars,
                    'COST' => $cost,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'COMPLETION_DATE' => $completionDate,
                    'PLANNED_START_DATE' => $planned_start_date,
                    'PLANNED_FINISH_DATE' => $planned_finish_date,
                    'DATE_STARTED' => $date_started,
                    'PROGRESS' => number_format($progress, 2) . '%',
                    'ACTION' => '<div class="d-flex gap-2">'.
                                $update . 
                                $delete . 
                                '</div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>