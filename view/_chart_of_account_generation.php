<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/chart-of-account-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$chartOfAccountModel = new ChartOfAccountModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: chart of account table
        # Description:
        # Generates the chart of account table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'chart of account table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateChartOfAccountTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $chartOfAccountDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 114, 'delete');

            foreach ($options as $row) {
                $chartOfAccountID = $row['chart_of_account_id'];
                $code = $row['code'];
                $name = $row['name'];
                $accountType = $row['account_type'];

                $chartOfAccountIDEncrypted = $securityModel->encryptData($chartOfAccountID);

                $delete = '';
                if($chartOfAccountDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-chart-of-account" data-chart-of-account-id="'. $chartOfAccountID .'" title="Delete Chart of Account">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $chartOfAccountID .'">',
                    'CODE' => $code,
                    'NAME' => $name,
                    'ACCOUNT_TYPE' => $accountType,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="chart-of-account.php?id='. $chartOfAccountIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
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