<?php
include_once '../config.php';
include_once '../Models/users.php';

$error_messages = [];

if (isset($_POST['nickname']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $data = [
        'nickname' => (string)$_POST['nickname'],
        'name' => (string)$_POST['name'],
        'email' => (string)$_POST['email'],
        'password' => (string)$_POST['password'],
    ];

    $length = mb_strlen($data['nickname']);
    if ($length < 1 || $length > 50) {
        $error_messages[] = 'ニックネームは1〜50文字にしてください';
    }
    if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = 'メールアドレスが不正です';
    }
    if (findUser($data['email'])) {
        $error_messages[] = 'このメールアドレスは使用されています';
    }
    if (findUser($data['name'])) {
        $error_messages[] = 'このユーザー名は使用されています';
    }

    if (!$error_messages) {
        if (createUser($data)) {
            header(('Location: ' . HOME_URL . 'Controllers/sign-in.php'));
            exit;
        }
    }
}

$view_error_messsages = $error_messages;
include_once '../Views/sign-up.php';