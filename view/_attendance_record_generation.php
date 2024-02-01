<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: attendance record table
        # Description:
        # Generates the attendance record table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'attendance record table':
            if(isset($_POST['filter_attendance_record_date_start_date']) && isset($_POST['filter_attendance_record_date_end_date'])){
                $filterAttendanceRecordDateStartDate = $systemModel->checkDate('empty', $_POST['filter_attendance_record_date_start_date'], '', 'Y-m-d', '');
                $filterAttendanceRecordDateEndDate = $systemModel->checkDate('empty', $_POST['filter_attendance_record_date_end_date'], '', 'Y-m-d', '');
                $filterEmployeeStatus = htmlspecialchars($_POST['employment_status_filter'], ENT_QUOTES, 'UTF-8');
                $filterCheckInMode = $_POST['check_in_mode_filter'];
                $filterCheckOutMode = $_POST['check_out_mode_filter'];
                $filterCompany = htmlspecialchars($_POST['company_filter'], ENT_QUOTES, 'UTF-8');
                $filterDepartment = htmlspecialchars($_POST['department_filter'], ENT_QUOTES, 'UTF-8');
                $filterJobPosition = htmlspecialchars($_POST['job_position_filter'], ENT_QUOTES, 'UTF-8');
                $filterBranch = htmlspecialchars($_POST['branch_filter'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAttendanceRecordTable(:filterAttendanceRecordDateStartDate, :filterAttendanceRecordDateEndDate, :filterCheckInMode, :filterCheckOutMode, :filterEmployeeStatus, :filterCompany, :filterDepartment, :filterJobPosition, :filterBranch)');
                $sql->bindValue(':filterAttendanceRecordDateStartDate', $filterAttendanceRecordDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterAttendanceRecordDateEndDate', $filterAttendanceRecordDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterCheckInMode', $filterCheckInMode, PDO::PARAM_STR);
                $sql->bindValue(':filterCheckOutMode', $filterCheckOutMode, PDO::PARAM_STR);
                $sql->bindValue(':filterEmployeeStatus', $filterEmployeeStatus, PDO::PARAM_STR);
                $sql->bindValue(':filterCompany', $filterCompany, PDO::PARAM_STR);
                $sql->bindValue(':filterDepartment', $filterDepartment, PDO::PARAM_STR);
                $sql->bindValue(':filterJobPosition', $filterJobPosition, PDO::PARAM_STR);
                $sql->bindValue(':filterBranch', $filterBranch, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $attendanceRecordDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 52, 'delete');

                foreach ($options as $row) {
                    $attendanceRecordID = $row['attendance_id'];
                    $contactID = $row['contact_id'];
                    $checkIn = $systemModel->checkDate('empty', $row['check_in'], '', 'F d, Y g:i a', '');
                    $checkInMode = $row['check_in_mode'];
                    $checkOut = $systemModel->checkDate('empty', $row['check_out'], '', 'F d, Y g:i a', '');
                    $checkOutMode = $row['check_out_mode'];

                    $employeeDetails = $employeeModel->getPersonalInformation($contactID);
                    $fileAs = $employeeDetails['file_as'];

                    $attendanceRecordIDEncrypted = $securityModel->encryptData($attendanceRecordID);

                    $delete = '';
                    if($attendanceRecordDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-attendance-record" data-attendance-id="'. $attendanceRecordID .'" title="Delete Attendance Setting">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $attendanceRecordID .'">',
                        'EMPLOYEE_NAME' => $fileAs,
                        'CHECK_IN' => $checkIn,
                        'CHECK_IN_MODE' => $checkInMode,
                        'CHECK_OUT' => $checkOut,
                        'CHECK_OUT_MODE' => $checkOutMode,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="attendance-record.php?id='. $attendanceRecordIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
    }
}

?>