<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/bank-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$bankModel = new BankModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: bank table
        # Description:
        # Generates the bank table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'bank table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBankTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $bankDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 39, 'delete');

            foreach ($options as $row) {
                $bankID = $row['bank_id'];
                $bankName = $row['bank_name'];

                $bankIDEncrypted = $securityModel->encryptData($bankID);

                $delete = '';
                if($bankDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-bank" data-bank-id="'. $bankID .'" title="Delete Bank">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $bankID .'">',
                    'BANK_NAME' => $bankName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="bank.php?id='. $bankIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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