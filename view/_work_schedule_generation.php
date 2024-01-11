<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/work-schedule-model.php';
require_once '../model/work-schedule-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$workScheduleModel = new WorkScheduleModel($databaseModel);
$workScheduleTypeModel = new WorkScheduleTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: work schedule table
        # Description:
        # Generates the work schedule table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'work schedule table':
            if(isset($_POST['filter_work_schedule_type'])){
                $filterWorkScheduleType = htmlspecialchars($_POST['filter_work_schedule_type'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateWorkScheduleTable(:filterWorkScheduleType)');
                $sql->bindValue(':filterWorkScheduleType', $filterWorkScheduleType, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $workScheduleDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 42, 'delete');

                foreach ($options as $row) {
                    $workScheduleID = $row['work_schedule_id'];
                    $workScheduleName = $row['work_schedule_name'];
                    $workScheduleDescription = $row['work_schedule_description'];
                    $workScheduleTypeID = $row['work_schedule_type_id'];

                    $workScheduleIDEncrypted = $securityModel->encryptData($workScheduleID);

                    $workScheduleTypeDetails = $workScheduleTypeModel->getWorkScheduleType($workScheduleTypeID);
                    $workScheduleTypeName = $workScheduleTypeDetails['work_schedule_type_name'] ?? null;

                    $delete = '';
                    if($workScheduleDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-work-schedule" data-work-schedule-id="'. $workScheduleID .'" title="Delete Work Schedule">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $workScheduleID .'">',
                        'WORK_SCHEDULE_NAME' => '<div class="col">
                                                    <h6 class="mb-0">'. $workScheduleName .'</h6>
                                                    <p class="f-12 mb-0">'. $workScheduleDescription .'</p>
                                                </div>',
                        'WORK_SCHEDULE_TYPE' => $workScheduleTypeName,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="work-schedule.php?id='. $workScheduleIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: fixed working hours table
        # Description:
        # Generates the fixed working hours table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'fixed working hours table':
            if(isset($_POST['work_schedule_id']) && !empty($_POST['work_schedule_id'])){
                $workScheduleID = htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateWorkHoursTable(:workScheduleID)');
                $sql->bindValue(':workScheduleID', $workScheduleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $updateWorkHours = $userModel->checkSystemActionAccessRights($user_id, 30);
                $deleteWorkHours = $userModel->checkSystemActionAccessRights($user_id, 31);

                foreach ($options as $row) {
                    $workHoursID = $row['work_hours_id'];
                    $startDayOfWeek = $row['start_day_of_week'];
                    $endDayOfWeek = $row['end_day_of_week'];
                    $lunchBreakStartDayOfWeek = $row['lunch_break_start_day_of_week'];
                    $lunchBreakEndDayOfWeek = $row['lunch_break_end_day_of_week'];
                    $startTime = $systemModel->checkDate('empty', $row['start_time'], '', 'h:i a', '');
                    $endTime = $systemModel->checkDate('empty', $row['end_time'], '', 'h:i a', '');
                    $lunchBreakStartTime = $systemModel->checkDate('empty', $row['lunch_break_start_time'], '', 'h:i a', '');
                    $lunchBreakEndTime = $systemModel->checkDate('empty', $row['lunch_break_end_time'], '', 'h:i a', '');
                    $notes = $row['notes'];

                    $update = '';
                    if($updateWorkHours['total'] > 0){
                        $update = '<button class="btn btn-icon btn-success update-fixed-working-hours" type="button" data-bs-toggle="offcanvas" data-bs-target="#fixed-working-hours-offcanvas" aria-controls="fixed-working-hours-offcanvas" data-work-hours-id="'. $workHoursID .'">
                            <i class="ti ti-pencil"></i>
                        </button>';
                    }

                    $delete = '';
                    if($deleteWorkHours['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-fixed-working-hours" data-work-hours-id="'. $workHoursID .'" title="Delete Fixed Work Hours">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'WORK_FROM' => date('l', strtotime("Sunday +$startDayOfWeek days")) . '<br/>' . $startTime,
                        'WORK_TO' => date('l', strtotime("Sunday +$endDayOfWeek days")) . '<br/>' . $endTime,
                        'LUNCH_START' => date('l', strtotime("Sunday +$lunchBreakStartDayOfWeek days")) . '<br/>' . $lunchBreakStartTime,
                        'LUNCH_END' => date('l', strtotime("Sunday +$lunchBreakEndDayOfWeek days")) . '<br/>' . $lunchBreakEndTime,
                        'NOTES' => $notes,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $update .'
                                        '. $delete .'
                                    </div>'
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: flexible working hours table
        # Description:
        # Generates the flexible working hours table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'flexible working hours table':
            if(isset($_POST['work_schedule_id']) && !empty($_POST['work_schedule_id'])){
                $workScheduleID = htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateWorkHoursTable(:workScheduleID)');
                $sql->bindValue(':workScheduleID', $workScheduleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $updateWorkHours = $userModel->checkSystemActionAccessRights($user_id, 30);
                $deleteWorkHours = $userModel->checkSystemActionAccessRights($user_id, 31);

                foreach ($options as $row) {
                    $workHoursID = $row['work_hours_id'];
                    $startWorkDate = $systemModel->checkDate('empty', $row['start_work_date'], '', 'm/d/Y', '');
                    $endWorkDate = $systemModel->checkDate('empty', $row['end_work_date'], '', 'm/d/Y', '');
                    $startTime = $systemModel->checkDate('empty', $row['start_time'], '', 'h:i a', '');
                    $endTime = $systemModel->checkDate('empty', $row['end_time'], '', 'h:i a', '');
                    $lunchBreakStartWorkDate = $systemModel->checkDate('empty', $row['lunch_break_start_work_date'], '', 'm/d/Y', '');
                    $lunchBreakEndWorkDate = $systemModel->checkDate('empty', $row['lunch_break_end_work_date'], '', 'm/d/Y', '');
                    $lunchBreakStartTime = $systemModel->checkDate('empty', $row['lunch_break_start_time'], '', 'h:i a', '');
                    $lunchBreakEndTime = $systemModel->checkDate('empty', $row['lunch_break_end_time'], '', 'h:i a', '');
                    $notes = $row['notes'];

                    $update = '';
                    if($updateWorkHours['total'] > 0){
                        $update = '<button class="btn btn-icon btn-success update-flexible-working-hours" type="button" data-bs-toggle="offcanvas" data-bs-target="#flexible-working-hours-offcanvas" aria-controls="flexible-working-hours-offcanvas" data-work-hours-id="'. $workHoursID .'">
                            <i class="ti ti-pencil"></i>
                        </button>';
                    }

                    $delete = '';
                    if($deleteWorkHours['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-flexible-working-hours" data-work-hours-id="'. $workHoursID .'" title="Delete Flexible Work Hours">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'WORK_FROM' => $startWorkDate . '<br/>' . $startTime,
                        'WORK_TO' => $endWorkDate . '<br/>' . $endTime,
                        'LUNCH_START' => $lunchBreakStartWorkDate . '<br/>' . $lunchBreakStartTime,
                        'LUNCH_END' => $lunchBreakEndWorkDate . '<br/>' . $lunchBreakEndTime,
                        'NOTES' => $notes,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $update .'
                                        '. $delete .'
                                    </div>'
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

    }
}

?>