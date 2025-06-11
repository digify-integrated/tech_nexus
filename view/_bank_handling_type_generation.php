<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/bank-handling-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$bankHandlingTypeModel = new BankHandlingTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: bank handling type table
        # Description:
        # Generates the bank handling type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'bank handling type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBankHandlingTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $bankHandlingTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 151, 'delete');

            foreach ($options as $row) {
                $bankHandlingTypeID = $row['bank_handling_type_id'];
                $bankHandlingTypeName = $row['bank_handling_type_name'];

                $bankHandlingTypeIDEncrypted = $securityModel->encryptData($bankHandlingTypeID);

                $delete = '';
                if($bankHandlingTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-bank-handling-type" data-bank-handling-type-id="'. $bankHandlingTypeID .'" title="Delete Bank Handling Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $bankHandlingTypeID .'">',
                    'BANK_HANDLING_TYPE_NAME' => $bankHandlingTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="bank-handling-type.php?id='. $bankHandlingTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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