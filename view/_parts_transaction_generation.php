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
require_once '../model/product-model.php';
require_once '../model/miscellaneous-client-model.php';
require_once '../model/customer-model.php';
require_once '../model/back-job-monitoring-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/contractor-model.php';
require_once '../model/work-center-model.php';
require_once '../model/department-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsModel = new PartsModel($databaseModel);
$partsTransactionModel = new PartsTransactionModel($databaseModel);
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
            $company = $_POST['company'];
            $sql = $databaseModel->getConnection()->prepare('CALL generateInStockPartOptions(:parts_transaction_id, :company)');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
            $sql->bindValue(':company', $company, PDO::PARAM_STR);
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
        case 'add job order table':
            $parts_transaction_id = $_POST['parts_transaction_id'];
            $generate_job_order = $_POST['generate_job_order'];

            $partsTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $customer_id = $partsTransactionDetails['customer_id'] ?? null;

            #$company = $_POST['company'];
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsTransactionJobOrderOptions(:parts_transaction_id, :customer_id, :generate_job_order)');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
            $sql->bindValue(':customer_id', $customer_id, PDO::PARAM_STR);
            $sql->bindValue(':generate_job_order', $generate_job_order, PDO::PARAM_STR);
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
        case 'job order table':
            $parts_transaction_id = $_POST['parts_transaction_id'];

            $partTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_job_order WHERE part_transaction_id = :parts_transaction_id AND type = :type');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
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
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-job-order" data-parts-transaction-job-order-id="'. $part_transaction_job_order_id  .'" title="Delete Job Order">
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
            $parts_transaction_id = $_POST['parts_transaction_id'];

            $partTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_job_order WHERE part_transaction_id = :parts_transaction_id AND type = :type');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
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
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-internal-job-order" data-parts-transaction-job-order-id="'. $part_transaction_job_order_id  .'" title="Delete Internal Job Order">
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
            $parts_transaction_id = $_POST['parts_transaction_id'];

            $partTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_additional_job_order WHERE part_transaction_id = :parts_transaction_id AND type = :type');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
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
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-additional-job-order" data-parts-transaction-additional-job-order-id="'. $part_transaction_additional_job_order_id   .'" title="Delete Additional Job Order">
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
            $parts_transaction_id = $_POST['parts_transaction_id'];

            $partTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_additional_job_order WHERE part_transaction_id = :parts_transaction_id AND type = :type');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
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
                    $action = '<button type="button" class="btn btn-icon btn-danger delete-internal-additional-job-order" data-parts-transaction-additional-job-order-id="'. $part_transaction_additional_job_order_id  .'" title="Delete Internal Additional Job Order">
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
        case 'job order table 2':
            $updatePaidStatus = $userModel->checkSystemActionAccessRights($user_id, 223);
            $filter_payment_status = $_POST['filter_payment_status'] ?? ''; // e.g. "Paid", "Cancelled", "Unpaid"

            $query = 'SELECT * FROM sales_proposal_job_order WHERE job_cost > 0';
            $conditions = [];

            // Build conditions based on filter
            if ($filter_payment_status === 'Paid') {
                $conditions[] = 'payment_date IS NOT NULL';
            } elseif ($filter_payment_status === 'Cancelled') {
                $conditions[] = 'payment_cancellation_date IS NOT NULL';
            } else {
                $conditions[] = '(payment_date IS NULL AND payment_cancellation_date IS NULL)';
            }

            // If any conditions were added, append them with OR
            if (!empty($conditions)) {
                $query .= ' AND (' . implode(' OR ', $conditions) . ')';
            }

            $sql = $databaseModel->getConnection()->prepare( $query);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $sales_proposal_job_order_id = $row['sales_proposal_job_order_id'];
                $payment_date = $row['payment_date'];
                $payment_cancellation_date = $row['payment_cancellation_date'];
                
                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalJobOrder($sales_proposal_job_order_id);
                $sales_proposal_id = $salesProposalJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $salesProposalJobOrderDetails['job_order'] ?? null;
                $contractor_id = $salesProposalJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $salesProposalJobOrderDetails['work_center_id'] ?? null;
                $job_cost = $salesProposalJobOrderDetails['job_cost'] ?? 0;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($updatePaidStatus['total'] > 0 && empty($payment_date) && empty($payment_cancellation_date)){
                    $action = '<button type="button" class="btn btn-icon btn-success paid-sp-job-order" data-bs-toggle="offcanvas" data-bs-target="#paid-offcanvas" aria-controls="paid-offcanvas" data-sales-proposal-job-order-id="'. $sales_proposal_job_order_id  .'" title="Tag Paid">
                                        <i class="ti ti-check"></i>
                                    </button>
                                     <button type="button" class="btn btn-icon btn-warning cancel-sp-job-order" data-sales-proposal-job-order-id="'. $sales_proposal_job_order_id  .'" title="Cancel Paid">
                                        <i class="ti ti-x"></i>
                                    </button>';
                }

                if(empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-info">Unpaid</span>';
                }
                else if(!empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-success">Paid</span>';
                }
                else if(empty($payment_date) && !empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-warning">Cancelled</span>';
                }

                $response[] = [
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'JOB_COST' => number_format($job_cost, 2) . ' PHP',
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'internal job order table 2':
            $updatePaidStatus = $userModel->checkSystemActionAccessRights($user_id, 223);
            $filter_payment_status = $_POST['filter_payment_status'] ?? ''; // e.g. "Paid", "Cancelled", "Unpaid"

            $query = 'SELECT * FROM backjob_monitoring_job_order WHERE cost > 0';
            $conditions = [];

            // Build conditions based on filter
            if ($filter_payment_status === 'Paid') {
                $conditions[] = 'payment_date IS NOT NULL';
            } elseif ($filter_payment_status === 'Cancelled') {
                $conditions[] = 'payment_cancellation_date IS NOT NULL';
            } else {
                $conditions[] = '(payment_date IS NULL AND payment_cancellation_date IS NULL)';
            }

            // If any conditions were added, append them with OR
            if (!empty($conditions)) {
                $query .= ' AND (' . implode(' OR ', $conditions) . ')';
            }

            $sql = $databaseModel->getConnection()->prepare( $query);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $backjob_monitoring_job_order_id  = $row['backjob_monitoring_job_order_id'];
                $payment_date = $row['payment_date'];
                $payment_cancellation_date = $row['payment_cancellation_date'];

                $backJobMonitoringJobOrderDetails = $backjobMonitoringModel->getBackJobMonitoringJobOrder($backjob_monitoring_job_order_id);
                $sales_proposal_id = $backJobMonitoringJobOrderDetails['sales_proposal_id'] ?? null;
                $backjob_monitoring_id = $backJobMonitoringJobOrderDetails['backjob_monitoring_id'] ?? null;
                $job_order = $backJobMonitoringJobOrderDetails['job_order'] ?? null;
                $contractor_id = $backJobMonitoringJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $backJobMonitoringJobOrderDetails['work_center_id'] ?? null;
                $cost = $backJobMonitoringJobOrderDetails['cost'] ?? 0;

                $backJobMonitoringDetails = $backjobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
                $backJobMonitoringType = $backJobMonitoringDetails['type'] ?? null;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($updatePaidStatus['total'] > 0 && empty($payment_date) && empty($payment_cancellation_date)){
                    $action = '<button type="button" class="btn btn-icon btn-success paid-bj-job-order" data-bs-toggle="offcanvas" data-bs-target="#paid-offcanvas" aria-controls="paid-offcanvas" data-backjob-monitoring-job-order-id="'. $backjob_monitoring_job_order_id  .'" title="Tag Paid">
                                        <i class="ti ti-check"></i>
                                    </button>
                                     <button type="button" class="btn btn-icon btn-warning cancel-sp-job-order" data-backjob-monitoring-job-order-id="'. $backjob_monitoring_job_order_id  .'" title="Cancel Paid">
                                        <i class="ti ti-x"></i>
                                    </button>';
                }

                if(empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-info">Unpaid</span>';
                }
                else if(!empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-success">Paid</span>';
                }
                else if(empty($payment_date) && !empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-warning">Cancelled</span>';
                }
            


                $response[] = [
                    'TYPE' => $backJobMonitoringType,
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'JOB_COST' => number_format($cost, 2) . ' PHP',
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'additional job order table 2':
            $updatePaidStatus = $userModel->checkSystemActionAccessRights($user_id, 223);
            $filter_payment_status = $_POST['filter_payment_status'] ?? ''; // e.g. "Paid", "Cancelled", "Unpaid"
            
            $query = 'SELECT * FROM sales_proposal_additional_job_order WHERE job_cost > 0';
            $conditions = [];

            // Build conditions based on filter
            if ($filter_payment_status === 'Paid') {
                $conditions[] = 'payment_date IS NOT NULL';
            } elseif ($filter_payment_status === 'Cancelled') {
                $conditions[] = 'payment_cancellation_date IS NOT NULL';
            } else {
                $conditions[] = '(payment_date IS NULL AND payment_cancellation_date IS NULL)';
            }

            // If any conditions were added, append them with OR
            if (!empty($conditions)) {
                $query .= ' AND (' . implode(' OR ', $conditions) . ')';
            }

            $sql = $databaseModel->getConnection()->prepare( $query);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $sales_proposal_additional_job_order_id = $row['sales_proposal_additional_job_order_id'];
                $payment_date = $row['payment_date'];
                $payment_cancellation_date = $row['payment_cancellation_date'];
                
                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalAdditionalJobOrder($sales_proposal_additional_job_order_id);
                $sales_proposal_id = $salesProposalJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $salesProposalJobOrderDetails['particulars'] ?? null;
                $contractor_id = $salesProposalJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $salesProposalJobOrderDetails['work_center_id'] ?? null;
                $job_cost = $salesProposalJobOrderDetails['job_cost'] ?? 0;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($updatePaidStatus['total'] > 0 && empty($payment_date) && empty($payment_cancellation_date)){
                    $action = '<button type="button" class="btn btn-icon btn-success paid-sp-additional-job-order" data-bs-toggle="offcanvas" data-bs-target="#paid-offcanvas" aria-controls="paid-offcanvas" data-sales-proposal-additional-job-order-id="'. $sales_proposal_additional_job_order_id  .'" title="Tag Paid">
                                        <i class="ti ti-check"></i>
                                    </button>
                                     <button type="button" class="btn btn-icon btn-warning cancel-sp-additional-job-order" data-sales-proposal-additional-job-order-id="'. $sales_proposal_additional_job_order_id  .'" title="Cancel Paid">
                                        <i class="ti ti-x"></i>
                                    </button>';
                }

                if(empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-info">Unpaid</span>';
                }
                else if(!empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-success">Paid</span>';
                }
                else if(empty($payment_date) && !empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-warning">Cancelled</span>';
                }


                $response[] = [
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'JOB_COST' => number_format($job_cost, 2) . ' PHP',
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'internal additional job order table 2':
            $updatePaidStatus = $userModel->checkSystemActionAccessRights($user_id, 223);
            $filter_payment_status = $_POST['filter_payment_status'] ?? ''; // e.g. "Paid", "Cancelled", "Unpaid"

            $query = 'SELECT * FROM backjob_monitoring_additional_job_order WHERE cost > 0';
            $conditions = [];

            // Build conditions based on filter
            if ($filter_payment_status === 'Paid') {
                $conditions[] = 'payment_date IS NOT NULL';
            } elseif ($filter_payment_status === 'Cancelled') {
                $conditions[] = 'payment_cancellation_date IS NOT NULL';
            } else {
                $conditions[] = '(payment_date IS NULL AND payment_cancellation_date IS NULL)';
            }

            // If any conditions were added, append them with OR
            if (!empty($conditions)) {
                $query .= ' AND (' . implode(' OR ', $conditions) . ')';
            }

            $sql = $databaseModel->getConnection()->prepare( $query);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $backjob_monitoring_additional_job_order_id   = $row['backjob_monitoring_additional_job_order_id'];
                $payment_date = $row['payment_date'];
                $payment_cancellation_date = $row['payment_cancellation_date'];
                
                $backJobMonitoringJobOrderDetails = $backjobMonitoringModel->getBackJobMonitoringAdditionalJobOrder($backjob_monitoring_additional_job_order_id);
                $sales_proposal_id = $backJobMonitoringJobOrderDetails['sales_proposal_id'] ?? null;
                $backjob_monitoring_id = $backJobMonitoringJobOrderDetails['backjob_monitoring_id'] ?? null;
                $job_order = $backJobMonitoringJobOrderDetails['particulars'] ?? null;
                $contractor_id = $backJobMonitoringJobOrderDetails['contractor_id'] ?? null;
                $work_center_id = $backJobMonitoringJobOrderDetails['work_center_id'] ?? null;
                $cost = $backJobMonitoringJobOrderDetails['cost'] ?? 0;

                $backJobMonitoringDetails = $backjobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
                $backJobMonitoringType = $backJobMonitoringDetails['type'] ?? null;

                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $sales_proposal_number = $salesProposalDetails['sales_proposal_number'] ?? null;

                $contractorDetails = $contractorModel->getContractor($contractor_id);
                $contractor_name = $contractorDetails['contractor_name'] ?? null;

                $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                $action = '';
                if($updatePaidStatus['total'] > 0 && empty($payment_date) && empty($payment_cancellation_date)){
                    $action = '<button type="button" class="btn btn-icon btn-success paid-bj-additional-job-order" data-bs-toggle="offcanvas" data-bs-target="#paid-offcanvas" aria-controls="paid-offcanvas" data-backjob-monitoring-additional-job-order-id="'. $backjob_monitoring_additional_job_order_id  .'" title="Tag Paid">
                                        <i class="ti ti-check"></i>
                                    </button>
                                     <button type="button" class="btn btn-icon btn-warning cancel-bj-additional-job-order" data-backjob-monitoring-additional-job-order-id="'. $backjob_monitoring_additional_job_order_id  .'" title="Cancel Paid">
                                        <i class="ti ti-x"></i>
                                    </button>';
                }

                if(empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-info">Unpaid</span>';
                }
                else if(!empty($payment_date) && empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-success">Paid</span>';
                }
                else if(empty($payment_date) && !empty($payment_cancellation_date)){
                    $status = '<span class="badge bg-warning">Cancelled</span>';
                }

                $response[] = [
                    'TYPE' => $backJobMonitoringType,
                    'OS_NUMBER' => $sales_proposal_number,
                    'JOB_ORDER' => $job_order,
                    'JOB_COST' => number_format($cost, 2) . ' PHP',
                    'CONTRACTOR' => $contractor_name,
                    'WORK_CENTER' => $work_center_name,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                   '. $action .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        case 'add additional job order table':
            $parts_transaction_id = $_POST['parts_transaction_id'];
            $generate_job_order = $_POST['generate_job_order'];

            $partsTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $customer_id = $partsTransactionDetails['customer_id'] ?? null;

            #$company = $_POST['company'];
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsTransactionAdditionalJobOrderOptions(:parts_transaction_id, :customer_id, :generate_job_order)');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
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
            $parts_transaction_id = $_POST['parts_transaction_id'];

            $partTransactionDetails = $partsTransactionModel->getPartsTransaction($parts_transaction_id);
            $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? 'Draft';

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartItemTable(:parts_transaction_id)');
            $sql->bindValue(':parts_transaction_id', $parts_transaction_id, PDO::PARAM_STR);
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

            $parts_transaction_start_date = $systemModel->checkDate('empty', $_POST['parts_transaction_start_date'], '', 'Y-m-d', '');
            $parts_transaction_end_date = $systemModel->checkDate('empty', $_POST['parts_transaction_end_date'], '', 'Y-m-d', '');

            $partDetails = $partsModel->getParts($parts_id);
            $unitSale = $partDetails['unit_sale'] ?? null;

            $unitCode = $unitModel->getUnit($unitSale);
            $short_name = $unitCode['short_name'] ?? null;            

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartItemTable2(:parts_id, :parts_transaction_start_date, :parts_transaction_end_date)');
            $sql->bindValue(':parts_id', $parts_id, PDO::PARAM_STR);
            $sql->bindValue(':parts_transaction_start_date', $parts_transaction_start_date, PDO::PARAM_STR);
            $sql->bindValue(':parts_transaction_end_date', $parts_transaction_end_date, PDO::PARAM_STR);
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
               

                $partTransactionDetails = $partsTransactionModel->getPartsTransaction($part_transaction_id);
                $company_id = $partTransactionDetails['company_id'];
                 $customer_type = $partTransactionDetails['customer_type'];
                $customer_id = $partTransactionDetails['customer_id'];

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
                    $link = 'netruck-parts-transaction';
                }
                else{
                    $link = 'parts-transaction';
                }
                
                $response[] = [
                    'PART_TRANSACTION_NO' => '<a href="'. $link .'.php?id='. $part_transaction_id_encrypted .'" target="_blank">
                                        '. $part_transaction_id .'
                                    </a>',
                    'PRODUCT' => $stock_number,
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

            $parts_transaction_start_date = $systemModel->checkDate('empty', $_POST['parts_transaction_start_date'], '', 'Y-m-d', '');
            $parts_transaction_end_date = $systemModel->checkDate('empty', $_POST['parts_transaction_end_date'], '', 'Y-m-d', '');                   

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartItemTable3(:product_id, :parts_transaction_start_date, :parts_transaction_end_date)');
            $sql->bindValue(':product_id', $product_id, PDO::PARAM_STR);
            $sql->bindValue(':parts_transaction_start_date', $parts_transaction_start_date, PDO::PARAM_STR);
            $sql->bindValue(':parts_transaction_end_date', $parts_transaction_end_date, PDO::PARAM_STR);
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

                $partTransactionDetails = $partsTransactionModel->getPartsTransaction($part_transaction_id);
                $company_id = $partTransactionDetails['company_id'];
                 $customer_type = $partTransactionDetails['customer_type'];
                $customer_id = $partTransactionDetails['customer_id'];

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
                    $link = 'netruck-parts-transaction';
                }
                else{
                    $link = 'parts-transaction';
                }
                
                $response[] = [
                    'PART_TRANSACTION_NO' => '<a href="'. $link .'.php?id='. $part_transaction_id_encrypted .'" target="_blank">
                                        '. $part_transaction_id .'
                                    </a>',
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
            $company = $_POST['company'];

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

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsTransactionTable(:company, :filterTransactionDateStartDate, :filterTransactionDateEndDate, :filter_approval_date_start_date, :filter_approval_date_end_date, :filter_transaction_status)');
            $sql->bindValue(':company', $company, PDO::PARAM_INT);
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
                $issuance_no = $row['issuance_no'];
                $reference_number = $row['reference_number'];
                $customer_type = $row['customer_type'];
                $customer_id = $row['customer_id'];
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

                $part_transaction_id_encrypted = $securityModel->encryptData($part_transaction_id);

                if($company == '1'){
                    $link = 'supplies-transaction';
                    $number = $issuance_no;
                }
                else if($company == '2'){
                    $link = 'netruck-parts-transaction';
                    $number = $issuance_no;
                }
                else{
                    $link = 'parts-transaction';
                    $number = $reference_number;
                }

                $response[] = [
                    'TRANSACTION_ID' => $number,
                    'PRODUCT' => $transaction_reference,
                    'NUMBER_OF_ITEMS' => number_format($number_of_items, 0),
                    'ADD_ON' => number_format($add_on, 2) . ' PHP',
                    'DISCOUNT' => number_format($total_discount, 2) . ' PHP',
                    'SUB_TOTAL' => number_format($sub_total, 2) . ' PHP',
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
        case 'parts transaction dashboard table':

            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsTransactionDashboardTable()');
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
                    $link = 'netruck-parts-transaction';
                    $number = $issuance_no;
                }
                else{
                    $link = 'parts-transaction';
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

        case 'parts transaction dashboard list':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsTransactionDashboardTable()');
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
                    $link = 'netruck-parts-transaction';
                    $number = $issuance_no;
                }
                else{
                    $link = 'parts-transaction';
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