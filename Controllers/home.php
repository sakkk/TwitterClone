<?php
include_once '../config.php';
include_once '../util.php';

include_once '../Models/follows.php';
include_once '../Models/tweets.php';

$user = getUserSession();
if (!$user) {
    header('Location: ' . HOME_URL . 'Controlloers/sign-in.php');
    exit;
}

$following_user_ids = findFollowingUserIds($user['id']);
$following_user_ids[] = $user['id'];

$view_user = $user;
$view_tweets = findTweets($user, null, $following_user_ids);

include_once '../Views/home.php';