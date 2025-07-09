<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/bank-adb-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$bankADBModel = new BankADBModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: bank adb table
        # Description:
        # Generates the bank adb table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'bank adb table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBankADBTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $bankADBDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 161, 'delete');

            foreach ($options as $row) {
                $bankADBID = $row['bank_adb_id'];
                $bankADBName = $row['bank_adb_name'];

                $bankADBIDEncrypted = $securityModel->encryptData($bankADBID);

                $delete = '';
                if($bankADBDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-bank-adb" data-bank-adb-id="'. $bankADBID .'" title="Delete Bank ADB">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $bankADBID .'">',
                    'BLOOD_TYPE_NAME' => $bankADBName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="bank-adb.php?id='. $bankADBIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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