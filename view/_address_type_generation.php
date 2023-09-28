<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/address-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$addressTypeModel = new AddressTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: address type table
        # Description:
        # Generates the address type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'address type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateAddressTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $addressTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 45, 'delete');

            foreach ($options as $row) {
                $addressTypeID = $row['address_type_id'];
                $addressTypeName = $row['address_type_name'];

                $addressTypeIDEncrypted = $securityModel->encryptData($addressTypeID);

                $delete = '';
                if($addressTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-address-type" data-address-type-id="'. $addressTypeID .'" title="Delete Address Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $addressTypeID .'">',
                    'ADDRESS_TYPE_NAME' => $addressTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="address-type.php?id='. $addressTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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