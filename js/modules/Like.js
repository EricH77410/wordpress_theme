import $ from 'jquery';

class Like {
    constructor() {
        this.events();
    }

    events() {
        $('.like-box').on('click', this.clickDispatch.bind(this));
    }

    // methods
    clickDispatch(e) {
        var currentLikeBox = $(e.target).closest('.like-box');

        if (currentLikeBox.attr('data-exists') === 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce',universityData.nonce);
            },
            url: universityData.root_url+'/wp-json/like/v1/manageLike',
            type: 'POST',
            data: { 'professorId': currentLikeBox.data('professor') },
            success: (res) => {
                currentLikeBox.attr('data-exists', 'yes');
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(),10);
                likeCount++;
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like',res);
                console.log(res);                
            },
            error: (err) => {
                console.log(err);
            }
        });
    }

    deleteLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce',universityData.nonce);
            },
            url: universityData.root_url+'/wp-json/like/v1/manageLike',            
            type: 'DELETE',
            data: {'like': currentLikeBox.attr('data-like')},
            success: (res) => {
                currentLikeBox.attr('data-exists', 'no');
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(),10);
                likeCount--;
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like','');
                console.log(res);
            },
            error: (err) => {
                console.log(err);
            }
        })
    }
}

export default Like;