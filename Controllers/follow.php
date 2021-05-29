<?php
include_once '../config.php';
include_once '../util.php';

include_once '../Models/follows.php';

$user = getUserSession();
if (!$user) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

$follow_id = null;
if (isset($_POST['followed_user_id'])) {
    $data = [
        'followed_user_id' => $_POST['followed_user_id'],
        'follow_user_id' => $user['id'],
    ];
    $follow_id = createFollow($data);
}

if (isset($_POST['follow_id'])) {
    $data = [
        'follow_id' => $_POST['follow_id'],
        'follow_user_id' => $user['id'],
    ];
    deleteFollow($data);
}

$response = [
    'message' => 'successful',
    'follow_id' => $follow_id,
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);