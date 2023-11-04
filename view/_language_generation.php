<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/language-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$languageModel = new LanguageModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: language table
        # Description:
        # Generates the language table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'language table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateLanguageTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $languageDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 49, 'delete');

            foreach ($options as $row) {
                $languageID = $row['language_id'];
                $languageName = $row['language_name'];

                $languageIDEncrypted = $securityModel->encryptData($languageID);

                $delete = '';
                if($languageDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-language" data-language-id="'. $languageID .'" title="Delete Language">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $languageID .'">',
                    'LANGUAGE_NAME' => $languageName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="language.php?id='. $languageIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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