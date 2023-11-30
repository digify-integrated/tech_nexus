<?php
$contactDetails = $userModel->getContactByID($user_id);
$userAccountContactID = $contactDetails['contact_id'] ?? null;
$employeeInformationDetails = $employeeModel->getPersonalInformation($userAccountContactID);

$user = $userModel->getUserByID($user_id);
$email = $user['email'];
$receiveNotification = $user['receive_notification'];
$twoFactorAuthentication = $user['two_factor_auth'];
$userAccountProfileImage = $systemModel->checkImage($user['profile_picture'], 'profile');

$fileAs = (!empty($employeeInformationDetails['file_as']) && !empty($userAccountContactID))? $employeeInformationDetails['file_as']: ($user['file_as'] ?? null);

?>