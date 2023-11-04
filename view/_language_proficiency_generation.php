<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/language-proficiency-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$languageProficiencyModel = new LanguageProficiencyModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {        
        # -------------------------------------------------------------
        #
        # Type: language proficiency table
        # Description:
        # Generates the language proficiency table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'language proficiency table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateLanguageProficiencyTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $languageProficiencyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 50, 'delete');

            foreach ($options as $row) {
                $languageProficiencyID = $row['language_proficiency_id'];
                $languageProficiencyName = $row['language_proficiency_name'];
                $description = $row['description'];

                $languageProficiencyIDEncrypted = $securityModel->encryptData($languageProficiencyID);

                $delete = '';
                if($languageProficiencyDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-language-proficiency" data-language-proficiency-id="'. $languageProficiencyID .'" title="Delete Language Proficiency">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $languageProficiencyID .'">',
                    'LANGUAGE_PROFICIENCY_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $languageProficiencyName .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $description .'</p>
                                        </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="language-proficiency.php?id='. $languageProficiencyIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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