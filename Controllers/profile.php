<?php
include_once '../config.php';
include_once '../util.php';

include_once '../Models/users.php';
include_once '../Models/tweets.php';

$user = getUserSession();
if (!$user) {
    header('Location: ' . HOME_URL . 'Controlloers/sign-in.php');
    exit;
}

if (isset($_POST['nickname']) && isset($_POST['name']) && isset($_POST['email'])) {
    $data = [
        'id' => $user['id'],
        'name' => $_POST['name'],
        'nickname' => $_POST['nickname'],
        'email' => $_POST['email'],
    ];

    if (isset($_POST['password']) && $_POST['password'] !== '') {
        $data['password'] = $_POST['password'];
    }

    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $data['image_name'] = uploadImage($user, $_FILES['image'], 'user');
    }

    if (updateUser($data)) {
        $user = findUser($user['id']);
        saveUserSession($user);
        header('Location: ' . HOME_URL . 'Controllers/profile.php');
    }
}

$requested_user_id = $user['id'];
if (isset($_GET['user_id'])) {
    $requested_user_id = (int)$_GET['user_id'];
}

$view_user = $user;
$view_requested_user = findUser($requested_user_id, $user['id']);

if (!$view_requested_user) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

$view_tweets = findTweets($user, null, [$requested_user_id]);

include_once '../Views/profile.php';