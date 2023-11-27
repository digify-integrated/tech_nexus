<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/attendance-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$attendanceSettingModel = new AttendanceSettingModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: attendance setting table
        # Description:
        # Generates the attendance setting table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'attendance setting table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateAttendanceSettingTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $attendanceSettingDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 51, 'delete');

            foreach ($options as $row) {
                $attendanceSettingID = $row['attendance_setting_id'];
                $attendanceSettingName = $row['attendance_setting_name'];
                $attendanceSettingDescription = $row['attendance_setting_description'];
                $value = $row['value'];

                $attendanceSettingIDEncrypted = $securityModel->encryptData($attendanceSettingID);

                $delete = '';
                if($attendanceSettingDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-attendance-setting" data-attendance-setting-id="'. $attendanceSettingID .'" title="Delete Attendance Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $attendanceSettingID .'">',
                    'ATTENDANCE_SETTING_NAME' => '<div class="col">
                                                <h6 class="mb-0">'. $attendanceSettingName .'</h6>
                                                <p class="text-muted f-12 mb-0">'. $attendanceSettingDescription .'</p>
                                            </div>',
                    'VALUE' => $value,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="attendance-setting.php?id='. $attendanceSettingIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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