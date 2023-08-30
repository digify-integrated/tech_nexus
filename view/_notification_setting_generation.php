<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/notification-setting-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$notificationSettingModel = new NotificationSettingModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: notification setting table
        # Description:
        # Generates the notification setting table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'notification setting table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateNotificationSettingTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $notificationSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 16, 'delete');

            foreach ($options as $row) {
                $notificationSettingID = $row['notification_setting_id'];
                $notificationSettingName = $row['notification_setting_name'];
                $notificationSettingDescription = $row['notification_setting_description'];

                $notificationSettingIDEncrypted = $securityModel->encryptData($notificationSettingID);

                $delete = '';
                if($notificationSettingDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-notification-setting" data-notification-setting-id="'. $notificationSettingID .'" title="Delete Notification Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $notificationSettingID .'">',
                    'SYSTEM_SETTING_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $notificationSettingName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $notificationSettingDescription .'</p>
                                        </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="notification-setting.php?id='. $notificationSettingIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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