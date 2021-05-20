/////////////////////////
// いいね！用のJavaScript
/////////////////////////
$(function() {
    $('.js-like').click(function() {
        const this_obj = $(this);
        const like_id = $(this).data('like-id');
        const like_count_obj = $(this).parent().find('.js-like-count');
        let like_count = Number(like_count_obj.html());

        if (like_id) {
            like_count--;
            like_count_obj.html(like_count);
            this_obj.data('like-id', null);

            $(this).find('img').attr('src', '../Views/img/icon-heart.svg');
        } else {
            like_count++;
            like_count_obj.html(like_count);
            this_obj.data('like-id', true);

            $(this).find('img').attr('src', '../Views/img/icon-heart-twitterblue.svg')
        }
    })
})