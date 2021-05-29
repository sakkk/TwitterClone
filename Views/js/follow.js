$(function () {
    $('.js-follow').click(function() {
        const this_obj = $(this);
        const followed_user_id = $(this).data('followed-user-id');
        const follow_id = $(this).data('follow-id');

        if (follow_id) {
            $.ajax({
                url: 'follow.php',
                type: 'POST',
                data: {
                    'follow_id': follow_id
                },
                timeout: 10000
            })
            .done(() => {
                this_obj.addClass('btn-reverse');
                this_obj.text('フォローする');
                this_obj.data('follow-id', null);
            })
            .fail((data) => {
                alert('処理中にエラーが発生しました。');
                console.log(data);
            })
        } else {
            $.ajax({
                url: 'follow.php',
                type: 'POST',
                data: {
                    'followed_user_id': followed_user_id
                },
                timeout: 10000
            })
            .done((data) => {
                this_obj.removeClass('btn-reverse');
                this_obj.text('フォローを外す');
                this_obj.data('follow-id', data['follow_id']);
            })
            .fail((data) => {
                alert('処理中にエラーが発生しました。');
                console.log(data);
            })
        }
    })
});