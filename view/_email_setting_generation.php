<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/email-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$emailSettingModel = new EmailSettingModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: email setting table
        # Description:
        # Generates the email setting table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'email setting table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateEmailSettingTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $emailSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 12, 'delete');

            foreach ($options as $row) {
                $emailSettingID = $row['email_setting_id'];
                $emailSettingName = $row['email_setting_name'];
                $emailSettingDescription = $row['email_setting_description'];

                $emailSettingIDEncrypted = $securityModel->encryptData($emailSettingID);

                $delete = '';
                if($emailSettingDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-email-setting" data-email-setting-id="'. $emailSettingID .'" title="Delete Email Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $emailSettingID .'">',
                    'EMAIL_SETTING_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $emailSettingName .'</h6>
                                            <p class="text-muted f-12 mb-0">'. $emailSettingDescription .'</p>
                                        </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="email-setting.php?id='. $emailSettingIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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