<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/leave-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leaveTypeModel = new LeaveTypeModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: leave application table
        # Description:
        # Generates the leave application table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leave application table':
            $contactID = $_SESSION['contact_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateLeaveApplicationTable(:contactID)');
            $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $leaveApplicationDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 138, 'delete');

            foreach ($options as $row) {
                $leaveApplicationID = $row['leave_application_id'];
                $status = $row['status'];
                $leaveTypeID = $row['leave_type_id'];
                $application_type = $row['application_type'];
                $leaveDate = $systemModel->checkDate('empty', $row['leave_date'], '', 'm/d/Y', '');
                $leaveStartTime = $systemModel->checkDate('empty', $row['leave_start_time'], '', 'h:i a', '');
                $leaveEndTime = $systemModel->checkDate('empty', $row['leave_end_time'], '', 'h:i a', '');
                $applicationDate = $systemModel->checkDate('empty', $row['application_date'], '', 'm/d/Y', '');

                $leaveApplicationIDEncrypted = $securityModel->encryptData($leaveApplicationID);

                $leaveTypeDetails = $leaveTypeModel->getLeaveType($leaveTypeID);
                $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

                $delete = '';
                if($leaveApplicationDeleteAccess['total'] > 0 && ($status == 'Draft')){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-leave-application" data-leave-application-id="'. $leaveApplicationID .'" title="Delete Leave Application">
                                    <i class="ti ti-trash"></i>
                                </button>'; 
                }

                if($leaveTypeID == '1'){
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $application_type .'</p>
                    </div>';
                }
                else{
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $leaveStartTime . ' - ' . $leaveEndTime .'</p>
                    </div>';
                }

                $response[] = [
                    'LEAVE_TYPE' => $leaveTypeName,
                    'LEAVE_DATE' => $leaveDateDetails,
                    'APPLICATION_DATE' => $applicationDate,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="leave-application.php?id='. $leaveApplicationIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        case 'manual leave application table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateManualLeaveApplicationTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $leaveApplicationDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 138, 'delete');

            foreach ($options as $row) {
                $leaveApplicationID = $row['leave_application_id'];
                $contact_id = $row['contact_id'];
                $status = $row['status'];
                $leaveTypeID = $row['leave_type_id'];
                $application_type = $row['application_type'];
                $leaveDate = $systemModel->checkDate('empty', $row['leave_date'], '', 'm/d/Y', '');
                $leaveStartTime = $systemModel->checkDate('empty', $row['leave_start_time'], '', 'h:i a', '');
                $leaveEndTime = $systemModel->checkDate('empty', $row['leave_end_time'], '', 'h:i a', '');
                $applicationDate = $systemModel->checkDate('empty', $row['application_date'], '', 'm/d/Y', '');

                $leaveApplicationIDEncrypted = $securityModel->encryptData($leaveApplicationID);

                $leaveTypeDetails = $leaveTypeModel->getLeaveType($leaveTypeID);
                $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

                $delete = '';
                if($leaveApplicationDeleteAccess['total'] > 0 && ($status == 'Draft')){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-leave-application" data-leave-application-id="'. $leaveApplicationID .'" title="Delete Leave Application">
                                    <i class="ti ti-trash"></i>
                                </button>'; 
                }

                if($leaveTypeID == '1'){
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $application_type .'</p>
                    </div>';
                }
                else{
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $leaveStartTime . ' - ' . $leaveEndTime .'</p>
                    </div>';
                }

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';

                $response[] = [
                    'FILE_AS' => $fileAs,
                    'LEAVE_TYPE' => $leaveTypeName,
                    'LEAVE_DATE' => $leaveDateDetails,
                    'APPLICATION_DATE' => $applicationDate,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="manual-leave-application.php?id='. $leaveApplicationIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        case 'leave recommendation table':
            $contactID = $_SESSION['contact_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateLeaveRecommendationTable(:contactID)');
            $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $leaveApplicationID = $row['leave_application_id'];
                $status = $row['status'];
                $employeeID = $row['contact_id'];
                $leaveTypeID = $row['leave_type_id'];
                $application_type = $row['application_type'];
                $leaveDate = $systemModel->checkDate('empty', $row['leave_date'], '', 'm/d/Y', '');
                $leaveStartTime = $systemModel->checkDate('empty', $row['leave_start_time'], '', 'h:i a', '');
                $leaveEndTime = $systemModel->checkDate('empty', $row['leave_end_time'], '', 'h:i a', '');
                $applicationDate = $systemModel->checkDate('empty', $row['application_date'], '', 'm/d/Y', '');

                $leaveApplicationIDEncrypted = $securityModel->encryptData($leaveApplicationID);

                $leaveTypeDetails = $leaveTypeModel->getLeaveType($leaveTypeID);
                $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

                $employeeDetails = $employeeModel->getPersonalInformation($employeeID);
                $fileAs = $employeeDetails['file_as'] ?? '';

                if($leaveTypeID == '1'){
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $application_type .'</p>
                    </div>';
                }
                else{
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $leaveStartTime . ' - ' . $leaveEndTime .'</p>
                    </div>';
                }

                $response[] = [
                    'FILE_AS' => '<a href="leave-recommendation.php?id='. $leaveApplicationIDEncrypted .'">'. $fileAs . '</a>',
                    'LEAVE_TYPE' => $leaveTypeName,
                    'LEAVE_DATE' => $leaveDateDetails,
                    'APPLICATION_DATE' => $applicationDate,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="leave-recommendation.php?id='. $leaveApplicationIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
        case 'leave approval table':
            $contactID = $_SESSION['contact_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateLeaveApprovalTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $leaveApplicationID = $row['leave_application_id'];
                $status = $row['status'];
                $leaveTypeID = $row['leave_type_id'];
                $application_type = $row['application_type'];
                $contact_id = $row['contact_id'];
                $leaveDate = $systemModel->checkDate('empty', $row['leave_date'], '', 'm/d/Y', '');
                $leaveStartTime = $systemModel->checkDate('empty', $row['leave_start_time'], '', 'h:i a', '');
                $leaveEndTime = $systemModel->checkDate('empty', $row['leave_end_time'], '', 'h:i a', '');
                $applicationDate = $systemModel->checkDate('empty', $row['application_date'], '', 'm/d/Y', '');

                $leaveApplicationIDEncrypted = $securityModel->encryptData($leaveApplicationID);

                $leaveTypeDetails = $leaveTypeModel->getLeaveType($leaveTypeID);
                $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';

                if($leaveTypeID == '1'){
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $application_type .'</p>
                    </div>';
                }
                else{
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $leaveStartTime . ' - ' . $leaveEndTime .'</p>
                    </div>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leaveApplicationID .'">',
                    'FILE_AS' => '<a href="leave-approval.php?id='. $leaveApplicationIDEncrypted .'">'. $fileAs . '</a>',
                    'LEAVE_TYPE' => $leaveTypeName,
                    'LEAVE_DATE' => $leaveDateDetails,
                    'APPLICATION_DATE' => $applicationDate,
                    'STATUS' => $status,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="leave-approval.php?id='. $leaveApplicationIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        case 'leave dashboard approval table':
            $contactID = $_SESSION['contact_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateLeaveDashboardApprovalTable(:contactID)');
            $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $leaveApplicationID = $row['leave_application_id'];
                $contact_id = $row['contact_id'];
                $status = $row['status'];
                $leaveTypeID = $row['leave_type_id'];
                $application_type = $row['application_type'];
                $leaveDate = $systemModel->checkDate('empty', $row['leave_date'], '', 'm/d/Y', '');
                $leaveStartTime = $systemModel->checkDate('empty', $row['leave_start_time'], '', 'h:i a', '');
                $leaveEndTime = $systemModel->checkDate('empty', $row['leave_end_time'], '', 'h:i a', '');
                $applicationDate = $systemModel->checkDate('empty', $row['application_date'], '', 'm/d/Y', '');

                $leaveApplicationIDEncrypted = $securityModel->encryptData($leaveApplicationID);

                $leaveTypeDetails = $leaveTypeModel->getLeaveType($leaveTypeID);
                $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';

                if($leaveTypeID == '1'){
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $application_type .'</p>
                    </div>';
                }
                else{
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $leaveStartTime . ' - ' . $leaveEndTime .'</p>
                    </div>';
                }

                $response[] = [
                    'FILE_AS' => '<a href="leave-approval.php?id='. $leaveApplicationIDEncrypted .'">'. $fileAs . '</a>',
                    'LEAVE_TYPE' => $leaveTypeName,
                    'LEAVE_DATE' => $leaveDateDetails,
                    'APPLICATION_DATE' => $applicationDate,
                    'STATUS' => $status
                    ];
            }

            echo json_encode($response);
        break;
        case 'leave summary table':
            
            $leaveStatusFilter = htmlspecialchars($_POST['leave_status_filter'], ENT_QUOTES, 'UTF-8');
            $filterLeaveStartDate = $systemModel->checkDate('empty', $_POST['filter_leave_start_date'], '', 'Y-m-d', '');
            $filterLeaveEndDate = $systemModel->checkDate('empty', $_POST['filter_leave_end_date'], '', 'Y-m-d', '');

            $filterApplicationStartDate = $systemModel->checkDate('empty', $_POST['filter_application_start_date'], '', 'Y-m-d', '');
            $filterApplicationEndDate = $systemModel->checkDate('empty', $_POST['filter_application_end_date'], '', 'Y-m-d', '');

            $filterApprovalStartDate = $systemModel->checkDate('empty', $_POST['filter_approval_start_date'], '', 'Y-m-d', '');
            $filterApprovalEndDate = $systemModel->checkDate('empty', $_POST['filter_approval_end_date'], '', 'Y-m-d', '');

            $sql = $databaseModel->getConnection()->prepare('CALL generateLeaveSummaryTable(:leaveStatusFilter, :filterLeaveStartDate, :filterLeaveEndDate, :filterApplicationStartDate, :filterApplicationEndDate, :filterApprovalStartDate, :filterApprovalEndDate)');
            $sql->bindValue(':leaveStatusFilter', $leaveStatusFilter, PDO::PARAM_STR);
            $sql->bindValue(':filterLeaveStartDate', $filterLeaveStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterLeaveEndDate', $filterLeaveEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterApplicationStartDate', $filterApplicationStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterApplicationEndDate', $filterApplicationEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filterApprovalStartDate', $filterApprovalStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterApprovalEndDate', $filterApprovalEndDate, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $contact_id = $row['contact_id'];
                $leaveApplicationID = $row['leave_application_id'];
                $status = $row['status'];
                $leaveTypeID = $row['leave_type_id'];
                $reason = $row['reason'];
                $application_type = $row['application_type'];
                $leaveDate = $systemModel->checkDate('empty', $row['leave_date'], '', 'm/d/Y', '');
                $leaveStartTime = $systemModel->checkDate('empty', $row['leave_start_time'], '', 'h:i a', '');
                $leaveEndTime = $systemModel->checkDate('empty', $row['leave_end_time'], '', 'h:i a', '');
                $applicationDate = $systemModel->checkDate('empty', $row['application_date'], '', 'm/d/Y', '');
                $approval_date = $systemModel->checkDate('empty', $row['approval_date'], '', 'm/d/Y', '');

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';

                $leaveApplicationIDEncrypted = $securityModel->encryptData($leaveApplicationID);

                $leaveTypeDetails = $leaveTypeModel->getLeaveType($leaveTypeID);
                $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

                if($leaveTypeID == '1'){
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $application_type .'</p>
                    </div>';
                }
                else{
                    $leaveDateDetails =  ' <div class="col">
                    <h6 class="mb-0">'. $leaveDate .'</h6>
                    <p class="text-muted f-12 mb-0">'. $leaveStartTime . ' - ' . $leaveEndTime .'</p>
                    </div>';
                }

                $response[] = [
                    'FILE_AS' => $fileAs,
                    'LEAVE_TYPE' => ' <div class="col">
                                        <h6 class="mb-0">'. $leaveTypeName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $reason .'</p>
                                        </div>',
                    'LEAVE_DATE' => $leaveDateDetails,
                    'APPLICATION_DATE' => $applicationDate,
                    'APPROVAL_DATE' => $approval_date,
                    'STATUS' => $status
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>