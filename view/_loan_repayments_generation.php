<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: loan repayments table
        # Description:
        # Generates the loan repayments table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'loan repayments table':
            $filterDueDateStartDate = $systemModel->checkDate('empty', $_POST['filter_due_date_start_date'], '', 'Y-m-d', '');
            $filterDueDateEndDate = $systemModel->checkDate('empty', $_POST['filter_due_date_end_date'], '', 'Y-m-d', '');
            $filterRepaymentStatus = $_POST['filter_repayments_status'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateLoanRepaymentsTable(:filterRepaymentStatus, :filterDueDateStartDate, :filterDueDateEndDate)');
            $sql->bindValue(':filterRepaymentStatus', $filterRepaymentStatus, PDO::PARAM_STR);
            $sql->bindValue(':filterDueDateStartDate', $filterDueDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterDueDateEndDate', $filterDueDateEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalRepaymentID = $row['sales_proposal_repayment_id'];
                $salesProposalID = $row['sales_proposal_id'];
                $reference = $row['reference'];
                $repaymentStatus = $row['repayment_status'];
                $dueDate = $systemModel->checkDate('empty', $row['due_date'], '', 'm/d/Y', '');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $loanNumber = $salesProposalDetails['loan_number'] ?? null;

                $statusClasses = [
                    'Unpaid' => 'danger',
                    'Partially Paid' => 'warning',
                    'Fully Paid' => 'success'
                ];
                
                $defaultClass = 'dark';
                
                $class = $statusClasses[$repaymentStatus] ?? $defaultClass;
                
                $badge = '<span class="badge bg-' . $class . '">' . $repaymentStatus . '</span>';

                $salesProposalRepaymentIDEncrypted = $securityModel->encryptData($salesProposalRepaymentID);

                $response[] = [
                    'LOAN_NUMBER' => $loanNumber,
                    'REFERENCE' => $reference,
                    'DUE_DATE' => $dueDate,
                    'STATUS' => $badge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="loan-repayments.php?id='. $salesProposalRepaymentIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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