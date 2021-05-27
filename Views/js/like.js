/////////////////////////
// いいね！用のJavaScript
/////////////////////////
$(function() {
    $('.js-like').click(function() {
        const this_obj = $(this);
        const tweet_id = $(this).data('tweet-id');
        const like_id = $(this).data('like-id');
        const like_count_obj = $(this).parent().find('.js-like-count');
        let like_count = Number(like_count_obj.html());

        if (like_id) {
            $.ajax({
                url: 'like.php',
                type: 'POST',
                data: {
                    'like_id': like_id
                },
                timeout: 10000
            })
            .done(() => {
                like_count--;
                like_count_obj.html(like_count);
                this_obj.data('like-id', null);

                $(this).find('img').attr('src', '../Views/img/icon-heart.svg');
            })
            .fail((data) => {
                alert('処理中にエラーが発生しました。');
                console.log(data);
            })
        } else {
            $.ajax({
                url: 'like.php',
                type: 'POST',
                data: {
                    'tweet_id': tweet_id
                },
                timeout: 10000
            })
            .done((data) => {
                like_count++;
                like_count_obj.html(like_count);
                this_obj.data('like-id', data['like_id']);

                $(this).find('img').attr('src', '../Views/img/icon-heart-twitterblue.svg')
            })
            .fail((data) => {
                alert('処理中にエラーが発生しました。');
                console.log(data);
            })
        }
    })
})