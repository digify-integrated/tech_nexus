<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/sales-proposal-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: sales proposal accessories table
        # Description:
        # Generates the sales proposal accessories table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal accessories table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalAccessoriesTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalAccessoriesID = $row['sales_proposal_accessories_id'];
                    $accessories = $row['accessories'];
                    $cost = number_format($row['cost'], 2);

                    $response[] = [
                        'ACCESSORIES' => $accessories,
                        'COST' => $cost,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-accessories" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-accessories-offcanvas" aria-controls="sales-proposal-accessories-offcanvas" data-sales-proposal-accessories-id="'. $salesProposalAccessoriesID .'" title="Update Accessories">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-accessories" data-sales-proposal-accessories-id="'. $salesProposalAccessoriesID .'" title="Delete Accessories">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal job order table
        # Description:
        # Generates the sales proposal job order table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal job order table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalJobOrderID = $row['sales_proposal_job_order_id'];
                    $jobOrder = $row['job_order'];
                    $cost = number_format($row['cost'], 2);

                    $response[] = [
                        'JOB_ORDER' => $jobOrder,
                        'COST' => $cost,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-job-order" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-offcanvas" aria-controls="sales-proposal-job-order-offcanvas" data-sales-proposal-job-order-id="'. $salesProposalJobOrderID .'" title="Update Job Order">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-job-order" data-sales-proposal-job-order-id="'. $salesProposalJobOrderID .'" title="Delete Job Order">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal additional job order table
        # Description:
        # Generates the sales proposal additional job order table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal additional job order table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalAdditionalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalAdditionalJobOrderID = $row['sales_proposal_additional_job_order_id'];
                    $jobOrderNumber = $row['job_order_number'];
                    $jobOrderDate = $systemModel->checkDate('summary', $row['job_order_date'], '', 'F d, Y', '');
                    $particulars = $row['particulars'];
                    $cost = number_format($row['cost'], 2);

                    $response[] = [
                        'JOB_ORDER_NUMBER' => $jobOrderNumber,
                        'JOB_ORDER_DATE' => $jobOrderDate,
                        'PARTICULARS' => $particulars,
                        'COST' => $cost,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-additional-job-order" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Update Additional Job Order">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-additional-job-order" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Delete Additional Job Order">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal deposit amount table
        # Description:
        # Generates the sales proposal deposit amount table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal deposit amount table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalDepositAmountTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalDepositAmountID = $row['sales_proposal_deposit_amount_id'];
                    $depositDate = $systemModel->checkDate('summary', $row['deposit_date'], '', 'F d, Y', '');
                    $referenceNumber = $row['reference_number'];
                    $depositAmount = number_format($row['deposit_amount'], 2);

                    $response[] = [
                        'DEPOSIT_DATE' => $depositDate,
                        'REFERENCE_NUMBER' => $referenceNumber,
                        'DEPOSIT_AMOUNT' => $depositAmount,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-deposit-amount" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" data-sales-proposal-deposit-amount-id="'. $salesProposalDepositAmountID .'" title="Update Amount of Deposit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-deposit-amount" data-sales-proposal-deposit-amount-id="'. $salesProposalDepositAmountID .'" title="Delete Amount of Deposit">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>