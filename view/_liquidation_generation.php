<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/security-model.php';
require_once '../model/miscellaneous-client-model.php';
require_once '../model/system-model.php';
require_once '../model/department-model.php';
require_once '../model/company-model.php';
require_once '../model/chart-of-account-model.php';
require_once '../model/disbursement-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$chartOfAccountModel = new ChartOfAccountModel($databaseModel);
$disbursementModel = new DisbursementModel($databaseModel);
$securityModel = new SecurityModel();
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: liquidation table
        # Description:
        # Generates the liquidation table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'liquidation table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateLiquidationTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $liquidation_id = $row['liquidation_id'];
                $disbursement_particulars_id = $row['disbursement_particulars_id'];
                $disbursement_id = $row['disbursement_id'];
                $remaining_balance = $row['remaining_balance'];

                $disbursementDetails = $disbursementModel->getDisbursement($disbursement_id);
                $transaction_number = $disbursementDetails['transaction_number'] ?? '';

                $disbursementParticularsDetails = $disbursementModel->getDisbursementParticulars($disbursement_particulars_id);
                $chart_of_account_id = $disbursementParticularsDetails['chart_of_account_id'];

                $chartOfAccountDetails = $chartOfAccountModel->getChartOfAccount($chart_of_account_id);
                $chartOfAccountName = $chartOfAccountDetails['name'] ?? null;

                $liquidationIDEncrypted = $securityModel->encryptData($liquidation_id);

                $response[] = [
                    'PARTICULARS' => $chartOfAccountName,
                    'TRANSACTION_NUNMBER' => $transaction_number,
                    'REMAINING_BALANCE' => number_format($remaining_balance, 2),
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="liquidation.php?id='. $liquidationIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'liquidation particulars table':
            $liquidation_id = $_POST['liquidation_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateLiquidationParticularsTable(:liquidation_id)');
            $sql->bindValue(':liquidation_id', $liquidation_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $liquidation_particulars_id = $row['liquidation_particulars_id'];
                $particulars = $row['particulars'];
                $remarks = $row['remarks'];
                $particulars_amount = $row['particulars_amount'];
                $reference_type = $row['reference_type'];
                $reference_number = $row['reference_number'];
                
                $action = '';

                $liquidationWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 125, 'write');

                if($liquidationWriteAccess['total'] > 0){
                    $action = '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-danger delete-liquidation-particulars" data-liquidation-particulars-id="'. $liquidation_particulars_id .'" title="Delete Particular">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>';
                }

                $response[] = [
                    'PARTICULARS' => $particulars,
                    'PARTICULAR_AMOUNT' => number_format($particulars_amount, 2),
                    'ACTION' => $action
                ];
            }

            echo json_encode($response);
        break;
        
        case 'liquidation addon particulars table':
            $liquidation_id = $_POST['liquidation_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateLiquidationAddOnParticularsTable(:liquidation_id)');
            $sql->bindValue(':liquidation_id', $liquidation_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $liquidation_particulars_id = $row['liquidation_particulars_id'];
                $particulars = $row['particulars'];
                $remarks = $row['remarks'];
                $particulars_amount = $row['particulars_amount'];
                $reference_type = $row['reference_type'];
                $reference_number = $row['reference_number'];
                
                $action = '';

                $liquidationWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 125, 'write');

                if($liquidationWriteAccess['total'] > 0){
                    $action = '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-icon btn-danger delete-liquidation-particulars" data-liquidation-particulars-id="'. $liquidation_particulars_id .'" title="Delete Particular">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>';
                }

                $response[] = [
                    'PARTICULARS' => $particulars,
                    'REFERENCE_TYPE' => $reference_type,
                    'REFERENCE_NUMBER' => $reference_number,
                    'PARTICULAR_AMOUNT' => number_format($particulars_amount, 2),
                    'ACTION' => $action
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>