<?php
$user_details = $api->get_user_details(null, $email);
$file_as = $user_details[0]['FILE_AS'];
$user_status = $api->get_user_status($user_details[0]['USER_STATUS'])[0]['BADGE'];
?>