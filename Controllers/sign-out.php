<?php
include_once '../config.php';
include_once '../util.php';

deleteUserSession();

header('Location: ' . HOME_URL . 'Controllers/sign-in.php');
exit;