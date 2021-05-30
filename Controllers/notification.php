<?php
include_once '../config.php';
include_once '../util.php';

include_once '../Models/notifications.php';

$user = getUserSession();
if (!$user) {
    header('Location: ' . HOME_URL . 'Controlloers/sign-in.php');
    exit;
}

$view_user = $user;
$view_notifications = findNotifications($user['id']);

include_once '../Views/notification.php';