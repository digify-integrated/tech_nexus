<?php
$user = $userModel->getUserByID($user_id);

if (!$user || !$user['is_active']) {
  header('location: logout.php?logout');
  exit;
}
?>