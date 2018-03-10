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

        if (currentLikeBox.data('exists') === 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox) {
        $.ajax({
            url: universityData.root_url+'/wp-json/like/v1/manageLike',
            type: 'POST',
            data: { 'professorId': currentLikeBox.data('professor') },
            success: (res) => {
                console.log(res);
            },
            error: (err) => {
                console.log(err);
            }
        });
    }

    deleteLike(currentLikeBox) {
        $.ajax({
            url: universityData.root_url+'/wp-json/like/v1/manageLike',
            type: 'DELETE',
            success: (res) => {
                console.log(res);
            },
            error: (err) => {
                console.log(err);
            }
        })
    }
}

export default Like;