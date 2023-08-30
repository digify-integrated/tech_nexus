<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/currency-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$currencyModel = new CurrencyModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: currency table
        # Description:
        # Generates the currency table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'currency table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateCurrencyTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $currencyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 23, 'delete');

            foreach ($options as $row) {
                $currencyID = $row['currency_id'];
                $currencyName = $row['currency_name'];
                $shorthand = $row['shorthand'];
                $symbol = $row['symbol'];

                $currencyIDEncrypted = $securityModel->encryptData($currencyID);

                $delete = '';
                if($currencyDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-currency" data-currency-id="'. $currencyID .'" title="Delete Currency">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $currencyID .'">',
                    'CURRENCY_NAME' => $currencyName,
                    'SHORTHAND' => $shorthand,
                    'SYMBOL' => $symbol,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="currency.php?id='. $currencyIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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