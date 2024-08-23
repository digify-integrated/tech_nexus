<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/class-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$classModel = new ClassModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: class table
        # Description:
        # Generates the class table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'class table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateClassTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $classDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 103, 'delete');

            foreach ($options as $row) {
                $classID = $row['class_id'];
                $className = $row['class_name'];

                $classIDEncrypted = $securityModel->encryptData($classID);

                $delete = '';
                if($classDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-class" data-class-id="'. $classID .'" title="Delete Class">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $classID .'">',
                    'CLASS_NAME' => $className,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="class.php?id='. $classIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
        # Type: class reference table
        # Description:
        # Generates the class reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'class reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateClassTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $classID = $row['class_id'];
                $className = $row['class_name'];

                $response[] = [
                    'CLASS_ID' => $classID,
                    'CLASS' => $className
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>