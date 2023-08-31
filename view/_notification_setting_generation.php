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
                $systemNotification = $row['system_notification'];
                $emailNotification = $row['email_notification'];
                $smsNotification = $row['sms_notification'];

                $notificationChannel = '<div class="d-flex flex-wrap gap-2">';

                if ($systemNotification) {
                    $notificationChannel .= '<span class="badge bg-primary">System</span>';
                }

                if ($emailNotification) {
                    $notificationChannel .= '<span class="badge bg-warning">Email</span>';
                }

                if ($smsNotification) {
                    $notificationChannel .= '<span class="badge bg-success">SMS</span>';
                }
                
                $notificationChannel .= '</div>';

                $notificationChannel = !empty($notificationChannel) ? $notificationChannel : 'No Notification Channel';

                $notificationSettingIDEncrypted = $securityModel->encryptData($notificationSettingID);

                $delete = '';
                if($notificationSettingDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-notification-setting" data-notification-setting-id="'. $notificationSettingID .'" title="Delete Notification Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $notificationSettingID .'">',
                    'NOTIFICATION_SETTING_NAME' => ' <div class="col">
                                        <h6 class="mb-0">'. $notificationSettingName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $notificationSettingDescription .'</p>
                                        </div>',
                    'NOTIFICATION_CHANNEL' => $notificationChannel,
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
        
        # -------------------------------------------------------------
        #
        # Type: update notification channel table
        # Description:
        # Generates the update notification channel table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'update notification channel table':
            if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
                $notificationSettingID = htmlspecialchars($_POST['notification_setting_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateUpdateNotificationChannelTable(:notificationSettingID)');
                $sql->bindValue(':notificationSettingID', $notificationSettingID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $systemNotification = $row['system_notification'];
                    $emailNotification = $row['email_notification'];
                    $smsNotification = $row['sms_notification'];

                    $systemNotificationChecked = $systemNotification ? 'checked' : '';
                    $emailNotificationChecked = $emailNotification ? 'checked' : '';
                    $smsNotificationChecked = $smsNotification ? 'checked' : '';

                    $notificationChannels = [
                        ['channel' => 'System', 'checked' => $systemNotificationChecked],
                        ['channel' => 'Email', 'checked' => $emailNotificationChecked],
                        ['channel' => 'SMS', 'checked' => $smsNotificationChecked]
                    ];
                    
                    foreach ($notificationChannels as $channelData) {
                        $response[] = [
                            'NOTIFICATION_CHANNEL' => $channelData['channel'],
                            'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input update-notification-channel-status" type="checkbox" data-channel="'. strtolower($channelData['channel']) .'" '. $channelData['checked'] .'></div>'
                        ];
                    }
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>