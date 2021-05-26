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

/**
 * Undocumented function
 *
 * @param string $email
 * @param string $password
 * @return array
 */
function findUserAndCheckPassword(string $email, string $password) {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_errno . "\n";
        exit;
    }

    $email = $mysqli->real_escape_string($email);

    $query = 'SELECT * FROM users WHERE email = "'.$email.'"';

    $result = $mysqli->query($query);
    if (!$result) {
        echo 'エラーメッセージ：' . $mysqli->error . '\n';

        $mysqli->close();
        return false;
    }

    $user = $result->fetch_array(MYSQLI_ASSOC);
    if (!$user) {
        $mysqli->close();
        return false;
    }

    if(!password_verify($password, $user['password'])) {
        $mysqli->close();
        return false;
    }

    $mysqli->close();
    return $user;
}