<?php
include_once '../config.php';

/**
 * Undocumented function
 *
 * @param array $date
 * @return void
 */
function createUser(array $data) {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_errno . "\n";
        exit;
    }

    $query = 'INSERT INTO users (email, name, nickname, password) VALUES (?, ?, ?, ?)';
    $statement = $mysqli->prepare($query);

    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    $statement->bind_param('ssss', $data['email'], $data['name'], $data['nickname'], $data['password']);
    
    $response = $statement->execute();
    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . "\n";
    }

    $statement->close();
    $mysqli->close();

    return $response;
}