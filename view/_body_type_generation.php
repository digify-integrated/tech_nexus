<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/body-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$bodyTypeModel = new BodyTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: body type table
        # Description:
        # Generates the body type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'body type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBodyTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $bodyTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 60, 'delete');

            foreach ($options as $row) {
                $bodyTypeID = $row['body_type_id'];
                $bodyTypeName = $row['body_type_name'];

                $bodyTypeIDEncrypted = $securityModel->encryptData($bodyTypeID);

                $delete = '';
                if($bodyTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-body-type" data-body-type-id="'. $bodyTypeID .'" title="Delete Body Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $bodyTypeID .'">',
                    'BODY_TYPE_NAME' => $bodyTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="body-type.php?id='. $bodyTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: body type reference table
        # Description:
        # Generates the body type reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'body type reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBodyTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $bodyTypeID = $row['body_type_id'];
                $bodyTypeName = $row['body_type_name'];

                $response[] = [
                    'BODY_TYPE_ID' => $bodyTypeID,
                    'BODY_TYPE' => $bodyTypeName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>