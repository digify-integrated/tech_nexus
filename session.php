<?php   
session_start();

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $contact_id = $_SESSION['contact_id'] ?? null;
} 
else {
    session_unset();
    session_destroy();
    header('location: index.php');
    exit();
}
?>