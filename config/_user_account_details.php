<?php
$user = $userModel->getUserByID($user_id);
$email = $user['email'];
$fileAs = $user['file_as'];
$receiveNotification = $user['receive_notification'];
?>