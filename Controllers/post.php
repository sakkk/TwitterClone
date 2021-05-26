<?php
include_once '../config.php';
include_once '../util.php';
include_once '../Models/tweets.php';

$user = getUserSession();
if (!$user) {
    header('Location: ' . HOME_URL . 'Controlloers/sign-in.php');
    exit;
}

if (isset($_POST['body'])) {
    $image_name = null;
    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $image_name = uploadImage($user, $_FILES['image'], 'tweet');
    }

    $data = [
        'user_id' => $user['id'],
        'body' => $_POST['body'],
        'image_name' => $image_name,
    ];

    if (createTweet($data)) {
        header('Location: ' . HOME_URL . '/Controllers/home.php');
        exit;
    }
}

$view_user = $user;
include_once '../Views/post.php';
