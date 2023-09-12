<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/gender-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$genderModel = new GenderModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: gender table
        # Description:
        # Generates the gender table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'gender table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateGenderTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $genderDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 33, 'delete');

            foreach ($options as $row) {
                $genderID = $row['gender_id'];
                $genderName = $row['gender_name'];

                $genderIDEncrypted = $securityModel->encryptData($genderID);

                $delete = '';
                if($genderDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-gender" data-gender-id="'. $genderID .'" title="Delete Gender">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $genderID .'">',
                    'GENDER_NAME' => $genderName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="gender.php?id='. $genderIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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