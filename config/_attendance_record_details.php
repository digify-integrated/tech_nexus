<?php
$userCheckIn = '--';
$userCheckOut = '--';
$currentAttendanceID = null;
$userAttendanceRecordCount = 0;
$maxAttendanceRecord = $attendanceSettingModel->getAttendanceSetting(1)['value'];

if (!empty($contact_id)) {
    $userAttendanceRecordCount = $employeeModel->getAttendanceRecordCount($contact_id)['total'];
    $userAttendanceRecordWithoutCheckOut = $employeeModel->getAttendanceRecordWithoutCheckOut($contact_id);
    $userLatestAttendanceRecord = $employeeModel->getLatestAttendanceRecord($contact_id);

    $currentAttendanceID = $userAttendanceRecordWithoutCheckOut['attendance_id'] ?? $userLatestAttendanceRecord['attendance_id'] ?? null;

    if (!empty($currentAttendanceID)) {
        $attendanceRecord = !empty($userAttendanceRecordWithoutCheckOut) ? $userAttendanceRecordWithoutCheckOut : $userLatestAttendanceRecord;

        $userCheckIn = !empty($attendanceRecord['check_in']) ? date('F d, Y g:i a', strtotime($attendanceRecord['check_in'])) : $userCheckIn;
        $userCheckOut = !empty($attendanceRecord['check_out']) ? date('F d, Y g:i a', strtotime($attendanceRecord['check_out'])) : $userCheckOut;
    }
}
?>