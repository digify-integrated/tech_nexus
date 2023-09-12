<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/nationality-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$nationalityModel = new NationalityModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: nationality table
        # Description:
        # Generates the nationality table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'nationality table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateNationalityTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $nationalityDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 35, 'delete');

            foreach ($options as $row) {
                $nationalityID = $row['nationality_id'];
                $nationalityName = $row['nationality_name'];

                $nationalityIDEncrypted = $securityModel->encryptData($nationalityID);

                $delete = '';
                if($nationalityDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-nationality" data-nationality-id="'. $nationalityID .'" title="Delete Nationality">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $nationalityID .'">',
                    'NATIONALITY_NAME' => $nationalityName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="nationality.php?id='. $nationalityIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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