<?php
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Tokyo');
define('HOME_URL', '/TwitterClone/');

$view_tweets = [
    [
        'user_id' => 1,
        'user_name' => 'taro',
        'user_nickname' => '太郎',
        'user_image_name' => 'sample-person.jpg',
        'tweet_body' => '今プログラミングをしています。',
        'tweet_image_name' => null,
        'tweet_created_at' => '2021-03-15 14:00:00',
        'like_id' => null,
        'like_count' => 0,
    ],
    [
        'user_id' => 2,
        'user_name' => 'jiro',
        'user_nickname' => '次郎',
        'user_image_name' => null,
        'tweet_body' => 'コワーキングスペースをオープンしました。',
        'tweet_image_name' => 'sample-post.jpg',
        'tweet_created_at' => '2021-03-14 14:00:00',
        'like_id' => 1,
        'like_count' => 1,
    ],
];

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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ホーム画面です">
    <link rel="icon" href="<?= HOME_URL; ?>Views/img/logo-twitterblue.svg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= HOME_URL; ?>Views/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" defer></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous" defer></script>
    <script src="<?= HOME_URL ?>Views/js/like.js" defer></script>
    <title>ホーム画面 / Twitterクローン</title>
</head>
<body class="home">
    <div class="container">
        <div class="side">
            <div class="side-inner">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="home.php" class="nav-link"><img src="<?= HOME_URL; ?>Views/img/logo-twitterblue.svg" alt="" class="icon"></a></li>
                    <li class="nav-item"><a href="home.php" class="nav-link"><img src="<?= HOME_URL; ?>Views/img/icon-home.svg" alt=""></a></li>
                    <li class="nav-item"><a href="search.php" class="nav-link"><img src="<?= HOME_URL; ?>Views/img/icon-search.svg" alt=""></a></li>
                    <li class="nav-item"><a href="notification.php" class="nav-link"><img src="<?= HOME_URL; ?>Views/img/icon-notification.svg" alt=""></a></li>
                    <li class="nav-item"><a href="profile.php" class="nav-link"><img src="<?= HOME_URL; ?>Views/img/icon-profile.svg" alt=""></a></li>
                    <li class="nav-item"><a href="post.php" class="nav-link"><img src="<?= HOME_URL; ?>Views/img/icon-post-tweet-twitterblue.svg" alt="" class="post-tweet"></a></li>
                    <li class="nav-item my-icon"><img src="<?= HOME_URL; ?>Views/img_uploaded/user/sample-person.jpg" alt="" class="js-popover" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="<a href='profile.php'>プロフィール</a><br><a href='sign-out.php'>ログアウト</a>" data-bs-html="true"></li>
                </ul>
            </div>
        </div>
        <div class="main">
            <div class="main-header">
                <h1>ホーム</h1>
            </div>
            <div class="tweet-post">
                <div class="my-icon">
                    <img src="<?= HOME_URL; ?>Views/img_uploaded/user/sample-person.jpg" alt="">
                </div>
                <div class="input-area">
                    <form action="post.php" method="post" enctype="multipart/form-data">
                        <textarea name="bady" placeholder="いまどうしてる？" maxlength="140"></textarea>
                        <div class="bottom-area">
                            <div class="mb-0">
                                <input type="file" name="image" class="form-control form-control-sm">
                            </div>
                            <button class="btn" type="submit">つぶやく</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ditch"></div>
            <?php if (empty($view_tweets)) : ?>
                <p class="p-3">ツイートがまだありません</p>
            <?php else : ?>
            <div class="tweet-list">
                <?php foreach ($view_tweets as $view_tweet) : ?>
                <div class="tweet">
                    <div class="user">
                        <a href="profile.php?user_id=<?= $view_tweet['user_id']; ?>">
                            <img src="<?= buildImagePath($view_tweet['user_image_name'], 'user'); ?>" alt="">
                        </a>
                    </div>
                    <div class="content">
                        <div class="name">
                            <a href="profile.php?user_id=<?= $view_tweet['user_id']; ?>">
                                <span class="nickname"><?= htmlspecialchars($view_tweet['user_nickname']); ?></span>
                                <span class="user-name">@<?= htmlspecialchars($view_tweet['user_name']); ?>・<?= convertToDayTimeAgo($view_tweet['tweet_created_at']); ?></span>
                            </a>
                        </div>
                        <p><?= htmlspecialchars($view_tweet['tweet_body']); ?></p>
                        <?php if (isset($view_tweet['tweet_image_name'])) : ?>
                            <img src="<?= buildImagePath($view_tweet['tweet_image_name'], 'tweet'); ?>" alt="" class="post-image">
                        <?php endif; ?>
                        <div class="icon-list">
                            <div class="like js-like" data-like-id=<?= $view_tweet['like_id']; ?>>
                                <?php
                                if (isset($view_tweet['like_id'])) {
                                    echo '<img src="' . HOME_URL . 'Views/img/icon-heart-twitterblue.svg" alt="">';
                                } else {
                                    echo '<img src="' . HOME_URL . 'Views/img/icon-heart.svg" alt="">';
                                }
                                ?>
                            </div>
                            <div class="like-count js-like-count"><?= $view_tweet['like_count']; ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.js-popover').popover({
                container: 'body'
            })
        }, false);
    </script>
</body>
</html>