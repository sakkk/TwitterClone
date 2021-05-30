<?php

/**
 * Undocumented function
 *
 * @param array $data
 * @return int|false
 */
function createNotification(array $data) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_error . "\n";
        exit;
    }

    $query  = 'INSERT INTO notifications (received_user_id, sent_user_id, message) VALUES (?, ?, ?)';
    $statement = $mysqli->prepare($query);
    $statement->bind_param('iis', $data['received_user_id'], $data['sent_user_id'], $data['message']);

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
 * @param integer $user_id
 * @return array|false
 */
function findNotifications(int $user_id) {

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました：' . $mysqli->connect_error . "\n";
        exit;
    }

    $user_id = $mysqli->real_escape_string($user_id);

    $query = <<<SQL
        SELECT
            N.id AS notification_id,
            N.message AS notification_message,
            U.name AS user_name,
            U.nickname AS user_nickname,
            U.image_name AS user_image_name
        FROM
            notifications AS N
            JOIN
                users U ON U.id = N.sent_user_id AND U.status = 'active'
        WHERE
            N.status = 'active' AND N.received_user_id = '$user_id'
        ORDER BY
            N.created_at DESC
        LIMIT 50
    SQL;

    if ($result = $mysqli->query($query)) {
        $notifications = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $notifications = false;
        echo 'エラーメッセージ：' . $mysqli->error . '\n';
    }

    $mysqli->close();
    return $notifications;
}