<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/work-schedule-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$workScheduleTypeModel = new WorkScheduleTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: work schedule type table
        # Description:
        # Generates the work schedule type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'work schedule type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateWorkScheduleTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $workScheduleTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 41, 'delete');

            foreach ($options as $row) {
                $workScheduleTypeID = $row['work_schedule_type_id'];
                $workScheduleTypeName = $row['work_schedule_type_name'];

                $workScheduleTypeIDEncrypted = $securityModel->encryptData($workScheduleTypeID);

                $delete = '';
                if($workScheduleTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-work-schedule-type" data-work-schedule-type-id="'. $workScheduleTypeID .'" title="Delete Work Schedule Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $workScheduleTypeID .'">',
                    'WORK_SCHEDULE_TYPE_NAME' => $workScheduleTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="work-schedule-type.php?id='. $workScheduleTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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