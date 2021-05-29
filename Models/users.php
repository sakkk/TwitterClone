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
 * @param array $data
 * @return bool
 */
function updateUser(array $data) {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_errno . "\n";
        exit;
    }

    $data['updated_at'] = date('Y-m-d H:i:s');
    if (isset($data['password'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    $set_columns = [];
    foreach([
        'name', 'nickname', 'email', 'password', 'image_name', 'updated_at'
    ] as $column) {
        if (isset($data[$column]) && $data[$column] !== '') {
            $set_columns[] = $column . ' = "' . $mysqli->real_escape_string($data[$column]) . '"';
        }
    }
    
    $query = 'UPDATE users SET ' . join(', ', $set_columns);
    $query .= ' WHERE id = "' . $mysqli->real_escape_string($data['id']) . '"';

    $response = $mysqli->query($query);

    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . "\n";
    }

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

/**
 * Undocumented function
 *
 * @param integer $user_id
 * @param integer $login_user_id
 * @return array|false
 */
function findUser(int $user_id, int $login_user_id = null) {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_errno . "\n";
        exit;
    }

    $user_id = $mysqli->real_escape_string($user_id);
    $login_user_id = $mysqli->real_escape_string($login_user_id);

    $query = <<<SQL
        SELECT
            U.id,
            U.name,
            U.nickname,
            U.email,
            U.image_name,
            (SELECT COUNT(1) FROM follows WHERE status = 'active' AND follow_user_id = U.id) AS follow_user_count,
            (SELECT COUNT(1) FROM follows WHERE status = 'active' AND followed_user_id = U.id) AS followed_user_count,
            F.id AS follow_id
        FROM
            users AS U
            LEFT JOIN
                follows AS F ON F.status = 'active' AND F.followed_user_id = '$user_id' AND F.follow_user_id = '$login_user_id'
        WHERE
            U.status = 'active' AND U.id = '$user_id'
    SQL;

    if ($result = $mysqli->query($query)) {
        $response = $result->fetch_array(MYSQLI_ASSOC);
    } else {
        $response = false;
        echo 'エラーメッセージ：' . $mysqli->error . '\n';
    }

    $mysqli->close();

    return $response;
}