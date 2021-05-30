<?php
include_once '../config.php';
include_once '../util.php';

include_once '../Models/likes.php';
include_once '../Models/tweets.php';
include_once '../Models/notifications.php';

$user = getUserSession();
if (!$user) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

$like_id = null;
if (isset($_POST['tweet_id'])) {
    $data = [
        'tweet_id' => $_POST['tweet_id'],
        'user_id' => $user['id'],
    ];
    $like_id = createLike($data);

    $tweet = findTweet($_POST['tweet_id']);

    $data_notification = [
        'received_user_id' => $tweet['user_id'],
        'sent_user_id' => $user['id'],
        'message' => 'いいね！されました。',
    ];
    createNotification($data_notification);
}

if (isset($_POST['like_id'])) {
    $data = [
        'like_id' => $_POST['like_id'],
        'user_id' => $user['id'],
    ];
    deleteLike($data);
}

$response = [
    'message' => 'successful',
    'like_id' => $like_id,
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);