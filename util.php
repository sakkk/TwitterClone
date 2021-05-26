<?php
/**
 * Undocumented function
 *
 * @param string $name
 * @param string $type
 * @return string
 */
function buildImagePath(string $name = null, string $type) {
    if ($type === 'user' && !isset($name)) {
        return HOME_URL . 'Views/img/icon-default-user.svg';
    }

    return HOME_URL . 'Views/img_uploaded/' . $type . '/' . htmlspecialchars($name);
}

/**
 * Undocumented function
 *
 * @param string $datetime
 * @return void
 */
function convertToDayTimeAgo(string $datetime) {
    $unix = strtotime($datetime);
    $now = time();
    $diff_sec = $now - $unix;

    if ($diff_sec < 60) {
        $time = $diff_sec;
        $unit = '秒前';
    } elseif ($diff_sec < 3600) {
        $time = $diff_sec / 60;
        $unit = '分前';
    } elseif ($diff_sec < 86400) {
        $time = $diff_sec / 3600;
        $unit = '時間前';
    } elseif ($diff_sec < 2764800) {
        $time = $diff_sec / 86400;
        $unit = '日前';
    } else {
        if (date('Y') != date('Y', $unix)) {
            $time = date('Y年n月j日', $unix);
        } else {
            $time = date('n月j日', $unix);
        }
        return $time;
    }

    return (int)$time . $unit;
}

/**
 * Undocumented function
 *
 * @param array $user
 * @return void
 */
function saveUserSession(array $user) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['USER'] = $user;
}

/**
 * Undocumented function
 *
 * @return void
 */
function deleteUserSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['USER']);
}

/**
 * Undocumented function
 *
 * @return array|false
 */
function getUserSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['USER'])) {
        return false;
    }
    $user = $_SESSION['USER'];

    if(!isset($user['image_name'])) {
        $user['image_name'] = null;
    }
    $user['image_path'] = buildImagePath($user['image_name'], 'user');

    return $user;
}