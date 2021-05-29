<?php
include_once '../config.php';

/**
 * Undocumented function
 *
 * @param array $data
 * @return bool
 */
function createTweet(array $data) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_error . "\n";
        exit;
    }

    $query = 'INSERT INTO tweets (user_id, body, image_name) VALUES (?, ?, ?)';
    $statement = $mysqli->prepare($query);
    $statement->bind_param('iss', $data['user_id'], $data['body'], $data['image_name']);
    $response = $statement->execute();

    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . '\n';
    }

    $statement->close();
    $mysqli->close();

    return $response;
}

/**
 * Undocumented function
 *
 * @param array $user
 * @param string $keyword
 * @return array|false
 */
function findTweets(array $user, string $keyword = null) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_error . "\n";
        exit;
    }

    $login_user_id = $mysqli->real_escape_string($user['id']);

    $query = <<<SQL
        SELECT
            T.id AS tweet_id,
            T.status AS tweet_status,
            T.body AS tweet_body,
            T.image_name AS tweet_image_name,
            T.created_at AS tweet_created_at,
            U.id AS user_id,
            U.name AS user_name,
            U.nickname AS user_nickname,
            U.image_name AS user_image_name,
            L.id AS like_id,
            (SELECT COUNT(*) FROM likes WHERE status = 'active' AND tweet_id = T.id) AS like_count
        FROM
            tweets AS T
            JOIN
            users AS U ON U.id = T.user_id AND U.status = 'active'
            LEFT JOIN
            likes AS L ON L.tweet_id = T.id AND L.status = 'active' AND L.user_id = '$login_user_id'
        WHERE
            T.status = 'active'
    SQL;

    if (isset($keyword)) {
        $keyword = $mysqli->real_escape_string($keyword);
        $query .= ' AND CONCAT(U.nickname, U.name, T.body) LIKE "%' . $keyword . '%"';
    }

    $query .= ' ORDER BY T.created_at DESC';
    $query .= ' LIMIT 50';

    if ($result = $mysqli->query($query)) {
        $response = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $response = false;
        echo 'エラーメッセージ：' . $mysqli->error . '\n';
    }

    $mysqli->close();

    return $response;
}