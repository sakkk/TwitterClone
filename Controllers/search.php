<?php
include_once '../config.php';
include_once '../util.php';

include_once '../Models/tweets.php';

$user = getUserSession();
if (!$user) {
    header('Location: ' . HOME_URL . 'Controlloers/sign-in.php');
    exit;
}

$keyword = null;
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
}

$view_user = $user;
$view_keyword = $keyword;

$view_tweets = findTweets($user, $keyword);

include_once '../Views/search.php';