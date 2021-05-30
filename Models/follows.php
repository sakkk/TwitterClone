<?php

/**
 * Undocumented function
 *
 * @param array $data
 * @return int|false
 */
function createFollow(array $data) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_error . "\n";
        exit;
    }

    $query = 'INSERT INTO follows (follow_user_id, followed_user_id) VALUES (?, ?)';
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ii', $data['follow_user_id'], $data['followed_user_id']);

    if ($statement->execute()) {
        $response = $mysqli->insert_id;
    } else {
        $response = false;
        echo 'エラーメッセージ：' . $mysqli->error . '\n';
    }

    $statement->close();
    $mysqli->close();

    return $response;
}

/**
 * Undocumented function
 *
 * @param array $data
 * @return bool
 */
function deleteFollow(array $data) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_error . "\n";
        exit;
    }

    $data['updated_at'] = date('Y-m-d H:i:s');

    $query = 'UPDATE follows SET status = "deleted", updated_at = ? WHERE id = ? AND follow_user_id = ?';
    $statement = $mysqli->prepare($query);

    $statement->bind_param('sii', $data['updated_at'], $data['follow_id'], $data['follow_user_id']);

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
 * @param integer $user_id
 * @return array|false
 */
function findFollowingUserIds(int $follow_user_id) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_error . "\n";
        exit;
    }

    $query = <<<SQL
        SELECT
            followed_user_id
        FROM
            follows
        WHERE
            status = 'active' AND follow_user_id = '$follow_user_id'
    SQL;

    if ($result = $mysqli->query($query)) {
        $response = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $response = false;
        echo 'エラーメッセージ：' . $mysqli->error . '\n';
    }

    $mysqli->close();

    $following_user_ids = [];
    foreach ($response as $follow) {
        $following_user_ids[] = $follow['followed_user_id'];
    }

    return $following_user_ids;
}