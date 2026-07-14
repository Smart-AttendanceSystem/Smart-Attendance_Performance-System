<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    $uid = (int) $_SESSION['user_id'];
    $conn->query("UPDATE `user` SET last_activity = NULL WHERE id = $uid");
}

session_unset();
session_destroy();

header('Location: login.php');
exit;
?>

