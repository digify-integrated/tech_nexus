<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/id-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$idTypeModel = new IDTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: id type table
        # Description:
        # Generates the id type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'id type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateIDTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $idTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 32, 'delete');

            foreach ($options as $row) {
                $idTypeID = $row['id_type_id'];
                $idTypeName = $row['id_type_name'];

                $idTypeIDEncrypted = $securityModel->encryptData($idTypeID);

                $delete = '';
                if($idTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-id-type" data-id-type-id="'. $idTypeID .'" title="Delete ID Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $idTypeID .'">',
                    'ID_TYPE_NAME' => $idTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="id-type.php?id='. $idTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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