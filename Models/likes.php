<?php

/**
 * Undocumented function
 *
 * @param array $data
 * @return int|false
 */
function createLike(array $data) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_errno . "\n";
        exit;
    }

    $query = 'INSERT INTO likes (user_id, tweet_id) VALUES (?, ?)';
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ii', $data['user_id'], $data['tweet_id']);
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
function deleteLike(array $data) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_errno . "\n";
        exit;
    }

    $query = 'UPDATE likes SET status = "deleted" WHERE id = ? AND user_id = ?';
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ii', $data['like_id'], $data['user_id']);
    $response = $statement->execute();

    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . '\n';
    }

    $statement->close();
    $mysqli->close();

    return $response;
}