<?php
$contactDetails = $userModel->getContactByID($user_id);
$userAccountContactID = $contactDetails['contact_id'] ?? null;
$employeePersonalInformationDetails = $employeeModel->getPersonalInformation($userAccountContactID);
$employeeEmploymentInformationDetails = $employeeModel->getEmploymentInformation($userAccountContactID);

$user = $userModel->getUserByID($user_id);
$email = $user['email'];
$receiveNotification = $user['receive_notification'];
$twoFactorAuthentication = $user['two_factor_auth'];
$userAccountProfileImage = $systemModel->checkImage($user['profile_picture'], 'profile');

$fileAs = (!empty($employeePersonalInformationDetails['file_as']) && !empty($userAccountContactID))? $employeePersonalInformationDetails['file_as']: ($user['file_as'] ?? null);
$departmentID = $employeeEmploymentInformationDetails['department_id'] ?? null;

$employeeDepartmentDetails = $departmentModel->getDepartment($departmentID);
$employeeDepartmentName = $employeeDepartmentDetails['department_name'] ?? null;

?>