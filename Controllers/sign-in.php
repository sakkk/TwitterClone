<?php
include_once '../config.php';
include_once '../util.php';
include_once '../Models/users.php';

$try_login_reslut = null;

if (isset($_POST['email']) && isset($_POST['password'])) {
    
    $user = findUserAndCheckPassword($_POST['email'], $_POST['password']);

    if ($user) {
        saveUserSession($user);
        header('Location: ' . HOME_URL . 'Controllers/home.php');
        exit;
    } else {
        $try_login_reslut = false;
    }
}

$view_try_login_result = $try_login_reslut;
include_once '../Views/sign-in.php';