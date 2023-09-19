<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/bank-account-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$bankAccountTypeModel = new BankAccountTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: bank account type table
        # Description:
        # Generates the bank account type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'bank account type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBankAccountTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $bankAccountTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 43, 'delete');

            foreach ($options as $row) {
                $bankAccountTypeID = $row['bank_account_type_id'];
                $bankAccountTypeName = $row['bank_account_type_name'];

                $bankAccountTypeIDEncrypted = $securityModel->encryptData($bankAccountTypeID);

                $delete = '';
                if($bankAccountTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-bank-account-type" data-bank-account-type-id="'. $bankAccountTypeID .'" title="Delete Bank Account Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $bankAccountTypeID .'">',
                    'BANK_ACCOUNT_TYPE_NAME' => $bankAccountTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="bank-account-type.php?id='. $bankAccountTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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