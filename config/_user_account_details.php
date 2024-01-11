<?php
$contactDetails = $userModel->getContactByID($user_id);
$userAccountContactID = $contactDetails['contact_id'] ?? null;

$user = $userModel->getUserByID($user_id);
$email = $user['email'];
$receiveNotification = $user['receive_notification'];
$twoFactorAuthentication = $user['two_factor_auth'];
$userAccountProfileImage = $systemModel->checkImage($user['profile_picture'], 'profile');

if (!empty($userAccountContactID)) {
    $currentTime = date('H:i:00');
    $currentDate = date('Y-m-d');
    $currentDay = date('l');

    $employeePersonalInformationDetails = $employeeModel->getPersonalInformation($userAccountContactID);
    $employeeEmploymentInformationDetails = $employeeModel->getEmploymentInformation($userAccountContactID);

    $fileAs = $employeePersonalInformationDetails['file_as'] ?? null;
    $employeeDepartmentID = $employeeEmploymentInformationDetails['department_id'] ?? null;
    $employeeWorkScheduleID = $employeeEmploymentInformationDetails['work_schedule_id'] ?? null;

    $employeeWorkScheduleDetails = $workScheduleModel->getWorkSchedule($employeeWorkScheduleID);
    $employeeWorkScheduleName = $employeeWorkScheduleDetails['work_schedule_name'] ?? null;
    $employeeWorkScheduleTypeID = $employeeWorkScheduleDetails['work_schedule_type_id'] ?? null;

    /*$getCurrentWorkingHours = $employeeWorkScheduleTypeID == 1
        ? $workScheduleModel->getCurrentFixedWorkingHours($employeeWorkScheduleID, $currentDay, $currentTime)
        : $workScheduleModel->getCurrentFlexibleWorkingHours($employeeWorkScheduleID, $currentDate, $currentTime);*/

    $startTime = $systemModel->checkDate('empty', $getCurrentWorkingHours[0]['start_time'] ?? null, '', 'h:i a', '');
    $endTime = $systemModel->checkDate('empty', $getCurrentWorkingHours[0]['end_time'] ?? null, '', 'h:i a', '');
    $currentShift = $startTime . ' - ' . $endTime;

    $employeeDepartmentDetails = $departmentModel->getDepartment($employeeDepartmentID);
    $employeeDepartmentName = $employeeDepartmentDetails['department_name'] ?? null;
}
else {
    $fileAs = $user['file_as'] ?? null;
    $employeeWorkScheduleName = null;
    $employeeDepartmentName = null;
    $currentShift = null;
}


?>