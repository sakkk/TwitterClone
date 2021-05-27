<?php
include_once '../config.php';
include_once '../util.php';

include_once '../Models/tweets.php';

$user = getUserSession();
if (!$user) {
    header('Location: ' . HOME_URL . 'Controlloers/sign-in.php');
    exit;
}

$view_user = $user;
$view_tweets = findTweets($user);

include_once '../Views/home.php';