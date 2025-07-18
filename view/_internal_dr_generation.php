<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/internal-dr-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/contractor-model.php';
require_once '../model/work-center-model.php';
require_once '../model/product-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$internalDRModel = new InternalDRModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$contractorModel = new ContractorModel($databaseModel);
$workCenterModel = new WorkCenterModel($databaseModel);
$productModel = new ProductModel($databaseModel);
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
        case 'internal DR table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateInternalDRTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $internalDRDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 78, 'delete');

            foreach ($options as $row) {
                $internalDRID = $row['internal_dr_id'];
                $releaseTo = $row['release_to'];
                $releaseAddress = $row['release_address'];
                $drType = $row['dr_type'];
                $drNumber = $row['dr_number'];
                $drStatus = $row['dr_status'];
                $stockNumber = $row['stock_number'];

                if($drStatus == 'Released'){
                    $releasedDate = $systemModel->checkDate('summary', $row['release_date'], '', 'm/d/Y', '');
                }
                else{
                    $releasedDate = '--';
                }

                $internalDRIDEncrypted = $securityModel->encryptData($internalDRID);

                $delete = '';
                if($internalDRDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-internal-dr" data-internal-dr-id="'. $internalDRID .'" title="Delete Zoom API">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $internalDRID .'">',
                    'RELEASE_TO' => ' <div class="col">
                                        <h6 class="mb-0">'. $releaseTo .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $releaseAddress .'</p>
                                        </div>',
                    'INTERNAL_DR_NUMBER' => $internalDRID,
                    'DR_TYPE' => $drType,
                    'DR_NUMBER' => $drNumber,
                    'STOCK_NUMBER' => $stockNumber,
                    'DR_STATUS' => $drStatus,
                    'RELEASED_DATE' => $releasedDate,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="internal-dr.php?id='. $internalDRIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'unit return table':
            $unit_return_status_filter = $_POST['filter_unit_return_status'];
            $estimated_return_date_start_date = $systemModel->checkDate('empty', $_POST['estimated_return_date_start_date'], '', 'Y-m-d', '');
            $estimated_return_date_end_date = $systemModel->checkDate('empty', $_POST['estimated_return_date_end_date'], '', 'Y-m-d', '');
            $return_date_start_date = $systemModel->checkDate('empty', $_POST['return_date_start_date'], '', 'Y-m-d', '');
            $return_date_end_date = $systemModel->checkDate('empty', $_POST['return_date_end_date'], '', 'Y-m-d', '');

            if (!empty($unit_return_status_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $unit_return_status_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_unit_return_status = implode(', ', $quoted_values_array);
            } else {
                $filter_unit_return_status = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateUnitReturnTable(:filter_unit_return_status, :estimated_return_date_start_date, :estimated_return_date_end_date, :return_date_start_date, :return_date_end_date)');
            $sql->bindValue(':filter_unit_return_status', $filter_unit_return_status, PDO::PARAM_STR);
            $sql->bindValue(':estimated_return_date_start_date', $estimated_return_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':estimated_return_date_end_date', $estimated_return_date_end_date, PDO::PARAM_STR);
            $sql->bindValue(':return_date_start_date', $return_date_start_date, PDO::PARAM_STR);
            $sql->bindValue(':return_date_end_date', $return_date_end_date, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            
            $unitReturnWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 160, 'write');

            foreach ($options as $row) {
                $unit_return_id = $row['unit_return_id'];
                $internal_dr_id = $row['internal_dr_id'];
                $product_id = $row['product_id'];
                $incoming_checklist = $systemModel->checkImage($row['incoming_checklist'], 'default');

                $interDRDetails = $internalDRModel->getInternalDR($internal_dr_id);
                $release_to = $interDRDetails['release_to'];
                $product_description = $interDRDetails['product_description'];

                $estimated_return_date = $systemModel->checkDate('empty', $row['estimated_return_date'], '', 'm/d/Y', '');
                $return_date = $systemModel->checkDate('empty', $row['return_date'], '', 'm/d/Y', '');
                
                $productDetails = $productModel->getProduct($product_id);
                $stockNumber = $productDetails['stock_number'] ?? null;
                
                if(empty($return_date)){
                    $returnDate = DateTime::createFromFormat('m/d/Y', $estimated_return_date);
                    $returnDate->setTime(0, 0, 0);
                    $today = new DateTime('today');

                    $daysDiff = (int) $returnDate->diff($today)->format('%R%a');

                    if($daysDiff > 0){
                        $status = '<span class="badge bg-light-danger">Overdue</span>';
                    }
                    else{
                        $status = '<span class="badge bg-light-warning">On-Going</span>';
                    }

                    $incomingChecklist = '';
                }
                else{
                    $daysDiff = 0;
                    $status = '<span class="badge bg-light-success">Returned</span>';
                    
                    $incomingChecklist = '<a href="'. $incoming_checklist .'" target="_blank">View Incoming Checklist</a>';
                }

                if($daysDiff < 0){
                    $daysDiff = 0;
                }

                $action = '';
                if($unitReturnWriteAccess['total'] > 0 && empty($return_date)){
                    $action = '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-success receive-unit" data-unit-return-id="'. $unit_return_id .'" data-bs-toggle="offcanvas" data-bs-target="#receive-unit-offcanvas" aria-controls="receive-unit-offcanvas" title="Receive">
                                        <i class="ti ti-check"></i>
                                    </button>
                                </div>';
                }

                $response[] = [
                    'RELEASED_TO' => '<span class="text-wrap">'. $release_to . '</span>',
                    'PRODUCT' => '<div class="row text-wrap">
                                        <div class="col ">
                                            <h6 class="mb-0">'. $stockNumber .'</h6>
                                            <p class="f-12 mb-0">'. $product_description .'</p>
                                        </div>
                                    </div>',
                    'ESTIMATED_RETURN_DATE' => $estimated_return_date,
                    'RETURN_DATE' => $return_date,
                    'OVERDUE' => $daysDiff . ' Day(s)',
                    'STATUS' => $status,
                    'INCOMING_CHECKLIST' => $incomingChecklist,
                    'ACTION' => $action
                ];
            }

            echo json_encode($response);
        break;

        case 'job order monitoring table':
            $internal_dr_id = htmlspecialchars($_POST['internal_dr_id'], ENT_QUOTES, 'UTF-8');
            $internalDRDetails = $internalDRModel->getInternalDR($internal_dr_id);
            $drStatus = $internalDRDetails['dr_status'] ?? null;


            $sql = $databaseModel->getConnection()->prepare('CALL generateInternalDRJobOrderTable(:internal_dr_id)');
            $sql->bindValue(':internal_dr_id',  $internal_dr_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $internalDRJobOrderID = $row['internal_dr_job_order_id'];
                $job_order_id = $row['job_order_id'];
                $progress = $row['progress'];
                $contractor_id = $row['contractor_id'];
                $work_center_id = $row['work_center_id'];

                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalJobOrder($job_order_id);
                $jobOrder = $salesProposalJobOrderDetails['job_order'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $delete = '';
                $update = '';
                if($drStatus === 'On-Process'){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-internal-dr-job-order" data-internal-dr-job-order-id="'. $internalDRJobOrderID .'" title="Delete Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    $update = '<button type="button" class="btn btn-icon btn-success update-internal-dr-job-order" data-bs-toggle="offcanvas" data-bs-target="#job-order-monitoring-offcanvas" aria-controls="job-order-monitoring-offcanvas" data-internal-dr-job-order-id="'. $internalDRJobOrderID .'" title="Update Job Order Progress">
                                    <i class="ti ti-edit"></i>
                                </button>';
                }

                $response[] = [
                    'JOB_ORDER' => $jobOrder,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'PROGRESS' => number_format($progress, 2) . '%',
                    'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        case 'additional job order monitoring table':
            $internal_dr_id = htmlspecialchars($_POST['internal_dr_id'], ENT_QUOTES, 'UTF-8');
            $internalDRDetails = $internalDRModel->getInternalDR($internal_dr_id);
            $drStatus = $internalDRDetails['dr_status'] ?? null;

            $sql = $databaseModel->getConnection()->prepare('CALL generateInternalDRAdditionalJobOrderTable(:internal_dr_id)');
            $sql->bindValue(':internal_dr_id',  $internal_dr_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $internal_dr_job_additional_order_id  = $row['internal_dr_job_additional_order_id '];
                $additional_job_order_id = $row['additional_job_order_id'];
                $progress = $row['progress'];
                $contractor_id = $row['contractor_id'];
                $work_center_id = $row['work_center_id'];

                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalAdditionalJobOrder($additional_job_order_id);
                $job_order_number = $salesProposalJobOrderDetails['job_order_number'] ?? null;
                $job_order_date = $systemModel->checkDate('summary', $row['job_order_date'], '', 'F d, Y', '');
                $particulars = $salesProposalJobOrderDetails['particulars'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $delete = '';
                $update = '';
                if($drStatus === 'On-Process'){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-internal-dr-additional-job-order" data-internal-dr-additional-job-order-id="'. $internal_dr_job_additional_order_id  .'" title="Delete Job Order">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    $update = '<button type="button" class="btn btn-icon btn-success update-internal-dr-additional-job-order" data-bs-toggle="offcanvas" data-bs-target="#additional-job-order-monitoring-offcanvas" aria-controls="additional-job-order-monitoring-offcanvas" data-internal-dr-additional-job-order-id="'. $internal_dr_job_additional_order_id  .'" title="Update Additional Job Order Progress">
                                    <i class="ti ti-edit"></i>
                                </button>';
                }

                $response[] = [
                    'JOB_ORDER_NUMBER' => $jobOrderNumber,
                    'JOB_ORDER_DATE' => $jobOrderDate,
                    'PARTICULARS' => $particulars,
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'PROGRESS' => number_format($progress, 2) . '%',
                    'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>