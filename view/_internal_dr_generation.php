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

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$internalDRModel = new InternalDRModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$contractorModel = new ContractorModel($databaseModel);
$workCenterModel = new WorkCenterModel($databaseModel);
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