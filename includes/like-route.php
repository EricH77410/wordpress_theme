<?

add_action('rest_api_init', 'likeRoutes');

function likeRoutes () {
    register_rest_route('like/v1','manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route('like/v1','manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {
    $profId = sanitize_text_field($data['professorId']);
    wp_insert_post(array(
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => '2nd PHP create post test',
        'meta_input' => array(
            'liked_professor_id' => $profId
        )
    ));
}

function deleteLike() {
    return 'Like deleted';
}