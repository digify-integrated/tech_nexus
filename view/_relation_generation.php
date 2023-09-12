<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/relation-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$relationModel = new RelationModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: relation table
        # Description:
        # Generates the relation table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'relation table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateRelationTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $relationDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 35, 'delete');

            foreach ($options as $row) {
                $relationID = $row['relation_id'];
                $relationName = $row['relation_name'];

                $relationIDEncrypted = $securityModel->encryptData($relationID);

                $delete = '';
                if($relationDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-relation" data-relation-id="'. $relationID .'" title="Delete Relation">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $relationID .'">',
                    'RELATION_NAME' => $relationName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="relation.php?id='. $relationIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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