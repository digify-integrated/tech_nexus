<?php
$user = $userModel->getUserByID($user_id);
$email = $user['email'];
$fileAs = $user['file_as'];
$receiveNotification = $user['receive_notification'];
$twoFactorAuthentication = $user['two_factor_auth'];
$userAccountProfileImage = $systemModel->checkImage($user['profile_picture'], 'profile');
?>